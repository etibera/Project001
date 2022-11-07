<?php
require_once "../include/database.php";

class CustomerActivityReport
{
  private $con;

  public function __construct()
  {
    $this->con = new Database();
    $this->con = $this->con->getmyDB();
  }

  public function get()
  {
    $output = $data = $dataParams = $column = array();

    $params = $_REQUEST;

    $column = ["`key`", "ip", "date_added"];

    $from = $_POST['from'] ?? null;
    $to = $_POST['to'] ?? null;

    $sql = "SELECT a.*,c.firstname,c.lastname from oc_customer_activity a inner join oc_customer c on a.customer_id = c.customer_id";

    $base = $this->con->prepare($sql);
    $base->execute();
    $totalCount = $base->rowCount();

    $connector = " WHERE";

    if (!empty($from) && !empty($to)) {
      $sql .= $connector;
      $connector = " AND";
      $sql .= " a.date_added BETWEEN :from AND :to";
      $dataParams = array_merge($dataParams, [":from" => "$from 00:00:00", ":to" => "$to 23:59:59"]);
    }

    if (!empty($params['search']['value'])) {
      $sql .= $connector;
      $sql .= " (CONCAT(c.firstname,c.lastname) LIKE :value";
      $sql .= " OR a.ip LIKE :value";
      $sql .= " OR a.date_added LIKE :value)";

      $dataParams = array_merge($dataParams, [":value" => "%" . trim($params['search']['value']) . "%"]);
    }

    if (isset($params['order'])) {
      $sql .= " ORDER BY {$column[$params['order'][0]['column']]} {$params['order'][0]['dir']}";
    } else {
      $sql .= " ORDER BY date_added DESC";
    }

    $filterData = $this->con->prepare($sql);
    $filterData->execute($dataParams);
    $filterCount = $filterData->rowCount();

    if ($params['draw'] > 0 && $params['length'] > 0) {
      $sql .= " LIMIT :start, :length";
      $dataParams = array_merge($dataParams, [':start' => intval($params['start']), ':length' => intval($params['length'])]);
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
    $getAllData = $stmt->fetchAll(PDO::FETCH_OBJ);

    foreach ($getAllData as $row) {

      $comment = $row->key;

      if ($comment == "login") {
        $comment = "<a href='customer_update.php?cid=$row->customer_id'>$row->firstname $row->lastname</a> logged in.";
      } else if ($comment == "register") {
        $comment = "<a href='customer_update.php?cid=$row->customer_id'>$row->firstname $row->lastname</a> registered for an account.";
      } else if ($comment == "order_account") {
        $comment = "<a href='customer_update.php?cid='$row->customer_id'>'$row->firstname $row->lastname'</a> created a new order.";
      }
      $data[] = array(
        $comment,
        $row->ip,
        $row->date_added
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

  public function search()
  {
    $result = array();
    $params = array();
    $sql = "SELECT a.*,c.firstname,c.lastname from oc_customer_activity a inner join oc_customer c on a.customer_id = c.customer_id";

    $dateFrom = $_POST["dateFrom"] ?? null;
    $dateTo = $_POST["dateTo"] ?? null;

    if (empty($dateFrom)) return array("type" => "error", "message" => "Select Date From");
    if (empty($dateTo)) return array("type" => "error", "message" => "Select Date To");

    if (!empty($dateFrom) && !empty($dateTo)) {
      $sql .= " WHERE a.date_added BETWEEN :dateFrom AND :dateTo";
      $params = array_merge($params, [":dateFrom" => "$dateFrom 00:00:00", ":dateTo" => "$dateTo 23:59:59"]);
    }

    $sql .= " order by a.date_added";

    $stmt = $this->con->prepare($sql);
    $stmt->execute($params);

    $data = $stmt->fetchAll(PDO::FETCH_OBJ);


    if (empty($data)) return array("type" => "error", "message" => "No Data found.");

    foreach ($data as $row) {

      $comment = $row->key;

      if ($comment == "login") {
        $comment = "<a href='customer_update.php?cid=$row->customer_id'>$row->firstname $row->lastname</a> logged in.";
      } else if ($comment == "register") {
        $comment = "<a href='customer_update.php?cid=$row->customer_id'>$row->firstname $row->lastname</a> registered for an account.";
      } else if ($comment == "order_account") {
        $comment = "<a href='customer_update.php?cid='$row->customer_id'>'$row->firstname $row->lastname'</a> created a new order.";
      }
      $result[] = array(
        $comment,
        $row->ip,
        $row->date_added
      );
    }

    return $result;
  }
}
