<?php
include "model/Coupon.php";
 
if(isset($_GET['searchproduct'])) {
  
    $coupon = new Coupon();
    $list = $coupon->product();
    echo json_encode($list);       
    
}else if(isset($_GET['searchcategory'])) {
  
    $coupon = new Coupon();
    $list = $coupon->category();
    echo json_encode($list);       
    
}else{
    $json['success'] ="Error Occured...";
    echo json_encode($json) ; 
}    
?>