
<?php
include "model/orderhistory.php";
 
if(isset($_POST['oid']) && isset($_POST['pymnt']) && isset($_POST['cid'])) {
  $order_id =$_POST['oid'];
  $payment=$_POST['pymnt'];
  $customer_id=$_POST['cid'];
 
  $order = new OrderHistory();
  $stmt = $order->order_cancel($order_id, $payment, $customer_id);
  $json = array();
  $json['success'] = $stmt;

  echo json_encode($json);       	
	
}else{
	$json = array();
  	$json['success'] ="Error Occured.";
	echo json_encode($json)  ; 
}	
?>