<?php
require_once '../init.php';
if(isset($_GET['action'])){
    $source = $_GET['action'];
}else{
    $source = "";
}
$data = array();
switch($source){
        case 'editAccount':
        $data = $customer->edit_customer($_POST);
        echo json_encode($data);
        break;
        case 'changePass':
        $data = $customer->change_password($_POST);
        echo json_encode($data);
        break;
        case 'checkEmail':
        $data = $auth->check_email($_POST['email']);
        echo json_encode($data);
        break;
        case 'checkMobileNumber':
            $data = $auth->checkNumber($_GET['number']);
            echo json_encode($data);
        break;
        case 'checkUsername':
            $data = $auth->check_username($_GET['username']);
            echo json_encode($data);
        break;
        case 'checkMobileNumberByCustomer':
            $data = $auth->checkNumberByCustomer($_GET['number'], $_GET['customer_id']);
            echo json_encode($data);
        break;
        case 'checkEmailByCustomer':
            $data = $auth->checkEmailByCustomer($_GET['email'], $_GET['customer_id']);
            echo json_encode($data);
        break;
        case 'generateSms':
            $data = $auth->generate_message($_POST['telephone']);
            echo json_encode($data);
        break;
        case 'getInfo':
            $data = $information->get_information($_GET['informationId']);
            echo json_encode($data);
        break;
    	default:
        break;
}

