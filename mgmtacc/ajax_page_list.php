<?php
include "./model/PageList.php";

$page = new PageList();

if (isset($_GET['action'])) {
  $action = $_GET['action'];

  switch ($action) {
    case 'get':
      echo json_encode($page->get());
      break;
    case 'getType':
      echo json_encode($page->getType());
      break;
    case 'getSingle':
      if ($id = $_POST['id']) {
        echo json_encode($page->getSingle($id));
      }
      break;
    case 'insert':
      echo json_encode($page->insert());
      break;
    case 'update':
      if ($id = $_POST['id']) {
        echo json_encode($page->update($id));
      }
      break;
    default:
      break;
  }
}
