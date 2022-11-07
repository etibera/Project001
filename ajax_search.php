<?php
require_once "model/Search.php";
$model = new Search();

if (isset($_GET['action'])) {
  $action = $_GET['action'];

  switch ($action) {
    case 'get':
      echo json_encode($model->getSearchProducts());
      break;
    case 'suggestion':
      echo json_encode($model->searchSuggestion());
      break;
    default:
      break;
  }
}
