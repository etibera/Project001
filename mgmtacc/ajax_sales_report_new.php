<?php
include './model/Sales_Report_New.php';
$model = new SalesReport();

if (isset($_GET['action'])) {
  $action = $_GET['action'];

  switch ($action) {
    case 'get':
      function utf8ize($d)
      {
        if (is_array($d)) {
          foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
          }
        } else if (is_string($d)) {
          return utf8_encode($d);
        }
        return $d;
      }
      echo json_encode($model->get());
      // echo json_encode(utf8ize($model->get()));
      // echo json_last_error_msg();
      break;
    default:
      break;
  }
}
