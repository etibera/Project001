<?php
require_once '../init.php';
if(isset($_GET['action'])){
    $source = $_GET['action'];
}else{
    $source = "";
}
$data = array();
switch($source){
        case 'productSharing':
            echo json_encode($sharing->productSharing($_POST['prod_id'], $_POST['cust_id'], $_POST['type']));
        break;
        case 'insertSharing':
            echo json_encode($sharing->insertSharing($_POST['product_id'], $_POST['seller_id'], $_POST['customer_id']));
        break;
        default:
        break;
}

