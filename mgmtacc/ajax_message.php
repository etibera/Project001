<?php 
	if(isset($_GET['action'])){ 
    $source = $_GET['action'];    
  
	}else{ 
	    $source = ""; 
	}
	switch($source){
		case 'GetConversationsADMIN':        		
			require_once "model/sellerMessages.php";	
			$mod_SM=new sellerMessage; 
			$results = $mod_SM->adGetConversations($_GET['seller_id'],$_GET['customer_id'],$_GET['branch_id']);
			echo json_encode($results);
        break;
		case 'GetAdminMessagesCA':        		
			require_once "model/message.php";
			$model=new message;
			$results = $model->GetAdminMessagesCA($_GET['admin_id']);				 		
			echo json_encode($results);
        break;
        case 'GetConversationsCA':        		
			require_once "model/message.php";
			$model=new message; 
			$results = $model->GetConversationsCA($_GET['admin_id'],$_GET['customer_id']);
			echo json_encode($results);
        break;
     	case 'GetTotalUnreadsCA':        		
			require_once "model/message.php";
			$model=new message;
			$results = $model->GetTotalUnreadsCA($_GET['admin_id']);
			echo json_encode($results);
        break;
     	case 'UpdateToIsReadCA':        		
			require_once "model/message.php";
			$model=new message;
			$results = $model->UpdateToIsReadCA($_POST['admin_id'],$_POST['customer_id']);
			echo json_encode($results);
        break;
        case 'InsertMessageCA':        		
			require_once "model/message.php";
			$model=new message;
			$order_id=0;
			if(isset($_POST['order_id'])){
				$order_id=$_POST['order_id'];
			}
			$results = $model->InsertMessageCA($_POST['admin_id'],$_POST['customer_id'],$_POST['message'],$order_id);
			echo json_encode($results);
        break;
     
        default:
        break;
	}
?>