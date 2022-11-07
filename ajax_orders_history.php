<?php
include_once "model/OrdersHistory.php";
$model = new OrdersHistory();

if (isset($_GET['action'])) {
  $action = $_GET['action'];

  switch ($action) {
    case 'get':
      echo json_encode($model->get());
      break;
    case 'getRowCount':
      echo json_encode($model->getRowCount());
      break;
    case 'cancelOrder':
      echo json_encode($model->cancelOrders());
      break;
    default:
      break;
  }
}
