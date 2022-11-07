<?php
include "common/headertest.php";
include "model/wishlist.php";
// Insert Activity
require_once "model/customer_activity.php";
$customerActivity = new CustomerActivity();
$customerActivity->insertactivity($userid, 'Wishlist');
$id = $_SESSION['user_login'];
$wishlist = new Wishlist();

if (isset($_GET['remid']) && isset($_GET['pid'])) {

	$stats = $wishlist->remid($_GET['remid'], $_GET['pid']);
}
?>



<div class="container bg-white p-sm-3" style="margin-top: 135px">
	<div class="row mb-3">
		<div class="col-sm-12">
			<span style="font-size: 26px">Wishlist</span>
			<br>
			<?php if (isset($stats)) { ?>
				<?php if ($stats == "200") { ?>
					<div class="alert alert-success">
						<strong>Successfully Removed.</strong>
					</div>
				<?php } else { ?>
					<div class="alert alert-danger">
						<strong><?php echo $stats; ?></strong>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
	<div class="row mb-3">
		<div class="col-sm-12">
			<div class="table-responsive">
				<table class="table" id="inviteestable" class="table table-striped table-bordered table-hover">
					<thead>
						<th colspan="2" style="text-align: center;">Product Name</th>
						<th>Price</th>
						<th>Stocks</th>
						<th>Action</th>
					</thead>
					<tbody>
						<?php

						foreach ($wishlist->getwishlist($id) as $wishlist) : ?>
							<tr>
								<td>
									<?php $image = "img/" . $wishlist['image']; ?>
									<img src="<?php echo $image; ?>" width="50" style="margin:auto" />
								</td>
								<td class="text-left"><?php echo $wishlist['model']; ?></td>
								<td class="text-left"><?php echo number_format($wishlist['price'], 2); ?></td>
								<td class="text-left"><?php echo $wishlist['quantity']; ?></td>

								<td>
									<a href="product.php?product_id=<?php echo $wishlist['product_id']; ?>" class="btn btn-primary">Go to Product</a>
									<a class="btn btn-danger" href="wishlist.php?remid=<?php echo  $id; ?>&pid=<?php echo $wishlist['product_id']; ?>" onclick="return confirm('Are you sure you want to remove <?php echo $wishlist['model']; ?>?');">Remove
									</a>
								</td>
							</tr>

						<?php endforeach; ?>
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
	var islog = '<?php echo $is_log; ?>';
	if (islog == "0") {
		location.replace("home.php");
	}
	$(document).ready(function() {







	});
</script>