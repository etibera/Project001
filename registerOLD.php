
<?php 
	include "common/header.php";
	$invitee_id = isset($_GET['cust_id']) ? $_GET['cust_id']: 333; 
	require_once 'model/reg_new.php';
	$reg=0;
	$add_address = new Register_new();	
	
	if(isset($_POST['add_customer'])){	 
		$fname =''; // $_POST['txtfname'];
		$lname ='';  //$_POST['txtlname'];
		$bday = ''; //$_POST['txtbday'];
		$email =  $_POST['txtemail'];
		$mobile =  '';//$_POST['txtmobile'];
		$username =  $_POST['txtemail'];
		$password =  $_POST['txtpassword'];
		$confirmpassword =  $_POST['txtconfirmpassword'];

		/*if($add_address->checkuser($username)){
		  	$errorMsg[]=array('name' => 'Username already exists!'); 
		}*/
		if($password !== $confirmpassword){
			$errorMsg[]=array('name' => 'Password Unmatched ! '); 
		}
		/*if($add_address->checkMobileNumber($mobile)){
		  	$errorMsg[]=array('name' => 'Mobile Number already exists!'); 
		}*/
		if($add_address->checkemailAdress($_POST['txtemail'])){
		  	$errorMsg[]=array('name' => 'Email Address already exists!'); 		  	
		}		        
        if(!isset($errorMsg)){
  			
        	$res=$add_address->save_customernew($_POST,$invitee_id);
        	if($res['code']=="200"){
        		$reslastid=$res['last_id'];  
        		$cust_det=$add_address->getCustomerdetails($reslastid);
        		if($cust_det){
        			$add_address->add_shipping_wallet($cust_det['customer_id']);
        			$_SESSION['regok_mobile_verify'] = 'Successfully Registered!'; 
        			$session->login($cust_det);
					$add_address->insertactivity($cust_det['customer_id'],'login');
					$locationV="<script> window.location.href='home.php";
				  	$locationV.="';</script>";
			  		echo $locationV;
        		}
        		/*$_SESSION['message_regok'] = 'Successfully Registered!'; 
        		$messageval='Your PESO Verification Code Is '.$res['confirmcode'];
        		$add_address->sendVevification($res['mobile'],$messageval); 
        		
			  	$locationV="<script> window.location.href='reg_activatemobile.php?RegIdVal=".$reslastid;
			  	$locationV.="';</script>";
		  		echo $locationV;		*/
        		
        	}else{
        		$errorMsg[]=array('name' => $res['code']); 
        	}
        }
	}
?>
<script type="text/javascript">
  var islog='<?php echo $is_log;?>';
      if(islog=="1"){
      location.replace("home.php");
    }
</script>

<div class="wrapper">	
	<div class="container">			
		<div class="col-lg-12">	
			<h2>Register Account for Free</h2>
			<p>If you already have an account with us, please login at the <a data-toggle="modal" data-target="#LoginModal"> login page </a></p>
			<?php if(!isset($_SESSION['access_token'])){ ?>
	          <a class="btn btn" style="box-shadow: 0 0.0625rem 0.125rem 0 rgba(0,0,0,.6);border-radius: 4px;background: #fff; color: black;font-size: 13px;" href="<?php echo $google_client->createAuthUrl();?>"><img src="assets/sign-in-with-google.png"  style="height: 18px;" /> Sign up with Google</a>
	         <?php } ?>
	         <br><br>
			<?php if(isset($errorMsg)){ ?>
			     <div class="alert alert-danger">
			         <?php foreach ($errorMsg as $error) : ?>  
			            <strong><?php echo $error['name']?></strong></br>
			         <?php  endforeach;?>
			     </div>
	    	<?php } ?>
	    	<?php if(isset($sMsg)){ ?>
				<div class="alert alert-success">
				    <strong><?php echo $sMsg;?></strong></br>
				</div>
			<?php } ?>
			<form action="register.php" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">				
				<div class="alert alert-info">
					<strong>Your Personal Details</strong>
				</div>
				<!-- <div class="form-group" >
					<label class="col-sm-3 control-label">First Name</label>
					<div class="col-sm-6">
						<input type="text" name="txtfname" class="form-control" placeholder="First Name" value="<?php if(isset($_POST['txtfname'])):echo $_POST['txtfname'];endif;?>"  required />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Last Name</label>
					<div class="col-sm-6">						
						<input type="text" name="txtlname" class="form-control" placeholder="Last Name" value="<?php if(isset($_POST['txtlname'])):echo $_POST['txtlname'];endif;?>" required />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Birthday</label>
					<div class="col-sm-6">
						<input type="date" name="txtbday" class="form-control" placeholder="" value="<?php if(isset($_POST['txtbday'])):echo $_POST['txtbday'];endif;?>"  required/>
					</div>
				</div> -->
				<div class="form-group">
					<label class="col-sm-3 control-label">Email Address</label>
					<div class="col-sm-6">
						<input type="text" name="txtemail" class="form-control" placeholder="Email Address" value="<?php if(isset($_POST['txtemail'])):echo $_POST['txtemail'];endif;?>" required/>
						<div id="email_add">
			       		</div>
					</div>
					
				</div>
				<!-- <div class="form-group">
					<label class="col-sm-3 control-label">Mobile No.</label>
					<div class="col-sm-6">
						<input type="Number" name="txtmobile" class="form-control" placeholder="Mobile No. " value="<?php if(isset($_POST['txtmobile'])):echo $_POST['txtmobile'];endif;?>" required/>
						<div id="divmobile">
			       		</div>
					</div>
				</div>			 -->		
				<!-- <div class="alert alert-info">
					<strong>Your Username</strong>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Username</label>
					<div class="col-sm-6">
						<input type="text" name="txtusername" class="form-control" placeholder="Username" value="<?php if(isset($_POST['txtusername'])):echo $_POST['txtusername'];endif;?>" required/>
						<div id="divusername">
			       		</div>
					</div>
				</div> -->
				<div class="alert alert-info">
					<strong>Your Password</strong>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Password</label>
					<div class="col-sm-6">
						<input type="Password" name="txtpassword" class="form-control" placeholder="Password"  required/>
						<div id="divpassword">
			       		</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Confirm Password</label>
					<div class="col-sm-6">
						<input type="Password" name="txtconfirmpassword" class="form-control" placeholder="Confirm Password" required/>
						<div id="divcpassword">
			       		</div>
					</div>
					
					<br>
				</div>
				<div class="col-sm-9 " style="margin-bottom: 30px">
					<div class="pull-right ">
						<label >
					I have read and agree to the <a id="reg_tnc"> Terms and Condition </a></label>
						<input type="checkbox" style="margin-right: 10px;margin-left: 5px;"  class="" id="" name="" value="" required>
						<input type="submit" name="add_customer" style="margin-top: 5px;" class="btn btn-primary " value="Continue">
					</div>
				</div>
					<br><br><br><br><br><br>				
			</form>
		</div>
	</div>
</div>		
<script type="text/javascript">
$('input[name="txtemail"]').change(function(e) {
	var emailStr = $(this).val();
    var regex = /^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i;
    if(!regex.test(emailStr)) {
       $("#email_add").empty();
       $("#email_add").append('<div class="text-danger">Email address is not valid</div>');
       $(':input[type="submit"]').prop('disabled', true);
    }else{
    	$("#email_add").empty();
    	$(':input[type="submit"]').prop('disabled', false);
    }
});
$('input[name="txtmobile"]').change(function(e) {
	var mobStr = $(this).val();
    
    if(mobStr.length!= 10 || !$.isNumeric(mobStr)) {
       $("#divmobile").empty();
       $("#divmobile").append('<div class="text-danger">Mobile No. must be 10 characters. (Ex. 9xxxxxxxxx)</div>');
       $(':input[type="submit"]').prop('disabled', true);
    }else{
    	$("#divmobile").empty();
    	$(':input[type="submit"]').prop('disabled', false);
    }
});
$('input[name="txtusername"]').change(function(e) {
	var userStr = $(this).val();    
    if(userStr.length <6 ) {
       $("#divusername").empty();
       $("#divusername").append('<div class="text-danger">Username must be between 6 and 100 characters! </div>');
       $(':input[type="submit"]').prop('disabled', true);
    }else{
    	$("#divusername").empty();
    	$(':input[type="submit"]').prop('disabled', false);
    }
});
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
$(document).delegate('#reg_tnc', 'click', function() {
	$('#modal_info_data').modal('show');
	$("#TandC_head").css("display", "block");
	$("#info_div_TandC").css("display", "block");
	$("#About_Us_head").css("display", "none");
	$("#info_div_AboutUs").css("display", "none");
});
</script>								
<?php
include "common/footer.php";
?>
