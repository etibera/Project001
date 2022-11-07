<?php
  require_once("include/init.php");
  echo $session->is_signed_in()."<br>";
	if(isset($_SESSION['user_login'])){
 		echo "user login session Set:".$_SESSION['user_login']."<br>";
 	}else{
 		echo "Session user_login not set"."<br>";
 	}
 	if(isset($_SESSION['user_name'])){
 		echo "user_name session Set:".$_SESSION['user_name']."<br>";
 	}else{
 		echo "Session user_name not set"."<br>";;
 	}
 	if(isset($_SESSION['mobile_statuscode'])){
 		echo "mobile_statuscode session Set:".$_SESSION['mobile_statuscode']."<br>";
 	}else{
 		echo "Session mobile_statuscode not set";
 	}
 	session_destroy();
?>
