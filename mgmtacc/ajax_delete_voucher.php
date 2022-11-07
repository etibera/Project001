<?php
include "model/Voucher.php";
 
if(isset($_POST['voucher_id'])) {
  	$id =$_POST['voucher_id'];
	if($_POST['type'] == '0') {
		$voucher = new Voucher();
		$stats = $voucher->voucher_delete($id);
		$json['success'] = $stats;
		echo json_encode($json);     
	}else if($_POST['type'] == '1') {
		$voucher = new Voucher();
		$stats = $voucher->voucher_theme_delete($id);
		$json['success'] = $stats;
		echo json_encode($json);     
	}  
	
}else{
	$json['success'] ="Error Occured...";
	echo json_encode($json) ; 
}	
?>