<?php
include_once('template/header.php');

$perm = $_SESSION['permission'];
if (!strpos($perm, "'252529';") !== false) {
  header("Location: landing.php");
}
?>

<div class="container">
  <div class="container-fluid">
    <h2>Page Type</h2>
  </div>
  <button name="insert" type="button" class="btn btn-success" style="margin: 15px" data-toggle="modal" data-target="#typeModal">
    Add Page Type
  </button>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i>Page Type List</h3>
      </div>
      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="pageTypeTable">
            <thead>
              <tr>
                <td class="text-center" style="max-width: 50px">ID</td>
                <td class="text-center" style="max-width: 50px">Sort Order</td>
                <td class="text-center">Name</td>
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
<div class="modal fade" id="typeModal" tabindex="-1" role="dialog" aria-labelledby="typeModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTitle">Add Page Type</h4>
      </div>
      <div class="modal-body">
        <div class="form-group row">
          <div class="col-md-8">
            <label for="typeName" class="control-label">Type Name: </label>
            <input type="text" class="form-control" id="typeName">
          </div>
          <div class="col-md-4">
            <label for="order" class="control-label">Order Number: </label>
            <input type="number" class="form-control" id="order" step="1" min="1">
          </div>
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
  let actionType = "";
  let id = "";

  $(document).ready(() => {
    $('.loading').css('display', 'none');

    datatable = $('#pageTypeTable').DataTable({
      "order": [],
      "bSort": true,
      "dom": 'Blfrtip',
      "oLanguage": {
        "sSearch": "Quick Search:"
      },
      "buttons": [{
          extend: 'excel',
          title: 'Page Type'
        },
        {
          extend: 'pdf',
          title: 'Page Type'
        },
        {
          extend: 'print',
          title: 'Page Type',
        },
      ],
      "lengthMenu": [
        [15, 50, 100],
        [15, 50, 100]
      ],
      "ajax": {
        "url": "ajax_page_type.php?action=get&t=" + new Date().getTime()
      }
    });

  });

  let pageType = (action, btnID) => {
    let typeName = $('#typeName').val();
    let order = $('#order').val();
    id = btnID;

    $.ajax({
      url: `ajax_page_type.php?action=${action}`+'&t=' + new Date().getTime(),
      method: 'POST',
      dataType: 'json',
      data: {
        typeName,
        order,
        id
      },
      success: response => {
        if (response.type === 'success') {
          datatable.ajax.reload();
          $('#typeName').val('');
          $('#order').val('');
          $('#typeModal').modal('hide');
        }
        bootbox.alert(response.message);
      }
    });
  }

  $(document).on('click', '[name="update"]', function() {
    actionType = "update";
    id = this.id;

    $('#modalTitle').html('Update Page Type');
    $('#submit').html('Update');

    $('#typeModal').modal('show');

    $.ajax({
      url: 'ajax_page_type.php?action=getSingle&t=' + new Date().getTime(),
      method: 'POST',
      dataType: 'json',
      data: {
        id
      },
      success: response => {
        $('#typeName').val(response.name);
        $('#order').val(response.order_number);
      }
    });

  });

  $(document).on('click', '[name="insert"]', function() {
    actionType = "insert";
    $('#typeName').val('');
    $('#order').val('');

    $('#modalTitle').html('Add Page Type');
    $('#submit').html('Add');
  });

  $('#submit').click(() => {
    if (actionType === 'insert') {
      pageType('insert', id);
    }
    if (actionType === 'update') {
      pageType('update', id);
    }
  });
</script>