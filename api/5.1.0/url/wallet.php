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
        case 'shippingWalletLists':
            echo json_encode($wallet->getShippingWallet($_GET['id']));
        break;
        case 'shippingWalletTotal':
            echo json_decode($wallet->getShippingWalletTotal($_GET['id']));
        break;
        case 'discountWalletTotal':
            echo json_decode($wallet->getDiscountWaletTotal($_GET['id']));
        break;
        case 'cashWalletTotal':
            echo json_decode($wallet->getCashWalletTotal($_GET['id']));
        break;
        case 'cashOut':
            echo json_encode($wallet->cashOut($_POST));
        break;
        default:
        break;
}

