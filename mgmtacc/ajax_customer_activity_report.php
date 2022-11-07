<?php
include_once "model/CustomerActivityReport.php";

$model = new CustomerActivityReport();

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
