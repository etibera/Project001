<?php
require_once '../init.php';
if(isset($_GET['action'])){
    $source = $_GET['action'];
}else{
    $source = "";
}
$data = array();
switch($source){
        case 'products':
        $cart = $cart->get_products($_GET['customer_id'], $_GET['token']);
        echo json_encode($cart);
        break;
        case 'add':
        $cart = $cart->add();
        echo json_encode($cart);
        break;
        case 'delete':
        $cart = $cart->multipleDeleteCart($_GET['ids'], $_GET['customer_id']);
        echo json_encode($cart);
        break;
        case 'changeQuantity':
        $cart = $cart->changeQuantity();
        echo json_encode($cart);
        break;
        case 'totalCart':
        echo $cart->totalCart($_GET['customer_id']);
        break;
    	default:
        break;
}

