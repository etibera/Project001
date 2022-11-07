<?php 

if(isset($_POST['add_order'])){	
	require_once "model/checkoutLatest.php";	
	$checkout = new CheckoutLatest();
/*	echo'<pre>'	;
	print_r($_POST); */
	$res=$checkout->confirm_order($_POST);
	/*print_r($res);*/
}
	
?>