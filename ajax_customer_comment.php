<?php
include_once "model/Customer_comment.php";

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

$model = new Customer_comment();
if (isset($_GET['action'])) {
  $action = $_GET['action'];

  switch ($action) {    
    case 'CustomerComment':
      echo json_encode(convertToUtf($model->CustomerComment()));
    break;
    case 'followBrand':
      echo json_encode(convertToUtf($model->followBrand()));
    break;
    case 'followStore':
      echo json_encode(convertToUtf($model->followStore()));
    break; 
    case 'countFallowers':
      echo json_encode(convertToUtf($model->countFallowers()));
    break;
    case 'countFallowersFS':
      echo json_encode(convertToUtf($model->countFallowersFS()));
    break;
    case 'GenerateBeginningFallowers':
      echo json_encode(convertToUtf($model->GenerateBeginningFallowers()));
    break; 
    case 'getPagination':
      echo json_encode(convertToUtf($model->getPagination()));
    break;
    case 'SendComment':  
      $results = $model->SendComment($_POST['source_id'],$_POST['comment'],$_POST['customer_id'],$_POST['type']);
      $json['success'] = $results;
      echo json_encode($json);  
    break;
    default:
      break;
  }
}
