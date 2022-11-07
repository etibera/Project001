
<?php
include "model/cash_out_request.php";
 
if(isset($_POST['id']) && isset($_POST['refnum'])&& isset($_POST['type'])){
  $id =$_POST['id'];
  $refnum =$_POST['refnum'];
  $type =$_POST['type'];
 


  $cashout = new cashout();
  $list = $cashout->saveref($id,$refnum,$type);
  $json = array();
	if($list=="200"){
		$json['success'] ="Successfully Added.";
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