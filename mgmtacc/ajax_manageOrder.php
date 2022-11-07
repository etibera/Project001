<?php 
    if(isset($_GET['action'])){
    $source = $_GET['action'];
    }else{
        $source = "";
    }
    switch($source){
        case 'DeleteOrderStatus':    
            require_once "model/manage_order_status.php"; 
            $model = new OrderStatus();   
            $results = $model->DeleteOrderStatus($_POST['id']);
            $json['success'] =$results;
            echo json_encode($json);            
        break; 
        case 'AddOrderStaus':    
            require_once "model/manage_order_status.php"; 
            $model = new OrderStatus();   
            $results = $model->AddOrderStaus($_POST['name'],$_POST['type'],$_POST['status_type']);
            $json['success'] =$results;
            echo json_encode($json);            
        break; 
        case 'UpdateOrderStaus':    
            require_once "model/manage_order_status.php"; 
            $model = new OrderStatus();   
            $results = $model->UpdateOrderStaus($_POST['name'],$_POST['type'],$_POST['id'],$_POST['status_type']);
            $json['success'] =$results;
            echo json_encode($json);            
        break;
        default:
        break;
    }
