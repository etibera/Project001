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
// 		    $res = '';
// 		    $data = $auth->insert_customer_view($_POST['platform']);
// 		    if($data){
// 		        $res = 'success';
// 		    }else{
// 		        $res = 'failed';
// 		    }
// 			echo 'test';
		break;
		case 'chinaBrandLogin':
			echo json_encode($auth->chinaBrandLogin());
		break;
    	default:
        break;
}

