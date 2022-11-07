<?php
require_once '../init.php';
if(isset($_GET['action'])){
    $source = $_GET['action'];
}else{
    $source = "";
}
$data = array();
switch($source){
        case 'partnerProgram':
        $data = $affiliate->partner_program($_GET['customer_id']);
        echo json_encode($data);
        break;
        case 'affiliateRegister':
        $data = $affiliate->register_affiliate($_POST['customer_id']);
        echo json_encode($data);
    	default:
        break;
}

