<?php
include "./model/PageType.php";

$page = new PageType();

if (isset($_GET['action'])) {
  $action = $_GET['action'];

  switch ($action) {
    case 'get':
      echo json_encode($page->get());
      break;
    case 'insert':
      echo json_encode($page->insert());
      break;
    case 'update':
      if ($id = $_POST['id']) {
        echo json_encode($page->update($id));
      }
      break;
    case 'getSingle':
      if ($id = $_POST['id']) {
        echo json_encode($page->getSingle($id));
      }
      break;
    default:
      break;
  }
}
