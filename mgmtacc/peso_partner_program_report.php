<?php
include_once "template/header.php";

$perm = $_SESSION['permission'];
if (!strpos($perm, "'19';") !== false) {
  header("Location: landing.php");
}
?>
<div class="container">
  <h2>PESO Partner Program Report</h2>
  <br>
  <ul class="nav nav-tabs"></ul>
  <div class="tab-content"></div>

  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Modal title</h4>
        </div>
        <div class="modal-body">
          <table class="table table-bordered" id="recommendTable">
            <thead></thead>
            <tbody></tbody>
            <tfoot></tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>

  <?php include_once "template/footer.php"; ?>

  <script>
    $(document).on('click', '.nav-tabs a', function() {
      $(this).tab('show');
    });

    $(document).ready(() => {
      showLoading();
      $.ajax({
        url: `ajax_peso_partner_program_report.php?action=getBranches&t=${new Date().getTime()}`,
        dataType: 'json',
        success: response => {
          response.map(res => {
            $(".nav.nav-tabs").append(`<li role="presentation"><a href="#${res.branch_id}Tab">${res.branch_name}</a></li>`);
            $(".tab-content").append(`<div class="tab-pane" id="${res.branch_id}Tab" style="margin-top: 1rem">
            <table class="table table-bordered table-hover" id="${res.branch_id}Table">
              <thead>
                <tr>
                  <th colspan="12" class="text-center" id="${res.branch_id}tableTitle"></th>
                </tr>
                <tr>
                  <td colspan="12" id="${res.branch_id}totalMembers"></td>
                </tr>
                <tr>
                  <th>Salesman</th>
                  <th>Total Recommend Products</th>
                  <th>Number of Success Sales</th>
                  <th>Number of Pending Sales</th>
                  <th>Sales Cash Wallet</th>
                  <th style='min-width: 50px'>Details</th>
                </tr>
              </thead>
            </table>
            </div>`);

            $(`#${res.branch_id}tableTitle`).html(res.branch_name);

            $(`#${res.branch_id}Table`).DataTable({
              serverSide: true,
              bSort: false,
              autoWidth: false,
              oLanguage: {
                sSearch: "Quick Search:",
              },
              lengthMenu: [
                [15, 50, 100, 500, 1000, 2000],
                [15, 50, 100, 500, 1000, 2000]
              ],
              dom: 'Blftrip',
              buttons: [{
                  extend: 'excel',
                  title: 'PESO Partner Program Reports',
                  exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                  }
                },
                {
                  extend: 'pdf',
                  title: 'PESO Partner Program Reports',
                  exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                  }
                },
                {
                  extend: 'print',
                  title: 'PESO Partner Program Reports',
                  exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                  }
                },
              ],
              ajax: {
                url: `ajax_peso_partner_program_report.php?action=get&t=${new Date().getTime()}`,
                method: 'POST',
                data: {
                  branch_id: res.branch_id
                },
              },
              fnPreDrawCallback: () => {
                showLoading();
              },
              fnDrawCallback: () => {
                hideLoading();
              },
              initComplete: (settings, data) => {
                $(`#${res.branch_id}totalMembers`).html(`Total Number Of Members: ${data.totalMembers}`);
              }
            });
            // DataTable
          });
          // response map
          $(`a[href='#${Object.values(response)[0].branch_id}Tab']`).parent().addClass('active');
          $(`#${Object.values(response)[0].branch_id}Tab`).addClass('active');
        }
        // success response
      });
      // ajax
    });
    // document ready

    $(document).on('click', '[name="recommend"]', function() {
      let id = $(this).attr('id');
      let name = $(this).data('name');

      $('#recommendTable>thead').html(`
          <tr>
            <th>No.</th>
            <th>Product Name</th>
            <th>Date</th>
            <th>Mode of Share</th>
          </tr>`);
      $('#recommendTable>tbody').html('');
      $('#recommendTable>tfoot').html('');

      $('#myModal').modal('show');
      $.ajax({
        url: `ajax_peso_partner_program_report.php?action=recommendDetails&t=${new Date().getTime()}`,
        method: 'POST',
        dataType: 'json',
        data: {
          id
        },
        success: response => {
          $('.modal-title').html(`${name} Recommend Products Details`);
          response.map((res, index) => {
            $('#recommendTable>tbody').append(`
            <tr>
              <td>${index+1}</td>
              <td>${res.product_name}</td>
              <td>${res.date}</td>
              <td>${res.mode_of_share}</td>
            </tr>
          `);
          })
        }
      });
    });

    $(document).on('click', '[name="cashWallet"]', function() {
      let id = $(this).attr('id');
      let name = $(this).data('name');

      $('#recommendTable>thead').html(`
          <tr>
            <th>No.</th>
            <th>Order ID</th>
            <th>Product Name</th>
            <th>Date of Conversion</th>
            <th>Amount</th>
          </tr>`);
      $('#recommendTable>tbody').html(``);
      $('#recommendTable>tfoot').html(`
      <tr>
        <td class="text-right" colspan="4">
          <b>Total Wallet</b>
        </td>
        <td>
          <b id="totalWallet">0.00</b>
        </td>
      </tr>
      `);

      $('#myModal').modal('show');

      $.ajax({
        url: `ajax_peso_partner_program_report.php?action=cashWalletDetails&t=${new Date().getTime()}`,
        method: 'POST',
        dataType: 'json',
        data: {
          id
        },
        success: response => {
          $('.modal-title').html(`${name} Sales Cash Wallet Details`);
          for (let i = 0; i < response.length - 1; i++) {
            $('#recommendTable>tbody').append(`
            <tr>
              <td>${i+1}</td>
              <td>${response[i].order_id}</td>
              <td>${response[i].product_name}</td>
              <td>${response[i].date}</td>
              <td>${response[i].amount}</td>
            </tr>`);
          }
          $('#totalWallet').html(response[response.length - 1].totalAmount);
        }
      });
    });
  </script>