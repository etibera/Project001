<?php 
	include "common/header_new.php";
	require_once "include/Session.php";
	
	require_once 'model/forgot_password.php';
	require_once 'model/reg_new.php';
	$FPMod=new ForgotPasswowrd();
	$add_address = new Register_new();	
	$token=0;
	if(isset($_GET['Y2F0X2lk'])){
		$token=$FPMod->count_token($_GET['Y2F0X2lk']);
		if(isset($_POST['ChangePassword'])){
			if($token!=0){			
				$customer_data=$FPMod->customer_data_bytoken($_GET['Y2F0X2lk']);
				$customer_id=$customer_data['customer_id'];
				$telephone="0".$customer_data['telephone'];
				$res=$FPMod->update_password($customer_id,$_POST);
				if($res=="200"){
					$messageval='Your PESO password has been change.';
        			$add_address->sendVevification($telephone,$messageval); 
					$_SESSION['regok_mobile_verify'] = 'Change Password Successfully Updated'; 
					$locationV="<script> window.location.href='home.php";
				  	$locationV.="';</script>";
			  		echo $locationV;

				}
			}
		}
	}
?>
<script type="text/javascript">
  var token='<?php echo $token;?>';
      if(token=="0"){
      location.replace("home.php");
    }
</script>
<div class="wrapper">	
	<div class="container">			
		<div class="col-lg-12">	
			<form action="change_password.php?Y2F0X2lk=<?php echo $_GET['Y2F0X2lk'];?>&5aP7W1h9K=5217c1cbaaf7c1a7627b87760fe6efa9f704008a" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">	
			<div class="alert alert-info">
					<strong>Reset  Password</strong>
			</div>
				<div class="form-group">
					<label class="col-sm-3 control-label"> New Password</label>
					<div class="col-sm-6">
						<!-- <input type="hidden" name="customer_id" class="form-control"   value="<?php echo $customer_id;?>" required/>
						<input type="hidden" name="telephone" class="form-control"   value="<?php echo $telephone;?>" required/> -->
						<input type="Password" name="txtpassword" class="form-control" placeholder="New Password"  required/>
						<div id="divpassword">
			       		</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Confirm New Password</label>
					<div class="col-sm-6">
						<input type="Password" name="txtconfirmpassword" class="form-control" placeholder="Confirm New Password" required/>
						<div id="divcpassword">
			       		</div>
					</div>					
					<br>
				</div>
				<div class="col-sm-9 " style="margin-bottom: 30px">
					<div class="pull-right ">
						<input type="submit" name="ChangePassword" style="margin-top: 5px;" class="btn btn-primary " value="Continue">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
$('input[name="txtpassword"]').change(function(e) {
	var passStr = $(this).val();    
    if(passStr.length <6 ) {
       $("#divpassword").empty();
       $("#divpassword").append('<div class="text-danger">Password must be 6 characters or more ! </div>');
       $(':input[type="submit"]').prop('disabled', true);
    }else{
    	$("#divpassword").empty();
    	$(':input[type="submit"]').prop('disabled', false);
    }
});
$('input[name="txtconfirmpassword"]').change(function(e) {
	var CuserStr = $(this).val();   
	var passStr = $('input[name="txtpassword"]').val(); 
    if(CuserStr != passStr) {
       $("#divcpassword").empty();
       $("#divcpassword").append('<div class="text-danger">Password Unmatched ! </div>');
       $(':input[type="submit"]').prop('disabled', true);
    }else{
    	$("#divcpassword").empty();
    	$(':input[type="submit"]').prop('disabled', false);
    }
});
</script>
<?php
include "common/footer.php";
?>