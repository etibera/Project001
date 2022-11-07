<?php
include_once "template/header.php";

$perm = $_SESSION['permission'];
if (!strpos($perm, "'252549';") !== false) {
  header("Location: landing.php");
}

?>

<div class="container-fluid">
  <h2> Brand / Store / Flagship Customer Comment</h2>
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
          <label class="control-label" for="ccStatus">Status</label>
          <select name="payable_status" id="ccStatus" class="form-control">
            <option value="all">All</option>
            <option value="0">Pending</option>
            <option value="1">Approved</option>
          </select>
        </div>
      </div>
      <div class="col-sm-2">
        <div class="form-group">
          <label class="control-label" for="ccType">Type</label>
          <select name="payable_status" id="ccType" class="form-control">
            <option value="1">Brand</option>           
            <option value="2">Store</option>
            <option value="3">Flagship</option>
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
          <a class="btn btn-primary pull-right" id="ApproveComment" title="Confirm Payment" style="margin-top: .9em; margin-right: 1em; padding: 1em 1.5em"><i class="fas fa-check-circle"></i> Approve Comment</a>
        </div>
      </div>
    </div>
  </div>
  <table class="table table-bordered table-hover" id="ccTable">
    <thead>
      <th>
        <center><input type="checkbox" id="checkAll"></center>
      </th>
      <th>Customer Name</th>
      <th>Customer Comment </th>
      <th>Status</th>
      <th>Type</th>
      <th id="ccname"></th>
      <th>Date Added</th>
    </thead>
  </table>
</div>

<?php
include_once "template/footer.php";
?>
<script >
   let datatable;
  $(document).ready(() => {
    loadData();
  });

  $("#checkAll").click(function() {
    $('input:checkbox').not(this).prop('checked', this.checked);
  });
  $('#search').click(() => {
    datatable.destroy();
    loadData();
  });
  $('#ApproveComment').click(function() {
    showLoading();
    let ccid = [];
    $('input[name="ccid"]:checked').each(function() {
      ccid.push($(this).val());
    });
    $.ajax({
      url: `ajax_customerComment.php?action=ApproveComment&t=${new Date().getTime()}`,
      method: 'POST',
      dataType: 'json',
      data: {
        ccid
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
   $('#clear').click(() => {
    $('#dateFrom').val('');
    $('#dateTo').val('');
    $('#ccType').val('1');
    $('#ccStatus').val('all');
    datatable.destroy();
    loadData();
  });
   let loadData = () => {
    showLoading();
    const ccType = $('#ccType').val();
    const ccStatus = $('#ccStatus').val();
    const from = $('#dateFrom').val();
    const to = $('#dateTo').val();
    var ccname = "";
    if(ccType==1){
     $('#ccname').html("Brand Name");
     ccname="Brand Customer Comment Report";
    }else if(ccType==2){
      $('#ccname').html("Store Name");
      ccname="Store Comment Report";
    }else{
      $('#ccname').html("Flagship Name");
      ccname="Flagship Comment Report";
    }
    datatable = $('#ccTable').DataTable({
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
          title: ccname,
          className: 'btn btn-success',
          text: '<i class="fas fa-file-excel"></i> Excel',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'pdf',
          title:ccname,
          className: 'btn btn-danger', text: '<i class="fas fa-file-pdf"></i> PDF',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'print',
          title: ccname, 
          className: 'btn btn-primary', text: '<i class="fas fa-print"></i> Print',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6]
          }
        }
      ],
      columnDefs: [{
        targets: 0,
        orderable: false
      }],
      ajax: {
        url: `ajax_customerComment.php?action=getCustomerComment&t=${new Date().getTime()}`,
        method: 'POST',
        data: {
          ccType,
          ccStatus,
          from,
          to
        }
      },
      initComplete: () => {
        hideLoading();
        $('.dt-button').removeClass('dt-button')
      }
    });
  }
</script>