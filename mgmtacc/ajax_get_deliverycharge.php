<?php
include "model/delivery_charge.php";

if(isset($_GET['trigger'])){
 
  
$delivery = new delivery_charge();
  
  $data = array();
  
  foreach ($delivery->getdeliveryname() as $row) {
	 $data[] = array(
	                    'name' => $row['name'],
                		'id' => $row['id']

	                );                     
  }
  echo json_encode($data);
	
	
}else{
	$json = array();
	$json['success'] ="Error Occured.";
	echo json_encode($json)  ; 
}	
?>