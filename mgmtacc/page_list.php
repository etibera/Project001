<?php
include_once('template/header.php');

$perm = $_SESSION['permission'];
if (!strpos($perm, "'252530';") !== false) {
  header("Location: landing.php");
}

?>

<div class="container">
  <div class="container-fluid">
    <h2>Page List</h2>
  </div>
  <button name="insert" type="button" class="btn btn-success" style="margin: 15px" data-toggle="modal" data-target="#pageModal">
    Add Page List
  </button>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i>Page List</h3>
      </div>
      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="pageListTable">
            <thead>
              <tr>
                <td class="text-center" style="max-width: 50px">ID</td>
                <td class="text-center" style="max-width: 50px">Sort Order</td>
                <td class="text-center">Name</td>
                <td class="text-center">Link</td>
                <td class="text-center" style="max-width: 100px">Type</td>
                <td class="text-center" style="max-width: 50px">Action</td>
              </tr>
            </thead>
            <div class="loading">
              <div class="load">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
              </div>
            </div>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="pageModal" tabindex="-1" role="dialog" aria-labelledby="pageModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTitle">Add Page List</h4>
      </div>
      <div class="modal-body row">
        <div class="form-group col-md-8">
          <label for="pageName" class="control-label">Name: </label>
          <input id="pageName" type="text" class="form-control">
        </div>
        <div class="form-group col-md-4">
          <label for="order" class="control-label">Order Number: </label>
          <input type="number" class="form-control" id="orderNumber" step="1" min="1">
        </div>
        <div class="form-group col-md-12">
          <label for="order" class="control-label">Link: </label>
          <input type="text" class="form-control" id="link" placeholder="Ex: index.php">
        </div>
        <div class="form-group col-md-12">
          <label for="pageType" class="control-label">Type: </label>
          <select id="pageType" class="form-control">

          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="submit">Add</button>
      </div>
    </div>
  </div>
</div>


<script>
  let datatable;
  let action;
  let id;

  $(document).ready(() => {
    $('.loading').css('display', 'none');

    datatable = $('#pageListTable').DataTable({
      "order": [],
      "oLanguage": {
        "sSearch": "Quick Search:"
      },
      "bSort": true,
      "dom": 'Blfrtip',
      "buttons": [{
          extend: 'excel',
          title: 'Page List'
        },
        {
          extend: 'pdf',
          title: 'Page List'
        },
        {
          extend: 'print',
          title: 'Page List',
        },
      ],
      "lengthMenu": [
        [15, 50, 100],
        [15, 50, 100]
      ],
      "ajax": {
        "url": "ajax_page_list.php?action=get&t=' + new Date().getTime()"
      }
    });

    $.ajax({
      url: 'ajax_page_list.php?action=getType&t=' + new Date().getTime(),
      dataType: 'json',
      success: response => {
        $('#pageType').html(response);
      }
    })
  });

  let pageList = (action, id) => {
    let pageName = $('#pageName').val();
    let pageType = $('#pageType').val();
    let orderNumber = $('#orderNumber').val();
    let link = $('#link').val();

    $.ajax({
      url: `ajax_page_list.php?action=${action}`+'&t=' + new Date().getTime(),
      method: 'POST',
      dataType: 'json',
      data: {
        id,
        pageName,
        pageType,
        orderNumber,
        link
      },
      success: response => {
        if (response.type === 'success') {
          $('#pageName').val();
          $('#orderNumber').val();
          $('#link').val();
          $('#pageModal').modal('hide');
          datatable.ajax.reload(null, false);
        }
         bootbox.alert(response.message);
      }
    });
  }



  $(document).on('click', '[name="insert"]', function() {
    action = 'insert';

    $('#modalTitle').html('Add Page List');
    $('#submit').html('Add');

    $('#pageName').val('');
    $('#orderNumber').val('');
    $('#link').val('');
    $('#pageType').val(1);
  });

  $(document).on('click', '[name="update"]', function() {
    action = 'update';

    $('#modalTitle').html('Update Page List');
    $('#submit').html('Update');
    $('#pageModal').modal('show');

    id = this.id;

    $.ajax({
      url: 'ajax_page_list.php?action=getSingle&t=' + new Date().getTime(),
      method: 'POST',
      dataType: 'json',
      data: {
        id
      },
      success: response => {
        $('#pageName').val(response.pageName);
        $('#pageType').val(response.pageType);
        $('#orderNumber').val(response.orderNumber);
        $('#link').val(response.link);
      }
    });
  });

  $('#submit').click(() => {
    pageList(action, id);
  });
</script>