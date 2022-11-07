
<?php

 
if(isset($_POST['order_id'])){
	include "model/Order.php";
	$json = array();
	
  $model = new Order();
  $order_id =$_POST['order_id'];
  $order_pid =$_POST['order_pid'];
  $c_p_id=$_POST['c_p_id'];
  $OP_serial_code=$_POST['OP_serial_code'];
  $order_pqty=$_POST['order_pqty'];
  $order_id=$_POST['order_id'];
  $order_NOA=$_POST['order_NOA'];
  $pruduct_cost=$_POST['pruduct_cost'];
  $product_info_fb = $model->getProduct($c_p_id);
  foreach($product_info_fb as $row){
	$product_price =$row['price'];
	$product_name =$row['name'];
  }

  $isduplicate=$model->getserilal($OP_serial_code);
  if($isduplicate!=0){
  	$json['success'] = "Serial is already Exists";
  }else{
  	
  	$res=$model->addserial($order_pid,$order_pqty,$OP_serial_code,$order_id,$order_NOA,$pruduct_cost,$c_p_id,$product_price,$product_name);
  	if($res=="200"){
  		$json['success'] ="Successfully added Serial.";
  	}else{
  		$json['success'] ="Error Occured.";
  	}
  }	
  echo json_encode($json)  ; 
}else if(isset($_POST['op_id'])){
	include "model/Order.php";
	$json = array();	
  	$model = new Order();
  	$op_id =$_POST['op_id'];
    $clorder_id_s =$_POST['clorder_id_s'];
  	$res = $model->creal_serlial($op_id,$clorder_id_s);
  	if($res=="200"){
  		$json['success'] ="Success.";
  	}else{
  		$json['success'] ="Error Occured.";
  	}

 	echo json_encode($json) ;
}else{
	$json = array();
	$json['success'] ="Error Occured.";
	echo json_encode($json)  ; 
}	
?>