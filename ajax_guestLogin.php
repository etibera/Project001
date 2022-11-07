<?php 
	include "common/headertest.php";
    require_once 'model/home_new.php'; 
    require_once 'model/reg_new.php';
    require_once "model/user.php";		
    $modelG = new Register_new();  
    $home_new_mod=new home_new();
    $user = new User();

    $username =$_GET['loginAsGuest'];
  	$res = $modelG->loginAsGuest($username);
	if($res['code']=="200"){
		$reslastid=$res['last_id'];  
		$cust_detGoogle=$modelG->getCustomerdetails($reslastid);
	    if($cust_detGoogle){	
	    	if(isset($_GET['product_id'])){
	    		$_SESSION['regok_mobile_verify'] = 'Thank You, Welcome to PESO'; 		
				$session->login($cust_detGoogle);
				$modelG->insertactivity($cust_detGoogle['customer_id'],'Guest '.$username.' Login');     
		      	$locationV="<script> window.location.href='product.php?product_id=".$_GET['product_id'];
		      	$locationV.="';</script>";
		      	echo $locationV;  

	    	}else{
	    		$_SESSION['regok_mobile_verify'] = 'Thank You, Welcome to PESO'; 		
				$session->login($cust_detGoogle);
				$modelG->insertactivity($cust_detGoogle['customer_id'],'Guest '.$username.' Login');     
		      	$locationV="<script> window.location.href='home.php";
		      	$locationV.="';</script>";
		      	echo $locationV;      

	    	}
	    	
	    }else{
	    	$_SESSION['regok_mobile_verify'] = 'Thank You, Welcome to PESO'; 
			$locationV="<script> window.location.href='home.php";
		          	$locationV.="';</script>";
		          	echo $locationV; 	    }
	}else{
		$_SESSION['regok_mobile_verify'] = $res['code']; 
		$locationV="<script> window.location.href='home.php";
		          	$locationV.="';</script>";
		          	echo $locationV;
	}
	
?>