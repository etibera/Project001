<?php
require_once '../init.php';
if(isset($_GET['action'])){
    $source = $_GET['action'];
}else{
    $source = "";
}
$data = array();
switch($source){
        case 'autocomplete':
                echo json_encode($search->autocomplete(trim($_POST['searchValue'])));
        break;
        case 'recommend':
            echo json_encode($search->recommendation());
        break;
        default:
        break;
}

