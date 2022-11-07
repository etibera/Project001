<?php
require_once "model/checkoutLatest.php";
$checkout = new CheckoutLatest();
$res=$checkout->SendEmailallstoreCustomer(17,2403,""); 
$json=array();
$json['data']=$res;
echo json_encode($json); 
echo '<pre>' ;
print_r($res);
?>