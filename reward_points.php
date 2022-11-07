<?php
include "common/headertest.php";
include "model/reward_points.php";
// Insert Activity
require_once "model/customer_activity.php";
$customerActivity = new CustomerActivity();
$customerActivity->insertactivity($userid, 'Your Reward Points');
$model = new RewardPoints();
if (isset($_SESSION['user_login'])) {
	$data = $model->reward_list($_SESSION['user_login']);
	$reward_total = $model->reward_total($_SESSION['user_login']);
}
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
				<li><a href="account.php">Account</a></li>
				<li><a href="change_pass.php">Change Password</a></li>
				<li><a href="address_mod.php">Address</a></li>
				<li><a class="active" href="reward_points.php">Reward Points</a></li>
			</ul>
		</div>
	</div>
	<div class="row">
		<div class="form-group">
			<div class="col-sm-12">
				<span style="font-size: 26px" class="pull-left">Your Reward Points</span>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="form-group">
			<div class="col-sm-12">
				<small>Your total number of reward points is: <b><?php echo $reward_total['total'] . 'pts'; ?></b></small>
			</div>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-sm-12">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover order-table">
					<thead>
						<th>Date Added</th>
						<th>Description</th>
						<th>Points</th>
					</thead>
					<tbody>
						<?php
						if (count($data) > 0) {
							foreach ($data as $o) :
						?>
								<tr>
									<td><?php echo $o['date_added']; ?></td>
									<td>
										<a href="order_details.php?order_id=<?php echo $o['order_id']; ?>">
											<?php echo $o['description']; ?>
										</a>
									</td>
									<td><?php echo $o['points']; ?></td>
								</tr>
							<?php
							endforeach;
						} else { ?>
							<tr>
								<td colspan="3" align="center">No data found.</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php
include "common/footer.php";
?>

<script>
	$(document).ready(function() {

		$('.order-table').paging({
			limit: 5,
			rowDisplayStyle: 'block',
			activePage: 0,
			rows: []
		});

	});
</script>