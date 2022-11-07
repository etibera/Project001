
<?php
include "model/order_status.php";
 
if(isset($_POST['id'])&&isset( $_POST['name']) ){
  $name =$_POST['name'];
  $id=$_POST['id'];

  $save = new orderstatus();
  $list = $save->updatestatus($id,$name);
  $json = array();
	if($list=="200"){
		$json['success'] ="Successfully Updated.";
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