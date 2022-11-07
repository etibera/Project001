
<?php
include "model/product.php";
 
if(isset($_POST['product'])){
    $product_id =$_POST['product'];
    $cust_id=$_POST['cust_id'];
    $quantity=1;
    $recurring_id=0;
    $product2 = new product();
    if(isset($_POST['type'])){
      if($_POST['type'] == 'ae'){
          $ae_id = $product2->getAliexpressId($product_id);
          $list = $product2->addtocart($ae_id, $quantity,$recurring_id,$cust_id,3);
          $json = array();
          if($list=="200"){
            $json['success'] ="Successfully added.";
          }else{
            $json['success'] ="Error Occured.";
          }
            echo json_encode($json); 

      }else{
          $list = $product2->addtocart($product_id, $quantity,$recurring_id,$cust_id,0);
          $json = array();
          if($list=="200"){
            $json['success'] ="Successfully added.";
          }else{
            $json['success'] ="Error Occured.";
          }
            echo json_encode($json); 
      }
    }else{
        $list = $product2->addtocart($product_id, $quantity,$recurring_id,$cust_id,0);
        $json = array();
        if($list=="200"){
          $json['success'] ="Successfully added.";
        }else{
          $json['success'] ="Error Occured.";
        }
          echo json_encode($json); 
    }
    
  
}else if(isset($_POST['product_china'])){
  $product_id =$_POST['product_china'];
  $cust_id=$_POST['cust_id'];
  $quantity=1;
  $recurring_id=0;

  $product2 = new product();
  $list = $product2->addtocart($product_id, $quantity,$recurring_id,$cust_id,1);
  $json = array();
	if($list=="200"){
		$json['success'] ="Successfully added.";
	}else{
		$json['success'] ="Error Occured.";
	}
    echo json_encode($json)  ;      
}else if(isset($_POST['product_bg'])){
  $product_id =$_POST['product_bg'];
  $cust_id=$_POST['cust_id'];
  $quantity=1;
  $recurring_id=0;

  $product2 = new product();
  $list = $product2->addtocart($product_id, $quantity,$recurring_id,$cust_id,2);
  $json = array();
  if($list=="200"){
    $json['success'] ="Successfully added.";
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