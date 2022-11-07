<?php
include "model/invitees.php";

if(isset($_POST['customer_id'])&&isset($_POST['sender_id'])){
 
  $invitees = new Invitees();
  $userid =$_POST['customer_id'];
  $id =$_POST['sender_id'];
  $data = array();
  
  foreach ($invitees->getmessagedetails($userid,$id) as $row) {
	 $data[] = array(
	                    'fname' => $row['fname'],
                'id' => $row['id'],
                'timestamp' => $row['timestamp'],
                'message' => $row['message'],
                'receiver' => $row['receiver'],
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