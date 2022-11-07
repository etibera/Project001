<?php
include "model/credit_card.php";
$model_CR= new credit_card();

if($model_CR->isMobileDevice()):
?>
<?php
	echo json_encode(array(
		'title' => 'Transaction Cancelled',
		'message' => 'You Successfully Canceled Cards and Other Payment Method',
		'status' => 'danger'
	));
?>
<?php else: ?>
<?php 
include "common/header.php";

$message="You Successfully  Canceled Cards and Other Payment Method";

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