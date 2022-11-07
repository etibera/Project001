<?php
require_once '../init.php';
if(isset($_GET['action'])){
    $source = $_GET['action'];
}else{
    $source = "";
}
$data = array();
switch($source){
        case 'getMessages':
            if(isset($_GET['customer_id'])){
                $data = $message->getMessages(trim($_GET['customer_id']));
                echo json_encode($data);
            }
        break;
        case 'getSendbox':
            if(isset($_GET['customer_id'])){
                $data = $message->getSendBox(trim($_GET['customer_id']), $_GET['pageNumber']);
                echo json_encode($data);
            }
        break;
        case 'getInbox':
            if(isset($_GET['customer_id'])){
                $data = $message->getInbox(trim($_GET['customer_id']), $_GET['pageNumber'], $_GET['search']);
                echo json_encode($data);
            }
        break;
        case 'getNotification':
            if(isset($_GET['customer_id'])){
                $data = $message->getNotification(trim($_GET['customer_id']));
                echo json_encode($data);
            }
        break;
        case 'getMessagesThread':
            if(isset($_GET['sender']) &&  isset($_GET['receiver']) && isset($_GET['read']) ){
                $data = $message->getMessagesThread(trim($_GET['sender']), trim($_GET['receiver']), trim($_GET['read']));
                echo json_encode($data);
            }
        break;
        case 'reply':
            if(isset($_POST['sender_id']) &&  isset($_POST['customer_id']) && isset($_POST['msg']) ){
                $data = $message->reply($_POST['sender_id'], $_POST['customer_id'], $_POST['msg']);
                echo json_encode($data);
            }
        break;
        case 'messageDetail':
            $data = $message->messageDetail($_GET['sellerId']);
            echo json_encode($data);
        break;
        case 'getNewMessage':
            $data = $message->getNewMessage($_GET['lastId'], $_GET['customer_id'], $_GET['seller_id']);
            echo json_encode($data);
        break;
        case 'getAdminMessage':
            $data = $message->getAdminMessage($_GET['customer_id']);
            echo json_encode($data);
        default:
        
        break;

}
