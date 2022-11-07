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
        default:
        
        break;

}
