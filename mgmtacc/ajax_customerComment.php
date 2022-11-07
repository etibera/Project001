<?php
include_once "model/CustomerComment.php";
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

$model = new CustomerComment();

if (isset($_GET['action'])) {
  $action = $_GET['action'];
  switch ($action) {
    case 'getCustomerComment':
     echo json_encode(convertToUtf($model->getCustomerComment()));
    break;
    case 'ApproveComment':
      echo json_encode($model->ccApprove());
    break;
    default:
      break;
  }
}
