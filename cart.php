<?php
include "common/headertest.php";
require_once "model/cart_new.php";
// Insert Activity
require_once "model/customer_activity.php";
$customerActivity = new CustomerActivity();
$customerActivity->insertactivity($userid, 'Cart Page');
$session->check_the_login2();
$id = $_SESSION['user_login'];
$digitalwalletr_val = isset($_SESSION['digitalwallet']) ? $_SESSION['digitalwallet'] : 0;
$digitalwallet_cash_val = isset($_SESSION['digitalwallet_cash']) ? $_SESSION['digitalwallet_cash'] : 0;
$cart_model = new Cart_new();
if (isset($_GET['delid'])) {
	$cart2 = new Cart_new();
	$stats = $cart2->dltid($_GET['delid']);
}
$totalitem = 0;
$inventorycount = 0;
$total_discount_Wallet = $cart_model->total_discount_Wallet($id);
$total_cash_Wallet = $cart_model->total_cash_Wallet($id);
foreach ($cart_model->cart_branchid($id) as $cart1) {
	if ($cart1['quantity'] > $cart1['r_quantity']) {
		$inventorycount++;
	}
	$totalitem++;
}
?>
<script type="text/javascript">
	var islog = '<?php echo $is_log; ?>';
	if (islog == "0") {
		location.replace("home.php");
	}
</script>
	<div class="container bg-white p-sm-3" style="margin-top: 135px">
		<div class="row">
			<div class="col-sm-12">
				<span style="font-size: 26px">Cart Page</span>
			</div>
		</div>
		</br></br></br></br>
		<?php if (isset($_SESSION['error_chkcart_id'])) { ?>
			<div class="alert alert-danger">
				<strong><?php echo $_SESSION['error_chkcart_id']; ?></strong>
			</div>
		<?php unset($_SESSION['error_chkcart_id']);
		} ?>
		<?php if (isset($stats)) { ?>
			<?php if ($stats == "200") { ?>
				<div class="alert alert-success">
					<strong>Successfully Deleted.</strong>
				</div>
			<?php } else { ?>
				<div class="alert alert-danger">
					<strong><?php echo $stats; ?></strong>
				</div>
			<?php } ?>
		<?php }	?>

		<div class="alert alert-danger" id="qty_danger_validation" style="display: none;">Products marked with *** are not available in the desired quantity or not in stock! </div>
		<div class="row">
			<div class="col-sm-12">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<th colspan="2">Image</th>
							<th>Name</th>
							<th>Store</th>
							<th>Quantity</th>
							<th>Original Price</th>
							<th>Option</th>
							<th>Promo/Discount</th>
							<th>Freebies</th>
							<th>Price w/ Discount</th>
							<th>Total Price</th>
							<th>Action</th>
						</thead>
						<tbody>
							<?php
							unset($stats);
							$total = 0;
							if (count($cart_model->cart_branchid($id)) > 0) {
								foreach ($cart_model->cart_branchid($id) as $cart) : ?>

									<tr>
										<td class="text-left" onclick="CheckCartPrd(<?php echo $cart['r_quantity']; ?>,<?php echo $cart['quantity']; ?>,<?php echo $cart['cart_id']; ?>)">
											<div id="cartProduct-div_<?php echo $cart['cart_id']; ?>" onclick="CheckCartPrd(<?php echo $cart['r_quantity']; ?>,<?php echo $cart['quantity']; ?>,<?php echo $cart['cart_id']; ?>)">
												<input type="checkbox" name="chkcart_id" value="<?php echo $cart['cart_id']; ?>" />
											</div>
										</td>
										<td class="text-center" onclick="CheckCartPrd(<?php echo $cart['r_quantity']; ?>,<?php echo $cart['quantity']; ?>,<?php echo $cart['cart_id']; ?>)">
											<?php if ($cart['p_type'] == 0) { ?>
												<img src="<?php echo "img/" . $cart['img']; ?>" class="img-responsive" style="width: 100px; width: 100px;" />
											<?php } else { ?>
												<img src="<?php echo $cart['img']; ?>" class="img-responsive" style="width: 100px; width: 100px;" />
											<?php } ?>
										</td>
										<td style="color: blue" onclick="CheckCartPrd(<?php echo $cart['r_quantity']; ?>,<?php echo $cart['quantity']; ?>,<?php echo $cart['cart_id']; ?>)">
											<?php
											if ($cart['quantity'] <= $cart['r_quantity']) {
												echo  utf8_encode($cart['name']);
											} else {
												echo $cart['name'] . "<span style=\"color:red\"> ***</span>";
											}
											?>
										</td>
										<td><?php if ($cart['p_type'] == "0") {
													echo $cart['store'];
												} ?></td>
										<td>
											<input type="number" class="qty" style="width: 50px" name="" value="<?php echo $cart['quantity'] ?>" onchange="editqty(this.value,<?php echo  $cart['cart_id']; ?>,<?php echo  $cart['r_quantity']; ?>)">
										</td>
										<td><?php echo number_format($cart['price'], 2); ?></td>

										<td>
											<?php
											if ($cart['p_type'] == "2") {
												$poa_name = array();
												$poa_list_val = $cart_model->get_poa_list_bg($cart['cart_id']);
												if (count($poa_list_val)) {
													foreach ($poa_list_val as $cart_poa) {
														array_push($poa_name, $cart_poa['option_name'] . ':' . $cart_poa['poa_name']);
													}
												}
												echo implode(",", $poa_name);
											} else {
											}
											?>
										</td>
										<td><?php if ($cart['p_type'] == "0") {
													echo $cart['discount_details'];
												} ?></td>
										<td><?php if ($cart['p_type'] == "0") {
													echo $cart['freebies'];
												} ?></td>
										<td><?php echo number_format($cart['newprice'], 2); ?></td>
										<td><?php echo number_format($cart['total_price'], 2); ?></td>
										<td>
											<a class="btn btn-danger" href="cart.php?delid=<?php echo  $cart['cart_id']; ?>" onclick="return confirm('Are you sure you want to delete <?php echo $cart['name']; ?>?');">Delete
											</a>
										</td>
									</tr>
								<?php $total += $cart['total_price'];
								endforeach;
							} else { ?>
								<tr>
									<td colspan="9" align="center">No items found !</td>
								</tr>
							<?php }  ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		

		<div class="row">
			<div class="col-sm-12">
				<button class="btn btn-primary float-end"  name="checkout_cart" <?php if ($inventorycount != 0 || $totalitem == 0) {echo "disabled";} ?> id="btnCheckout">Checkout</button>
			</div>
		</div>
	</div>
<?php
include "common/footer.php";
?>
<script>
	$('#btnCheckout').on('click', function() {
		$('#btnCheckout').prop('disabled', true);
		var chk_id_add = [];
		 $.each($("input[name='chkcart_id']:checked"), function(){
                chk_id_add.push($(this).val());
        });		
		var res= chk_id_add.join(',');
		if(chk_id_add.length==0){
        	 bootbox.alert("Please Select Product To check Out!");
        	 $('#btnCheckout').prop('disabled', false);
        }else{
        	location.replace("checkout.php?checkout_cart=" + res + "&t=" + new Date().getTime());
        }
	});
	function CheckCartPrd(rqty, qty, cid) {
		$("#cartProduct-div_" + cid).find('input[type=checkbox]').each(function() {
			if (this.checked == true) {
				this.checked = false;
				$('#btnCheckout').prop('disabled', false);
				$("#qty_danger_validation").css("display", "none")
			} else {
				this.checked = true;
				if (qty <= rqty) {
					$('#btnCheckout').prop('disabled', false);
					$("#qty_danger_validation").css("display", "none")
				} else {
					$('#btnCheckout').prop('disabled', true);
					qty_danger_validation
					$("#qty_danger_validation").css("display", "block")
				}
			}
		});
	}
	$('#button-wallet_CASH').on('click', function() {
		$.ajax({
			url: 'ajx_wallet.php?action=CW',
			type: 'post',
			data: 'CASHwallet=' + encodeURIComponent($('input[name=\'CASHwallet\']').val()),
			dataType: 'json',
			success: function(json) {
				if (json['success']) {
					bootbox.alert(json['success'], function() {
						location.replace("cart.php");
					});
				}
			}
		});
	});
	$('#button-wallet').on('click', function() {
		var total = $("#ptotal").text();
		var wallet = encodeURIComponent($('input[name=\'digitalwallet\']').val());
		var total2 = parseFloat(total.replace(/,/g, ''))
		var totalval = total2 / 2;
		var validate = totalval - wallet;
		if (validate < 0) {
			bootbox.alert("Warning: Digital Wallet must not exceed 50% of the total purchase amount!");
			return false;
		}
		$.ajax({
			url: 'ajx_wallet.php?action=DW',
			type: 'post',
			data: 'digitalwallet=' + encodeURIComponent($('input[name=\'digitalwallet\']').val()),
			dataType: 'json',
			success: function(json) {
				if (json['success']) {
					bootbox.alert(json['success'], function() {
						location.replace("cart.php");
					});
				}
			}
		});
	});
	$('#button-wallet_cancel').on('click', function() {
		$.ajax({
			url: 'ajx_wallet.php?action=DWcancel',
			type: 'post',
			data: 'digitalwallet=' + encodeURIComponent($('input[name=\'digitalwallet\']').val()),
			dataType: 'json',
			success: function(json) {
				if (json['success']) {
					bootbox.alert(json['success'], function() {
						location.replace("cart.php");
					});
				}
			}
		});
	});
	$('#button-wallet_CASH-cancel').on('click', function() {
		$.ajax({
			url: 'ajx_wallet.php?action=CWcancel',
			type: 'post',
			data: 'CASHwallet=' + encodeURIComponent($('input[name=\'CASHwallet\']').val()),
			dataType: 'json',
			success: function(json) {
				if (json['success']) {
					bootbox.alert(json['success'], function() {
						location.replace("cart.php");
					});
				}
			}
		});
	});

	function editqty(qty, cart_id, rqty) {
		if (qty > rqty) {
			bootbox.alert("No available stock !", function() {
				location.replace("cart.php");
			});
		} else {
			$.ajax({
				url: 'ajax_editcart.php',
				type: 'POST',
				data: 'cart_id=' + cart_id + '&qty=' + qty,
				dataType: 'json',
				success: function(json) {
					if (json['success']) {
						bootbox.alert(json['success'], function() {
							location.replace("cart.php");
						});
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}
</script>