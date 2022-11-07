
<?php
include "model/delivery_charge.php";
 
if(isset($_POST['name']) && isset($_POST['max_quantity'])&& isset($_POST['convert_quantity'])&&  isset($_POST['amount'])&& isset($_POST['delivery_option'])){
  $name =$_POST['name'];
  $max_quantity=$_POST['max_quantity'];
  $convert_quantity=$_POST['convert_quantity'];
  $amount=$_POST['amount'];
  $amountprv=$_POST['amountprv'];
  $delivery_option=$_POST['delivery_option'];

  $save = new delivery_charge();
  $list = $save->savedelivery($name, $max_quantity, $convert_quantity,$amount,$delivery_option,$amountprv);
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