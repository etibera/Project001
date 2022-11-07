<?php
include "model/manage_homepage.php";

if(isset($_POST['arrayItems'])) {
	$model = new manage_homepage();
	$data = $model->add_categories($_POST['arrayItems']);
	$json['success'] = $data;
	echo json_encode($json);		
}else if(isset($_POST['category_id'])) {
	$model = new manage_homepage();
	$data = $model->delete_category($_POST['category_id']);
	$json['success'] = $data;
	echo json_encode($json);
}else{
	$json['success'] ="Error Occured...";
	echo json_encode($json) ; 
}
?>