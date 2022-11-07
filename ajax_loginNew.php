<?php 
	include "common/header.php";
    require_once 'model/home_new.php'; 
    require_once 'model/reg_new.php';
    require_once "model/user.php";		
    $modAddGoogle = new Register_new();  
    $home_new_mod=new home_new();
    $user = new User();

    $username =$_GET['username'];
  	$email =$_GET['username'];
  	$password=$_GET['password'];
  	$user_found= $user->login($username,$email,$password);
  	if($user_found){
		$_SESSION['regok_mobile_verify'] = 'Account Successfully Login'; 
		$session->login($user_found);
		$modAddGoogle->insertactivity($user_found['customer_id'],$user_found['firstname'].' logged in');     
		$locationV="<script> window.location.href='home.php";
		          	$locationV.="';</script>";
		          	echo $locationV;      
	}else{
		$_SESSION['regok_mobile_verify'] = "Your password or username are incorrect";
		$locationV="<script> window.location.href='home.php";
		$locationV.="';</script>";
		echo $locationV;    
	}	
			
?>