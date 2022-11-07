<?php
require_once "../include/database.php";

class ProductViewedReport
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

    $column = ["opd.name", "op.model", "", "total_views", "percentage"];

    $sql = "SELECT DISTINCT pv.product_id,
    SUM(ANY_VALUE(pv.total_views)) as total_views,
    ROUND( (SUM(pv.total_views) / (select SUM(total_views) from product_views) * 100), 2 ) as percentage,
    MAX(opd.name) as product_name,
    MAX(op.model) as product_model
    FROM product_views AS pv
    INNER JOIN oc_product_description AS opd ON opd.product_id = pv.product_id
    INNER JOIN oc_product as op ON op.product_id = pv.product_id WHERE pv.p_type = 0";

    $base = $this->con->prepare("SELECT DISTINCT pv.product_id,
    SUM(ANY_VALUE(pv.total_views)) as total_views,
    ROUND( (SUM(pv.total_views) / (select SUM(total_views) from product_views) * 100), 2 ) as percentage,
    MAX(opd.name) as product_name,
    MAX(op.model) as product_model
    FROM product_views AS pv
    INNER JOIN oc_product_description AS opd ON opd.product_id = pv.product_id
    INNER JOIN oc_product as op ON op.product_id = pv.product_id WHERE pv.p_type = 0 GROUP BY pv.product_id");
    $base->execute();
    $totalCount = $base->rowCount();

    if (!empty($params['search']['value'])) {
      $sql .= " AND";
      $sql .= " (opd.name LIKE :value";
      $sql .= " OR op.model LIKE :value)";

      $dataParams = array_merge($dataParams, [':value' => "%" . trim($params['search']['value']) . "%"]);
    }

    $sql .= " GROUP BY pv.product_id";

    $filterData = $this->con->prepare($sql);
    $filterData->execute($dataParams);
    $filterCount = $filterData->rowCount();

    if (isset($params['order'])) {
      if ($params['order'][0]['column'] == 3 || $params['order'][0]['column'] == 4) {
        $sql .= " ORDER BY {$column[$params['order'][0]['column']]} {$params['order'][0]['dir']}";
      } else {
        $sql .= " ORDER BY MAX({$column[$params['order'][0]['column']]}) {$params['order'][0]['dir']}";
      }
    } else {
      $sql .= " ORDER BY total_views DESC";
    }


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

      $data[] = array(
        $row->product_name,
        $row->product_model,
        "Local Products",
        $row->total_views,
        $row->percentage
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
}
