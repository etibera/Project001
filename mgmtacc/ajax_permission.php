<?php
include_once "./model/Permission_New.php";

$permission = new Permission();

if (isset($_GET['action'])) {
  $action = $_GET['action'];

  switch ($action) {
    case 'get':
      echo json_encode($permission->get());
      break;
    case 'getType':
      echo json_encode($permission->getType());
      break;
    case 'getPage':
      if ($id = $_POST['id']) {
        echo json_encode($permission->getPage($id));
      }
      break;
    case 'getSingleUser':
      if ($id = $_POST['id']) {
        echo json_encode($permission->getSingleUser($id));
      }
      break;
    case 'insert':
      echo json_encode($permission->insert());
      break;
    case 'insertPermission':
      echo json_encode($permission->insertPermission());
      break;
    case 'delete':
      if ($id = $_POST['id']) {
        echo json_encode($permission->delete($id));
      }
      break;
    case 'update':
      if ($id = $_POST['id']) {
        echo json_encode($permission->update($id));
      }
      break;
    case 'updatePermission':
      if ($id = $_POST['id']) {
        echo json_encode($permission->updatePermission($id));
      }
      break;
    default:
      break;
  }
}
