
<?php 
	if(isset($_GET['action'])){
    $source = $_GET['action'];
	}else{
	    $source = "";
	}
	switch($source){
        case 'SaveOrderHistory':  
			require_once "../mgmtseller/model/order.php";  
			$json=array();
			$model=new order; 
			$seller_id = trim($_POST['seller_id']);
			$order_id = trim($_POST['order_id']);
			$order_status_id = trim($_POST['order_status_id']);
			$comment = trim($_POST['comment']);
			$result = $model->insert_order_history_seller($order_status_id, $comment, $order_id,$seller_id);
			if($result['code']=="200"){
				$order = $model->order_details_seller($order_id,$seller_id);	
				$json['success'] =$result['message'];				
			}else{
				$json['success'] =$result['message'];
			}			
      		echo json_encode($json);            
        break; 
        case 'StoreOrderHistory':  
			require_once "model/viewStoreOrderDetails.php";
			$json=array();
			$model=new StoreOrder;	
			$seller_id = trim($_POST['seller_id']);
			$order_id = trim($_POST['order_id']);
			$order_status_id = trim($_POST['order_status_id']);
			$comment = trim($_POST['comment']);
			$order_number = trim($_POST['order_number']);
			$result = $model->saveStoreOrderHistory($order_status_id, $comment, $order_id,$seller_id,$order_number);
			if($result['code']=="200"){		
				$json['success'] =$result['message'];				
			}else{
				$json['success'] =$result['message'];
			}			
      		echo json_encode($json);         
        break; 
        case 'SaveOrderHistoryPackage':  
			require_once "../mgmtseller/model/order.php";  
			$json=array();
			$model=new order; 
			$seller_id = trim($_POST['seller_id']);
			$order_id = trim($_POST['order_id']);
			$order_status_id = trim($_POST['order_status_id']);
			$package_type = trim($_POST['package_type']);
			$comment = trim($_POST['comment']);
			$result = $model->insert_order_history_seller($order_status_id, $comment, $order_id,$seller_id);
			if($result['code']=="200"){
				$order = $model->order_details_seller($order_id,$seller_id);	
				$model->order_seller_shipment($order_id,$seller_id,$package_type);	
				$json['success'] =$result['message'];				
			}else{
				$json['success'] =$result['message'];
			}			
      		echo json_encode($json);            
        break; 
        case 'StoreHistoryPackage':  
			require_once "model/viewStoreOrderDetails.php";
			$json=array();
			$model=new StoreOrder;	
			$seller_id = trim($_POST['seller_id']);
			$order_id = trim($_POST['order_id']);
			$order_status_id = trim($_POST['order_status_id']);
			$package_type = trim($_POST['package_type']);
			$comment = trim($_POST['comment']);
			$order_number = trim($_POST['order_number']);
			$result = $model->saveStoreOrderHistory($order_status_id, $comment, $order_id,$seller_id,$order_number);
			if($result['code']=="200"){			
				$model->StoreOrderShipment($order_id,$seller_id,$package_type,$order_number);	
				$json['success'] =$result['message'];				
			}else{
				$json['success'] =$result['message'];
			}			
      		echo json_encode($json);            
        break; 
        default:
        break;
	}
?>