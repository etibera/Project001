<?php
	 require_once("include/init.php");
	require_once "model/user.php";		
	$user = new User();
	if(isset($_GET['username']) && isset($_GET['password'])) {
		$username =$_GET['username'];
	  	$email =$_GET['username'];
	  	$password=$_GET['password'];
	  	$user_found= $user->login($username,$email,'October25#');
	  	if($user_found){
             $session->login($user_found);
			$user->insertactivity($user_found['customer_id'],'login');
			$json['success']= "Successfully Login...";
		}else{
			$json['success']= "Your password or username are incorrect";
		}	
		echo json_encode($json)  ;      
		
	}else{
		$json = array();
		$json['success'] ="Error Occured.";
		echo json_encode($json)  ; 
	}
	if(isset($_SESSION['user_login'])){
 	echo "user login session Set:".$_SESSION['user_login']."<br>";
 	}else{
 		echo "Session user_login not set"."<br>";
 	}
 	if(isset($_SESSION['user_name'])){
 		echo "user_name session Set:".$_SESSION['user_name']."<br>";
 	}else{
 		echo "Session user_name not set"."<br>";
 	}
 	if(isset($_SESSION['mobile_statuscode'])){
 		echo "mobile_statuscode session Set:".$_SESSION['mobile_statuscode']."<br>";
 	}else{
 		echo "Session mobile_statuscode not set";
 	}   
?>