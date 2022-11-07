<?php

include_once "model/PendingReceivablesNew.php";

$model = new PendingReceivables();

if (isset($_GET['action'])) {
  $action = $_GET['action'];

  switch ($action) {
    case 'get':
      echo json_encode($model->get());
      break;
    case 'getOrderStatus':
      echo json_encode($model->getOrderStatus());
      break;
    case 'pay':
      echo json_encode($model->pay());
      break;
    default:
      break;
  }
}
