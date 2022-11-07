<?php
include "common/headertest.php";
include "model/returns.php";
// Insert Activity
require_once "model/customer_activity.php";
$customerActivity = new CustomerActivity();
$customerActivity->insertactivity($userid, 'Return History');
$session->check_the_login2();
$model = new Returns();
if (isset($_SESSION['user_login'])) {
	$data = $model->return_historyC($_SESSION['user_login']);
}
?>
<script type="text/javascript">
	var islog = '<?php echo $is_log; ?>';
	if (islog == "0") {
		location.replace("home.php");
	}
</script>
<div class="container bg-white p-sm-3" style="margin-top: 135px">
	<div class="row mb-3">
		<div class="col-sm-12">
			<span style="font-size: 26px">Return History</span>
		</div>
	</div>
	<div class="row mb-3">
		<div class="col-sm-12">
			<div class="table-responsive">
				<table id="table" class="table table-striped table-bordered table-hover">
					<thead>
						<th>Return ID</th>
						<th>Status</th>
						<th>Date Added</th>
						<th>Order ID</th>
						<th>Customer</th>
						<th>Action</th>
					</thead>
					<tbody>
						<?php
						if (count($data) > 0) {
							foreach ($data as $o) :
						?>
								<tr>
									<td><?php echo '#' . $o['return_id']; ?></td>
									<td><?php echo $o['return_status']; ?></td>
									<td><?php echo $o['date_added']; ?></td>
									<td><?php echo $o['order_id']; ?></td>
									<td><?php echo $o['customer']; ?></td>
									<td>
										<a class="btn btn-sm btn-info" href="return_details.php?retid=<?php echo  $o['return_id']; ?>">
											<i class="far fa-eye"></i>
										</a>

									</td>
								</tr>
							<?php
							endforeach;
						} else { ?>

						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<br>
	<div class="form-group pull-right">
		<a href="./index.php" title="Continue" class="btn btn-primary">Continue</a>
	</div>
</div>
<?php
include "common/footer.php";
?>

<script>
	$(document).ready(function() {
		$('#table').DataTable({
			order: [],
			oLanguage: {
				sSearch: "Quick Search:"
			},
			lengthMenu: [
				[15, 50, 100, 500, 1000, 2000],
				[15, 50, 100, 500, 1000, 2000]
			],
			dom: 'Blftrip',
			buttons: [{
					extend: 'excel',
					title: 'Return History',
				},
				{
					extend: 'pdf',
					title: 'Return History',
				},
				{
					extend: 'print',
					title: 'Return History',
				}
			]
		});
	});
</script>