<?php

include "model/Product_Purchase.php";
$model = new ProductPurchase();

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
