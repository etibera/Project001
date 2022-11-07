<?php
include 'template/header.php';

$perm = $_SESSION['permission'];
if (!strpos($perm, "'252528';") !== false) {
  header("Location: landing.php");
}
?>

<div id="content">
  <div class="container-fluid">
    <h2>Sales Report</h2>
  </div>
  <div class="container-fluid well">
    <div class="col-md-2">
      <label for="">Filter by: </label>
      <select id="filterDate" class="form-control">
        <option value="0">Date Added</option>
        <option value="1">Date of Sales</option>
      </select>
    </div>
    <div class="col-md-2">
      <label for="from">Date from: </label>
      <input class="form-control" type="date" id="from">
    </div>
    <div class="col-md-2">
      <label for="to">Date to: </label>
      <input class="form-control" type="date" id="to">
    </div>
    <div class="col-md-2">
      <button id="search" class="btn btn-primary" style="width: 40px; height: 40px; margin-top: 10px"><i class="fa fa-search" aria-hidden="true"></i></button>
      <button id="clear" class="btn btn-danger" style="width: 40px; height: 40px; margin-top: 10px"><i class="fa fa-times" aria-hidden="true"></i></button>
    </div>
  </div>
  <div class="container-fluid">
    <table class="table table-bordered table-hover" id="salesReportTable">
      <thead>
        <tr>
          <td class="text-center" colspan="4"></td>
          <td class="text-center" colspan="1">TOTAL Transations</td>
          <td class="text-center" colspan="1">Successful Transactions</td>
          <td class="text-center" colspan="1">Bank Transactions</td>
          <td class="text-center" colspan="1">Total Charges</td>
          <td class="text-center" colspan="4"></td>
        </tr>
        <tr>
          <td class="text-center" colspan="4"></td>
          <td id="totalTransaction" class="text-center" colspan="1"></td>
          <td id="successfullTransaction" class="text-center" colspan="1"></td>
          <td id="bankTransaction" class="text-center" colspan="1"></td>
          <td id="totalCharge" class="text-center" colspan="1"></td>
          <td class="text-center" colspan="4"></td>
        </tr>
        <tr>
          <td class="text-center">Order Number</td>
          <td class="text-center">Customer Name</td>
          <td class="text-center">Status</td>
          <td class="text-center">Mode of Payment</td>
          <td class="text-center">Total</td>
          <td class="text-center">Successful Sales</td>
          <td class="text-center">Bank Transactions</td>
          <td class="text-center">OP System charge</td>
          <td class="text-center">Date Added </td>
          <td class="text-center">Date of Sales </td>
          <td class="text-center">Seller Receipt No </td>
          <td class="text-center">Serial No.</td>
        </tr>
      </thead>
      <tbody>

      </tbody>
    </table>
  </div>
</div>
<?php include_once "template/footer.php"; ?>
<script>
  let datatable;
  $(document).ready(function() {
    showLoading();
    loadData();
  });

  $('#search').click(() => {
    datatable.destroy();
    loadData();
  });

  $('#clear').click(() => {
    $('#filterDate').val(0);
    $('#from').val('');
    $('#to').val('');
    datatable.destroy();
    loadData();
  });

  const loadData = () => {
    const filter = $('#filterDate').val();
    const from = $('#from').val();
    const to = $('#to').val();

    datatable = $('#salesReportTable').DataTable({
      serverSide: true,
      order: [],
      oLanguage: {
        sSearch: "Quick Search:"
      },
      bSort: false,
      dom: 'Blfrtip',
      buttons: [{
          extend: 'excel',
          className: 'btn btn-success',text: '<i class="fas fa-file-excel"></i> Excel',
          title: 'Sales Report',
        },
        {
          extend: 'pdf',
          title: 'Sales Report',
          className: 'btn btn-danger', text: '<i class="fas fa-file-pdf"></i> PDF',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 9],
          }
        },
        {
          extend: 'print',
          title: 'Sales Report',
          className: 'btn btn-primary', text: '<i class="fas fa-print"></i> Print',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 9],
          }
        },
      ],
      lengthMenu: [
        [15, 50, 100, 500,1000,2000],
        [15, 50, 100, 500,1000,2000]
      ],
      ajax: {
        url: `ajax_sales_report_new.php?action=get&t=${new Date().getTime()}`,
        method: 'POST',
        dataType: 'json',
        data: {
          filter,
          from,
          to
        },
      },
      initComplete: (settings, response) => {
        $('.dt-button').removeClass('dt-button');
        hideLoading();
        $('#totalTransaction').html(response.totalTransaction);
        $('#successfullTransaction').html(response.successfullTransaction);
        $('#bankTransaction').html(response.bankTransaction);
        $('#totalCharge').html(response.totalCharge);

      }
    });
  }
</script>