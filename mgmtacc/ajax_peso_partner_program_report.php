<?php
include_once "model/PesoPartnerProgramReport.php";

$model = new PesoPartnerProgramReport();

function converttoutf($data)
{
  if (is_array($data)) {
    foreach ($data as $key => $value) {
      $data[$key] = converttoutf($value);
    }
  } else if (is_string($data)) {
    return utf8_encode($data);
  }
  return $data;
}

if (isset($_GET['action'])) {
  $action = $_GET['action'];

  switch ($action) {
    case 'get':
      echo json_encode(converttoutf($model->get()));
      break;
    case 'getBranches':
      echo json_encode(converttoutf($model->getBranches()));
      break;
    case 'recommendDetails':
      $id = $_POST['id'] ?? null;
      echo json_encode(converttoutf($model->recommendDetails($id)));
      break;
    case 'cashWalletDetails':
      $id = $_POST['id'] ?? null;
      echo json_encode(converttoutf($model->cashWalletDetails($id)));
      break;
    default:
      break;
  }
}
