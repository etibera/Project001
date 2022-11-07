<?php 
	if(isset($_GET['action'])){
    $source = $_GET['action'];
	    session_start();
	}else{
	    $source = "";
	}
	switch($source){
        case 'approvePRD':        		
			require_once "../mgmtseller/model/sellerAddProduct.php";	
  			$mod_SellerAddProduct=new SellerAddProduct;
			$results = $mod_SellerAddProduct->approvePRD($_POST['product_id']);
			$json['success'] = $results;
      		echo json_encode($json);            
        break; 
		default:
        break;
	}
?>