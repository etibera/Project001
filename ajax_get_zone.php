<?php
include "model/address.php";

if(isset($_POST['country_id'])){
 
  $address = new Address();
  $country_id =$_POST['country_id'];
  $data = array();
  
  foreach ($address->zone($country_id) as $row) {
	 $data[] = array(
	                    'zone_id' => $row['zone_id'],
	                    'zone_name' => $row['zone_name']

	                );                     
  }
  echo json_encode($data);
	
	
}else{
	$json = array();
	$json['success'] ="Error Occured.";
	echo json_encode($json)  ; 
}	
?>