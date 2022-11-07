<?php
include "common/headertest.php";
include "model/manage_homepage.php";
// Insert Activity
require_once "model/customer_activity.php";
$customerActivity = new CustomerActivity();
$customerActivity->insertactivity($userid, 'Manage Home Page');
$session->check_the_login2();
$model = new manage_homepage();
if (isset($_SESSION['user_login'])) {
	$data = $model->mhp_list($_SESSION['user_login']);
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
		<div class="form-group">
			<div class="col-sm-12">
				<span style="font-size: 26px" class="float-start">Manage Home Page</span>
				<div class="float-end">
					<a href="./home.php" class="btn btn-danger" title="Back"><i class="fas fa-arrow-left"></i></a>
					<button class="btn btn-primary btn-add" title="Add Category"><i class="fas fa-plus"></i></i></a>
				</div>
			</div>
		</div>
	</div>
	<br>
	<?php if (isset($_SESSION['message'])) : ?>
		<div class="alert alert-success"><?php echo $_SESSION['message']; ?></div>
	<?php endif; ?>
	<?php unset($_SESSION['message']); ?>
	<input type="hidden" id="customer_id" value="<?php echo $_SESSION['user_login']; ?>">
	<div class="row">
		<div class="col-sm-12">
			<div class="table-responsive">
				<table id="table" class="table table-striped table-bordered table-hover order-table">
					<thead>
						<tr>
							<th>Category Name</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (count($data) > 0) {
							foreach ($data as $o) :
						?>
								<tr>
									<td><?php echo $o['name']; ?></td>
									<td>
										<div class="pull-right">
											<button class="btn btn-danger btn-delete" data-id="<?php echo  $o['cat_id']; ?>"><i class="fas fa-trash-alt"></i>
											</button>
										</div>
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

<div class="modal fade" id="category-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-error-dialog">
		<div class="modal-content">
			<div class="modal-header bg-ahi">
				<span class="modal-title" style="font-size: 18px" id="myModalLabel">Please Select Home Page Categories</span>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover category-list">
								<thead>
									<tr>
										<th>Category List</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" id="category-add-more" title="Add More"><i class="fas fa-plus"></i></button>
				<button type="button" class="btn btn-primary" id="category-save" title="Save"><i class="fas fa-save"></i></button>

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
					title: 'Home Page',
				},
				{
					extend: 'pdf',
					title: 'Home Page',
				},
				{
					extend: 'print',
					title: 'Home Page',
				}
			]
		});
		$('.btn-add').on('click', function() {
			$('#category-modal').modal('show');
			$('.category-list tbody').empty();
			$('.category-list tbody').append(
				'<tr class="category-tr">' +
				'<td class="category_id">' +
				'<select class="form-control category_name"></select>' +
				'</td>' +
				'<td>' +
				'<button class="btn btn-danger btn-delete" title="Delete"><i class="fas fa-trash-alt"></i></button>' +
				'</td>' +
				'</tr>'
			);

			category_list();

		});

		$('#category-add-more').on('click', function() {
			$('.category-list tbody').append(
				'<tr class="category-tr">' +
				'<td class="category_id">' +
				'<select class="form-control category_name"></select>' +
				'</td>' +
				'<td>' +
				'<button class="btn btn-danger btn-delete" title="Delete"><i class="fas fa-trash-alt"></i></button>' +
				'</td>' +
				'</tr>'
			);

			category_list();

		});

		$('.category-list').on('click', '.btn-delete', function() {

			$(this).parent('td').parent('tr').remove();

		});


		$('.order-table').on('click', '.btn-delete', function() {
			var getid = $(this).data("id");

			bootbox.confirm("Remove this Category?", function(result) {
				if (result == true) {
					$.ajax({
						url: 'ajax_add_categories.php',
						type: 'POST',
						data: 'category_id=' + getid,
						dataType: 'json',
						success: function(json) {
							bootbox.alert('' + json.success, function() {
								location.replace('./manage_homepage.php');
							});
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}

			});

		});

		$('#category-save').on('click', function() {
			var blank = 0;
			var arrayItems = [];
			$('.category-list > tbody > tr.category-tr').each(function() {


				var customer_id = $('#customer_id').val();
				var category_id = $(this).find('td.category_id').find('select').val();
				if (category_id == '') {
					blank++;
				}

				arrayItems.push({
					category_id: category_id,
					customer_id: customer_id
				});
			});

			if (blank > 0) {
				bootbox.alert('Please Select Category.');
				return false;
			}

			$.ajax({
				url: 'ajax_add_categories.php',
				type: 'POST',
				data: {
					arrayItems: arrayItems
				},
				dataType: 'json',
				success: function(json) {
					bootbox.alert('' + json.success, function() {
						location.replace('manage_homepage.php');
					});
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});


		});

	});

	function category_list() {

		$.ajax({
			url: 'ajax_get_categories.php',
			type: 'GET',
			dataType: 'json',
			success: function(json) {
				$(".category_name").empty();
				$(".category_name").append('<option value="">--Select Category--</option>');

				for (var i = 0; i < json.length; i++) {
					$(".category_name").append('<option value="' + json[i].category_id + '">' + json[i].name + '</option>');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});

	}
</script>