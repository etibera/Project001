<?php
require_once '../init.php';
if(isset($_GET['action'])){
    $source = $_GET['action'];
}else{
    $source = "";
}
$data = array();
switch($source){
    case 'orders':
        $oh = $orderhistory->getOrders($_GET['customer_id']);
        echo json_encode($oh);
    break;
    case 'orderinfo':
        $oh = $orderhistory->getOrderInfo($_GET['customer_id'], $_GET['order_id']);
        echo json_encode($oh);
    break;
    case 'cancel':
        $cancel = $orderhistory->cancelOrder($_POST['order_id']); 
        echo json_encode($cancel);
    break;
}