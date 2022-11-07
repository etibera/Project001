
<?php
include "model/order_status.php";
 
if(isset($_POST['name'])){
  $name =$_POST['name'];
  


  $save = new orderstatus();
  $list = $save->savestatus($name);
  $json = array();
	if($list=="200"){
		$json['success'] ="Successfully Saved.";
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