<?php 
	include "common/header.php";
    require_once 'model/home_new.php'; 
    require_once 'model/reg_new.php';
    $modAddGoogle = new Register_new();  
    $home_new_mod=new home_new();
    $custid = isset($_SESSION['user_login']) ? $_SESSION['user_login']: 0;

    if(isset($_GET["code"])){
	    $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
	    if(!isset($token['error'])){  
	      	$google_client->setAccessToken($token['access_token']);
	      	$_SESSION['access_token'] = $token['access_token'];
	      	$google_service = new Google_Service_Oauth2($google_client);
	      	$data = $google_service->userinfo->get();
	      	/*echo"<pre>";
	      	print_r($data);*/
	      	if(!empty($data['given_name'])){
	        	$_SESSION['user_first_name'] = $data['given_name'];
	      	}
	      	if(!empty($data['family_name'])){
	        	$_SESSION['user_last_name'] = $data['family_name'];
	      	}
	      	if(!empty($data['email'])){
	        	$_SESSION['user_email_address'] = $data['email'];
	    	}
		    if(!empty($data['gender'])){
		    	$_SESSION['user_gender'] = $data['gender'];
		    }
		    if(!empty($data['picture'])){
		    	$_SESSION['user_image'] = $data['picture'];
		    }
	      	$rescustGoogle=$home_new_mod->save_customerGoogleAcc($data);
	      	if($rescustGoogle['code']=="200"){		        
	      		//print_r($rescustGoogle);
		        $reslastid=$rescustGoogle['last_id'];  
		        $cust_detGoogle=$modAddGoogle->getCustomerdetails($reslastid);
	        	if($cust_detGoogle){
		          	$modAddGoogle->add_shipping_wallet($cust_detGoogle['customer_id']);
		          	$_SESSION['regok_mobile_verify'] = 'Google Account Successfully Login'; 
		          	$session->login($cust_detGoogle);
		          	$modAddGoogle->insertactivity($cust_detGoogle['customer_id'],'login Google Account');     
		          	$locationV="<script> window.location.href='home.php";
		          	$locationV.="';</script>";
		          	if (!isset($_SESSION['getPrd_id'])) {
		          	    	echo $locationV;    
		          	}else{
		          	    $locationV="<script> window.location.href='product.php?product_id=".$_SESSION['getPrd_id']."&t=".uniqid();
        		      	$locationV.="';</script>";
        		      	echo $locationV;   
		          	}     
	        	}
	      	}
	    }
	}
?>