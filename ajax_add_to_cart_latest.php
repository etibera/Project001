<?php 
	if(isset($_GET['action'])){
	    $source = $_GET['action'];
	    session_start();
		$id =isset($_SESSION['user_login'])?$_SESSION['user_login']:333;
	}else{
	    $source = "";
	}
	switch($source){
	    case 'followSeller':     
        	$seller_id =$_POST['seller_id'];
        	$customer_id =$_POST['customer_id'];
			require_once 'model/FlagshipStoresHome.php';    
			$FSmod=new FSHome();  
			$res = $FSmod->follow($customer_id, $seller_id);
	        $json = array();
	        $json['success'] =$res;
      		echo json_encode($json);            
        break;
        case 'addtocart':     
        	$seller_id =$_POST['seller_id'];
        	$option_select =$_POST['option_select'];
        	$price =$_POST['price'];
        	$product_id =$_POST['product'];
		    $cust_id=$_POST['cust_id'];
		    $freebies=$_POST['freebies'];
		    $option_select_id=$_POST['option_select_id'];
		    $quantity=1;
		    $recurring_id=0;
			require_once 'model/product.php';
			$product_mod = new product();

			$list = $product_mod->addtocart_new($product_id, $quantity,$recurring_id,$cust_id,0,$seller_id,$option_select,$price,$freebies,$option_select_id);
	        $json = array();
	        if($list=="200"){
	          $json['success'] ="Successfully added.";
	        }else{
	          $json['success'] ="Error Occured.";
	        }
      		echo json_encode($json);            
        break;
        case 'addtocart_new':     
        	$seller_id =$_POST['seller_id'];
        	$option_select =$_POST['option_select'];
        	$price =$_POST['price'];
        	$product_id =$_POST['product'];
		    $cust_id=$_POST['cust_id'];
		    $freebies=$_POST['freebies'];
		    $option_select_id=$_POST['option_select_id'];
		    $branch_id=$_POST['branch_id'];
		    $quantity=1;
		    $recurring_id=0;
			require_once 'model/product.php';
			$product_mod = new product();

			$list = $product_mod->addtocart_branchId($product_id, $quantity,$recurring_id,$cust_id,0,$seller_id,$option_select,$price,$freebies,$option_select_id,$branch_id);
	        $json = array();
	        if($list=="200"){
	          $json['success'] ="Successfully added.";
	        }else{
	          $json['success'] ="Error Occured.";
	        }
      		echo json_encode($json);            
        break;
        case 'buynow_new':     
        	$seller_id =$_POST['seller_id'];
        	$option_select =$_POST['option_select'];
        	$price =$_POST['price'];
        	$product_id =$_POST['product'];
		    $cust_id=$_POST['cust_id'];
		    $freebies=$_POST['freebies'];
		    $option_select_id=$_POST['option_select_id'];
		    $branch_id=$_POST['branch_id'];
		    $quantity=1;
		    $recurring_id=0;
			require_once 'model/product.php';
			$product_mod = new product();

			$list = $product_mod->buynow_branchId($product_id, $quantity,$recurring_id,$cust_id,0,$seller_id,$option_select,$price,$freebies,$option_select_id,$branch_id);
	        $json = array();
	        $json['success'] =$list;
      		echo json_encode($json);            
        break;
        case 'buynow':     
        	$seller_id =$_POST['seller_id'];
        	$option_select =$_POST['option_select'];
        	$price =$_POST['price'];
        	$product_id =$_POST['product'];
		    $cust_id=$_POST['cust_id'];
		    $freebies=$_POST['freebies'];
		    $option_select_id=$_POST['option_select_id'];
		    $quantity=1;
		    $recurring_id=0;
			require_once 'model/product.php';
			$product_mod = new product();

			$list = $product_mod->buynow_new($product_id, $quantity,$recurring_id,$cust_id,0,$seller_id,$option_select,$price,$freebies,$option_select_id);
	        $json = array();
	        $json['success'] =$list;
      		echo json_encode($json);            
        break;
        case 'ReciveOrder':     
        	$seller_id =$_POST['seller_id'];
        	$order_id =$_POST['order_id'];
        	$comment="";
        	if(isset($_POST['comment'])){
        		$comment =$_POST['comment'];
        	}
        	
        	require_once "model/orderhistory.php";        	
			$model=new OrderHistory;
			$getpayment_method=$model->getpayment_method($order_id);
			$list = $model->ReciveOrder($order_id,$seller_id,$comment);
	        $json = array();
	        if($list=="200"){
	        	$orderData=$model->order_details_seller($order_id,$seller_id);		  	
		  		$model->SendEmailSeller(49,$orderData,'');
		  		$model->SendEmailAdmin(49,$orderData,'');
		  		$model->SendEmailcustomer(49,$orderData,'');
	        	$totalAmount=$model->TotalAmountPerSellerOrder($order_id,$seller_id);
	        	$deduction=$totalAmount*0.035;
	        	$totaStoreWallet=$totalAmount-$deduction;
	        	if($getpayment_method=="maxx_payment"){
					$getbdotermsconfirm=$model->getbdotermsconfirm($order_id);
					if($getbdotermsconfirm){
						$getbank_charge=$model->getbank_charge($getbdotermsconfirm);						
						$bankCdeduct=$totalAmount*$getbank_charge;
						$totaldeduc=$deduction+$bankCdeduct;
						$totaStoreWalletBC=$totalAmount-$totaldeduc;
						$model->AddSellerWallet($order_id,$seller_id,$totaStoreWalletBC);
					}else{
						$model->AddSellerWallet($order_id,$seller_id,$totaStoreWallet);
					}
	        	}else{
	        		$model->AddSellerWallet($order_id,$seller_id,$totaStoreWallet);
	        	}
	        	
	        	//$model->AddStorePayables($order_id,$seller_id);
	        	$json['success'] =" Order Successfully Recived.";
	        }else{
	          $json['success'] ="Error Occured.";
	        }
      		echo json_encode($json);            
        break;
        case 'ReciveStoreOrder':     
        	$seller_id =$_POST['seller_id'];
        	$order_id =$_POST['order_id'];
        	$order_number =$_POST['order_number'];
        	$comment="";
        	if(isset($_POST['comment'])){
        		$comment =$_POST['comment'];
        	}        	
        	require_once "model/StoreOrderDetails.php";      	
			$model=new StoreOrderHistory;
			$getpayment_method=$model->getpayment_method($order_id);
			$list = $model->ReciveOrder($order_id,$seller_id,$comment,$order_number);
	        $json = array();
	        if($list=="200"){	        	
	        	$totalAmount=$model->TotalAmountPerSellerOrder($order_id,$seller_id,$order_number);
	        	$deduction=$totalAmount*0.035;
	        	$totaStoreWallet=$totalAmount-$deduction;
	        	if($getpayment_method=="maxx_payment"){
					$getbdotermsconfirm=$model->getbdotermsconfirm($order_id);
					if($getbdotermsconfirm){
						$getbank_charge=$model->getbank_charge($getbdotermsconfirm);						
						$bankCdeduct=$totalAmount*$getbank_charge;
						$totaldeduc=$deduction+$bankCdeduct;
						$totaStoreWalletBC=$totalAmount-$totaldeduc;
						$model->AddSellerWallet($order_id,$seller_id,$totaStoreWalletBC,$order_number);
					}else{
						$model->AddSellerWallet($order_id,$seller_id,$totaStoreWallet,$order_number);
					}
	        	}else{
	        		$model->AddSellerWallet($order_id,$seller_id,$totaStoreWallet,$order_number);
	        	}
	        	$orderData=$model->order_details_seller($order_id,$seller_id,$order_number);		  	
		  		$model->SendEmailSeller(49,$orderData,'');
		  		$model->SendEmailAdmin(49,$orderData,'');
		  		$model->SendEmailcustomer(49,$orderData,'');
	        	
	        	//$model->AddStorePayables($order_id,$seller_id);
	        	$json['success'] =" Order Successfully Recived.";
	        }else{
	          $json['success'] ="Error Occured.";
	        }
      		echo json_encode($json);            
        break;
    	default:
        break;
	}
?>