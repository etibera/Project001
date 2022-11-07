<?php
require_once '../init.php';
if(isset($_GET['action'])){
    $source = $_GET['action'];
}else{
    $source = "";
}
$data = array();
switch($source){
        case 'details':
            echo json_encode($review->getReviewDetail());
        break;
        case 'submit':
            echo json_encode($review->submitReview());
        break;
        default:
        break;
}

