<?php
include_once "template/header.php";

$perm = $_SESSION['permission'];
if (!strpos($perm, "'252527';") !== false) {
  header("Location: landing.php");
}
?>

<div class="container-fluid">
  <h2>Pending Receivables</h2>
  <div class="well">
    <div class="row">
      <div class="col-sm-2">
        <div class="form-group">
          <label class="control-label" for="dateFrom">Date From</label>
          <input type="date" id="dateFrom" class="form-control">
        </div>
      </div>
      <div class="col-sm-2">
        <div class="form-group">
          <label class="control-label" for="dateTo">Date To</label>
          <input type="date" id="dateTo" class="form-control">
        </div>
      </div>
      <div class="col-sm-2">
        <div class="form-group">
          <label class="control-label" for="fundStatus">Fund Status</label>
          <select name="payable_status" id="fundStatus" class="form-control">
             <option value="all">--Select Status--</option>
            <option value="all">All</option>
            <option value="Paid">Paid</option>
            <option value="Unpaid">UnPaid</option>
          </select>
        </div>
      </div>
      <div class="col-sm-2">
        <div class="form-group">
          <label class="control-label" for="orderStatus">Order Status</label>
          <select name="payable_status" id="orderStatus" class="form-control">
             <option value="17">--Select Status--</option>
            <option value='all'>All</option>
          </select>
        </div>
      </div>
      <div class="col-sm-2">
        <div class="form-group">
          <button id="search" class="btn btn-primary" style="width: 40px; height: 40px; margin-top: 10px"><i class="fa fa-search" aria-hidden="true"></i></button>
          <button id="clear" class="btn btn-danger" style="width: 40px; height: 40px; margin-top: 10px"><i class="fa fa-times" aria-hidden="true"></i></button>
        </div>
      </div>
      <div class="col-sm-2">
        <div class="form-group">
          <a class="btn btn-primary pull-right" id="confirmPayment" title="Confirm Payment" style="margin-top: .9em; margin-right: 1em; padding: 1em 1.5em"><i class="fas fa-check-circle"></i> Confirm Payment</a>
        </div>
      </div>
    </div>
  </div>
  <table class="table table-bordered table-hover" id="receivablesTable">
    <thead>
      <th>
        <center><input type="checkbox" id="checkAll"></center>
      </th>
      <th>Order ID</th>
      <th>Customer Name</th>
      <th>Payment Method</th>
      <th>Order Status</th>
      <th>Amount</th>
      <th>Bank Accounts</th>
      <th>OPS Verification</th>
      <th>Fund Status</th>
      <th>Date Added</th>
      <th>Date of Sales</th>
    </thead>
  </table>
</div>

<?php
include_once "template/footer.php";
?>

<script>
  let datatable;
  $(document).ready(() => {
    loadData();

  });

  let getOrderStatus = () => {
    $.ajax({
      url: `ajax_pending_receivables.php?action=getOrderStatus&t=${new Date().getTime()}`,
      dataType: 'json',
      success: response => {
        $('#orderStatus').append(response);
      }
    })
  }

  $("#checkAll").click(function() {
    $('input:checkbox').not(this).prop('checked', this.checked);
  });

  $('#search').click(() => {
    datatable.destroy();
    loadData();
  });

  $('#clear').click(() => {
    $('#dateFrom').val('');
    $('#dateTo').val('');
    $('#fundStatus').val('all');
    $('#orderStatus').val('17');

    datatable.destroy();
    loadData();
  });

  $('#confirmPayment').click(function() {
    showLoading();
    let orderId = [];
    $('input[name="orderId"]:checked').each(function() {
      orderId.push($(this).val());
    });

    $.ajax({
      url: `ajax_pending_receivables.php?action=pay&t=${new Date().getTime()}`,
      method: 'POST',
      dataType: 'json',
      data: {
        orderId
      },
      success: response => {
        if (response.type === "success") {
          datatable.ajax.reload();
        }
        hideLoading();
        bootbox.alert(response.message);
      }
    })
  });

  let loadData = () => {
    showLoading();
    const orderStatus = $('#orderStatus').val();
    const fundStatus = $('#fundStatus').val();
    const from = $('#dateFrom').val();
    const to = $('#dateTo').val();

    datatable = $('#receivablesTable').DataTable({
      serverSide: true,
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
          title: 'Pending Receivables',  
          className: 'btn btn-success',text: '<i class="fas fa-file-excel"></i> Excel',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
          }
        },
        {
          extend: 'pdf',
          title: 'Pending Receivables',
          className: 'btn btn-danger', text: '<i class="fas fa-file-pdf"></i> PDF',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
          }
        },
        {
          extend: 'print',
          title: 'Pending Receivables',
          className: 'btn btn-primary', text: '<i class="fas fa-print"></i> Print',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
          }
        }
      ],
      columnDefs: [{
        targets: 0,
        orderable: false
      }],
      ajax: {
        url: `ajax_pending_receivables.php?action=get&t=${new Date().getTime()}`,
        method: 'POST',
        data: {
          orderStatus,
          fundStatus,
          from,
          to
        }
      },
      initComplete: () => {
        $('.dt-button').removeClass('dt-button');
        hideLoading();
        getOrderStatus();
      }
    });
  }
</script>