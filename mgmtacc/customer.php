<?php
include_once 'template/header.php';
$type = "";
$headertitle = "Registration Report";
if (isset($_GET['type'])) {
  $type = $_GET['type'];
  switch ($type) {
    case 'Verified_Customer':
      $headertitle = "Verified Customers (Manual Reg) List";
      break;
    case 'google_apple_facebook':
      $headertitle = "Verified Customers (Google/Apple/Facebook) List";
      break;
    case 'Landbank_Account':
      $headertitle = "Lanbank Customer List";
      break;
    case 'Unverified_Customer':
      $headertitle = "Unverified Customers List";
      break;
    case 'Guest':
      $headertitle = "Unverified Customers (Guest) List";
    case '4Gives_Account':
      $headertitle = "4Gives Customer List";
      break;
    default:
      break;
  }
}
?>
<div id="content">
  <div class="container-fluid">

    <h2><?php echo $headertitle; ?></h2>
  </div>
  <div class="container-fluid well">
    <div class="col-md-2">
      <label for="">Filter by: </label>
      <select id="filter" class="form-control">
        <option value="dateCreated">Date Created</option>
        <option value="fullName">Fullname</option>
      </select>
    </div>
    <div class="col-md-2 fullname">
      <label for="from">Fullname: </label>
      <input class="form-control" type="text" id="fullName">
    </div>
    <div class="date">
      <div class="col-md-2">
        <label for="from">Date created from: </label>
        <input class="form-control" type="date" id="from">
      </div>
      <div class="col-md-2">
        <label for="to">Date created to: </label>
        <input class="form-control" type="date" id="to">
      </div>
    </div>
    <div class="col-md-2">
      <button id="search" class="btn btn-primary" style="width: 40px; height: 40px; margin-top: 10px"><i class="fa fa-search" aria-hidden="true"></i></button>
      <button id="clear" class="btn btn-danger" style="width: 40px; height: 40px; margin-top: 10px"><i class="fa fa-times" aria-hidden="true"></i></button>
    </div>
  </div>
  <div class="container-fluid">
    <table class="table table-bordered table-hover
    " id="customerTable">
      <thead>
        <tr>
          <td class="text-center">Customer ID</td>
          <td class="text-center">Fullname</td>
          <td class="text-center">Email</td>
          <td class="text-center">Mobile#</td>
          <td class="text-center">Date Created</td>
          <td class="text-center">Type</td>
        </tr>
      </thead>
    </table>
  </div>
</div>
<?php include 'template/footer.php'; ?>

<script>
  let datatable;
  $(document).ready(() => {
    $('.fullname').css('display', 'none');
    showLoading();
    loadData();
  });

  $('#search').click(() => {
    datatable.destroy();
    loadData();
  });

  $('#clear').click(() => {
    $('#fullName').val('');
    $('#from').val('');
    $('#to').val('');

    datatable.destroy();
    loadData();
  })

  $('#filter').on('change', function() {
    let filter = this.value;
    $('#fullName').val('');
    $('#from').val('');
    $('#to').val('');

    if (filter == 'fullName') {
      $('.date').css('display', 'none');
      $('.fullname').css('display', 'block');
    } else {
      $('.fullname').css('display', 'none');
      $('.date').css('display', 'block');
    }

  });

  let loadData = () => {
    const from = $('#from').val();
    const to = $('#to').val();
    const fullname = $('#fullName').val();
    const type = '<?php if (isset($_GET['type'])) {
                    echo $_GET['type'];
                  } else {
                    echo "notset";
                  } ?>';

    datatable = $("#customerTable").DataTable({
      "serverSide": true,
      "order": [],
      "oLanguage": {
        "sSearch": "Quick Search:"
      },
      "bSort": true,
      "dom": 'Blfrtip',
      "buttons": [{
          extend: 'excel',
          title: 'Registration Report',    
          className: 'btn btn-success',text: '<i class="fas fa-file-excel"></i> Excel',
        },
        {
          extend: 'pdf',
          title: 'Registration Report',
          className: 'btn btn-danger', text: '<i class="fas fa-file-pdf"></i> PDF',

        },
        {
          extend: 'print',
          title: 'Registration Report',
          className: 'btn btn-primary', text: '<i class="fas fa-print"></i> Print',
        },
      ],
      "lengthMenu": [
        [15, 50, 100, 500, 1000, 2000, 3000, 3272],
        [15, 50, 100, 500, 1000, 2000, 3000, 3272]
      ],
      "ajax": {
        "url": `ajax_customer.php?action=get&type=${type}&t=${new Date().getTime()}`,
        "method": "POST",
        "data": {
          fullname,
          from,
          to
        }
      },
      "initComplete": data => {
        hideLoading();
        $('.dt-button').removeClass('dt-button');
      }
    });
  }
</script>