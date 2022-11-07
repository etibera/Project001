<?php
include "common/headertest.php";
include "model/generate.php";
// Insert Activity
require_once "model/customer_activity.php";
$customerActivity = new CustomerActivity();
$customerActivity->insertactivity($userid, 'Click Share Button to Invite a Friend');
$model_generate = new generate();
$id = isset($_SESSION['user_login']) ? $_SESSION['user_login'] : 0;
?>
<div class="container bg-white p-sm-3" style="margin-top: 135px">
	<div class="row mb-3">
		<div class="col-12">
			<span style="font-size: 26px">Click Share Button to Invite a Friend</span>
		</div>
	</div>
	<div class="row mb-3">
		<div class="col-12">
			<div class="table-responsive">
				<table id="table" class="table table-striped table-bordered table-hover">
					<thead>
						<th>Link</th>
						<th>Share</th>
					</thead>
					<tbody>
						<?php foreach ($model_generate->getLinks($id) as $links) :  ?>
							<tr>
								<td><?php echo "https://www.facebook.com/sharer.php?u=https://pesoapp.ph/register.php?%26cust_id=" . $links['link_id']; ?></td>
								<td><a href="https://www.facebook.com/sharer.php?u=https://pesoapp.ph/register.php?%26cust_id=<?php echo  $links['customer_id']; ?>" data-toggle="tooltip" title="Share to facebook" target="_blank"><img src='./assets/FBlogo.png' style="width:70px;height: 45px;margin:0px;padding:0px; " /></a></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php include "common/footer.php"; ?>
<script>
	$(document).ready(() => {
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
					title: 'Invite a Friend',
				},
				{
					extend: 'pdf',
					title: 'Invite a Friend',
				},
				{
					extend: 'print',
					title: 'Invite a Friend',
				}
			]
		});
	})
</script>