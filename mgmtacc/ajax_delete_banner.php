<?php
include "model/Banner.php";
 
if(isset($_POST['banner_id'])) {
  $id =$_POST['banner_id'];
  
  $category = new Banner();
  $stats = $category->banner_delete($id);
	$json['success'] = $stats;
	echo json_encode($json);       
	
}else{
	$json['success'] ="Error Occured...";
	echo json_encode($json) ; 
}	
?>