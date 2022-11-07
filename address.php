<?php 
	include "common/header.php";
	include "model/address.php";
	$address = new Address();
	$id = isset($_SESSION['user_login']) ? $_SESSION['user_login']: 0;	
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
			 <center><h2>New Address</h2></center>
			 <div class="row">
					<a href="./cart_new.php"><< Back to Payment</a>
			</div>	
		</div>
	</div>
	<br>	
	<?php if(isset($_SESSION['message'])):?>
	<div class="alert alert-success"><?php echo $_SESSION['message'];?></div>
	<?php endif;?>
	<?php unset($_SESSION['message']); ?>
	<form action="submit.php" method="post">
	<input type="hidden" name="customer_id" value="<?php echo $_SESSION['user_login']?>">
	<div class="form-group">
		<label for=""><b style="color: red">*</b> First Name</label>
		<input type="text" name="firstname" class="form-control" placeholder="First Name" required>
	</div>
	<div class="form-group">
		<label for=""><b style="color: red">*</b> Last Name</label>
		<input type="text" name="lastname" class="form-control" placeholder="Last Name" required>
	</div>
	<div class="form-group">
		<label for="">Company</label>
		<input type="text" name="company" class="form-control" placeholder="Company">
	</div>
	<div class="form-group">
		<label for=""><b style="color: red">*</b> Address 1</label>
		<input type="text" name="address1" class="form-control" placeholder="Address 1">
	</div>
	<div class="form-group">
		<label for="">Address 2</label>
		<input type="text" name="address2" class="form-control" placeholder="Address 2">
	</div>
	<div class="form-group">
		<label for=""><b style="color: red">*</b> City</label>
		<input type="text" name="city" class="form-control" placeholder="City">
	</div>
	<div class="form-group">
		<label for=""><b style="color: red">*</b> Postal Code</label>
		<input type="text" name="postal" class="form-control" placeholder="Postal Code">
	</div>
	<div class="form-group">
		<label for=""><b style="color: red">*</b> Country</label>
		<select name="country" id="country" class="form-control" required>
			<option value="">--Select Country--</option>
			<?php foreach($address->country() as $c):?>
			<option value="<?php echo $c['country_id']; ?>"><?php echo $c['country_name'];?></option>
			<?php endforeach;?>
		</select>
	</div>
	<div class="form-group">
		<label for=""><b style="color: red">*</b> Region/State</label>
		<select name="region" id="region" class="form-control" required>	
		<option value="">--Select Region/State--</option>		
		</select>
	</div>
	<div class="form-group">
		<button class="btn btn-primary" type="submit" name="add_address">Save</button>
	</div>
</form>

</div>
<?php
include "common/footer.php";
?>	

 <script> 	
    $(document).ready(function() {    	
    	$('#country').on('change', function(){
	   		var country_id = $(this).val();
	   		$("#region").empty();
	   		$.ajax({
            url: 'ajax_get_zone.php',
            type: 'POST',
            data: 'country_id=' + country_id,
            dataType: 'json',
            success: function(json) {
                $("#region").append('<option value="">--Select Region/State--</option>');

                for (var i = 0; i < json.length; i++) {
                    $("#region").append('<option value="' + json[i].zone_id + '">' + json[i].zone_name + '</option>');
                }
            },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
          	});
	   }); 

    });
 </script>