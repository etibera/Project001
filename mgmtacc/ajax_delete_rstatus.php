
<?php
include "model/return_status.php";
 
if(isset($_POST['id'])){
 
  $id=$_POST['id'];

  $save = new returnstatus();
  $list = $save->deletestatus($id);
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