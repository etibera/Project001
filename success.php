<?php 
include "model/credit_card.php";
if(isset($_SESSION['user_login'])){	
	$customer_id = $_SESSION['user_login']; 
}else{
	$customer_id =0;
}
$results="";
// unset($_SESSION['order_id_CR']);
// unset($_SESSION['order_id_maxx_p']);
$model_CR= new credit_card();
if (isset($_GET['requestid']) and isset($_GET['responseid']) ) {
	$requestid=base64_decode($_GET['requestid']);
	$responseid=base64_decode($_GET['responseid']);	
	$results=$model_CR->getpaymentresponse($requestid,$responseid);
}else if(isset($_POST['sc_values'])){
	$json = $_POST['sc_values'];
    $obj = json_decode($json);
    $SC_MC = $obj->{'SC_MC'}; 
    $SC_MID = $obj->{'SC_MID'}; 
    $SC_AMOUNT =$obj->{'SC_AMOUNT'}; 
    $SC_PAYMODE =$obj->{'SC_PAYMODE'}; 
    $SC_PAYTERM =$obj->{'SC_PAYTERM'}; 
    $SC_REF =$obj->{'SC_REF'}; 
    $SC_PAYREF =$obj->{'SC_PAYREF'}; 
    $SC_ORIG_AMOUNT =$obj->{'SC_ORIG_AMOUNT'}; 
    $SC_CUR_DATA =$obj->{'SC_CUR_DATA'}; 
    $SC_STATUS =$obj->{'SC_STATUS'}; 
    $SC_SECUREHASH =$obj->{'SC_SECUREHASH'}; 
    $SC_URL =1; 
   	$res=$model_CR->save_maxxpayment_response($SC_MC,$SC_MID,$SC_AMOUNT,$SC_PAYMODE,$SC_PAYTERM,$SC_REF,$SC_PAYREF,$SC_ORIG_AMOUNT,$SC_CUR_DATA,$SC_STATUS,$SC_SECUREHASH,$SC_URL);   	 
   	$order_info = $model_CR->order_details($SC_REF);
	$opch=0;
	$subtotal=0;
	$total=0;
	/*foreach ($order_info['total'] as $totals):
		if($totals['title']=="Sub-Total"){
			$subtotal+=$totals['value'];
		}		       	 			
		if($totals['title']=="Total"){
			$total+=$totals['value'];
		}					       
    endforeach;*/
    //$opch=$subtotal*0.015;
    //$total+=$opch;	
    $model_CR->addOrderHistory($SC_REF); 				    
 	$model_CR->deletecart($res['customer_id']);
    //$model_CR->updatetotals($SC_REF,$opch,$total);    
    $resname=$model_CR->getcustomername($res['customer_id']); 
    $_SESSION['user_name']=$resname['firstname']; 
    $_SESSION['user_login']=$res['customer_id']; 
}
?>
<?php 
if($model_CR->isMobileDevice()):
?>
<?php 
if($results){
	if($results['response_code'] == 'GR001' || $results['response_code'] == 'GR002'){
		// $order_info = $model_CR->order_details($requestid);
		// $opch=0;
		// $subtotal=0;
		// $total=0;
		// $opch=$subtotal*0.028;
		// $total+=$opch;					    
		$model_CR->deletecart($customer_id);
		//$model_CR->updatetotals($requestid,$opch,$total);
		echo json_encode(array(
			'title' => $results['response_message'],
			'message' => $results['response_advise'],
			'status' => 'success'
		));
	}else{
		echo json_encode(array(
			'title' => $results['response_message'],
			'message' => $results['response_advise'],
			'status' => 'danger'
		));
	}
}else{
	echo json_encode(array(
		'title' => 'Transaction Success (BDO Installment)',
		'message' => 'Your order has been successfully processed, Thanks for shopping with us online.',
		'status' => 'success'
	));
}
?>
<?php else: ?>
<?php include "common/header.php"; ?>
</br>
<div class="wrapper">
	<div class="container">
    	<div class="row">
	        <div class="col-lg-12" >
		       	<?php if($results){?>
		       	 	<?php if($results['response_code'] == 'GR001' || $results['response_code'] == 'GR002'){ ?>
		       	 		<?php 
		       // 	 		$order_info = $model_CR->order_details($requestid);
		       // 	 		$opch=0;
		       // 	 		$subtotal=0;
		       // 	 		$total=0;
		       // 	 		foreach ($order_info['total'] as $totals):
		       // 	 			if($totals['title']=="Sub-Total"){
		       // 	 				$subtotal+=$totals['value'];
		       // 	 			}		       	 			
		       // 	 			if($totals['title']=="Total"){
		       // 	 				$total+=$totals['value'];
		       // 	 			}					       
					    // endforeach;
					    // $opch=$subtotal*0.028;
					    // $total+=$opch;					    
		       	 		$model_CR->deletecart($customer_id);
					    //$model_CR->updatetotals($requestid,$opch,$total);
					    if(isset($_SESSION['digitalwallet_cash'])){
					    	$model_CR->apply_cashwallet($requestid,$customer_id,$_SESSION['digitalwallet_cash']);
					    }
					    if(isset($_SESSION['digitalwallet'])){
					    	$model_CR->apply_digitalwallet($requestid,$customer_id,$_SESSION['digitalwallet']);
					    }
		       	 		?>
		       	 		<div class="alert alert-success">
				        	<h2><?php echo $results['response_message'];?></h2></br>
				        	<i style="color: red;"><?php echo $results['response_advise'];?> </i>
				        </div>
				        <div class="buttons">
			       			<div class="pull-left"><a href="order_history.php" class="btn btn-primary">Continue</a></div>
				        </div>	
		       	 	<?php }else{ ?>
		       	 		<div class="alert alert-danger">
				        	<h2>Transaction Failed. (<i><?php echo $results['response_message'];?></i>)</h2></br>
				        	<i style=><?php echo $results['response_advise'];?> </i>
				        </div>
				        <div class="buttons">
			       			<div class="pull-left"><a href="cart.php" class="btn btn-primary">Continue</a></div>
				        </div>	
		       	 	<?php } ?>			        	       
			    <?php }else{ ?>
			    	<div class="alert alert-success">
			        	<h2>Successfully Ordered!</h2>
			        </div>
		            <div class="buttons">
		       			<div class="pull-left"><a href="order_history.php" class="btn btn-primary">Continue</a></div>
			        </div>	
			    <?php } ?>
		    </div>     
	    </div> 
	</div>     
</div>  
<?php 
include "common/footer.php";
?>
<?php endif;?>