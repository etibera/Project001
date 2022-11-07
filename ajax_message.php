<?php 
	if(isset($_GET['action'])){ 
    $source = $_GET['action'];    
  
	}else{ 
	    $source = ""; 
	}
	switch($source){
		case 'GetCustomerMessages':        		
			require_once "model/message.php";
			$model=new message;
			$results = $model->GetCustomerMessages($_GET['customer_id']);
			echo json_encode($results);
        break;
        case 'GetConversations':        		
			require_once "model/message.php";
			$model=new message; 
			$results = $model->GetAllConversations($_GET['seller_id'],$_GET['customer_id'],$_GET['branch_id']);
			
			echo json_encode($results);
        break;
     	case 'GetTotalUnreads':        		
			require_once "model/message.php";
			$model=new message;
			$results = $model->GetTotalUnreads($_GET['customer_id']);
			echo json_encode($results);
        break;
     	case 'UpdateToIsRead':        		
			require_once "model/message.php";
			$model=new message;
			$results = $model->UpdateToIsRead($_POST['seller_id'],$_POST['customer_id'],$_POST['branch_id']);
			echo json_encode($results);
        break;
        case 'InsertMessage':        		 
			require_once "model/message.php";
			$model=new message;
			$results = $model->InsertMessage($_POST['seller_id'],$_POST['customer_id'],$_POST['message'], $_POST['product_id'],$_POST['branch_id']);
			echo json_encode($results); 
        break;

		case 'GetCustomerMessagesCA':        		
			require_once "model/message.php";
			$model=new message;
			$results = $model->GetCustomerMessagesCA($_GET['customer_id']);
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
			$results = $model->GetTotalUnreadsCA($_GET['customer_id']);
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
			$results = $model->InsertMessageCA($_POST['admin_id'],$_POST['customer_id'],$_POST['message']);
			echo json_encode($results);
        break;
      	case 'SeedMessageCA':        		
			require_once "model/message.php";
			$model=new message;
			$results = $model->SeedMessageCA($_POST['customer_id']);
			echo json_encode($results);
        break;
        default:
        break;
	}
?>