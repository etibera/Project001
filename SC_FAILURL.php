<?php
include "model/credit_card.php";
$model_CR= new credit_card();
if(isset($_POST['sc_values'])){
	$json = $_POST['sc_values'];
    $obj = json_decode($json);
    $SC_MC = $obj->{'SC_MC'}; 
    $SC_MID = $obj->{'SC_MID'}; 
    $SC_AMOUNT =$obj->{'SC_AMOUNT'}; 
    $SC_PAYMODE =$obj->{'SC_PAYMODE'}; 
    $SC_PAYTERM =$obj->{'SC_PAYTERM'}; 
    $SC_REF =$obj->{'SC_REF'}; 
    $SC_PAYREF =$obj->{'SC_REF'}; 
    $SC_ORIG_AMOUNT =$obj->{'SC_ORIG_AMOUNT'}; 
    $SC_CUR_DATA =$obj->{'SC_CUR_DATA'}; 
    $SC_STATUS =$obj->{'SC_STATUS'}; 
    $SC_SECUREHASH =$obj->{'SC_SECUREHASH'}; 
    $SC_URL =1; 
   	$res=$model_CR->save_maxxpayment_response($SC_MC,$SC_MID,$SC_AMOUNT,$SC_PAYMODE,$SC_PAYTERM,$SC_REF,$SC_PAYREF,$SC_ORIG_AMOUNT,$SC_CUR_DATA,$SC_STATUS,$SC_SECUREHASH,$SC_URL); 
   	$resname=$model_CR->getcustomername($res['customer_id']); 
    $model_CR->revert_cashwallet($res['customer_id'],$SC_REF); 
    $model_CR->revert_discountwallet($res['customer_id'],$SC_REF); 
    $_SESSION['user_name']=$resname['firstname'];   	 
	$_SESSION['user_login']=$res['customer_id']; 
    $message="BDO Card Installment ".$SC_STATUS;
}else{
	$message="BDO Card Installment Failed";
}
?>
<?php 
if($model_CR->isMobileDevice()):
?>
<?php 
echo json_encode(array(
	'title' => "BDO Card Installment ".$SC_STATUS,
	'message' => "BDO Card Installment Failed",
	'status' => 'danger'
));
?>
<?php else:?>
<?php include "common/header.php"; ?>
</br>
<div class="wrapper">
	<div class="container">
    	<div class="row">
	       <div class="col-lg-12" >
	       	<div class="alert alert-success">
		       <h2><?php echo $message;?></h2></br>		        	
		    </div>
		    <div class="buttons">
       			<div class="pull-left"><a href="cart.php" class="btn btn-primary">Continue</a></div>
	        </div>
	       </div>   
	    </div> 
	</div>     
</div>  
<?php 
include "common/footer.php";
?>
<?php endif;?>