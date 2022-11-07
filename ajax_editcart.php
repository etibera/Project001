
<?php
include "model/cart.php";
 
if(isset($_POST['cart_id']) && isset($_POST['qty'])) {
  $cart_id =$_POST['cart_id'];
  $qty=$_POST['qty'];
 
  $editqty = new Cart();
  $list = $editqty->editqty($cart_id, $qty);
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