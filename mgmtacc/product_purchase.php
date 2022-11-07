<?php
include_once "template/header.php";

$perm = $_SESSION['permission'];
if (!strpos($perm, "'21';") !== false) {
  header("Location: landing.php");
}
?>

<div class="container">
  <h2>Product Purchased Report</h2>
  <div class="well">
    <div class="row">
      <div class="col-sm-3">
        <div class="form-group">
          <label class="control-label" for="dateFrom">Date From:</label>
          <input type="date" id="dateFrom" class="form-control">
        </div>
      </div>
      <div class="col-sm-3">
        <div class="form-group">
          <label class="control-label" for="dateTo">Date To:</label>
          <input type="date" id="dateTo" class="form-control">
        </div>
      </div>
      <div class="col-sm-3">
        <div class="form-group">
          <label class="control-label" for="orderStatus">Order Status</label>
          <select name="payable_status" id="orderStatus" class="form-control">
            <option value="all">All</option>
          </select>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="form-group">
          <button id="search" class="btn btn-primary" style="width: 40px; height: 40px; margin-top: 10px"><i class="fa fa-search" aria-hidden="true"></i></button>
          <button id="clear" class="btn btn-danger" style="width: 40px; height: 40px; margin-top: 10px"><i class="fa fa-times" aria-hidden="true"></i></button>
        </div>
      </div>
    </div>
  </div>

  <table class="table table-bordered table-hovbe" id="productPurchaseTable">
    <thead>
      <tr>
        <th>Product Name</th>
        <th>Model</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Total Price</th>
      </tr>
    </thead>
  </table>
</div>

<?php include_once "template/footer.php"; ?>

<script>
  let datatable;
  $(document).ready(function() {
    loadData();
  });

  $('#search').click(() => {
    datatable.destroy();
    loadData();
  });

  $('#clear').click(() => {
    $('#dateFrom').val('');
    $('#dateTo').val('');
    $('#orderStatus').val('all');

    datatable.destroy();
    loadData();
  });

  const getOrderStatus = () => {
    $.ajax({
      url: `ajax_pending_receivables.php?action=getOrderStatus&t=${new Date().getTime()}`,
      dataType: 'json',
      success: response => {
        $('#orderStatus').append(response);
      }
    })
  }

  const loadData = () => {

    const orderStatus = $('#orderStatus').val();
    const from = $('#dateFrom').val();
    const to = $('#dateTo').val();

    datatable = $('#productPurchaseTable').DataTable({
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
          title: 'Product Purchased Report',
          className: 'btn btn-success',text: '<i class="fas fa-file-excel"></i> Excel',
        },
        {
          extend: 'pdf',
          title: 'Product Purchased Report',
          className: 'btn btn-danger', text: '<i class="fas fa-file-pdf"></i> PDF',
        },
        {
          extend: 'print',
          title: 'Product Purchased Report',
          className: 'btn btn-primary', text: '<i class="fas fa-print"></i> Print',
        }
      ],
      ajax: {
        url: `ajax_product_purchase.php?action=get&t=${new Date().getTime()}`,
        method: 'POST',
        data: {
          orderStatus,
          from,
          to
        }
      },
      fnPreDrawCallback: () => {
        showLoading();
      },
      fnDrawCallback: () => {
          $('.dt-button').removeClass('dt-button');
        hideLoading();
      },
      initComplete: () => {
          $('.dt-button').removeClass('dt-button');
        getOrderStatus();
        hideLoading();
      }
    });
  }
</script>