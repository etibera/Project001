<?php 
	if(isset($_GET['action'])){
	    $source = $_GET['action'];
	    session_start();
		$id =isset($_SESSION['user_login'])?$_SESSION['user_login']:1601;
	}else{
	    $source = "";
	}
	switch($source){
        case 'getproduct_most_popular':        		
			require_once 'model/home_new.php';
			$home_new_mod=new home_new();
			$json=array();
			$most_popular=$home_new_mod->most_popular_new($_POST['limit']);
			$json['most_popular']=$most_popular;
      		echo json_encode($json);            
        break;
        case 'getproduct_recommended':  
        	$json=array();		
			require_once 'model/home_new.php';
			$home_new_mod=new home_new();
			if(isset($_SESSION['user_login'])){				
				$recommended_product=$home_new_mod->recommended_product_new($_SESSION['user_login'],9);
				$json['recommended_product']=$recommended_product;
	  			echo json_encode($json);            
			}else{
				$recommended_product=$home_new_mod->recommended_product_new(0,9);
				$json['recommended_product']=$recommended_product;
				echo json_encode($json); 
			}			
        break;
        case 'getproduct_recommended2':        		
			require_once 'model/home_new.php';
			$home_new_mod=new home_new();
			$json=array();
			$recommended_product2=$home_new_mod->recommended_product_new($_GET['cust_id'],$_GET['limit']);
			$json['recommended_product2']=$recommended_product2;
			$json['recommended_product25']=$_GET['cust_id'] ;
      		echo json_encode($json);            
        break;
    	default:
        break;
	}
?>