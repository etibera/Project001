<?php
require_once '../init.php';
if(isset($_GET['action'])){
    $source = $_GET['action'];
}else{
    $source = "";
}
$data = array();
switch($source){
        case 'checkoutDetails':
        $details = $checkout->checkoutDetails($_GET['product_ids'],$_GET['customer_id'], $_GET['total_price']);
        echo json_encode($details);
        break;
        case 'discountWallet':
        $total = $wallet->getDiscountWaletTotal($_GET['customer_id']);
        echo json_encode($total);
        break;
        case 'cashWallet':
            $total = $wallet->getCashWalletTotal($_GET['customer_id']);
            echo json_encode($total);
        break;
        case 'addOrder':
            $add = $checkout->addOrder($_POST['product_ids'], $_POST['token']);
            echo json_encode($add);
        break;
        default:
        break;
}

