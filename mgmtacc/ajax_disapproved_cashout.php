
<?php
include "model/cash_out_request.php";
 
if(isset($_POST['id']) && isset($_POST['remarks'])){
  $id =$_POST['id'];
  $remarks =$_POST['remarks'];
 


  $cashout = new cashout();
  $list = $cashout->disapprovedcash($id,$remarks);
  $json = array();
	if($list=="200"){
		$json['success'] ="Successfully Disapproved.";
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