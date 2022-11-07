<?php 
	if(isset($_GET['action'])){ 
    $source = $_GET['action'];    
  
	}else{ 
	    $source = ""; 
	}
	
	switch($source){
		case 'GetCustomerList':        		
			require_once "model/sms_admin.php";
			$model=new sms_admin;
			$results = $model->GetCustomerList();
			echo json_encode($results);
        break;
        case 'fGivesDuplicateNumber':        		
			require_once "model/FourGives_CustomerLIst.php";
			$model = new FGives_Custumer();	
			$results = $model->checkcustomer($_POST['mobStr']);			
			echo json_encode($results);
        break;
        case 'InsertSMS':        		
			require_once "model/sms_admin.php";
			$number =$_POST['number'];
			$message =$_POST['message'];
			$model=new sms_admin;
			$results = $model->InsertSMS($number,$message);
			echo json_encode($results);
        break;
        case 'sendSMSPesoapp':        		
			require_once "model/sms_admin.php";
			$model=new sms_admin;
			$data=json_decode($_POST['chk_mobilenumber']);
          	$results = $model->sendSMSPesoapp($data,$_POST['message']);
			$json['success'] = $results ;
          	echo json_encode($json);     
        break;
        case 'sendSMS':        		
			require_once "model/sms_admin.php";
			$model=new sms_admin;
			$data=json_decode($_POST['chk_mobilenumber']);
          	$results = $model->sendSMS($data,$_POST['message']);
			$json['success'] = $results ;
          	echo json_encode($json);     
        break;
        case 'sendEmail':        		
			require_once "model/AdminSendEmail.php";
			$model=new SendEmail;
			$data=json_decode($_POST['chk_customer_id']);
          	$results = $model->sendCustomerEmail($data,$_POST['message'],$_POST['subject']);
          	//$results = $model->sendemailTest();
			$json['success'] = $results ;
          	echo json_encode($json);     
        break;
        case 'sendEmailBlast':        		
			require_once "model/AdminSendEmailBlast.php";
			$model=new SendEmailBlast;
          	$results = $model->sendEmailBlast($_POST['message'],$_POST['subject']);
          	//$results = $model->sendemailTest();
			$json['success'] = $results ;
          	echo json_encode($json);     
        break;
        case 'approveReceivables':        		
			require_once "model/Pendingreceivables.php";
			$model=new PendingReceivables;
			$data=json_decode($_POST['chk_customer_id']);
          	$results = $model->approveReceivables($data);
          	//$results = $model->sendemailTest();
			$json['success'] = $results ;
          	echo json_encode($json);     
        break; 
        case 'VerifyCustomer':        		
			require_once "model/Pendingreceivables.php";
			$model=new PendingReceivables;
          	$results = $model->VerifyCustomer($_POST['order_id']);
          	//$results = $model->sendemailTest();
			$json['success'] = $results ;
          	echo json_encode($json);     
        break;
        case 'deleteProduct':        		
			require_once "model/Product.php";
			$model=new Product();
          	$results = $model->deleteProduct($_POST['product_id']);
          	//$results = $model->sendemailTest();
			$json['success'] = $results ;
          	echo json_encode($json);     
        break;
        case 'deleteProductBatch':        		
			require_once "model/Product.php";
			$model=new Product();
          	$results = $model->deleteProductBatch(json_decode($_POST['chk_id']));
			$json['success'] = $results;
      		echo json_encode($json);  
        break;
        default:
        break;
	}
?>