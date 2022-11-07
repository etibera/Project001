<?php
include './model/Customers.php';

$model = new Customers();

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

if (isset($_GET['action'])) {
  $action = $_GET['action'];

  switch ($action) {
    case 'get':
      $type = "notset";
      if (isset($_GET['type'])) {
        $type = $_GET['type'];
      }
      echo json_encode(convertToUtf($model->get($type)));
      break;
    default:
      break;
  }
}
