<?php
include "common/headertest.php";
require_once "model/account.php";
// Insert Activity
require_once "model/customer_activity.php";
$customerActivity = new CustomerActivity();
$customerActivity->insertactivity($userid, 'Account Information');
require_once "include/M360Api.php";
$returntocart = "";
if (isset($_GET['checkout'])) {
	$returntocart = "&checkout=1";
}
if (isset($_GET['popupchk'])) {
	$returntocart = "&popupchk=1";
}
$model = new Account();
$M360Api = new M360Api;
$credentials = $M360Api->M360Credetial();
$M360Domain = $M360Api->M360Domain();
$M360Url = $M360Domain['production'];
$data = $model->account_details($_SESSION['user_login']);
$id = isset($_SESSION['user_login']) ? $_SESSION['user_login'] : 0;
$popupchk=$_GET['popupchk']??0;
$datacustype="";
if($data['landbankacc']==1){
 $datacustype="Landbank Account";
}else if($data['fgivesacc']==1){
	$datacustype="4Gives Account";
}else{
	$datacustype="Regular Account";
}
if (isset($_POST['update_account'])) {
	$customer_id =  $_POST['customer_id'];
	$firstname =  $_POST['firstname'];
	$lastname =  $_POST['lastname'];
	$b_day =  $_POST['b_day'];
	$email =  $_POST['email'];
	$telephone =  $_POST['telephone'];
	$confirmcode = rand(100000, 999999);
	$model->update_account($customer_id, $firstname, $lastname, $b_day, $email, $telephone, $confirmcode);
	$messageval = 'Your PESO Verification Code Is ' . $confirmcode;
	$mobileNumber = $telephone;
	$content = $messageval;
	$M360RequestData = array(
		'username' => $credentials['username'],
		'password' => $credentials['password'],
		'msisdn' =>  $mobileNumber,
		'content' =>  $content,
		'shortcode_mask' => $credentials['shortcode_mask'],
	);

	if ($data['nexmo_status'] == 0) {
		$M360sms = curl_init($M360Url);
		curl_setopt($M360sms, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($M360sms, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json'
		));
		curl_setopt($M360sms, CURLOPT_POST, 1);
		curl_setopt($M360sms, CURLOPT_POSTFIELDS, json_encode($M360RequestData));
		curl_setopt($M360sms, CURLOPT_FOLLOWLOCATION, 1);
		$dataM360sms = curl_exec($M360sms);
		curl_close($M360sms);
		$ResponsedataM360 = json_decode($dataM360sms);
		if ($ResponsedataM360->code == "400") {
			$model->sendVevification('0' . $telephone, $messageval);
		}
		$_SESSION['message_regok'] = 'Account Successfully Updated !';
		$locationV = "<script> window.location.href='reg_activatemobile.php?RegIdVal=" . $customer_id . $returntocart;
		$locationV .= "';</script>";
		echo $locationV;
	} else if ($data['telephone'] = !$telephone) {

		$M360sms = curl_init($M360Url);
		curl_setopt($M360sms, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($M360sms, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json'
		));
		curl_setopt($M360sms, CURLOPT_POST, 1);
		curl_setopt($M360sms, CURLOPT_POSTFIELDS, json_encode($M360RequestData));
		curl_setopt($M360sms, CURLOPT_FOLLOWLOCATION, 1);
		$dataM360sms = curl_exec($M360sms);
		curl_close($M360sms);
		$ResponsedataM360 = json_decode($dataM360sms);
		if ($ResponsedataM360->code == "400") {
			$model->sendVevification('0' . $telephone, $messageval);
		}
		if($popupchk==1){               
            $locationV="<script> window.close();</script>";
            echo $locationV;
        }else{
			$_SESSION['message_regok'] = 'Account Successfully Updated !';
			$locationV = "<script> window.location.href='reg_activatemobile.php?RegIdVal=" . $customer_id . $returntocart;
			$locationV .= "';</script>";
			echo $locationV;
		}
		
	} else {
		if($popupchk==1){               
            $locationV="<script> window.close();</script>";
            echo $locationV;
        }else{
			$data = $model->account_details($_SESSION['user_login']);
		}
	}
}
?>
<link rel="stylesheet" href="./assets/css/acc_nav.css">
<script type="text/javascript">
	var islog = '<?php echo $is_log; ?>';
	if (islog == "0") {
		location.replace("home.php");
	}
</script>
<div class="container bg-white rounded-3 p-sm-3" style="margin-top: 135px;" id="div_main_acc">
	<?php if($popupchk==0){ ?>
	<div class="row">
		<div class="form-group acc">
			<ul>
				<li><a class="active" href="account.php">Account</a></li>
			</ul>
		</div>
	</div>
	<?php } ?> 
	<form method="post" action="account.php?t=xt<?php echo $returntocart; ?>">
		<div class="row">
			<div class="form-group">
				<div class="col-sm-12">
					<span style="font-size: 26px" class="float-start">Account Information</span>
					<div class="float-end">
						<a href="./index.php" class="btn btn-danger" title="Back"><i class="fas fa-arrow-left"></i></a>
						<button type="submit" name="update_account" class="btn btn-primary" title="Save"><i class="far fa-save"></i></button>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<!-- <?php $newpass = md5("PS775364");
						echo "<br>" . $newpass . "<br>"; ?> -->
			<br>
			<?php if (isset($_SESSION['message'])) : ?>
				<?php echo $_SESSION['message']; ?>
			<?php endif; ?>
			<?php if (!isset($_SESSION['message'])) : ?>
				<?php
				unset($_SESSION['firstname']);
				unset($_SESSION['lastname']);
				unset($_SESSION['telephone']);
				unset($_SESSION['b_day']);
				unset($_SESSION['email']); ?>
			<?php endif; ?>
			<?php unset($_SESSION['message']); ?>
			<?php if($popupchk==0){ ?>
			<div class="row">
				<div class="form-group acc">
					<ul>
						<li> <li><a class="bg-success" href=""><?php echo $datacustype;?></a></li></li>
					</ul>
				</div>
			</div>
			<?php } ?> 
			
			<div class="row mb-2">
				<label class="form-label">First Name
					<input type="text" name="firstname" class="form-control" placeholder="First Name" value="<?php echo isset($_SESSION['firstname']) ? $_SESSION['firstname'] : $data['firstname']; ?>" required />
					<input type="hidden" name="customer_id" value="<?php echo $_SESSION['user_login']; ?>" />
				</label>
			</div>
			<div class="row mb-2">
				<label class="form-label">Last Name
					<input type="text" name="lastname" class="form-control" placeholder="Last Name" value="<?php echo isset($_SESSION['lastname']) ? $_SESSION['lastname'] : $data['lastname']; ?>" required />
				</label>
			</div>
			<div class="row mb-2">
				<label class="form-label">Mobile No.
					<input type="Number" name="telephone" class="form-control" placeholder="Mobile No. " value="<?php echo isset($_SESSION['telephone']) ? $_SESSION['telephone'] : $data['telephone']; ?>" required />
				</label>
				<div id="divmobile"></div>
			</div>
			<div class="row mb-2">
				<label class="form-label">Birthday
					<input type="date" name="b_day" class="form-control" value="<?php echo isset($_SESSION['b_day']) ? $_SESSION['b_day'] : $data['b_day']; ?>" required />
				</label>
			</div>
			<div class="row mb-2">
				<label class="form-label">Email
					<input type="text" class="form-control" placeholder="Email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : $data['email']; ?>" disabled />
					<input type="hidden" name="email" class="form-control" placeholder="Email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : $data['email']; ?>" required />
				</label>
			</div>
		</div>
	</form>
</div>

<?php if($popupchk==0){include "common/footer.php";} ?>

<script>
	var customer_id = '<?php echo $_SESSION['user_login']; ?>';
	var popupchk = '<?php echo $popupchk; ?>';


	$(document).ready(function() {

		if(popupchk==1){
			$("#nav_cat").css("display", "none");
			$("#subhead").css("display", "none");
			$("#div_main_acc").css("margin-top","50px");
			
		}else{
			$("#nav_cat").css("display", "block");
			$("#subhead").css("display", "block");
			$("#div_main_acc").css("margin-top","135px");
		}
		$('input[name="telephone"]').change(function(e) {
			var mobStr = $(this).val();
			if (mobStr.length != 10 || !$.isNumeric(mobStr)) {
				$("#divmobile").empty();
				$("#divmobile").append('<div class="text-danger">Mobile No. must be 10 characters. (Ex. 9xxxxxxxxx)</div>');
				$(':input[type="submit"]').prop('disabled', true);
			} else {
				console.log(customer_id);
				$.ajax({
					url: 'ajaxAddress.php?action=GetmobileStats&t=' + new Date().getTime(),
					type: 'POST',
					data: 'customer_id=' + customer_id + '&mobile=' + mobStr,
					dataType: 'json',
					success: function(json) {
						if (json) {
							$("#divmobile").empty();
							$("#divmobile").append('<div class="text-danger">Mobile Number already exists!</div>');
							$(':input[type="submit"]').prop('disabled', true);
						} else {
							$("#divmobile").empty();
							$(':input[type="submit"]').prop('disabled', false);
						}
					}
				});

			}
		});

	});
</script>