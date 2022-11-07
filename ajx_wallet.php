<?php 
	if(isset($_GET['action'])){
	    $source = $_GET['action'];
	    session_start();
		$id =isset($_SESSION['user_login'])?$_SESSION['user_login']:333;
	}else{
	    $source = "";
	}
	switch($source){
        case 'CW':        		
			include "model/cart.php";
       		$model=new Cart();
       		$wallet_info=$model->total_cash_Wallet($id) ;
       		$wallet =isset($_POST['CASHwallet']) ? $_POST['CASHwallet']: 0; 

       		if (empty ($_POST['CASHwallet'])) {
				$json['success'] = 'Warning: Please enter Enter your Preferred Amount!';
				unset($this->session->data['digitalwallet_cash']);
			} elseif ($wallet_info>=$wallet) {
				$_SESSION['digitalwallet_cash'] = $_POST['CASHwallet'];
				$json['success'] = 'Your Cash Wallet has been applied!';
				
			} else {
				$json['success'] = 'Warning: Cash Wallet is either invalid or the balance has been used up!';
				unset($_SESSION['digitalwallet_cash']);
			}
      		echo json_encode($json);            
        break;
        case 'DW':        		
			include "model/cart.php";
       		$model=new Cart();
       		$wallet_info=$model->total_discount_Wallet($id) ;
       		$wallet =isset($_POST['digitalwallet']) ? $_POST['digitalwallet']: 0; 
       		if (empty ($_POST['digitalwallet'])) {
				$json['success'] = 'Warning: Please enter Enter your Preferred Amount!';
				unset($this->session->data['digitalwallet']);
			} elseif ($wallet_info>=$wallet) {
				$_SESSION['digitalwallet'] = $_POST['digitalwallet'];
				$json['success'] = 'Your Discount Wallet has been applied!';
				
			} else {
				$json['success'] = 'Warning: Discount Wallet is either invalid or the balance has been used up!';
				unset($_SESSION['digitalwallet']);
			}
      		echo json_encode($json);            
        break;
        case 'CWcancel':    
        	$json['success'] = 'Your Cash Wallet has been canceled!';
			unset($_SESSION['digitalwallet_cash']); 
      		echo json_encode($json);            
        break;
         case 'DWcancel':    
        	$json['success'] = 'Your Discount Wallet has been canceled!';
			unset($_SESSION['digitalwallet']); 
      		echo json_encode($json);            
        break;
        case 'getdetailsprod':  
        	include "model/home.php";
			$model= new home();  
        	$p_id =isset($_POST['isprodid']) ? $_POST['isprodid']: 0;
        	$productdesc = $model->getproduct($p_id);
        	$getimg =str_replace(" ", "%20","home/irpge67jnamu/public_html/img/".$productdesc['image']);
        	$name_fb = $productdesc['name']." | " . number_format($productdesc['price'],2);
			$json['imgSrc'] = $getimg;
			$json['title'] = $name_fb;
      		echo json_encode($json);            
        break;
        case 'addclickFb':        		
			include "model/product.php";
    		$model_product = new product();
    		$type=$_GET['type'];
    		$links=$_GET['links'];
    		$cust_id=$_GET['cust_id'];
    		$prod_id=$_GET['prod_id'];
    		$prod_name=$_GET['prod_name'];
    		$prod_price=$_GET['prod_price'];
    		$url="";
    		$count_tweeter_click=$model_product->count_FB_click($cust_id,$prod_id,$type);
			if($count_tweeter_click==0){
				$model_product->insert_FB_click($cust_id,$prod_id,$type);
			}else{
				$model_product->update_FB_click($cust_id,$prod_id,$type);
			}

			$ismember_AFF = $model_product->get_ppp($cust_id);
			if($ismember_AFF!=0){
				$model_product->insert_affiliate_link_share($cust_id,$prod_id, $type);
			}
			$customer_count=$model_product->get_share_customer_id($cust_id);
			$date_now = new DateTime("now", new DateTimeZone('Asia/Manila') );
			$customer_get_date_today=$date_now->format('Y-m-d');
			if($customer_count==0){
				$model_product->insert_share_customer_id($cust_id,$customer_get_date_today);
			}else{
				$model_product->update_share_customer_id($cust_id,$customer_get_date_today);
			}

			$get_product_shared=$model_product->get_product_shared($cust_id,$prod_id);
			if($get_product_shared==0){
				$model_product->insert_product_shared($cust_id,$prod_id);
			}
    		switch($type){
				case 'Facebook':
				$url = "https://www.facebook.com/sharer.php?u=$links";
				break;
				case 'Twitter':
				$url = "https://twitter.com/intent/tweet?text=$prod_name&url=$links";
				break;
				case 'Viber':
				$url = "https://3p3x.adj.st/?adjust_t=u783g1_kw9yml&adjust_fallback=https%3A%2F%2Fwww.viber.com%2F%3Futm_source%3DPartner%26utm_medium%3DSharebutton%26utm_campaign%3DDefualt&adjust_campaign=Sharebutton&adjust_deeplink=viber://forward?text=check this out only $prod_price $links";
				break;
				case 'FB Messager':
				$url = "fb-messenger://share/?link=$links";
				break;
				default: 
				$url = $urls;
			}
			//var_dump($get_product_shared);
			 header("location:".$url); 
        break;
        case 'register_aff':        		
			include "model/cart.php";
       		$model=new Cart();
       		$get_register_aff=$model->get_register_aff($id);
			if($get_register_aff==0){
				$stat=$model->add_register_aff($id);
				if($stat=="200"){
					$json['success'] = 'You are now registered  to PESO Affiliate Program';
				}else{
					$json['success'] = $stat;
				}				
			}else{
				$json['success'] = 'You are already registered  to PESO Affiliate Program';
			}
       		
      		echo json_encode($json);            
        break;
        case 'update_bg_poa_list':        		
			include "model/bg_product.php";
       		$model = new bg_product();
       		$stats=$model->poal_Update_buffer($_POST['poa_option_id'],$_POST['cust_id'],$_POST['p_id'],$_POST['poa_id'],$_POST['poa_name']);
			if($stats=="200"){
				$json['success'] = 'Option Updated';		
			}else{
				$json['success'] = $stats;
			}
       		
      		echo json_encode($json);            
        break; 
        case 'bg_get_latest_price': 
       	 	
         	$json = array();
         	$poa_ids=array();       		
			include "model/bg_product.php";
			
       		$model = new bg_product();
       		$stats=$model->get_poa_list_bg_p($_POST['cust_id'],$_POST['p_id']);
       		foreach( $stats as $cart_poa){
       			array_push($poa_ids,$cart_poa['poa_id']);
       		}
       		$poa_id_val=implode(",",$poa_ids);
      		
       		$json['success_stock'] = $poa_id_val;
       	    echo json_encode($json);          
        break;   	
    	case 'bg_add_to_cart':        		
			include "model/bg_product.php";
       		$model = new bg_product();
       		$product_id =$_POST['product_bg'];
		  	$cust_id=$_POST['cust_id'];
			$quantity=1;
			$recurring_id=0;
			$list = $model->bg_addtocart($product_id, $quantity,$recurring_id,$cust_id,2);
			$json = array();
		  	if($list=="200"){
		    	$json['success'] ="Successfully added.";
		  	}else{
		    	$json['success'] =$list;
		  	}
       		
      		echo json_encode($json);            
        break;
        case 'cn_add_to_cart':        		
			include "model/bg_product.php";
       		$model = new bg_product();
       		$product_id =$_POST['product_china'];
		  	$cust_id=$_POST['cust_id'];
		  	$stockval=$_POST['stockval'];
			$quantity=1;
			$recurring_id=0;
			$list = $model->cn_addtocart($product_id, $quantity,$recurring_id,$cust_id,1,$stockval);
			$json = array();
		  	if($list=="200"){
		    	$json['success'] ="Successfully added.";
		  	}else{
		    	$json['success'] =$list;
		  	}
       		
      		echo json_encode($json);            
        break; 
        case 'getcategorybg':        		
			require_once 'model/home_new.php';
			$home_new_mod=new home_new();
			$bg_cat=$home_new_mod->getCategories_global_1st($_POST['catecory_id']);
      		echo json_encode($bg_cat);            
        break;
        case 'getcategorybgsecond':        		
			require_once 'model/home_new.php';
			$home_new_mod=new home_new();
			$json=array();
			$bg_cat=$home_new_mod->getCategories_global_1st($_POST['catecory_id']);
			$json['cat']=$bg_cat;
			$json['cat_id']=$_POST['catecory_id'];
			$json['catname']=$_POST['cat_name'];
			$json['cat_idsecond']=$_POST['cat_idsecond'];
      		echo json_encode($json);            
        break;
    	default:
        break;
	}
?>