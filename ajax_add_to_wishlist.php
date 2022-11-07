
<?php
include "model/wishlist.php";
 
if(isset($_POST['product'])){
  $product_id =$_POST['product'];
  $cust_id=$_POST['cust_id'];
 
  $wishlist = new Wishlist();
  $list = $wishlist->addtowishlist($product_id,$cust_id);
  $json = array();
	if($list=="200"){
		$json['success'] ="Successfully added to Wishlist";
	}
	else if($list=="201"){
		$json['success'] ="This product already exists in Wishlist.";
	}
	else{
		$json['success'] ="Error Occured.";
	}
    echo json_encode($json)  ;       
	
	
}else{
	$json = array();
	$json['success'] ="Error Occured.";
	echo json_encode($json)  ; 
}	
?>