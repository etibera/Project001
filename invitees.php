<?php
include "common/headertest.php";
include "model/invitees.php";
// Insert Activity
require_once "model/customer_activity.php";
$customerActivity = new CustomerActivity();
$customerActivity->insertactivity($userid, 'List of Invitees');
$id = $_SESSION['user_login'];
$invitees = new Invitees();
?>



<div class="container bg-white p-sm-3" style="margin-top: 135px">
	<div class="row mb-3">
		<div class="col-12">
			<span style="font-size: 26px">List of Invitees</span>
		</div>
	</div>
	<div class="row mb-3">
		<div class="col-12">
			<div class="table-responsive">
				<table id="table" id="inviteestable" class="table table-striped table-bordered table-hover">
					<thead>
						<th>Fullname</th>
						<th>Date</th>
						<th>Action</th>
					</thead>
					<tbody>
						<?php

						foreach ($invitees->listinvitees($id) as $invitees) : ?>
							<tr>
								<td class="text-left"><?php echo $invitees['firstname'] . " " . $invitees['lastname']; ?></td>
								<td class="text-left"><?php echo $invitees['date_added']; ?></td>
								<td></td>
							</tr>

						<?php endforeach; ?>
					</tbody>
				</table>

			</div>
		</div>
	</div>
</div>


<!-- Large modal -->


<div class="modal fade bd-example-modal-lg" id="MessageModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<a type="button" data-dismiss="modal" style="float: right;
                        font-size: 25px;
                        font-weight: 700;
                        line-height: 1;
                        color: #000;
                        text-shadow: 0 1px 0 #fff;
                      "><i class="fa fa-times-circle " style="color: black;font-size: 25px;"></i></a>
				<br>
				<p style="font-size: 23px" class="modal-title"><strong>Create Message</strong></p>


			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-12">
						<div class="form-group">
							<label class="text-body">Send To:</label>
							<input type="text" id="txtm_user" name="" class="full-text form-control " readonly />
							<input type="hidden" id="cid" name="">
						</div>
					</div>
					<div class="col-lg-12">
						<div class="form-group">
							<label class="text-body">Message</label>
							<textarea rows="3" id="txtmessage" class="full-text form-control ">
                            </textarea>
						</div>
					</div>
				</div>
				<div class="pull-right">
					<button class="btn btn-primary " id="btnsend" style="font-size: 15px;">Send</button>
					<button class="btn btn-default" id="btncancel1" style="font-size: 15px;">Cancel</button>
				</div>
				<br><br>
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
					title: 'List of Invitees',
				},
				{
					extend: 'pdf',
					title: 'List of Invitees',
				},
				{
					extend: 'print',
					title: 'List of Invitees',
				}
			]
		});

		$("#inviteestable").on("click", "#btnmessage", function() {
			var id = $(this).data('id');
			var fname = $(this).data('fname');


			$('#txtm_user').val(fname);
			$('#cid').val(id);

			$('#txtmessage').val('');
			$('#MessageModal').modal('show');

		});


		$("#btncancel1").click(function() {

			$('#MessageModal').modal('hide');

		});

		$("#btnsend").click(function() {
			var receiver = $('#txtm_user').val();
			var msg = $('#txtmessage').val();
			var cid = $('#cid').val();
			var userid = '<?php echo $id; ?>';

			sendmsg(userid, msg, cid);

		});




	});

	function sendmsg(userid, msg, cid) {
		if (msg == "" || cid == "" || userid == "") {

			bootbox.alert("Message must not be empty!!");
			return false;
		} else {
			$.ajax({
				url: 'ajax_sendmessage.php',
				type: 'POST',
				data: 'userid=' + userid + '&msg=' + msg + '&cid=' + cid,
				dataType: 'json',
				success: function(json) {

					if (json['success'] == "Successfully Send.") {
						bootbox.alert(json['success'], function() {
							location.replace("invitees.php");
						});


					} else {
						bootbox.alert(json['success']);
						return false;
					}

				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}

	}
</script>