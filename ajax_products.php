<?php
include_once "model/Products.php";

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

$model = new Products();
if (isset($_GET['action'])) {
  $action = $_GET['action'];

  switch ($action) {
    case 'getProducts':
      echo json_encode($model->getProductsDetails(), JSON_UNESCAPED_UNICODE);
      break;
    case 'getDiscountedPrice':
      $price = array('origPrice' => $model->getProductsDetails()['price']);
      $arr = convertToUtf($model->getDiscountedPrice());
      $newArr = array_merge($arr, $price);
      echo json_encode($newArr);
      break;
    case 'cart':
      echo json_encode(convertToUtf($model->addToCart()));
      break;
    case 'wish':
      echo json_encode(convertToUtf($model->addToWishlist()));
      break;
    case 'review':
      echo json_encode(convertToUtf($model->getReviews()));
      break;
    case 'getPagination':
      echo json_encode(convertToUtf($model->getPagination()));
      break;
    case 'getRecommendedProducts':
      echo json_encode(convertToUtf($model->getRecommendedProducts()));
      break;
    default:
      break;
  }
}
