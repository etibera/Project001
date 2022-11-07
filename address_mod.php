<?php
include "common/headertest.php";
include "model/address.php";
$model = new Address();
$data = $model->address_list($_SESSION['user_login']);

// Insert Activity
require_once "model/customer_activity.php";
$customerActivity = new CustomerActivity();
$customerActivity->insertactivity($userid, 'Address Book Entries');
?>
<link rel="stylesheet" href="./assets/css/acc_nav.css">
<script type="text/javascript">
	var islog = '<?php echo $is_log; ?>';
	if (islog == "0") {
		location.replace("home.php");
	}
</script>
<div class="container bg-white p-sm-3" style="margin-top: 135px">
	<div class="row">
		<div class="form-group acc">
			<ul>
				<li><a class="active" href="address_mod.php">Address</a></li>
			</ul>
		</div>
	</div>
	<div class="row">
		<div class="form-group">
			<div class="col-sm-12">
				<span style="font-size: 26px" class="float-start">Address Book Entries</span>
				<div class="float-end">
					<a href="./index.php" class="btn btn-danger" title="Back"><i class="fas fa-arrow-left"></i></a>
					<a href="address_mod_update.php?aid=0" class="btn btn-primary" title="Add New Address"><i class="fas fa-plus"></i></a>
				</div>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="form-group">
			<div class="col-sm-12">
				<div class="table-responsive">
					<table class="table table-bordered address-table">
						<thead>
							<tr>
								<th>Address</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($data as $add) : ?>
								<tr>
									<td>
										<?php echo $add['firstname'] . ' ' . $add['lastname']; ?><br>
										<?php echo $add['company']; ?><br>
										<?php echo $add['address_1']; ?><br>
										<?php echo $add['address_2']; ?><br>
										<?php echo $add['district']; ?><br>
										<?php echo $add['city'] . ' ' . $add['postcode']; ?><br>
										<?php echo $add['region']; ?><br>
										<?php echo $add['country']; ?><br>
									</td>
									<td>
										<div class="pull-right">
											<?php if ($Getdefaultaddress['region'] == "") { ?>
												<button data-id="<?php echo $add['address_id']; ?>" data-customer_id="<?php echo $_SESSION['user_login']; ?>" class="btn btn-success setdefault" title="Set as default"><i class="fas fa-tools"></i> Set as Default</button>
											<?php } else { ?>
												<?php if ($Getdefaultaddress['address_id'] == $add['address_id']) { ?>
													<button class="btn btn-success">Default Address</button>
												<?php } else { ?>
													<button data-id="<?php echo $add['address_id']; ?>" data-customer_id="<?php echo $_SESSION['user_login']; ?>" class="btn btn-success setdefault" title="Set as default"><i class="fas fa-tools"></i> Set as Default</button>
												<?php } ?>
											<?php } ?>
											<a href="address_mod_update.php?aid=<?php echo $add['address_id']; ?>" class="btn btn-primary" title="Edit"><i class="far fa-edit"></i></a>
											<button data-id="<?php echo $add['address_id']; ?>" class="btn btn-danger btn-remove" title="Delete"><i class="far fa-trash-alt"></i></button>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include "common/footer.php"; ?>
<script>
	$(document).ready(function() {
		$('.address-table').on('click', '.btn-remove', function() {
			var getid = $(this).data("id");
			bootbox.confirm("Remove this Address?", function(result) {
				if (result == true) {
					$.ajax({
						url: 'ajax_delete_address.php',
						type: 'POST',
						data: 'address_id=' + getid,
						dataType: 'json',
						success: function(json) {
							bootbox.alert('' + json.success, function() {
								location.replace('./address_mod.php');
							});
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}
			});
		});
		$('.address-table').on('click', '.setdefault', function() {
			var getid = $(this).data("id");
			var customer_id = $(this).data("customer_id");
			bootbox.confirm("Set this Address As default?", function(result) {
				if (result == true) {
					$.ajax({
						url: 'ajaxAddress.php?action=setdefaultAdd&t=' + new Date().getTime(),
						type: 'POST',
						data: 'address_id=' + getid + '&customer_id=' + customer_id,
						dataType: 'json',
						beforeSend: function() {
							bootbox.dialog({
								title: "Updating Address",
								message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
							});
						},
						success: function(json) {
							if (json['success']) {
								bootbox.alert(json['success'], function() {
									location.reload();
								});
							}
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}
			});
		});

	});
</script>