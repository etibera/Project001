<?php
require_once "model/checkoutLatest.php";

function convertToUtf($data)
{
  if (is_array($data)) {
    foreach ($data as $key => $value) {
      $data[$key] = convertToUtf($value);
    }
  }
  if (is_string($data)) {
    return utf8_encode($data);
  }
  return $data;
}

$model = new CheckoutLatest();
if (isset($_GET['action'])) {
  $action = $_GET['action'];

  switch ($action) {    
    case 'CartTransfer':
      echo json_encode(convertToUtf($model->CartTransfer()));
    break;
    
    
    default:
      break;
  }
}
