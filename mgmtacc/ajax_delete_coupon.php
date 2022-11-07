<?php
include "model/Coupon.php";
 
if(isset($_POST['coupon_id'])) {
  $id =$_POST['coupon_id'];
  
  $coupon = new Coupon();
  $stats = $coupon->coupon_delete($id);
	$json['success'] = $stats;
	echo json_encode($json);       
	
}else{
	$json['success'] ="Error Occured...";
	echo json_encode($json) ; 
}	
?>