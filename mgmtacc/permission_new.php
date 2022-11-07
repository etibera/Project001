<?php
include_once "./template/header.php";

$perm = $_SESSION['permission'];
if (!strpos($perm, "'1';") !== false) {
  header("Location: landing.php");
}
?>
<style>
  #right {
    height: calc(100vh - 134px);
    overflow-y: auto;
  }

  .mb-5 {
    margin-bottom: 1.5em;
  }

  .btn.btn-primary.active {
    background: #c23616;
  }
</style>
<div class="container-fluid">
  <button id="insert" class="btn btn-success" style="float: right; margin-right: 20px; padding: 10px 50px; font-size: 1.3em">Add User</button>
  <div class="row">
    <div class="col-12 col-md-12" id="left">
      <h2 class="text-uppercase text-dark mb-5">User List</h2>
      <table class="table table-border" id="permissionTable">
        <thead>
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Fullname</th>
            <th style="max-width: 300px">Pemissions</th>
            <th style="max-width: 120px">Action</th>
          </tr>
        </thead>
      </table>
    </div>
    <div class="col-12 col-md-6 col-lg-6 col-xl-6" id="right"></div>
  </div>
</div>

<script src="//cdn.datatables.net/plug-ins/1.11.5/dataRender/ellipsis.js"></script>

<script>
  let datatable;
  let action;
  let id = "";
  $(document).ready(() => {
    getData();
    $('#right').css('display', 'none');
  });

  let getData = () => {
    datatable = $('#permissionTable').DataTable({
      "order": [],
      "bSort": true,
      "dom": 'Blfrtip',
      "oLanguage": {
        "sSearch": "Quick Search:"
      },
      "buttons": [{
          extend: 'excel',
          title: 'Page Type',
          exportOptions: {
            columns: [0, 1, 2, 3],
            orthogonal: 'export'
          }
        },
        {
          extend: 'pdf',
          title: 'Page Type',
          exportOptions: {
            columns: [0, 1, 2, 3],
            orthogonal: 'export'
          }
        },
        {
          extend: 'print',
          title: 'Page Type',
          exportOptions: {
            columns: [0, 1, 2, 3],
            orthogonal: 'export'
          }
        },
      ],
      "lengthMenu": [
        [10, 30, 50],
        [10, 30, 50]
      ],
      "columnDefs": [{
        "targets": 3,
        "render": $.fn.dataTable.render.ellipsis(42)
      }],
      "ajax": {
        "url": 'ajax_permission.php?action=get&t=' + new Date().getTime()
      }
    })
  }

  function getSingleUser(that, attrib, hidden) {
    let id = $(that).attr('id');
    $.ajax({
      url: 'ajax_permission.php?action=getSingleUser&t=' + new Date().getTime(),
      method: 'POST',
      dataType: 'json',
      data: {
        id
      },
      success: response => {
        let pageId = response[1];

        pageId.map((res) => {
          $('button#page_' + res.user_pages).addClass('active');
        });

        $('#left').html(`
          <form method="POST" id="userForm">
            <h2 class="text-uppercase text-dark mb-5">${response[0].firstname} ${response[0].lastname}</h2>
            <div class="form-group">
              <label for="firstName">Firstname: </label>
              <input class="form-control" type="text" name="firstName" value=${response[0].firstname} ${attrib}>
            </div>
            <div class="form-group">
              <label for="lastName">Lastname: </label>
              <input class="form-control" type="text" name="lastName" value=${response[0].lastname} ${attrib}>
            </div>
            <div class="form-group">
              <label for="userName">Username: </label>
              <input class="form-control" type="text" name="userName" value=${response[0].username} ${attrib}>
            </div>
            <div class="form-group">
              <label for="email">Email: </label>
              <input class="form-control" type="email" name="email" value=${response[0].email} ${attrib}>
            </div>
            <div class="form-group ${hidden}">
              <label for="password">Password: </label>
              <i class="text-danger ${hidden}"><strong><small>Leave it blank if you do not want to change your password.</small></strong></i>
              <input class="form-control" type="password" name="password">
            </div>
            <div class="form-group ${hidden}">
              <label for="cpassword">Confirm Password: </label>
              <input class="form-control" type="password" name="cpassword">
            </div>
            <div class="form-group pull-right testing">
              <button type="submit" class="btn btn-primary ${hidden}">Save</button>
              <button type="button" class="btn btn-default" id="cancel">Back</button>
            </div>
          </form>
        `);
      }
    });
  }

  $.ajax({
    url: 'ajax_permission.php?action=getType',
    dataType: 'json',
    success: response => {
      for (let i = 0; i < response.length; i++) {
        $('#right').append(`<div id=type_${response[i][0]}>
          <h4 class="text-uppercase text-dark">${response[i][1]}</h4></div>`);
        $.ajax({
          url: 'ajax_permission.php?action=getPage&t=' + new Date().getTime(),
          method: 'POST',
          dataType: 'json',
          data: {
            id: response[i][0]
          },
          success: response2 => {
            for (let j = 0; j < response2.length; j++) {
              $(`#type_${response[i][0]}`).append(`<button style="margin: .2em; padding: 1em 3em" class="btn btn-primary" data-toggle="button" id=page_${response2[j][0]}>${response2[j][1]}</button>`);
            };
          }
        });
      }
    }
  });

  $(document).on('submit', '#userForm', function(e) {
    e.preventDefault();

    var form = new FormData(this);
    let page = [];
    $('button[data-toggle="button"].active').each(function() {
      page.push($(this).attr('id').replace('page_', ''));
    });
    form.append('page', page);
    form.append('id', id);

    $.ajax({
      url: `ajax_permission.php?action=${action}` + '&t=' + new Date().getTime(),
      method: 'POST',
      data: form,
      dataType: 'json',
      processData: false,
      contentType: false,
      success: response => {
        if (response.type == 'success') {
          $.ajax({
            url: `ajax_permission.php?action=${action}Permission` + '&t=' + new Date().getTime(),
            method: 'POST',
            dataType: 'json',
            data: {
              id: response.userId,
              page
            },
            success: response2 => {
              if (response2.type == "success") {
                $('#cancel').click();
              }
              bootbox.alert(response2.message);
            }
          });
        } else {
          bootbox.alert(response.message);
        }
      }
    });
  });

  $(document).on('click', '[name="show"]', function() {
    $('#right').css('display', 'block');
    $('#left').removeClass('col-md-12').addClass('col-md-6');
    getSingleUser(this, 'disabled', 'hidden');
  });
  $(document).on('click', '[name="update"]', function() {
    id = $(this).attr('id');
    action = 'update';
    $('#right').css('display', 'block');
    $('#left').removeClass('col-md-12').addClass('col-md-6');
    getSingleUser(this);
  });
  $(document).on('click', '[name="delete"]', function() {
    id = $(this).attr('id');

    bootbox.confirm("Are you sure you want to delete?", res => {
      if (res) {
        $.ajax({
          url: 'ajax_permission.php?action=delete&t=' + new Date().getTime(),
          method: 'POST',
          dataType: 'json',
          data: {
            id
          },
          success: response => {
            alert(response.message);
            datatable.ajax.reload();
          }
        });
      }
    })
  });

  $(document).on('click', '#cancel', () => {

    $('button[data-toggle="button"].active').removeClass('active');

    $('#left').html(`
      <h2 class="text-uppercase text-dark mb-5">User List</h2>
        <table class="table table-border" id="permissionTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>Fullname</th>
              <th style="max-width: 300px">Pemissions</th>
              <th style="max-width: 120px">Action</th>
            </tr>
          </thead>
        </table>`);
    getData();

    $('#right').css('display', 'none');
    $('#left').removeClass('col-md-6').addClass('col-md-12');
  });

  $(document).on('click', '#insert', () => {

    action = 'insert';
    $('button[data-toggle="button"].active').removeClass('active');

    $('#left').html(`
      <form method="POST" id="userForm">
        <h2 class="text-uppercase text-dark mb-5">Add User</h2>
        <div class="form-group">
          <label for="firstName">Firstname: </label>
          <input class="form-control" type="text" name="firstName">
        </div>
        <div class="form-group">
          <label for="lastName">Lastname: </label>
          <input class="form-control" type="text" name="lastName">
        </div>
        <div class="form-group">
          <label for="userName">Username: </label>
          <input class="form-control" type="text" name="userName">
        </div>
        <div class="form-group">
          <label for="email">Email: </label>
          <input class="form-control" type="email" name="email">
        </div>
        <div class="form-group">
          <label for="password">Password: </label>
          <input class="form-control" type="password" name="password">
        </div>
        <div class="form-group">
          <label for="cpassword">Confirm Password: </label>
          <input class="form-control" type="password" name="cpassword">
        </div>
        <div class="form-group pull-right">
          <button type="submit" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-default" id="cancel">Cancel</button>
        </div>
      </form>
    `);

    $('#right').css('display', 'block');
    $('#left').removeClass('col-md-12').addClass('col-md-6');

  });
</script>