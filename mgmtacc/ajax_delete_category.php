<?php
include "model/Category.php";
 
if(isset($_POST['category_id'])) {
  $id =$_POST['category_id'];
  
  $category = new Category();
  $stats = $category->category_delete($id);
	$json['success'] = $stats;
	echo json_encode($json);       
	
}else{
	$json['success'] ="Error Occured...";
	echo json_encode($json) ; 
}	
?>