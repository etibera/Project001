<?php
include_once "template/header.php";

$perm = $_SESSION['permission'];
if (!strpos($perm, "'25';") !== false) {
  header("Location: landing.php");
}
?>
<div class="container-fluid">
  <h2>Pending Payables</h2>
  <div class="well">
    <div class="row">
      <div class="col-sm-2">
        <div class="form-group">
          <label class="control-label" for="dateFrom">Date From:</label>
          <input type="date" id="dateFrom" class="form-control">
        </div>
      </div>
      <div class="col-sm-2">
        <div class="form-group">
          <label class="control-label" for="dateTo">Date To:</label>
          <input type="date" id="dateTo" class="form-control">
        </div>
      </div>
      <div class="col-sm-2">
        <div class="form-group">
          <label class="control-label" for="status">Status:</label>
          <select name="payable_status" id="status" class="form-control">
            <option value="all">All</option>
            <option value="pending">Pending</option>
            <option value="paid">Paid</option>
          </select>
        </div>
      </div>
      <div class="col-sm-2">
        <div class="form-group">
          <button id="search" class="btn btn-primary" style="width: 40px; height: 40px; margin-top: 10px"><i class="fa fa-search" aria-hidden="true"></i></button>
          <button id="clear" class="btn btn-danger" style="width: 40px; height: 40px; margin-top: 10px"><i class="fa fa-times" aria-hidden="true"></i></button>
        </div>
      </div>
    </div>
  </div>
  <table class="table table-bordered table-hover" id="payableTable">
    <thead>
      <th>Order ID</th>
      <th>Store Name</th>
      <th>Bank Name</th>
      <th>Bank Account Name</th>
      <th>Bank Account No.</th>
      <th>Amount</th>
      <th>Reference Number</th>
      <th>Status</th>
      <th>Date Added</th>
      <th>Action</th>
    </thead>
  </table>
</div>
<div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="payModal" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-error-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <label>
          <h2>Pay / Transfer</h2>
        </label>
        <button type="button" class="close" id="closemod" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label id="divOrderId"></label>
        </div>
        <div class="form-group">
          <label id="divBankName"></label>
        </div>
        <div class="form-group">
          <label id="divBankAccName"></label>
        </div>
        <div class="form-group">
          <label id="divBankAccNo"></label>
        </div>
        <div class="form-group">
          <label id="divAmount"></label>
        </div>
        <div class="form-group">
          <label>Reference Number:</label>
          <input type="text" name="reference_no" class="form-control" id="referenceNo" placeholder="Reference Number">
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <input type="button" value="Pay / Transfer" class="btn btn-primary" id="pay" />
              <a type="button" class="btn btn-default" data-dismiss="modal">Cancel</a>
            </div>
          </div>
        </div>
        <input type="hidden" name="payableId" class="form-control" id="payableId">
        <input type="hidden" name="inputOrderId" class="form-control" id="inputOrderId">
        <input type="hidden" name="inputBankName" class="form-control" id="inputBankName">
        <input type="hidden" name="inputBankAccNo" class="form-control" id="inputBankAccNo">
        <input type="hidden" name="inputBankAccName" class="form-control" id="inputBankAccName">
        <input type="hidden" name="inputsellerId" class="form-control" id="inputsellerId">
        <input type="hidden" name="inputamount" class="form-control" id="inputamount">
      </div>
    </div>
  </div>
</div>


<?php
include_once "template/footer.php";
?>

<script>
  let datatable;
  $(document).ready(() => {
    loadData();
  });

  $('#search').click(() => {
    datatable.destroy();
    loadData();
  });

  $('#clear').click(() => {
    const status = $('#status').val('all');
    const from = $('#dateFrom').val('');
    const to = $('#dateTo').val('');

    datatable.destroy();
    loadData();
  });

  $(document).on('click', '#paywallet', function() {
    $('#payModal').modal('show');

    var seller_id = $(this).data('seller_id');
    var order_id = $(this).data('order_id');
    var bank_account_no = $(this).data('bank_account_no');
    var bank_account_name = $(this).data('bank_account_name');
    var bank_name = $(this).data('bank_name');
    var payableId = $(this).data('id');
    var amount = $(this).data('amount');

    $("#divBankName").html("Bank Name: " + bank_name);
    $("#divBankAccNo").html("Bank Account Number: " + bank_account_no);
    $("#divBankAccName").html("Bank Account Name: " + bank_account_name);
    $("#divOrderId").html("Order Id: " + order_id);
    $("#divAmount").html("Amount: " + amount);

    $("#payableId").val(payableId);
    $("#inputOrderId").val(order_id);
    $("#inputBankName").val(bank_name);
    $("#inputBankAccNo").val(bank_account_no);
    $("#inputBankAccName").val(bank_account_name);
    $("#inputsellerId").val(seller_id);
    $("#inputamount").val(amount);

  });

  $('#pay').click(() => {

    showLoading();

    var seller_id = $("#inputsellerId").val();;
    var order_id = $("#inputOrderId").val();;
    var bank_account_no = $("#inputBankAccNo").val();
    var bank_account_name = $("#inputBankAccName").val();
    var bank_name = $("#inputBankName").val();
    var payableId = $("#payableId").val();
    var amount = $("#inputamount").val();
    var reference_no = $('#referenceNo').val();

    $.ajax({
      url: `ajax_pending_payables.php?action=pay&t=${new Date().getTime()}`,
      method: 'POST',
      dataType: 'json',
      data: {
        seller_id,
        order_id,
        bank_account_no,
        bank_account_name,
        bank_name,
        payableId,
        amount,
        reference_no
      },
      success: response => {
        if (response.type == 'success') {
          $('#payModal').modal('hide');
          $('#referenceNo').val('');

          datatable.ajax.reload();
        }
        hideLoading();
        bootbox.alert(response.message);
      }
    });

  });

  const loadData = () => {
    showLoading();
    const status = $('#status').val();
    const from = $('#dateFrom').val();
    const to = $('#dateTo').val();

    datatable = $('#payableTable').DataTable({
      serverSide: true,
      order: [],
      bSort: true,
      dom: 'Blfrtip',
      oLanguage: {
        sSearch: 'Quick Search:'
      },
      buttons: [{
          extend: 'excel',
          title: 'Pending Payables',  
          className: 'btn btn-success',text: '<i class="fas fa-file-excel"></i> Excel',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
          }
        },
        {
          extend: 'pdf',
          title: 'Pending Payables',
          className: 'btn btn-danger', text: '<i class="fas fa-file-pdf"></i> PDF',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
          }
        },
        {
          extend: 'print',
          title: 'Pending Payables',
          className: 'btn btn-primary', text: '<i class="fas fa-print"></i> Print',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
          }
        },
      ],
      lengthMenu: [
        [15, 50, 100, 500, 1000, 2000],
        [15, 50, 100, 500, 1000, 2000]
      ],
      columnDefs: [{
        targets: 9,
        orderable: false
      }],
      ajax: {
        url: `ajax_pending_payables.php?action=get&t=${new Date().getTime()}`,
        method: 'POST',
        data: {
          status,
          from,
          to
        }
      },
      initComplete: () => {        
        hideLoading();       
        $('.dt-button').removeClass('dt-button');
      }
    });
  }
</script>