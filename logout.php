<?php
require_once("include/init.php");
require_once 'model/reg_new.php';
$ModCustAct = new Register_new(); 
$ModCustAct->insertactivity($_SESSION['user_login'],'logOut');  
if(isset($_SESSION['access_token'])){ 
	require 'composer/vendor/autoload.php';
	$google_client= new Google_Client();
	$google_client->setClientId('1064903567645-977t3me9dbk0bnqhttkpa629scq7b0gh.apps.googleusercontent.com');
	$google_client->setClientSecret('8b-GG1A0vO6I_B4W1hGpWmkD');
	$google_client->setRedirectUri('https://pesoapp.ph/googleAcc.php');
	$google_client->addScope('email');
	$google_client->addScope('profile');
	$google_client->revokeToken($_SESSION['access_token']);
}
$session->logout();
session_destroy();
redirect("index");
?>