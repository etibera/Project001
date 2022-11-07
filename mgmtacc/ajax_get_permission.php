<?php
include "model/permission.php";

if(isset($_GET['user_id'])&&isset($_GET['user_page'])){

  $id =$_GET['user_id'];
  $page=$_GET['user_page'];
  
$perm = new permission();
  
  $data = array();
  
  foreach ($perm->getperm($id,$page) as $row) {
	 $data[] = array(
	                    'user_id' => $row['user_id'],
                		'user_pages' => $row['user_pages']

	                );                     
  }
  echo json_encode($data);
	
	
}else{
	$json = array();
	$json['success'] ="Error Occured.";
	echo json_encode($json)  ; 
}	
?>