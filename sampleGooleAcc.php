<?php 
	require './composer/vendor/autoload.php';
	$google_client= new Google_Client();
	$google_client->setClientId('1064903567645-977t3me9dbk0bnqhttkpa629scq7b0gh.apps.googleusercontent.com');
	$google_client->setClientSecret('8b-GG1A0vO6I_B4W1hGpWmkD');
	$google_client->setRedirectUri('https://pesoapp.ph/sampleGooleAcc.php');
	//$google_client->setRedirectUri('https://pesoapp.ph/googleAcc.php');
	$google_client->addScope('email');
	$google_client->addScope('profile');
	session_start();	
	$login_button = '';

//This $_GET["code"] variable value received after user has login into their Google Account redirct to PHP script then this variable value has been received
if(isset($_GET["code"])){
	$token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
	if(!isset($token['error'])){  
		$google_client->setAccessToken($token['access_token']);
		$_SESSION['access_token'] = $token['access_token'];
  		$google_service = new Google_Service_Oauth2($google_client);
		$data = $google_service->userinfo->get();
		echo "<pre>";
		print_r($data);
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
 	}
}

if(!isset($_SESSION['access_token'])){
 //Create a URL to obtain user authorization
 $login_button = '<a href="'.$google_client->createAuthUrl().'"><img src="assets/sign-in-with-google.png" /></a>';
}

?>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>PHP Login using Google Account</title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
  
 </head>
 <body>
  <div class="container">
   <br />
   <h2 align="center">PHP Login using Google Account</h2>
   <br />
   <div class="panel panel-default">
   <?php
   if($login_button == '')
   {
    echo '<div class="panel-heading">Welcome User</div><div class="panel-body">';
    echo '<img src="'.$_SESSION["user_image"].'" class="img-responsive img-circle img-thumbnail" />';
    echo '<h3><b>Name :</b> '.$_SESSION['user_first_name'].' '.$_SESSION['user_last_name'].'</h3>';
    echo '<h3><b>Email :</b> '.$_SESSION['user_email_address'].'</h3>';
    echo '<h3><a href="logout.php">Logout</h3></div>';
   }
   else
   {
    echo '<div align="center">'.$login_button . '</div>';
   }
   ?>
   </div>
  </div>
 </body>
</html>