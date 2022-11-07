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
            echo json_encode($wishlist->lists($_GET['customer_id']));
        break;
        case 'add':
            echo json_encode($wishlist->add($_POST['customer_id'], $_POST['product_id'], $_POST['p_type']));
        break;
        case 'delete':
            echo json_encode($wishlist->delete($_POST['customer_id'], $_POST['product_id'], $_POST['p_type']));
        break;
        default:
        break;
}

