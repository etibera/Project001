<?php
include_once "template/header.php";

$perm = $_SESSION['permission'];
if (!strpos($perm, "'22';") !== false) {
  header("Location: landing.php");
}
?>

<div class="container">
  <h2>Products Viewed Reports</h2>

  <table class="table table-bordered table-hover" id="productsViewTable">
    <thead>
      <tr>
        <th>Product Name</th>
        <th>Model</th>
        <th style="width: 77px">Type</th>
        <th>Viewed</th>
        <th>Percent</th>
      </tr>
    </thead>
  </table>
</div>

<?php include_once "template/footer.php"; ?>

<script>
  let datatable;
  $(document).ready(() => {
    datatable = $('#productsViewTable').DataTable({
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
          title: 'Products Viewed Report',          
          className: 'btn btn-success',text: '<i class="fas fa-file-excel"></i> Excel',
          exportOptions: {
            columns: [0, 1, 2, 3, 4]
          }
        },
        {
          extend: 'pdf',
          title: 'Products Viewed Report',
          className: 'btn btn-danger', text: '<i class="fas fa-file-pdf"></i> PDF',
          exportOptions: {
            columns: [0, 1, 2, 3, 4]
          }
        },
        {
          extend: 'print',
          title: 'Products Viewed Report',
          className: 'btn btn-primary', text: '<i class="fas fa-print"></i> Print',
          exportOptions: {
            columns: [0, 1, 2, 3, 4]
          }
        }
      ],
      columnDefs: [{
        targets: 2,
        orderable: false
      }],
      ajax: {
        url: `ajax_product_viewed_report.php?action=get&t=${new Date().getTime()}`,
      },
      fnPreDrawCallback: () => {
        showLoading();
      },
      fnDrawCallback: () => {
        $('.dt-button').removeClass('dt-button');
        hideLoading();
      }
    });
  });
</script>