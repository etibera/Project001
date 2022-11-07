<?php
require_once '../init.php';
if(isset($_GET['action'])){
    $source = $_GET['action'];
}else{
    $source = "";
}
$data = array();
switch($source){
    case 'login':
       	$login = $auth->login();
       	if($login){
       		$data = $login;
       	}else{
       		$data['error'] = 'No match for Username and/or Password.';
       	}
       	echo json_encode($data);
        break;
		case 'register':
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);
		$email = trim($_POST['email']);
		if($auth->check_username($username)){
			$data['error'] = 'Username is already exists';
		}elseif ($auth->check_email($email)){
			$data['error'] = 'Email is already exists';
		}else{
			$data = $auth->register();
		}
        echo json_encode($data);
		break;
		case 'customerView':
			echo json_encode($auth->insert_customer_view($_POST['platform']));
		break;
		case 'chinaBrandLogin':
			echo json_encode($auth->chinaBrandLogin());
		break;
		case 'resendCode':
		    echo json_encode($auth->resend_code($_POST['customer_id'], $_POST['telephone']));
		break;
		case 'verifyCode':
		    echo json_encode($auth->verify_code($_POST['customer_id'], $_POST['code']));
		break;
		case 'updateContactNumber':
		    echo json_encode($auth->update_contact_number($_POST['customer_id'], $_POST['telephone']));
		break;
		case 'forgotPassword':
			echo json_encode($forgotPassword->forgotPassword($_GET['username']));
		break;
		case 'googleLogin':
			echo json_encode($auth->googleLogin($_POST['googleData']));
		break;
	    case 'appleLogin':
			echo json_encode($auth->appleLogin($_POST['appleData']));
		break;
	    case 'facebookLogin':
			echo json_encode($auth->facebookLogin($_POST['facebookData']));
		break;
		case 'guestSignup':
			echo json_encode($auth->guestSignup());
		break;
    	default:
        break;
}

