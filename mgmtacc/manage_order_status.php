<?php
include 'template/header.php';
require_once "model/manage_order_status.php";
if (!$session->is_signed_in()) {
  redirect("index");
}
$model = new OrderStatus();
?>
<div id="content">
  <div class="page-header">
    <h2 class="text-center">Order Status Maintenance</h2>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading" style="padding:20px;">
        <div class="row">
          <div class="col-lg-6">
            <p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i> Order Status List
            </p>
          </div>
          <div class="col-lg-6">
            <div class="pull-right">
              <button class="btn btn-primary pull-right" id="add-brand" class="btn btn-primary"><i class="fa fa-plus"></i></button>
            </div>
          </div>
        </div>
      </div>
      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="deliverytb">
            <tbody>
              <thead>
                <tr>
                  <th>Description</th>
                  <th>Type</th>
                  <th>Status Type</th>
                  <th>Action</th>
                </tr>
              </thead>
              <?php foreach ($model->GetOrderStatus() as $os) : ?>
                <tr>
                  <td class="text-left"><?php echo $os['name']; ?></td>
                  <td class="text-left"><?php echo $os['typename']; ?></td>
                  <td class="text-left"><?php echo $os['status_type_name']; ?></td>
                  <td>
                    <button class="btn btn-primary" data-id="<?php echo $os['order_status_id']; ?>" data-type="<?php echo $os['type']; ?>" data-name="<?php echo $os['name']; ?>" data-status="<?php echo $os['status_type'] ?>" id="btnedit">
                      <i class="fa fa-edit"></i>
                    </button>
                    <!--  <button class="btn btn-danger" data-id="<?php echo $os['order_status_id']; ?>" id="btndelete">
                      <i class="fa fa-ban"></i> -->
                    </button>
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
<!-- Large modal -->
<div class="modal fade bd-example-modal-lg" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
        <p style="font-size: 23px" class="modal-title" id="modallabel"><strong></strong></p><input type="hidden" id="modid">
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover" id="particular-table">
            <thead>
              <tr>
                <th>Order Status Name</th>
                <th>Type</th>
                <th>Status Type</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <input class="form-control" type="text" placeholder="Name" id="name">
                </td>
                <td>
                  <select name="type" id="type" class="form-control" required> </select>
                </td>
                <td>
                  <select name="type" id="statusType" class="form-control">
                    <option value="1">New Order</option>
                    <option value="2">Order Verification</option>
                    <option value="3">Shipment Out</option>
                    <option value="4">Order Received</option>
                  </select>
                </td>
              </tr>
            </tbody>
          </table>
          </table>
        </div>
        <div class="form-group navbar-right" style="margin-right: 10px;">
          <button id="save_delivery" type="button" class="btn btn-primary btn-category-SAVE"><i class="fa fa-save"></i> Save</button>
        </div>
        <br><br>
      </div>
    </div>
  </div>
</div>
<?php include 'template/footer.php'; ?>
<script>
  $(document).ready(function() {
    $("#deliverytb").on("click", "#btnedit", function() {
      var id = $(this).data('id');
      var name = $(this).data('name');
      var type = $(this).data('type');
      var status_type = $(this).data('status');
      $('#statusType').val(status_type);
      $("#modallabel").html("Update Order Status");
      $("#save_delivery").html('<i class="fa fa-save"></i> Update');
      $("#name").val(name);
      $("#modid").val(id);
      $('#type').empty();
      if (type == 0) {
        $('#type').append($('<option>').val(0).text("Admin"));
        $('#type').append($('<option>').val(1).text("Seller"));
      } else {
        $('#type').append($('<option>').val(1).text("Seller"));
        $('#type').append($('<option>').val(0).text("Admin"));
      }
      $('#AddModal').modal('show');
    });
    $("#deliverytb").on("click", "#btndelete", function() {
      var id = $(this).data('id');
      bootbox.confirm({
        message: "Are you sure you want to delete this?",
        buttons: {
          confirm: {
            label: 'Yes',
            className: 'btn-success'
          },
          cancel: {
            label: 'No',
            className: 'btn-danger'
          }
        },
        callback: function(result) {
          if (result == true) {
            $.ajax({
              url: 'ajax_manageOrder.php?action=DeleteOrderStatus',
              type: 'post',
              data: 'id=' + id,
              dataType: 'json',
              success: function(json) {
                bootbox.alert(json['success'], function() {
                  location.reload();
                });
              }
            });
          }
        }
      });
    });
    $('#add-brand').click(function() {
      $("#modallabel").html("Add New Order Status");
      $("#save_delivery").html('<i class="fa fa-save"></i> Save');
      $("#name").val('');
      $("#modid").val('_null');
      $('#type').empty();
      $('#type').append($('<option>').val("_null").text("Select  Type"));
      $('#type').append($('<option>').val(0).text("Admin"));
      $('#type').append($('<option>').val(1).text("Seller"));
      $('#statusType').val(1);
      $('#AddModal').modal('show');
    });
    $('#save_delivery').click(function() {
      var name = $("#name").val();
      var id = $("#modid").val();
      var type = $("#type").val();
      var status_type = $("#statusType").val();

      if (name == "") {
        bootbox.alert("Order Status Name must not be empty!!");
        return false;
      }
      if (type == "_null") {
        bootbox.alert("Select Type First");
        return false;
      }
      if (id == "_null") {
        $.ajax({
          url: 'ajax_manageOrder.php?action=AddOrderStaus',
          type: 'post',
          data: 'name=' + name + '&type=' + type + '&status_type=' + status_type,
          dataType: 'json',
          success: function(json) {
            bootbox.alert(json['success'], function() {
              location.reload();
            });
          }
        });
      } else {
        $.ajax({
          url: 'ajax_manageOrder.php?action=UpdateOrderStaus',
          type: 'post',
          data: 'name=' + name + '&type=' + type + '&id=' + id + '&status_type=' + status_type,
          dataType: 'json',
          success: function(json) {
            bootbox.alert(json['success'], function() {
              location.reload();
            });
          }
        });
      }
    });
  });
</script>