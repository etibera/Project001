<?php
require_once "../include/database.php";

class PendingReceivables
{
  private $con;

  public function __construct()
  {
    $this->con = new Database();
    $this->con = $this->con->getmyDB();
  }

  public function get()
  {
    $output = $data = $dataParams = $params = $column = array();

    $params = $_REQUEST;

    $column = ["", "orderId", "fullName", "paymentMethod", "statusName", "grandTotal", "payment", "verification", "fundStatus", "dateAdded", "dateModified"];

    $orderStatus = $_POST['orderStatus'] ?? null;
    $fundStatus = $_POST['fundStatus'] ?? null;
    $from = $_POST['from'] ?? null;
    $to = $_POST['to'] ?? null;

    $sql = "SELECT o.order_id as orderId, CONCAT(o.firstname, ' ', o.lastname) AS fullName, o.payment_method AS paymentMethod,ocs.name AS statusName, ot.value as grandTotal,
    CASE WHEN o.payment_code = 'bank_transfer' THEN 'PCVill RCBC Account' 
    WHEN o.payment_code = 'land_bank_pay' THEN 'PCVill LandBank Account' 
    WHEN o.payment_code = '4Gives_pay' THEN 'PCVill AllBank Account' 
    WHEN o.payment_code = 'cod' THEN 'Cash/AHUB Account' 
    ELSE 'AHUB Account' END AS payment,
    CASE WHEN o.ops_verification IS NULL THEN 'Unverified' ELSE o.ops_verification END AS verification,
    o.fund_status AS fundStatus,o.date_added AS dateAdded,o.date_modified AS dateModified
    FROM oc_order o
    INNER JOIN oc_order_total ot ON ot.order_id=o.order_id
    INNER JOIN oc_order_status ocs ON o.order_status_id=ocs.order_status_id
    WHERE ot.title='Total'
    AND o.order_id in (SELECT distinct  so.order_id FROM store_orders so)";

    $base = $this->con->prepare($sql);
    $base->execute();
    $totalCount = $base->rowCount();


    if ($orderStatus != 'all') {
      $sql .= " AND o.order_status_id = :orderStatus";
      $dataParams = array_merge($dataParams, [":orderStatus" => $orderStatus]);
    }

    if ($fundStatus != 'all') {
      if ($fundStatus == 'Paid') {
        $sql .= " AND o.fund_status = :fundStatus";
        $dataParams = array_merge($dataParams, [":fundStatus" => $fundStatus]);
      } else {
        $sql .= " AND o.fund_status IS NULL";
      }
    }

    if (!empty($from) && !empty($to)) {
      $sql .= " AND date_added BETWEEN :from AND :to";
      $dataParams = array_merge($dataParams, [":from" => "$from 00:00:00", ":to" => "$to 23:59:59"]);
    }

    if (!empty($params['search']['value'])) {
      $sql .= " AND";
      $sql .= " (o.order_id LIKE :value";
      $sql .= " OR CONCAT(o.firstname, ' ', o.lastname) LIKE :value";
      $sql .= " OR o.payment_method LIKE :value";
      $sql .= " OR ocs.name LIKE :value";
      $sql .= " OR ot.value LIKE :value";
      $sql .= " OR CASE WHEN o.payment_code = 'bank_transfer' THEN 'PC vill account' ELSE 'AHUB account' END LIKE :value";
      $sql .= " OR CASE WHEN o.ops_verification IS NULL THEN 'Unverified' ELSE o.ops_verification END LIKE :value";
      $sql .= " OR o.fund_status LIKE :value";
      $sql .= " OR o.date_added LIKE :value";
      $sql .= " OR o.date_modified LIKE :value)";

      $dataParams = array_merge($dataParams, [":value" => $params['search']['value']]);
    }

    $searchCount = $this->con->prepare($sql);
    $searchCount->execute($dataParams);

    $filterCount = $searchCount->rowCount();

    if (isset($params['order'])) {
      $sql .= " ORDER BY {$column[$params['order'][0]['column']]} {$params['order'][0]['dir']}";
    } else {
      $sql .= " ORDER BY fundStatus, date_added DESC";
    }

    if ($params['draw'] != 0 && $params['length'] > 0) {
      $sql .= " LIMIT :start, :length";
      $dataParams = array_merge($dataParams, [":start" => intval($params['start']), ":length" => intval($params['length'])]);
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
    $filteredData = $stmt->fetchAll(PDO::FETCH_OBJ);

    foreach ($filteredData as $row) {
      if ($row->fundStatus == "Paid") {
        $checkbox = "";
      } else {
        $checkbox = "<center><input name='orderId' type='checkbox' value='$row->orderId'></input></center>";
      }
      $data[] = array(
        $checkbox,
        $row->orderId,
        $row->fullName,
        $row->paymentMethod,
        $row->statusName,
        utf8_encode('&#8369;') . number_format(floatval($row->grandTotal), 2),
        $row->payment,
        $row->verification,
        $row->fundStatus,
        $row->dateAdded,
        $row->dateModified,
      );
    }

    $output = array(
      'draw' => intval($params['draw']),
      'recordsFiltered' => intval($filterCount),
      'recordsTotal' => intval($totalCount),
      'data' => $data
    );

    return $output;
  }

  public function getOrderStatus()
  {
    $result = array();

    $stmt = $this->con->prepare("SELECT * FROM oc_order_status");
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    if (empty($data)) return array("data" => []);

    foreach ($data as $row) {
      $result[] = "<option value='$row->order_status_id'>$row->name</option>";
    }

    return $result;
  }

  public function pay()
  {
    $orderId = $_POST['orderId'] ?? null;

    if (empty($orderId)) return array("type" => "error", "message" => "Please select order ID");

    foreach ($orderId as $value) {
      $stmt = $this->con->prepare("UPDATE oc_order SET fund_status = :fundStatus WHERE order_id = :orderId");
      $result = $stmt->execute([":orderId" => $value, ":fundStatus" => "Paid"]);
    }

    if ($result) {
      return array("type" => "success", "message" => "Transaction Success");
    }
  }
}
