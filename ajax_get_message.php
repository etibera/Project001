<?php
include "model/invitees.php";

if(isset($_GET['customer_id'])){
 
  $invitees = new Invitees();
  $customer_id =$_GET['customer_id'];
  $data = array();
  
  foreach ($invitees->getmessage($customer_id) as $row) {
	 $data[] = array(
	                    'fname' => $row['fname'],
		                'unread' => $row['unread'],
		                'sender' => $row['sender']

	                );                     
  }
  echo json_encode($data);
	
	
}else{
	$json = array();
	$json['success'] ="Error Occured.";
	echo json_encode($json)  ; 
}	
?>