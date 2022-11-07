<?php
include_once "model/ProductsViewedReport.php";

$model = new ProductViewedReport();

if (isset($_GET['action'])) {
  $action = $_GET['action'];

  switch ($action) {
    case 'get':
      echo json_encode($model->get());
      break;
    default:
      break;
  }
}
