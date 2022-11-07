<?php
require_once '../init.php';
if(isset($_GET['action'])){
    $source = $_GET['action'];
}else{
    $source = "";
}
$data = array();
switch($source){
        case 'lists':
            if(isset($_GET['customer_id'])){
                $data = $manageHome->list(trim($_GET['customer_id']));
                echo json_encode($data);
            }
        break;
        case 'categories':
            echo json_encode($manageHome->category());
        break;
        case 'add':
           echo json_encode($manageHome->add(trim($_POST['categoryId']), trim($_POST['userId'])));
        break;
        case 'delete':
            $json = array();
            if(isset($_GET['id'])){
                if($manageHome->delete($_GET['id'])){
                    $json['success'] = "Category Successfully Deleted";
                }
            }
        break;
        default:
        
        break;

}
