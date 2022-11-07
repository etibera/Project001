
<?php
include "model/delivery_charge.php";
 
if(isset($_POST['id'])){
 
  $id=$_POST['id'];

  $save = new delivery_charge();
  $list = $save->deletedelivery($id);
  $json = array();
	if($list=="200"){
		$json['success'] ="Successfully Deleted.";
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