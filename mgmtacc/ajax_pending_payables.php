<?php
include_once "model/PendingPayables.php";
$payables = new PendingPayables();


if (isset($_GET['action'])) {
  $action = $_GET['action'];

  switch ($action) {
    case 'get':
      echo json_encode($payables->get());
      break;
    case 'pay':
      echo json_encode($payables->pay());
      break;
    default:
      break;
  }
}
