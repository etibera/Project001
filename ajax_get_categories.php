<?php
include "model/manage_homepage.php";
  $model = new manage_homepage();
  $data = $model->category_list();
  echo json_encode($data);	
?>