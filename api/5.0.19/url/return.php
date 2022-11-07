<?php
require_once '../init.php';
if(isset($_GET['action'])){
    $source = $_GET['action'];
}else{
    $source = "";
}
$data = array();
switch($source){
        case 'list':
            echo json_encode($return->list(trim($_GET['id'])));
        break;
        case 'info':
            echo json_encode($return->info($_GET['user_id'], $_GET['return_id']));
        break;
        case 'add':
            $data = $return->add($_POST);
            echo json_encode($data);
        break;
        default:
        break;
}

