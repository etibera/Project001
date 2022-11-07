<?php
include_once "model/OrderStatus.php";

$model = new OrderStatus();

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
