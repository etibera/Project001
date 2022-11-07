<?php
require_once "../include/database.php";
class SalesReport
{
  private $con;
  private $totalTransaction = 0;
  private $successfullTransaction = 0;
  private $bankTransaction = 0;
  private $totalCharge = 0;

  public function __construct()
  {
    $this->con = new Database();
    $this->con = $this->con->getmyDB();
  }

  public function get()
  {
    $output = $data = $dataParams = $params = array();

    $params = $_REQUEST;

    $filter = $_POST['filter'] ?? null;
    $from = $_POST['from'] ?? null;
    $to = $_POST['to'] ?? null;

    $sql = "SELECT oc.order_id as orderId, CONCAT(oc.firstname, ' ', oc.lastname) AS fullName,ocs.name AS statusName, oc.payment_method AS paymentMethod, ot.value AS total, 
    CASE WHEN oc.order_status_id = 49 THEN ot.value ELSE '' END AS sales,
    CASE WHEN oc.payment_code = 'maxx_payment' OR oc.payment_code = 'credit_card' THEN ot.value ELSE '' END AS bankTransaction, oc.date_added AS dateAdded,
    oc.date_modified AS dateOfSales, wr AS receipt, oc.payment_code
    FROM oc_order oc 
    INNER JOIN oc_order_status ocs ON oc.order_status_id=ocs.order_status_id
    INNER JOIN oc_order_total ot ON ot.order_id=oc.order_id
    WHERE ot.title='Total' AND oc.order_status_id > 0";

    $base = $this->con->prepare($sql);
    $base->execute();
    $totalCount = $base->rowCount();

    if (!empty($from) && !empty($to)) {
      if ($filter == 0) {
        $sql .= " AND oc.date_added BETWEEN :from AND :to ";
      } else {
        $sql .= " AND oc.date_modified BETWEEN :from AND :to ";
      }
      $dataParams = array_merge($dataParams, [":from" => "{$from} 00:00:00", ":to" => "{$to} 23:59:59"]);
    }

    if (!empty($params['search']['value'])) {
      $sql .= " AND";
      $sql .= " (oc.order_id LIKE :value";
      $sql .= " OR CONCAT(oc.firstname, ' ', oc.lastname) LIKE :value";
      $sql .= " OR ocs.name LIKE :value";
      $sql .= " OR oc.payment_method LIKE :value";
      $sql .= " OR ot.value LIKE :value";
      $sql .= " OR oc.date_added LIKE :value";
      $sql .= " OR wr LIKE :value";
      $sql .= " OR oc.payment_code LIKE :value)";

      $dataParams = array_merge($dataParams, [":value" => "%" . trim($params['search']['value']) . "%"]);
    }

    $searchCount = $this->con->prepare($sql);
    $searchCount->execute($dataParams);

    $filterCount = $searchCount->rowCount();

    $getTotal = $searchCount->fetchAll(PDO::FETCH_OBJ);

    foreach ($getTotal as $row) {
      $opSystemCharge = 0;
      if ($row->payment_code == "maxx_payment" || $row->payment_code == "credit_card") {
        $opSystemCharge = $this->SalesReportSubTotaL($row->orderId, "Convenience Fee");
      }
      $this->totalTransaction = $this->totalTransaction + floatval($row->total);
      $this->successfullTransaction = $this->successfullTransaction + floatval($row->sales);
      $this->bankTransaction = $this->bankTransaction + floatval($row->bankTransaction);
      $this->totalCharge = $this->totalCharge + floatval($opSystemCharge);
    }

    $sql .= " ORDER BY date_modified DESC";

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
    $filterData = $stmt->fetchAll(PDO::FETCH_OBJ);

    foreach ($filterData as $row) {
      $opSystemCharge = 0;
      if ($row->payment_code == "maxx_payment" || $row->payment_code == "credit_card") {
        $opSystemCharge = $this->SalesReportSubTotaL($row->orderId, "Convenience Fee");
      }
      $serial = $this->getSerial($row->orderId);
      $newSerial = !empty($serial) ? '' : '-';

      foreach ($serial as $new) {
        $newSerial .= $new['serial'] . "<br>";
      }

      $receipt = !empty($row->receipt) ? $row->receipt : '-';

      $data[] = array(
        $row->orderId,
        $row->fullName,
        $row->statusName,
        $row->paymentMethod,
        $row->total ? utf8_encode('&#8369;') . number_format(floatval($row->total), 2) : '-',
        $row->sales ? utf8_encode('&#8369;') . number_format(floatval($row->sales), 2) : '-',
        $row->bankTransaction ? utf8_encode('&#8369;') . number_format(floatval($row->bankTransaction), 2) : '-',
        $opSystemCharge ? utf8_encode('&#8369;') . number_format(floatval($opSystemCharge), 2) : '-',
        $row->dateAdded,
        $row->dateOfSales,
        $receipt,
        $newSerial,
      );
    }

    if (intval($params['length']) < 0) {
      $length = $filterCount;
    } else {
      $length = intval($params['length']);
    }

    if (intval($filterCount) - $length <= intval($params['start'])) {
      array_push($data, ['', '', '', '<h4>Grand Total:</h4>', '<h4>' . utf8_encode('&#8369;') . number_format($this->totalTransaction, 2) . '</h4>', '<h4>' . utf8_encode('&#8369;') . number_format($this->successfullTransaction, 2) . '</h4>', '<h4>' . utf8_encode('&#8369;') . number_format($this->bankTransaction, 2) . '</h4>', '<h4>' . utf8_encode('&#8369;') . number_format($this->totalCharge, 2) . '</h4>', '', '', '', '']);
    }

    $output = array(
      'draw' => intval($params['draw']),
      'recordsFiltered' => intval($filterCount),
      'recordsTotal' => intval($totalCount),
      'data' => $data,
      'totalTransaction' => utf8_encode('&#8369;') . number_format($this->totalTransaction, 2),
      'successfullTransaction' => utf8_encode('&#8369;') . number_format($this->successfullTransaction, 2),
      'bankTransaction' => utf8_encode('&#8369;') . number_format($this->bankTransaction, 2),
      'totalCharge' => utf8_encode('&#8369;') . number_format($this->totalCharge, 2),
    );

    return $output;
  }

  public function SalesReportSubTotaL($order_id, $title)
  {
    $data = array();
    $s = $this->con->prepare("SELECT * from oc_order_total  WHERE order_id = :order_id and title = :title");
    $s->bindValue(':order_id', $order_id);
    $s->bindValue(':title', $title);
    $s->execute();
    $data = $s->fetch(PDO::FETCH_ASSOC);
    return $data['value'];
  }

  public function getSerial($order_id)
  {
    $s = $this->con->prepare("SELECT * from oc_product_serial WHERE order_id = :order_id");
    $s->bindValue(':order_id', $order_id);
    $s->execute();
    $status = $s->fetchAll(PDO::FETCH_ASSOC);
    return $status;
  }
}
