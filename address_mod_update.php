<?php
include "common/headertest.php";
include "model/address.php";
// Insert Activity
require_once "model/customer_activity.php";
$customerActivity = new CustomerActivity();
$customerActivity->insertactivity($userid, 'Add New Address');
$address = new Address();
$data = $address->address_details($_GET['aid'], $_SESSION['user_login']);
$customer_name = $address->getcustomername($_SESSION['user_login']);
$returntocart = 0;
$retohome = 0;
$returntocarturl = "";
if (isset($_GET['checkout'])) {
	$returntocart = 1;
	$returntocarturl = '&checkout=1';
}else if(isset($_GET['retohome'])){
     $retohome=1;
     $returntocarturl='&retohome=1';
}else if(isset($_GET['popupchk'])){
     $returntocart=1;
     $returntocarturl='&popupchk=1';
}
$popupchk=$_GET['popupchk']??0;
if (isset($_POST['update_address'])) {
	if ($_POST['address_id'] == '0') {
		$res = $address->save_address_modNew($_POST);
		if ($returntocart == 1) {
		  if($popupchk==1){               
                $locationV="<script> myWindow.close();</script>";
                echo $locationV;
            }else{
                $locationV="<script> window.location.href='cart.php";
                $locationV.="';</script>";
                echo $locationV;
            }
		}else if($retohome==1){
			$locationV = "<script> window.location.href='home.php";
			$locationV .= "';</script>";
			echo $locationV;
		} else {
			if ($res == "200") {
				$sMsg = "Successfully Updated!";
			} else {
				$errsMsg = $res;
			}
		}
	} else {
		$res = $address->update_address_modNew($_POST);
		if ($returntocart == 1) {
			if($popupchk==1){               
	                $locationV="<script> myWindow.close();</script>";
	                echo $locationV;
	          }else{
	                $locationV="<script> window.location.href='cart.php";
	                $locationV.="';</script>";
	                echo $locationV;
	          }
		}else if($retohome==1){
			$locationV = "<script> window.location.href='home.php";
			$locationV .= "';</script>";
			echo $locationV;
		} else {
			if ($res == "200") {
				$sMsg = "Successfully Updated!";
			} else {
				$errsMsg = $res;
			}
		}

		$data = $address->address_details($_GET['aid'], $_SESSION['user_login']);
		$customer_name = $address->getcustomername($_SESSION['user_login']);
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

<div class="container bg-white p-sm-3" style="margin-top: 135px" id="div_main_acc">
	<?php if($popupchk==0){ ?>
	<div class="row">
		<div class="form-group acc">
			<ul>
				<li><a class="active" href="address_mod.php">Address</a></li>
			</ul>
		</div>
	</div>
	<?php 5?>
	<form action="address_mod_update.php?aid=<?php echo $_GET['aid'] . $returntocarturl; ?>" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
		<div class="row">
			<div class="form-group">
				<div class="col-sm-12">
					<?php $header = $_GET['aid'] !== '0' ? $header = 'Edit Address' : $header = 'Add New Address'; ?>
					<span style="font-size: 26px" class="float-start"><?php echo $header; ?></span>
					<div class="float-end">
						<?php if (isset($_GET['chk'])) { ?>
							<a href="cart.php" class="btn btn-danger" title="Back"><i class="fas fa-arrow-left"></i></a>
						<?php } else { ?>
							<a href="address_mod.php" class="btn btn-danger" title="Back"><i class="fas fa-arrow-left"></i></a>
						<?php } ?>
						<button type="submit" class="btn btn-primary" name="update_address" title="Save"><i class="far fa-save"></i></button>
					</div>
				</div>
			</div>
		</div>
		<br>
		<?php if (isset($sMsg)) { ?>
			<div class="alert alert-success">
				<strong><?php echo $sMsg; ?></strong></br>
			</div>
		<?php } ?>
		<?php if (isset($errsMsg)) { ?>
			<div class="alert alert-danger">
				<strong><?php echo $errsMsg; ?></strong></br>
			</div>
		<?php } ?>
		<div class="">
			<input type="hidden" name="address_id" value="<?php echo $_GET['aid'] ?>">
			<input type="hidden" name="customer_id" value="<?php echo $_SESSION['user_login'] ?>">

			<div class="row mb-3">
				<label class="form-label">Company/Contact Person
					<input type="text" name="company" class="form-control" placeholder="Company/Contact Person" value="<?php echo  $data['company'] ?? ''; ?>" />
				</label>
			</div>
			<div class="row mb-3">
				<label class="form-label">
					<b style="color: red">*</b>House Number/Street/Building Name
					<input type="text" name="address_1" class="form-control" placeholder="House Number/Street/Building Name" value="<?php echo  $data['address_1'] ?? ''; ?>" required />
				</label>
			</div>
			<div class="row mb-3">
				<label class="form-label">Unit/Floor
					<input type="text" name="address_2" class="form-control" placeholder="Unit/Floor" value="<?php echo  $data['address_2']?? ''; ?>" />
				</label>
			</div>
			<div class="row mb-3">
				<label class="form-label">
					<b style="color: red">*</b> Region/Province
					<select name="region" id="region" class="form-control" required>
						<option value="">--Region/Province--</option>
						<?php foreach ($address->getRegion() as $c) : ?>
							<?php if ($c['province'] == $data['region']) { ?>
								<option value="<?php echo $c['province']; ?>" selected><?php echo $c['province']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $c['province']; ?>"><?php echo $c['province']; ?></option>
							<?php } ?>

						<?php endforeach; ?>
					</select>
				</label>
			</div>

			<div class="row form-group" id="fgcity" <?php if ($_GET['aid'] == 0) { ?>style="display: none;" <?php } ?>>
				<div class="col-sm-4 control-label"><label><b style="color: red">*</b> City/Municipality</label></div>
				<div class="col-sm-8">
					<select name="city" id="city" class="form-control" required>
						<?php if ($_GET['aid'] != 0) { ?>
							<?php foreach ($address->getCity($data['region']) as $gc) : ?>
								<?php if ($gc['city'] == $data['city']) { ?>
									<option value="<?php echo $gc['city']; ?>" selected><?php echo $gc['city']; ?></option>
								<?php } else { ?>
									<option value="<?php echo $gc['city']; ?>"><?php echo $gc['city']; ?></option>
								<?php } ?>
							<?php endforeach; ?>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="row form-group" id="fgbarangay" <?php if ($_GET['aid'] == 0) { ?>style="display: none;" <?php } ?>>
				<div class="col-sm-4 control-label"><label><b style="color: red">*</b> Barangay/District</label></div>
				<div class="col-sm-8">
					<select name="district" id="district" class="form-control" required>
						<?php if ($_GET['aid'] != 0) { ?>
							<?php foreach ($address->getDistrict($data['region'], $data['city']) as $gd) : ?>
								<?php if ($gd['district'] == $data['district']) { ?>
									<option value="<?php echo $gd['district']; ?>" selected><?php echo $gd['district']; ?></option>
								<?php } else { ?>
									<option value="<?php echo $gd['district']; ?>"><?php echo $gd['district']; ?></option>
								<?php } ?>
							<?php endforeach; ?>
						<?php } ?>
					</select>
				</div>
			</div>
			<input type="hidden" name="postal_code" id="postal_code" value="<?php echo  $data['postcode']; ?>" required />
			<input type="hidden" name="tracking_id" id="tracking_id" value="<?php echo  $data['tracking_id']; ?>" required />
			<input type="hidden" name="firstname" value="<?php echo  $customer_name['firstname']; ?>" />
			<input type="hidden" name="lastname" value="<?php echo  $customer_name['lastname']; ?>" />
			<div class="row float-end" id="fgusedefault">
				<div>
					<label>Use this as default Address
						<input class="form-check-input" type="checkbox" style="margin-right: 10px;margin-left: 5px;" name="usedefault" checked>
					</label>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</form>
</div>
<?php if($popupchk==0){include "common/footer.php";} ?>
<script>
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
		$('#region').on('change', function() {
			$("#fgcity").css("display", "block");
			$("#city").empty();
			$("#district").empty();
			var province = $(this).val();
			$.ajax({
				url: 'ajaxAddress.php?action=getCity&t=' + new Date().getTime(),
				type: 'POST',
				data: 'province=' + province,
				dataType: 'json',
				success: function(json) {
					$("#city").append('<option value="">--Select City/Municipality--</option>');
					//console.log(json);
					for (var i = 0; i < json.length; i++) {
						$("#city").append('<option value="' + json[i].city + '">' + json[i].city + '</option>');
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});
		$('#city').on('change', function() {
			$("#fgbarangay").css("display", "block");
			$("#district").empty();
			var province = $("#region").val();
			var city = $(this).val();
			//alert(city);   		
			$.ajax({
				url: 'ajaxAddress.php?action=getDistrict&t=' + new Date().getTime(),
				type: 'POST',
				data: 'province=' + province + '&city=' + city,
				dataType: 'json',
				success: function(json) {
					$("#district").append('<option value="">--Select Barangay/District--</option>');
					//console.log(json);
					for (var i = 0; i < json.length; i++) {
						$("#district").append('<option value="' + json[i].district + '">' + json[i].district + '</option>');
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});
		$('#district').on('change', function() {
			var province = $("#region").val();
			var city = $("#city").val();
			var district = $(this).val();
			//alert(city);   		
			$.ajax({
				url: 'ajaxAddress.php?action=getTracking_id&t=' + new Date().getTime(),
				type: 'POST',
				data: 'province=' + province + '&city=' + city + '&district=' + district,
				dataType: 'json',
				success: function(json) {
					//console.log(json);
					$("#tracking_id").val(json['tracking_id']);
					$("#postal_code").val(json['postal_code']);
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}			});
		});
	});
</script>