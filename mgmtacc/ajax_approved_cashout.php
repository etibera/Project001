
<?php
include "model/cash_out_request.php";
 
if(isset($_POST['id']) ){
  $id =$_POST['id'];
 


  $cashout = new cashout();
  $list = $cashout->approvedcash($id);
  $json = array();
	if($list=="200"){
		$json['success'] ="Successfully Approved.";
	}else{
		$json['success'] ="Error Occured.";
	}
    echo json_encode($json)  ;             
	
	
}else{
	$json = array();
	$json['success'] ="Error Occured.";
	echo json_encode($json)  ; 
}	
?>