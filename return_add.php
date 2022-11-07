<?php 
	include "common/header.php";
	include "model/returns.php";
	$ret= new Returns();	
	$id = isset($_SESSION['user_login']) ? $_SESSION['user_login']: 0;	
	if(isset($_GET['oid'])){
		$reason = $ret->return_reason();
		$order = $ret->order_details($_GET['oid'],$_GET['pid']);
	}
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
	<?php if(isset($_SESSION['message'])):?>
	<div class="alert alert-success"><?php echo $_SESSION['message'];?></div>
	<?php endif;?>
	<?php unset($_SESSION['message']); ?>
 	<i style="color:grey;">Please complete the form below to request an RMA number.</i><br><br>
	
	<form action="submit_return.php" method="POST">
	<fieldset>
	<legend>Order Information</legend>
	<div class="form-group">
		<label for=""><b style="color: red">*</b> First Name</label>
		<input type="text" name="firstname" class="form-control" placeholder="First Name" value="<?php echo $order['firstname']?>"  required>
		<input type="hidden" name="oid" value="<?php echo $_GET['oid']?>"" />
		<input type="hidden" name="pid" value="<?php echo $_GET['pid']?>"" />
		<input type="hidden" name="srl" value="<?php echo $_GET['srl']?>"" />
	</div>
	<div class="form-group">
		<label><b style="color: red">*</b> Last Name</label>
		<input type="text" name="lastname" class="form-control" placeholder="Last Name" value="<?php echo $order['lastname']?>"  required>
	</div>
	<div class="form-group">
		<label><b style="color: red">*</b> Email</label>
		<input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo $order['email']?>"  required>
	</div>
	<div class="form-group">
		<label><b style="color: red">*</b> Telephone/Mobile No#</label>
		<input type="text" name="telephone" class="form-control" placeholder="telephone" value="<?php echo $order['telephone']?>" required>
	</div>
	<div class="form-group">
		<label><b style="color: red">*</b> Order ID</label>
		<input type="text" name="order_id" class="form-control" placeholder="Order ID" value="<?php echo $_GET['oid']?>" readonly>
	</div>	
	<div class="form-group">
		<label><b style="color: red">*</b> Order Date</label>
		<input type="date" name="date_ordered" class="form-control" placeholder="Order Date" 
		value="<?php echo explode(' ',$order['date_added'])[0];?>"  readonly>
	</div>
	</fieldset><br>
	<fieldset>
	<legend>Product Information & Reason for Return</legend>
	<?php foreach ($order['products'] as $p): ?>
	<div class="form-group">
		<label><b style="color: red">*</b> Product Name</label>
		<input type="text" name="product" class="form-control" placeholder="Product Name" value="<?php echo $p['name']?>"  readonly>
	</div>
	<div class="form-group">
		<label><b style="color: red">*</b> Product Code</label>
		<input type="text" name="model" class="form-control" placeholder="Product Code" value="<?php echo $p['model']?>" readonly>
	</div>
	<div class="form-group">
		<label><b style="color: red">*</b> Quantity</label>
		<input type="number" name="quantity" class="form-control" placeholder="Quantity" value="1" readonly>
	</div>
	<div class="row form-group required">
	 	<label class="col-sm-2"><b style= "color: red">*</b> Reason for Return</label>
	 	<div class="col-sm-10">
		 	<?php foreach ($reason as $r): ?>
	 		<div class="radio">
	        <label>
	          	<input type="radio" name="return_reason_id" value="<?php echo $r['return_reason_id']; ?>" checked="checked"/>
	          	<?php echo $r['name']; ?>
	      	</label>
	      	</div>
	      <?php endforeach;?>
        </div>		 	
 	</div>
 	<div class="row form-group required">
	 	<label class="col-sm-2"><b style= "color: red">*</b> Product is opened?</label>
	 	<div class="col-sm-10">
	 		<div class="radio-inline">
	        <label>
	          	<input type="radio" name="opened" value="1" checked="checked"/> Yes
	      	</label>
	      	 <label class="radio-inline">
	          	<input type="radio" name="opened" value="0"/> No
	      	</label>
	      	</div>
        </div>		 	
 	</div>
  	<div class="form-group" >
	    <label class="control-label"><b style=  "color: red">*</b> Faulty or other details</label>
	      <textarea name="comment" rows="10" placeholder="Faulty or other details" id="input-comment" 
	      class="form-control" required></textarea>
	</div>
	 <?php endforeach;?>
	</fieldset>
	<br>
	<div class="form-group pull-left">
		<a href="./order_details.php?order_id=<?php echo $_GET['oid'];?>" title="Back" class="btn btn-danger"><i data-feather="arrow-left"></i></a>
	</div>
	<div class="form-group pull-right">
		<span style="margin-right: 20px;">
			I have read and agree to th <a href="#TermsModal" data-toggle="modal"><b class="text-primary">Return and Refund</b></a>
			<input type="checkbox" name="agree" required/>
		</span>
		<button class="btn btn-primary" type="submit" name="add_returns">Submit</button>
	</div>
</form>
</div>

<!--Agreement Modal-->
<div class="modal fade bd-example-modal-lg" id="TermsModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
    	 <div class="modal-header">
        <span style="font-size: 20px" class="modal-title" ><strong>Return and Refund </strong></span>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h3 style="color:gray"><strong>RETURN, REFUND OR CANCELLATION POLICY</strong></h3>
        <h4><strong>A. 7 days return, replace and refund</strong></h4>
        <p style="text-align: justify;">Pinoy Electronic Store Online will automatically replace or refund defective item without question within 7 days from date of confirmed delivery provided that the unit will not fall under the voidable warranty clause and that the customer will bring at his own expense the unit to the proper product repair channel as advised by the product manufacturer and has officially notified Pinoy Electronic Store using the website page “RETURNS” (approval procedure applies) or if the customer has physically brought the item to any of our physical stores for official technical evaluation and recording.<br><br>***If the item is not returned thru the physical store then a return arrangement will be presented to the customer. In the proposed agreement the return back fee will be handled by the customer while the return to customer package will be handled by Pinoy Electronic Store Online however, before the item is approved for processing the customer must justify the return or refund request thru our website account page  called “returns” by properly documenting the defective item and submitting photos as needed. Pinoy electronic store may or may not at its own discretion personally contact the customer to perform technical evaluation such as remote session for diagnostic checking and technical report. This activity should determine if the item is indeed defective or if it’s just a problem involving wrong set up, usage related problem but not limited to limited warranty cases on certain products such as software operating system, desktop or mobile application and or virus or malware problems. The limited warranty will cover parts and services of the hardware and its built in functionalities and I also in compliance with the world wide warranty provided by the manufacturer of the product.<br><br>*** Pinoy Electronic Store Online is an online service provider and therefore manufacturing defect must be taken up in consideration with manufacturers specifications and warranty conditions.<br><br>NOTE : No return or exchange request will be entertained or accepted due to customers sudden change of mind.</p>
        <br>
         <h4><strong>B. After 7 days from date of purchase or regular warranty return, replacement or refund conditions.</strong></h4>
        <p style="text-align: justify;">Anything bought from Pinoy Electronic Store Online unless stated in the specifications or receipt otherwise will have a 12 months limited warranty period. When the 7 days contestability period is over your unit will automatically be subjected to this warranty agreement. The agreement states that your unit if under regular warranty will be subject to repair or replacement of parts or the entire unit itself depending on the recommendation of the product service center free of charge with in the entire warranty period provided that the unit will not fall under the voidable warranty clause and that the customer will bring at his own expense the unit to the proper product repair channel as advised by the product manufacturer.<br><br>As part of the warranty policy, customers must complete a request log to avail of the regular warranty thru the website under “YOUR WARRANTIES”. After filling up all the required information Pinoy Electronic Store Online will issue a warranty recommendation to the customer to be presented to our assign physical store, partner stores or official service center. Customer may now bring his unit to the assigned location for official service works.<br><br>NOTE : No return or exchange request will be entertained or accepted due to customers sudden change of mind.</p>
        <br>
         <h4><strong>C. Approved replacement, refund  or order cancellation</strong></h4>
        <p style="text-align: justify;">After complying and satisfying all necessary procedures customers may avail of the replacement or payment refund immediately using the “REFUND & REPLACE” page. For replacements, upon receiving the defective item Pinoy Electronic Store Online will send the new replacement unit to the customer totally free of charge with the next available business day subject to the availability of the stocks. For refunds and order cancellations, Pinoy Electronic Store Online will return the entire purchase value thru the customers Digital Wallet account system as a first option (subject to customers choice). The customer may then use this refund value immediately to purchase any item from the website. For complete cash or payment refund requests as the customers second option. Pinoy Electronic Store Online upon customer full compliance of the procedure will approve and return the entire amount to the customer by means of an official bank account deposit named under the customer. No other means of refund payment will be entertained by Pinoy Electronic Store as part of its internal security and fraud protection.</p>
      </div>
    </div>
  </div>
</div>

<?php include "common/footer.php";?>	
 <script> 	
    $(document).ready(function() {    	

    });
 </script>