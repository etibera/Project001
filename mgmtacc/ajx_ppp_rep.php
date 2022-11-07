<?php 
	if(isset($_GET['action'])){
    $source = $_GET['action'];
	    session_start();
		$id = $_SESSION['user_login'];
	}else{
	    $source = "";
	}
	switch($source){
		case 'cash_wallet':        		
			include "model/Ppp_report.php"; 
			$model=new Ppp_report();
			$totalwallet=0;
			$results = $model->get_details_persalesman($_POST['custgetId']);
			$totalwallet=$model->get_total_sales_wallet($_POST['custgetId']);
			$count=0;	
			$json['customers'] = array();
			foreach ($results as $result) {
				$count++;
				$json['customers'][] = array(
					'product_name'    => $result['product_name'],
					'order_id'    => $result['order_id'],
					'amount'           => number_format($result['amount'],2),
					'count'           => $count,
					'date_added'          => $result['date']
				);
			}
			$json['totalwallet']=number_format($totalwallet,2);       		
      		echo json_encode($json);            
        break;
        case 'btn_recommed':        		
			include "model/Ppp_report.php"; 
			$model=new Ppp_report();
			$results = $model->get_details_share_persalesman($_POST['custgetId']);
			$count=0;	
			$json['customers'] = array();
			foreach ($results as $result) {
				$count++;
				$json['customers'][] = array(
					'type'    => $result['type'],
					'name'    => $result['name'],
					'count'           => $count,
					'date_added'          => $result['date']
				);
			}
      		echo json_encode($json);            
        break;
        case 'remove_image':   
			include "model/Product.php"; 
       		$model=new Product();
       		$res=$model->delte_image($_POST['deletid']) ;
       		if ($res) {
       			$json['success'] = 'Successfully Deleted Image';
			} else {
				$json['success'] = 'Error Occured.';
			}
      		echo json_encode($json);            
        break;
        case 'delete_attribute':   
			include "model/Specification.php";
			$model = new Specification(); 
       		$res=$model->delete_attribute($_POST['deletid']) ;
       		if ($res) {
       			$json['success'] = 'Successfully Deleted attribute';
			} else {
				$json['success'] = 'Error Occured.';
			}
      		echo json_encode($json);            
        break;
        case 'attribute_Details':        		
			include "model/Specification.php";
			$model = new Specification(); 
			$results = $model->get_attr_details($_POST['id']);
			$json['items'] = array();
			foreach ($results as $result) {			
				$json['items'][] = array(
					'name'    => $result['name'],
					'description'    => $result['description'],
					'sort_order'          => $result['sort_order']
				);
			}     		
      		echo json_encode($json);            
        break;
        case 'update_store_status':        		
			include "model/manage_store.php";
			$model = new manage_store(); 
			$results = $model->UpdateStore($_POST['seller_id'],$_POST['status']);
			if ($res) {
       			$json['success'] = 'Store Successfully Updated';
			} else {
				$json['success'] = 'Error Occured.';
			}
      		echo json_encode($json);            
        break;
        case 'sample_ip': 
        	if (!empty($_SERVER['HTTP_CLIENT_IP']))   
				  {
				    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
				  }
				//whether ip is from proxy
				elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
				  {
				    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
				  }
				//whether ip is from remote address
				else
				  {
				    $ip_address = $_SERVER['REMOTE_ADDR'];
				  }       		
			$json['success'] = $ip_address;
      		echo json_encode($json);            
        break;
        default:
        break;
	}
?>