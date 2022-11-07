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
        $data = $invite->getNames($_GET['customer_id']);
        echo json_encode($data);
        break;
        case 'links':
        $data = $invite->getLinks($_GET['page'], $_GET['customer_id']);
        echo json_encode($data);
        break;
    	default:
        break;
}

