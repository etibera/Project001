<?php

require_once "../include/database.php";

class PendingPayables
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

    $column = ["order_id", "shop_name", "bank_name", "bank_account_name", "bank_account_no", "amount", "reference_number", "status", "date"];

    $status = $_POST['status'] ?? null;
    $from = $_POST['from'] ?? null;
    $to = $_POST['to'] ?? null;

    $sql = "SELECT * FROM ( 
      SELECT sp.id,sp.seller_id,sp.order_id,os.shop_name,sb.bank_name,sb.bank_account_name,sb.bank_account_no,sp.amount,sp.reference_number,CASE WHEN sp.status = 1 THEN 'Paid' ELSE 'Pending' END as status,sp.date
      FROM store_payables sp 
      INNER JOIN  oc_seller os 
        ON os.seller_id=sp.seller_id 
      INNER JOIN store_orders so 
        ON sp.seller_id=so.seller_id and sp.order_id=so.order_id and sp.order_number=so.order_number
      INNER JOIN seller_branch sb 
        ON sb.id=so.branch_id AND so.order_number IS NOT NULL
      UNION ALL                                                 
      SELECT sp.id,sp.seller_id,sp.order_id,os.shop_name,sb.bank_name,sb.bank_account_name,sb.bank_account_no,sp.amount,sp.reference_number,CASE WHEN sp.status = 1 THEN 'Paid' ELSE 'Pending' END as status,sp.date
      FROM store_payables sp 
      INNER JOIN  oc_seller os 
        ON os.seller_id=sp.seller_id 
      INNER JOIN store_orders so 
        ON sp.seller_id=so.seller_id and sp.order_id=so.order_id 
      INNER JOIN seller_branch sb 
        ON sb.id=so.branch_id AND so.order_number is null
      
      ) AS SPPL";

    $base = $this->con->prepare($sql);
    $base->execute();
    $totalCount = $base->rowCount();

    $connector = " WHERE";

    if ($status != 'all') {
      $sql .= $connector;
      $sql .= " status = :status";
      $dataParams = array_merge($dataParams, [':status' => $status]);
      $connector = " AND";
    }

    if (!empty($from) && !empty($to)) {
      $sql .= $connector;
      $sql .= " `date` BETWEEN :from AND :to";
      $dataParams = array_merge($dataParams, [':from' => "$from 00:00:00", ':to' => "$to 23:59:59"]);
      $connector = " AND";
    }

    if (!empty($params['search']['value'])) {
      $sql .= $connector;
      $sql .= " (order_id LIKE :value";
      $sql .= " OR shop_name LIKE :value";
      $sql .= " OR bank_name LIKE :value";
      $sql .= " OR bank_account_name LIKE :value";
      $sql .= " OR bank_account_no LIKE :value";
      $sql .= " OR amount LIKE :value";
      $sql .= " OR reference_number LIKE :value";
      $sql .= " OR CASE WHEN status = 1 THEN 'Paid' ELSE 'Pending' END LIKE :value";
      $sql .= " OR date LIKE :value)";

      $dataParams = array_merge($dataParams, [':value' => "%" . trim($params['search']['value']) . "%"]);
    }

    if (isset($params['order'])) {
      $sql .= " ORDER BY {$column[$params['order'][0]['column']]} {$params['order'][0]['dir']}";
    } else {
      $sql .= " ORDER BY date DESC";
    }

    $searchCount = $this->con->prepare($sql);
    $searchCount->execute($dataParams);

    $filterCount = $searchCount->rowCount();

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
      if ($row->status == 'Pending') {
        $button = "<button id='paywallet' class='btn btn-primary' data-id='$row->id' data-order_id='$row->order_id' data-bank_account_no='$row->bank_account_no' data-bank_account_name='$row->bank_account_name' data-seller_id='$row->seller_id' data-amount='$row->amount' data-bank_name='$row->bank_name'>
        <i class='fa fa-random'></i> Pay/Transfer
        </button>";
      } else {
        $button = '-';
      }

      if (!empty(trim($row->reference_number))) {
        $reference = $row->reference_number;
      } else {
        $reference = '-';
      }
      $data[] = array(
        $row->order_id,
        $row->shop_name,
        $row->bank_name,
        $row->bank_account_name,
        $row->bank_account_no,
        utf8_encode('&#8369;') . number_format(floatval($row->amount), 2),
        $reference,
        $row->status,
        $row->date,
        $button
      );
    }

    $output = array(
      'draw' => intval($params['draw']),
      'recordsFiltered' => intval($filterCount),
      'recordsTotal' => intval($totalCount),
      'data' => $data,
    );

    return $output;
  }

  public function pay()
  {
    try {

      $seller_id = $_POST['seller_id'];
      $order_id = $_POST['order_id'];
      $bank_account_no = $_POST['bank_account_no'];
      $bank_account_name = $_POST['bank_account_name'];
      $bank_name = $_POST['bank_name'];
      $payableId = $_POST['payableId'];
      $amount = $_POST['amount'];
      $reference_no = $_POST['reference_no'];

      if (!$reference_no) return array("type" => "error", "message" => "Please input the reference no.");

      $details = "Payment transfered to Acc.# : " . $bank_account_no . ", RF.#: " . $reference_no . ", Order Id:" . $order_id;
      $sw = $this->con->prepare("INSERT INTO seller_wallet SET `desc` = :orderdesc, amount = :amount, seller_id=:seller_id, `date` = convert_tz(utc_timestamp(),'-08:00','+0:00')");
      $sw->bindValue(':orderdesc',  $details);
      $sw->bindValue(':amount', '-' . $amount);
      $sw->bindValue(':seller_id', $seller_id);
      $res1 = $sw->execute();
      $sp = $this->con->prepare("UPDATE store_payables SET status=:status,reference_number=:reference_no, `date` = convert_tz(utc_timestamp(),'-08:00','+0:00') WHERE id=:payableId");
      $sp->bindValue(':payableId', $payableId);
      $sp->bindValue(':reference_no', $reference_no);
      $sp->bindValue(':status', 1);
      $res2 = $sp->execute();

      if ($res1 && $res2) {
        return array("type" => "success", "message" => "Transaction Success");
      }
    } catch (Exception $e) {
      return $e;
    }
  }
}
