<?php
require_once "../include/database.php";

class PesoPartnerProgramReport
{
  private $con;
  private $totalCount;
  private $filterCount;
  private $subTotal = 0;
  private $members = 0;

  public function __construct()
  {
    $this->con = new Database();
    $this->con = $this->con->getmyDB();
  }

  public function get()
  {
    $output = $data = $params = array();

    $params = $_REQUEST;

    $branch_id = $_POST['branch_id'];
    $start = $params['start'];
    $length = $params['length'];
    $search = "%" . trim($params['search']['value']) . "%";

    $customers = $this->getCustomers($branch_id, $start, $length, $search);
    if ($branch_id == "hq") {
      $customers = array_merge($customers, $this->getHq($start, $length, $search));
    }

    foreach ($customers as $customer) {
      $customerInfo = $this->getCustomersInfo($customer->customer_id, $search);

      foreach ($customerInfo as $info) {
        $full_name = ucwords(strtolower($info->full_name));
        $data[] = array(
          $full_name,
          $info->total_recommended_products,
          $info->successful_sales,
          $info->pending_sales,
          number_format($info->cash_wallet, 2),
          "<button name='recommend' data-toggle='tooltip' data-name='$full_name' title='Details For Recommend Products' id='$info->customer_id' type='button' class='btn btn-primary btn_recommed'><i class='fa fa-share-alt' style='font-size:15px; '></i></button><button name='cashWallet' data-toggle='tooltip' data-name='$full_name' title='Details For Recommend Products' id='$info->customer_id' type='button' class='btn btn-primary btn_recommed'><i class='fa fa-briefcase' style='font-size:15px; '></i></button>"
        );
      }
    }
    if (intval($this->filterCount) - $length <= intval($params['start'])) {
      $data[] = ['', '', '', 'Sub Total:', number_format($this->subTotal, 2), ''];
    }

    $output = array(
      'draw' => intval($params['draw']),
      'recordsFiltered' => intval($this->filterCount),
      'recordsTotal' => intval($this->totalCount),
      'data' => $data,
      'totalMembers' => $this->members
    );

    return $output;
  }

  public function getBranches()
  {
    $stmt = $this->con->prepare("SELECT CONCAT(oc.firstname, ' ', oc.lastname) AS branch_name ,oc.customer_id AS branch_id
    FROM oc_customer oc
    INNER JOIN oc_customer_links ocl ON oc.customer_id=ocl.invite_id
    WHERE ocl.customer_id=1568");
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_OBJ);
    $data[] = ['branch_name' => 'AHI HQ', 'branch_id' => 'hq'];

    return $data;
  }

  public function getCustomers($branch_id, $start, $length, $search)
  {
    $dataParams = array();

    $sql = "SELECT oc.customer_id AS customer_id, CONCAT(oc.firstname, ' ', oc.lastname) AS full_name
    FROM oc_customer oc
    INNER JOIN oc_customer_links ocl ON oc.customer_id=ocl.invite_id
    INNER JOIN oc_affiliate_program oap ON oap.customer_id=ocl.invite_id
    WHERE ocl.customer_id=:branch_id";

    if ($branch_id != '') {
      $dataParams = array_merge($dataParams, [':branch_id' => $branch_id]);
    }

    $base = $this->con->prepare($sql);
    $base->execute($dataParams);
    $this->totalCount = $base->rowCount();

    foreach ($base->fetchAll(PDO::FETCH_OBJ) as $row) {
      $this->members++;
      $customerInfo = $this->getCustomersInfo($row->customer_id);
      foreach ($customerInfo as $info) {
        $this->subTotal += floatval($info->cash_wallet);
      }
    }

    if ($search != '') {
      $sql .= " AND CONCAT(oc.firstname, ' ', oc.lastname) LIKE :value";

      $dataParams = array_merge($dataParams, [':value' => $search]);
    }

    $filterData = $this->con->prepare($sql);
    $filterData->execute($dataParams);
    $this->filterCount = $filterData->rowCount();

    $sql .= " ORDER BY full_name";

    if ($start != '' && $length != '') {
      $sql .= " LIMIT :start, :length";

      $dataParams = array_merge($dataParams, [':start' => intval($start), ':length' => intval($length)]);
    }

    $stmt = $this->con->prepare($sql);
    foreach ($dataParams as $key => $value) {
      if (is_int($value)) {
        $paramType = PDO::PARAM_INT;
      } else {
        $paramType = PDO::PARAM_STR;
      }
      $stmt->bindValue($key, $value, $paramType);
    }
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function getCustomersInfo($customer_id)
  {
    $stmt = $this->con->prepare("SELECT customer_id,
        (SELECT CONCAT(firstname, ' ', lastname) AS full_name WHERE customer_id = :customer_id) AS full_name,
        (SELECT COUNT(ID) AS total_recommended_products  FROM oc_affiliate_link_share WHERE customer_id = :customer_id) AS total_recommended_products,
        (SELECT COUNT(ID) AS successful_sales FROM oc_affiliate_costomer_sold_items WHERE seller_id = :customer_id AND status = 1) AS successful_sales,
        (SELECT COUNT(ID) AS successful_sales FROM oc_affiliate_costomer_sold_items WHERE seller_id = :customer_id AND status = 0) AS pending_sales,
        (SELECT SUM(amount) AS cash_wallet  FROM oc_affiliate_wallet where seller_id = :customer_id) AS cash_wallet
        FROM oc_customer WHERE customer_id = :customer_id");
    $stmt->execute([':customer_id' => $customer_id]);

    $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    return $data;
  }

  public function getHq($start, $length, $search)
  {
    $dataParams = array();

    $sql = "SELECT oc.customer_id, CONCAT(oc.firstname, ' ', oc.lastname) AS full_name
    FROM oc_customer oc
    INNER JOIN oc_affiliate_program oap on oap.customer_id=oc.customer_id where oap.customer_id  not in ( SELECT occ.customer_id as customer_id
    FROM oc_customer occ
    INNER JOIN oc_customer_links occl on occ.customer_id=occl.invite_id
    INNER JOIN oc_affiliate_program oapc on oapc.customer_id=occl.invite_id where occl.customer_id BETWEEN '2407' and '2414') and oc.customer_id not BETWEEN '2407' and '2414'";

    $base = $this->con->prepare($sql);
    $base->execute($dataParams);
    $this->totalCount = $base->rowCount();

    foreach ($base->fetchAll(PDO::FETCH_OBJ) as $row) {
      $this->members++;
      $customerInfo = $this->getCustomersInfo($row->customer_id);
      foreach ($customerInfo as $info) {
        $this->subTotal += floatval($info->cash_wallet);
      }
    }

    if ($search != '') {
      $sql .= " AND CONCAT(oc.firstname, ' ', oc.lastname) LIKE :value";

      $dataParams = array_merge($dataParams, [':value' => $search]);
    }

    $filterData = $this->con->prepare($sql);
    $filterData->execute($dataParams);
    $this->filterCount = $filterData->rowCount();

    $sql .= " ORDER BY full_name";

    if ($start != '' && $length != '') {
      $sql .= " LIMIT :start, :length";

      $dataParams = array_merge($dataParams, [':start' => intval($start), ':length' => intval($length)]);
    }

    $stmt = $this->con->prepare($sql);
    foreach ($dataParams as $key => $value) {
      if (is_int($value)) {
        $paramType = PDO::PARAM_INT;
      } else {
        $paramType = PDO::PARAM_STR;
      }
      $stmt->bindValue($key, $value, $paramType);
    }
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function recommendDetails($id)
  {
    $stmt = $this->con->prepare("SELECT opd.name AS product_name, oals.date, oals.type AS mode_of_share FROM oc_affiliate_link_share oals 
    INNER JOIN oc_product_description opd ON oals.product_id=opd.product_id 
    WHERE oals.customer_id = :customer_id ORDER BY oals.date DESC");
    $stmt->execute([':customer_id' => $id]);

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function cashWalletDetails($id)
  {
    $result = array();

    $stmt = $this->con->prepare("SELECT order_id, product_name, `date`, amount FROM  oc_affiliate_wallet WHERE seller_id=:customer_id ORDER BY `date` DESC");
    $stmt->execute([':customer_id' => $id]);
    $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    $totalAmount = 0;

    foreach ($data as $row) {

      $totalAmount += floatval($row->amount);
      $result[] = array(
        'order_id' => $row->order_id,
        'product_name' => $row->product_name,
        'date' => $row->date,
        'amount' => number_format($row->amount, 2)
      );
    }

    array_push($result, ['totalAmount' => number_format($totalAmount, 2)]);

    return $result;
  }
}
