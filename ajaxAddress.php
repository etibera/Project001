<?php
	if(isset($_GET['action']) ){
    $source = $_GET['action'];  
	}else{
	    $source = "";
	  
	}
	switch($source){
        case 'GetmobileStats': 
	        require_once 'model/reg_new.php';  		
			$add_address = new Register_new();	
			$results = $add_address->checkMobileNumberNew($_POST['customer_id'],$_POST['mobile']);
			echo json_encode($results);
		break; 
		case 'getCity': 
	        require_once "model/address.php";       		
			$address = new Address();
			$results = $address->getCity($_POST['province']);
			echo json_encode($results);
		break; 
		case 'getDistrict': 
	        require_once "model/address.php";       		
			$address = new Address();
			$results = $address->getDistrict($_POST['province'],$_POST['city']);
			echo json_encode($results);
		break;
		case 'getTracking_id': 
	        require_once "model/address.php";       		
			$address = new Address();
			$results = $address->getTracking_id($_POST['province'],$_POST['city'],$_POST['district']);
			echo json_encode($results);
		break;
		case 'deliverySatatus': 
	        require_once "model/address.php"; 
			$shipMethoData = array();    			      		
			$address = new Address();
			$delstats = $address->deliverySatatus($_POST['address_id']);			
			$seller_id=0;
			if($_POST['countcpfs']=="0"){
				$seller_id=20;
			}
			if($delstats['delivery']=="Yes"){
				$shipMethoData['option']=$address->shipMethodQuadx($seller_id);
			}else{
				$shipMethoData['option']=$address->shipMethspecialdel($seller_id);
			}
			$shipMethoData['delivery']=$delstats['delivery'];
			echo json_encode($shipMethoData);
		break;
		case 'pickupSatatus': 
	        require_once "model/address.php"; 
			$shipMethoData = array();    			      		
			$address = new Address();
			$pickstats = $address->pickupSatatus($_POST['branch_id']);
			if($pickstats['pickup']=="Yes"){
				$shipMethoData['option']=$address->shipMethodQuadx($_POST['seller_id']);
			}else{
				$shipMethoData['option']=$address->shipMethspecialdel($_POST['seller_id']);
			}
			$shipMethoData['pickup']=$pickstats['pickup'];
			echo json_encode($shipMethoData);
		break;
		case 'setdefaultAdd': 
	        require_once "model/address.php";       		
			$address = new Address();
			$results = $address->setdefaultAdd($_POST['address_id'],$_POST['customer_id']);
			if($results=="200"){
				$json['success'] ="Address Successfully Updated.";
			}else{
				$json['success'] =$results;
			}			
			echo json_encode($json);
		break;
		case 'get_shipingVal': 
	        require_once "model/address.php";       		
			$address = new Address();
			$json=array();
			$GetshipAddress=$address->GetshipAddress($_POST['address_id']);
			$pickupAddress=$address->GetPickupAddress($_POST['branch_id']);
			if($GetshipAddress['region']==""){
				$json['invalidAdd'] ="Please Update your Shipping Adress First.";
			}else{
				if($GetshipAddress['region']==$pickupAddress['region']){
					$json['success'] ="Not provincial rate";
					$FlatrateSamePRV = $address->getFlatrateSamePRV($_POST['customer_id'],json_decode($_POST['cart_ids']),$_POST['seller_id']);
					$json['flat_rate'] =$FlatrateSamePRV;
				}else{
					$json['success'] ="provincial rate";
					$FlatrateDiffPRV = $address->FlatrateDiffPRV($_POST['customer_id'],json_decode($_POST['cart_ids']),$_POST['seller_id']);
					$json['flat_rate'] =$FlatrateDiffPRV;
				}		
			}
			
			echo json_encode($json);
		break;
		case 'selectshippingAdress': 
	        require_once "model/checkoutLatest.php";     		
			$model = new CheckoutLatest();
			$json=array();
			$json['success']=$model->get_cart_local_perstore($_POST['customer_id'],json_decode($_POST['cart_ids']),$_POST['addr_idval']);
			$json['success2']=$model->get_cart_local_perstoreSmall($_POST['customer_id'],json_decode($_POST['cart_ids']),$_POST['addr_idval']);		
			echo json_encode($json);
		break;
        default: break;
	}
?>