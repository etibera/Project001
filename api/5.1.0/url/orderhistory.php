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
        $oh = $orderhistory->getOrders($_GET['customer_id'], $_GET['minDate'], $_GET['maxDate']);
        echo json_encode($oh);
    break;
    case 'orderinfo':
        $oh = $orderhistory->getOrderInfo($_GET['customer_id'], $_GET['order_id']);
        echo json_encode($oh);
    break;
    case 'orderHistoryDetail':
        $oh = $orderhistory->getOrderHistoryDetail($_GET['customer_id']);
        echo json_encode($oh);
    break;
    case 'orderSellerDetails': 
        $sd = $orderhistory->getSellerDetails($_GET['order_id']);
        echo json_encode($sd);
    break;
    case 'cancel':
        $cancel = $orderhistory->cancelOrder($_POST['order_id']); 
        echo json_encode($cancel);
    break;
    case 'orderTotalCount':
        echo json_encode($orderhistory->getPendingOrderTotal($_GET['customer_id']));
    break;
    case 'orderReceived':
        // echo json_encode($_POST);
        echo json_encode($orderhistory->receive($_POST['orderId'], $_POST['sellerId'], $_POST['orderNumber']));
    break;
    case 'sendMailForCancel':
        $orderhistory->sendMailForCancel($_POST['orderId']);
    break;
    case 'sendMailForReceived':
        $orderhistory->sendMailForReceived($_POST['orderId'], $_POST['sellerId'], $_POST['orderNumber']);
    break;
    case 'getOrderHistoryFilter':
        echo json_encode($orderhistory->getOrderHistoryFilter());
    break;
    case 'getOrderHistoryStatus':
        echo json_encode($orderhistory->getOrderHistoryStatus());
    break;
}