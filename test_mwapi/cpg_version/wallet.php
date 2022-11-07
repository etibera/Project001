<?php
require_once '../init.php';
if(isset($_GET['action'])){
    $source = $_GET['action'];
}else{
    $source = "";
}
$data = array();
switch($source){
        case 'discountWalletDetails':
            echo json_encode($wallet->getDiscountWalletDetails($_GET['id']));
        break;
        case 'cashWalletDetails':
            echo json_encode($wallet->getCashWalletDetails($_GET['id']));
        break;
        case 'cashOut':
            echo json_encode($wallet->cashOut($_POST));
        break;
        default:
        break;
}

