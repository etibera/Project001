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
    	default:
        break;
}

