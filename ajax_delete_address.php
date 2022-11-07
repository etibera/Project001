<?php
include "model/address.php";
 
if(isset($_POST['address_id'])) {
  $id =$_POST['address_id'];
  
  $address = new Address();
  $stats = $address->delete_address($id);
	$json['success'] = $stats;
	echo json_encode($json);       
	
	
}else{
	$json['success'] ="Error Occured...";
	echo json_encode($json) ; 
}	
?>