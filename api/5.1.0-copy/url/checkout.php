<?php
require_once '../init.php';
if(isset($_GET['action'])){
    $source = $_GET['action'];
}else{
    $source = "";
}
$data = array();
switch($source){
        case 'sendOrderEmail':
        $checkout->sendOrderEmail($_POST['orderStatusId'], $_POST['orderId']);
        break;
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
        case 'shippingWallet':
            $total = $wallet->getShippingWalletTotal($_GET['customer_id']);
            echo json_encode($total);
        break;
        case 'addOrder':
            $add = $checkout->addOrder($_POST['cart_ids'], $_POST['token']);
            echo json_encode($add);
        break;
        case 'shippingMethodList':
            $data = $checkout->shippingMethodList($_GET['address_id'], $_GET['cart_ids'], $_GET['customer_id'], $_GET['branch_ids'], $_GET['seller_ids'], $_GET['total']);
            echo json_encode($data);
        break;
        case 'shippingMethodListByStoreBranch':
            $data = $checkout->shippingMethodListByStoreBranch($_GET['branch_id'], $_GET['seller_id'], $_GET['customer_id'], $_GET['cart_ids'], $_GET['address_id']);
            echo json_encode($data);
        break;
        default:
        break;
}

