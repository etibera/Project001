<?php 
	include "common/header.php";
?>
<script type="text/javascript">
	var islog='<?php echo $is_log;?>';
    	if(islog=="0"){
    	location.replace("home.php");
    }
</script>
<div class="container" st>
	<div class="row">
		<div class="col-sm-12">
			 <center><h2>Product Returns</h2></center>
		</div>
	</div>
	<br><br>
	<div class="form-group" align="center">
 		<p>Thank you for submitting your return request. Your request has been sent to the relevant department for processing.<br>You will be notified via e-mail as to the status of your request.</p>
 	</div>
	<div class="form-group" align="center">
		<a href="./index.php" title="Continue" class="btn btn-primary">Continue Shopping</a>
	</div>
</div>

<?php include "common/footer.php";?>	
