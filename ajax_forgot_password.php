<?php 
	if(isset($_GET['action'])){
	    $source = $_GET['action'];
	    session_start();
		
	}else{
	    $source = "";
	}
	switch($source){
        case 'save_token':        		
			require_once 'model/forgot_password.php';
			$FPMod=new ForgotPasswowrd();
			$json=array();
			$result=$FPMod->sendForgotPass($_POST['username']);
			$json['success']=$result;
      		echo json_encode($json);            
        break;        
    	default:
        break;
	}
?>