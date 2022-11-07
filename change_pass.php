<?php
include "common/headertest.php";
// Insert Activity
require_once "model/customer_activity.php";
$customerActivity = new CustomerActivity();
$customerActivity->insertactivity($userid, 'Change Password');
?>
<link rel="stylesheet" href="./assets/css/acc_nav.css">
<script type="text/javascript">
	var islog = '<?php echo $is_log; ?>';
	if (islog == "0") {
		location.replace("home.php");
	}
</script>
<div class="container bg-white p-sm-3" style="margin-top: 135px;">
	<div class="row">
		<div class="form-group acc">
			<ul>
				<li><a class="active" href="change_pass.php">Change Password</a></li>
			</ul>
		</div>
	</div>
	<form method="post" action="submit.php">
		<div class="row">
			<div class="form-group">
				<div class="col-sm-12">
					<span style="font-size: 26px" class="float-start">Change Password</span>
					<div class="float-end">
						<a href="./index.php" class="btn btn-danger" title="Back"><i class="fas fa-arrow-left"></i></a>
						<button type="submit" name="update_password" class="btn btn-primary" title="Save"><i class="far fa-save"></i></button>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<br>
			<?php if (isset($_SESSION['message'])) : ?>
				<?php echo $_SESSION['message']; ?>
			<?php endif; ?>
			<?php if (!isset($_SESSION['message'])) : ?>
				<?php
				unset($_SESSION['oldpassword']);
				unset($_SESSION['password']);
				unset($_SESSION['confirmpassword']); ?>
			<?php endif; ?>
			<?php unset($_SESSION['message']); ?>
			<div class="row mb-3">
				<label>Old Password
					<input type="Password" name="oldpassword" class="form-control" placeholder="Old Password" value="<?php if (isset($_SESSION['oldpassword'])) : echo $_SESSION['oldpassword'];
																																																						endif; ?>" required />
				</label>
			</div>
			<div class="row mb-3">
				<label>New Password
					<input type="Password" name="txtpassword" class="form-control" placeholder="Password" value="<?php if (isset($_SESSION['password'])) : echo $_SESSION['password'];
																																																				endif; ?>" required />

					<input type="hidden" name="txtid" value="<?php echo $_SESSION['user_login']; ?>" />
				</label>
			</div>
			<div class="row mb-3">
				<label>Confirm New Password
					<input type="Password" name="txtconfirmpassword" class="form-control" placeholder="Confirm Password" value="<?php if (isset($_SESSION['confirmpassword'])) : echo $_SESSION['confirmpassword'];
																																																											endif; ?>" required />
				</label>
				<br>
			</div>
		</div>
	</form>
</div>
<?php include "common/footer.php"; ?>