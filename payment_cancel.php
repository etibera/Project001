<?php
include "model/credit_card.php";
$model_CR= new credit_card();

if($model_CR->isMobileDevice()):
?>

<?php
$message = '';
if(isset($_POST['sc_values'])){
	$message="You Successfully Canceled BDO Card Installment";
}
	echo json_encode(array(
		'title' => 'Transaction Cancelled',
		'message' => $message,
		'status' => 'danger'
	));
?>


<?php else: ?>
<?php 
include "common/header.php";

if(isset($_POST['sc_values'])){
	
	$json = $_POST['sc_values'];
	$obj = json_decode($json);
	$SC_REF =$obj->{'SC_REF'}; 
	$res=$model_CR->getcustomerid($SC_REF);
	$model_CR->revert_cashwallet($res['customer_id'],$SC_REF); 
    $model_CR->revert_discountwallet($res['customer_id'],$SC_REF); 
	$resname=$model_CR->getcustomername($res['customer_id']); 
    $_SESSION['user_name']=$resname['firstname']; 
	$_SESSION['user_login']=$res['customer_id']; 
    $session->check_the_login2();
    $message="You Successfully Canceled BDO Card Installment";
}
?>
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
<?php include "common/footer.php"; ?>
<?php endif; ?>