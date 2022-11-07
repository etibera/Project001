<?php

include_once "model/Review.php";
$model = new Review();

if (isset($_GET['action'])) {
  $action = $_GET['action'];

  switch ($action) {
    case 'get':
      echo json_encode($model->get());
      break;
    case 'generate':
      echo json_encode($model->generateReview());
      break;
    case 'submit':
      echo json_encode($model->submit());
      break;
    default:
      break;
  }
}
