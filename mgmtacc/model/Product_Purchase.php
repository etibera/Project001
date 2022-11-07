<?php
require_once "../include/database.php";

class ProductPurchase
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

    $column = ["name", "model", "total_quantity", "price", "total_price"];

    $orderStatus = $_POST['orderStatus'] ?? null;
    $from = $_POST['from'] ?? null;
    $to = $_POST['to'] ?? null;

    $base = $this->con->prepare("SELECT p.product_id,
    SUM(ANY_VALUE(p.quantity)) AS total_quantity
    FROM oc_order_product AS p
    INNER JOIN oc_order AS o ON p.order_id = o.order_id WHERE p.product_id IS NOT NULL AND o.order_status_id != 0 GROUP BY p.product_id");
    $base->execute();
    $totalCount = $base->rowCount();

    $sql = "SELECT p.product_id,
    SUM(ANY_VALUE(p.quantity)) AS total_quantity,
    SUM(p.quantity) * AVG(p.price) AS total_price
    FROM oc_order_product AS p
    INNER JOIN oc_order AS o ON p.order_id = o.order_id WHERE p.product_id IS NOT NULL AND o.order_status_id != 0";

    if ($orderStatus != "all") {
      $sql .= " AND o.order_status_id = :order_status_id";
      $dataParams = array_merge($dataParams, [":order_status_id" => $orderStatus]);
    }

    if (!empty($from) && !empty($to)) {
      $sql .= " AND o.date_added BETWEEN :from AND :to";
      $dataParams = array_merge($dataParams, [":from" => "$from 00:00:00", ":to" => "$to 23:59:59"]);
    }

    if (!empty($params['search']['value'])) {
      $sql .= " AND";
      $sql .= " (p.name LIKE :value";
      $sql .= " OR p.model LIKE :value";
      $sql .= " OR p.price = :price)";

      $dataParams = array_merge($dataParams, [':value' => "%" . trim($params['search']['value']) . "%", ':price' =>  preg_replace('/[^0-9]/', '', trim($params['search']['value']))]);
    }

    $sql .= " GROUP BY p.product_id";

    if (isset($params['order'])) {
      if ($params['order'][0]['column'] == '2' || $params['order'][0]['column'] == '4') {
        $sql .= " ORDER BY {$column[$params['order'][0]['column']]} {$params['order'][0]['dir']}";
      } else {
        $sql .= " ORDER BY MAX({$column[$params['order'][0]['column']]}) {$params['order'][0]['dir']}";
      }
    } else {
      $sql .= " ORDER BY MAX(p.name)";
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
      $prddata = $this->getprddet($row->product_id);
      $prdprice = $this->getprdprice($row->product_id, $row->total_quantity);
      $data[] = array(
        $prddata->name,
        $prddata->model,
        $row->total_quantity,
        '₱' . number_format($prdprice->price, 2),
        '₱' . number_format($prdprice->total_price, 2)
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

  public function getprddet($product_id)
  {
    $stmt = $this->con->prepare("SELECT p.name, p.model
    FROM oc_order_product AS p
    INNER JOIN oc_order AS o ON p.order_id = o.order_id
    WHERE p.product_id IS NOT NULL AND o.order_status_id != 0 AND p.product_id = :product_id LIMIT 1");
    $stmt->execute([":product_id" => $product_id]);
    $prd = $stmt->fetch(PDO::FETCH_OBJ);
    return $prd;
  }
  public function getprdprice($product_id, $total_quantity)
  {
    $stmt = $this->con->prepare("SELECT AVG(p.price) as price,AVG(p.price)  *  :total_quantity as total_price
    FROM oc_order_product AS p
    INNER JOIN oc_order AS o ON p.order_id = o.order_id
    WHERE p.product_id IS NOT NULL AND o.order_status_id != 0 AND p.product_id = :product_id ");
    $stmt->execute([":product_id" => $product_id, ":total_quantity" => $total_quantity]);
    $prd = $stmt->fetch(PDO::FETCH_OBJ);
    return $prd;
  }
}
