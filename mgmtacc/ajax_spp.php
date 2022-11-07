<?php 
	if(isset($_GET['action'])){
	    $source = $_GET['action'];	   
	}else{
	    $source = "";
	}
	switch($source){       
        case 'PayTransfer': 
            $json = array();  
            require_once "model/SellerPendingPayables.php";
			$model=new SPayables;

        	$seller_id =$_POST['seller_id'];
        	$order_id =$_POST['order_id'];
        	$payableId =$_POST['payableId'];
        	$bank_name =$_POST['bank_name'];
        	$bank_account_no =$_POST['bank_account_no'];
        	$amount =$_POST['amount'];
        	$reference_no =$_POST['reference_no'];
			$stats = $model->PayTransfer($seller_id,$order_id,$payableId,$bank_name,$bank_account_no,$amount,$reference_no);
		    if($stats=="200"){		        	
		        $json['success'] ="Pay / Transfer Successfully Updated.";
		    }else{
		          $json['success'] ="Error Occured.";
		    }
      		echo json_encode($json);            
        break;
        case 'addwallet': 
            $json = array();  
            require_once "model/manageWallet.php";
			$model=new ManageWallet;
        	$customer_id =$_POST['customer_id'];
        	$type =$_POST['type'];
        	$amount =$_POST['amount'];
        	$particulars =$_POST['particulars'];

			$stats = $model->saveWallet($customer_id,$type,$amount,$particulars);
		    if($stats=="200"){		        	
		        $json['success'] ="Wallet Successfully Added.";
		    }else{
		          $json['success'] ="Error Occured.";
		    }
      		echo json_encode($json);            
        break;
    	default:
        break;
	}
?>