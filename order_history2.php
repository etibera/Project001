<?php
include "common/headertest.php";
require_once "model/orderhistory.php";
// Insert Activity
require_once "model/customer_activity.php";
require_once "model/Review.php";
$customerActivity = new CustomerActivity();
$customerActivity->insertactivity($userid, 'Order History');
$session->check_the_login2();
$model = new OrderHistory();
$data1 = $model->OrderPrdDetails(1726);

$review = new Review();
if (isset($_SESSION['user_login']))	//check unauthorize user not access in "register.php" page
{

	if (isset($_GET['oid'])) {
		$order_id = $_GET['oid'];
		$get_store_orders = $model->get_store_orders($order_id);
		foreach ($get_store_orders as $so) {
			$orderData = $model->order_details_seller($so['order_id'], $so['seller_id']);
			$model->SendEmailSeller(31, $orderData, 'Customer Cancelled.');
		}
		/* $data1=$model->OrderPrdDetails($order_id);
		  $model->SendEmail(31,$data1,'');*/

		$model->order_cancel($order_id, $_SESSION['user_login']);
		$model->SendEmailallstoreCustomer(31, $_GET['oid'], "Customer Cancelled.");
		$model->SendEmailallstoreAdmin(31, $_GET['oid'], "Customer Cancelled.");
	}
	if (isset($_REQUEST['btn_search'])) {
		$status = $_REQUEST["status"];
		$data = $model->order_history($_SESSION['user_login'], $status);
	} else {
		$data = $model->order_history($_SESSION['user_login'], '0');
	}
	if (isset($_GET['email'])) {
		$data = $model->email_test($_GET['email']);
		unset($data);
	}
}
/*echo "<pre>";	
print_r($data1);*/
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
			<span style="font-size: 26px">Order History</span>
		</div>
	</div>
	<?php if (isset($_SESSION['message'])) : ?>
		<div class="alert alert-success"><?php echo $_SESSION['message']; ?></div>
	<?php endif; ?>
	<?php unset($_SESSION['message']); ?>
	<div class="row mb-3">
		<div class="col-sm-12">
			<form method="post" class="form-horizontal" action="order_history.php">
				<div class="">
					<div class="float-start">
						<select name="d_status" id="d_status" class="form-control">
							<option value="0">--Select Status--</option>
							<?php
							foreach ($model->order_status() as $status) {
								echo '<option value=' . $status['order_status_id'] . '>' . $status['name'] . '</option>';
							}
							?>
						</select>
					</div>
					<div class="float-end">
						<button type="submit" name="btn_search" class="btn btn-success"><i class="fas fa-search"></i></button>
					</div>
					<input type="hidden" name="status" id="status" value="<?php if (isset($_REQUEST['btn_search'])) {
																																	echo $_REQUEST["status"];
																																} else {
																																	echo '0';
																																} ?>" />
				</div>
			</form>
		</div>
	</div>
	<div class="row mb-3">
		<div class="col-sm-12">
			<div class="table-responsive">
				<table id="table" class="table table-striped table-bordered table-hover order-table">
					<thead>
						<tr>
							<th>Order ID</th>
							<th>Customer</th>
							<th>Status</th>
							<th>Date Added</th>
							<th>Total</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (count($data) > 0) {
							foreach ($data as $o) :
								$olddata = $model->count_OlddataOrder($o['order_id']);
								$countOrderNumber = $model->countOrderNumber($o['order_id']);
						?>
								<tr>
									<td><?php echo $o['order_id']; ?></td>
									<td><?php echo $o['customer']; ?></td>
									<td><?php echo $o['status']; ?></td>
									<td><?php echo date('F j, Y, g:i A', strtotime($o['date_added'])); ?></td>
									<td><?php echo number_format($o['total']); ?></td>
									<td>
										<?php if ($olddata != 0) { ?>
											<?php if ($countOrderNumber == 0) { ?>
												<a class="btn btn-sm btn-info position-relative" href="order_details_new.php?order_id=<?php echo  $o['order_id']; ?>">
													<i class="far fa-eye"></i>
													<span class="position-absolute badge top-0 start-100 translate-middle bg-danger rounded-pill"><?php echo intval($review->getReviewCount("orderId", $o['order_id'])) > 0 ? $review->getReviewCount("orderId", $o['order_id']) : "" ?></span>
												</a>
											<?php } else { ?>
												<a class="btn btn-sm btn-info  position-relative" href="StoreOrderDetails.php?order_id=<?php echo  $o['order_id']; ?>">
													<i class="far fa-eye"></i>
													<span class="position-absolute badge top-0 start-100 translate-middle bg-danger rounded-pill"><?php echo intval($review->getReviewCount("orderId", $o['order_id'])) > 0 ? $review->getReviewCount("orderId", $o['order_id']) : "" ?></span>
												</a>
											<?php } ?>
										<?php } else { ?>
											<a class="btn btn-sm btn-info  position-relative" href="order_details.php?order_id=<?php echo  $o['order_id']; ?>">
												<i class="far fa-eye"></i>
												<span class="position-absolute badge top-0 start-100 translate-middle bg-danger rounded-pill"><?php echo intval($review->getReviewCount("orderId", $o['order_id'])) > 0 ? $review->getReviewCount("orderId", $o['order_id']) : "" ?></span>
											</a>
										<?php } ?>

										<?php if ($o['order_status_id'] == "49") { ?>
											<a class="btn btn-sm btn-warning " href="invoice.php?orderid=<?php echo  $o['order_id']; ?>" target="_blank"> <i class="fas fa-print"></i>
											</a>
										<?php } ?>
										<?php $count_bg = $model->count_bgproduct($o['order_id']);
										$ContOrderNotCancel = $model->ContOrderNotCancel($o['order_id']);

										if ($count_bg == 0) { ?>
											<?php if ($ContOrderNotCancel == 0) { ?>
												<?php if ($model->count_serial($o['order_id']) == 0) { ?>
													<a class="btn btn-sm btn-danger btn-cancel" href="order_history.php?oid=<?php echo  $o['order_id']; ?>" onclick="return confirm('Are you sure you want to cancel order # <?php echo $o['order_id']; ?>?');"><i class="fas fa-minus-circle"></i>
													</a>
												<?php } ?>
											<?php } ?>
											<?php } else {
											require_once 'include/banggoodAPI.php';
											global $banggoodAPI;
											$bg_order_status = "";
											$params_goi = array('sale_record_id' => $o['order_id'], 'lang' => 'en');
											$banggoodAPI->setParams($params_goi);
											$result_goi = $banggoodAPI->getOrderInfo();
											$status_goi = $result_goi['code'];
											if ($status_goi == 0) {
												$bg_order_status = $result_goi['sale_record_id_list'][0]['order_list'][0]['status'];
											}
											if ($bg_order_status == "Payment Pending") {
											?>
												<a class="btn btn-sm btn-danger btn-cancel" href="order_history.php?oid=<?php echo  $o['order_id']; ?>" onclick="return confirm('Are you sure you want to cancel order # <?php echo $o['order_id']; ?>?');"><i class="fas fa-minus-circle"></i>
												</a>
										<?php
											}
										}
										?>

										<?php if ($o['order_status_id'] == "49") { ?>
											<a class="btn btn-sm btn-success btn-cancel" href="order_review.php?order_id=<?php echo  $o['order_id']; ?>"><i class="far fa-edit"></i>
											</a>
										<?php } ?>
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
					title: 'Order History',
				},
				{
					extend: 'pdf',
					title: 'Order History',
				},
				{
					extend: 'print',
					title: 'Order History',
				}
			]
		});

		if ($("#status") != '') {
			$("#d_status").val($("#status").val());
		}

		$("#d_status").on('change', function() {
			$('#status').val($(this).val());
		});

	});
</script>