<?php
include "common/headertest.php";
// Insert Activity
require_once "model/customer_activity.php";
$customerActivity = new CustomerActivity();
$customerActivity->insertactivity($userid, 'PESO Partner Program');
?>
<script type="text/javascript">
	var islog = '<?php echo $is_log; ?>';
	if (islog == "0") {
		location.replace("home.php");
	}
</script>
<style>
	ol {
		counter-reset: item
	}

	.terms li {
		display: block;
		margin: 1rem 0;
		font-family: Arial, sans-serif;
	}

	.terms li:before {
		content: counters(item, ".") " ";
		counter-increment: item;
		font-weight: bold;
	}
</style>
<div class="container" style="margin-top: 135px">
	<div class="row">
		<div class="col-sm-12">
			<img src='./assets/aff_logo.JPG' class="img-responsive img-thumbnail" style="border:none;" />
		</div>
	</div>
	<div class="container-fluid bg-white p-sm-3">
		<h2 class="text-center">
			Terms of Service
		</h2>
		<ol class="terms">
			<li><strong>Introduction</strong></li>
			<ol>
				<li>Welcome to the Partner Program of Pinoy Electronic Store Online (PESO). Please read the following Terms of Service carefully and be aware of your rights and obligations with respect to Partner Program of Pinoy Electronic Store Online. These Terms and Conditions govern your use of the Partner Program provided by Pinoy Electronic Store Online.</li>
				<li>
					The Partner Program allows a Pinoy Electronic Store Online (PESO) user to sign-up and earn rewards by referring PESO products to friends. Referral must use referral facility within the Pinoy Electronic Store Online (PESO) site/app for proper tracking. A PESO Partner Program member must be able to send a product referral link to his friends from the PESO site/app to facebook messenger, viber from a smart phone. In order to earn, referral must result into a successful sale via PESO site/app. PESO Partner Program participants are not allowed to recommend product to him/her self. Any referral made outside the site/app cannot be tracked or verified and therefore cannot be rewarded.
				</li>
				<li>
					PESO Partner Program rewards are based on a percentage of Gross Profit (SRP (actual selling price) less (shipping + product cost + other cost)) and Partner Program level. Rewards are credited to a separate PESO cash wallet and can be converted to cash. Amount in the cash wallet can also be transferred to your PESO discount wallet should you intend to use the amount as discount on your PESO purchase subject to PESO discount regulations.
				</li>
				<li>
					There are three (3) levels in the PESO Partner Program with increasing rewards percentages for each higher level. You will be able to see your level by going to Accounts/PESO Partner Program. Novice (1-3 successful sales in 30 days) = reward Regular (4-10 successful sales in 30 days) 1.5 x reward Pro (10 or more successful sales in 30 days) 2 x reward
				</li>
				<li>
					PESO Partners need to maintain activity to retain their level. Inactivity (no successful sales in 30 days) will result in level downgrade to Novice. The inactivity period will be computed from the last successful sale resulting from your referral.
				</li>
				<li>
					By signing up to become a PESO Partner, you agree not to commit fraud, false and/or misleading claims, misrepresentation.
				</li>
				<li>
					Pinoy Electronic Store Online (PESO) reserves the right to change, modify, suspend or discontinue all or any part of the Partner Program at any time or upon notice as required by local law. Pinoy Electronic Store Online may also impose limits on certain features or restrict your access to parts of, or the entire, Site or Services in its sole discretion and without notice or liability.
				</li>
				<li>
					Pinoy Electronic Store Online reserves the right to refuse to provide you access to the Partner Program for any reason.
				</li>
			</ol>
		</ol>
		<div>
			<div>
				I have read and agree to the Terms and Conditions <input class="form-check-input" type="checkbox" id="agree" value="1" />
			</div>
			<div class="float-start mt-3"><a href="home.php" class="btn btn-secondary">Back</a></div>
			<div class="float-end"><input type="button" id="register" value="Register" class="btn btn-primary" /> </div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>


<?php include "common/footer.php"; ?>
<script>
	$('#register').on('click', function() {
		var checkBox = document.getElementById("agree");
		if (checkBox.checked == true) {
			$.ajax({
				url: 'ajx_wallet.php?action=register_aff',
				type: 'post',
				dataType: 'json',
				success: function(json) {
					if (json['success']) {
						bootbox.alert(json['success'], function() {
							location.replace("home.php");
						});
					}
				}
			});
		} else {
			bootbox.alert('You must agree to the Terms & Conditions!');
		}

	});
</script>