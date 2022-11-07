<?php
include "common/headertest.php";
require_once "model/cash_wallet.php";
// Insert Activity
require_once "model/customer_activity.php";
$customerActivity = new CustomerActivity();
$customerActivity->insertactivity($userid, 'Cash Wallet');
$id = isset($_SESSION['user_login']) ? $_SESSION['user_login'] : 0;
$cash_wallet = new cash_wallet();

?>
<script type="text/javascript">
  var islog = '<?php echo $is_log; ?>';
  if (islog == "0") {
    location.replace("home.php");
  }
</script>

<div class="container bg-white p-sm-3" style="margin-top: 135px">
  <div class="row mb-3">
    <div class="col-12">
      <span style="font-size: 26px">Cash Wallet</span>
    </div>
  </div>
  <div class="row mb-3">
    <div class="col-12">
      <div class="table-responsive">
        <table class="table table-bordered table-hover mt-2" id="table">
          <tbody>
            <button class="btn btn-primary m-1 float-start" style="font-size: 20px;" id="cashmodal" data-bs-toggle="modal" data-bs-target="#TermsModal"><i class="fas fa-hand-holding-usd"></i></button>
            <?php
            $total = 0;
            foreach ($cash_wallet->getcashwallet($id) as $cwallet1) {
              $total += $cwallet1['amount'];
            }
            ?>
            <h5 class="float-end"><b>Your Total Wallet amount: <span style="font-size: 18px"><?php echo number_format($total, 2) ?> </span> </b></h5>
            <input class="d-none" type="label" id="totalwallet" value="<?php echo number_format($total, 2) ?>" name="">
            <input class="d-none" type="label" id="custid" value="<?php echo $id ?>" name="">
            <div class="clearfix"></div>
            <thead>
              <tr>
                <td class="text-left"><b>Particulars</b></td>
                <td class="text-center"><b>Date Added</b></td>
                <td class="text-center"><b>Amount</b></td>
              </tr>
            </thead>
            <?php foreach ($cash_wallet->getcashwallet($id) as $cwallet) { ?>
              <tr>
                <td class="text-left"><?php echo $cwallet['product_name']; ?></td>
                <td class="text-center"><?php echo $cwallet['date']; ?></td>
                <td class="text-center"><?php echo number_format($cwallet['amount'], 2); ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<div class="modal fade bd-example-modal-lg" id="TermsModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <p style="font-size: 20px" class="modal-title"><strong>Cash Out </strong></p>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-hover">
          <tbody>
            <tr>
              <th colspan="2">
                Cash Out Type
              </th>
            </tr>
            <tr>
              <td colspan="2">
                <select name="cashouttype" id="cashouttype" class="form-control">
                  <option value="0">Select Cash Out Type</option>
                  <option value="Bank Deposit">Bank Deposit (BDO, Union and BPI only)</option>
                  <option value="GCash">GCash</option>
                  <option value="Palawan Express">Palawan Express</option>
                  <option value="Cebuana Lhuillier">Cebuana Lhuillier</option>
                </select>
              </td>
            </tr>
          </tbody>
          <tbody>
            <tr>
              <th>
                Account Name
              </th>
              <th>
                Account Number
              </th>
            </tr>
            <tr>
              <td>
                <input class="form-control" type="text" name="" id="accountname" placeholder="Account Name">
              </td>
              <td>
                <input class="form-control" type="text" name="" id="accountnumber" placeholder="Account Number">
              </td>
            </tr>
          </tbody>
          <tbody>
            <tr>
              <th colspan="2">
                Amount
              </th>
            </tr>
            <tr>
              <td colspan="2">
                <input class="form-control" id="txtamount" type="number" name="">
              </td>

            </tr>
          </tbody>
        </table>
        <button class="btn btn-primary " id="btncashout" style="font-size: 20px;"><i class="fas fa-hand-holding-usd"></i></button>

      </div>
    </div>
  </div>
</div>


<script>
  $(document).ready(function() {
    $('#table').DataTable({
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
          title: 'Cash Wallet',
        },
        {
          extend: 'pdf',
          title: 'Cash Wallet',
        },
        {
          extend: 'print',
          title: 'Cash Wallet',
        }
      ]
    });

  });

  $("#cashmodal").click(function() {

    var amount = $("#totalwallet").val();
    $("#txtamount").val(amount?.replace(/,/g, ''));

    $("#cashouttype").val("0");
    $("#accountname").val("");
    $("#accountnumber").val("");


  });

  $("#btncashout").click(function() {
    var id = $("#custid").val();
    var wallet = $("#totalwallet").val()?.replace(/,/g, '');
    var amount = $("#txtamount").val()?.replace(/,/g, '');
    var cashtype = $("#cashouttype").val();
    var accname = $("#accountname").val();
    var accnumber = $("#accountnumber").val();

    if (cashtype == "0" || accname == "" || accnumber == "" || amount == "") {
      bootbox.alert("All fields must not be empty");
      return false;
    } else if (amount < 500) {
      bootbox.alert("Minimum Cash Out should be 500 cash wallet.");
      return false;
    } else {
      $.ajax({
        url: 'ajax_cashout.php',
        type: 'POST',
        data: 'cashtype=' + cashtype + '&accname=' + accname + '&accnumber=' + accnumber + '&amount=' + amount + '&id=' + id,
        dataType: 'json',
        success: function(json) {

          if (json['success']) {
            bootbox.alert(json['success'], function() {
              location.replace("cash_wallet.php");
            });
          }

        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }

  });

  function editqty(qty, cart_id, rqty) {
    if (qty > rqty) {

      alert("No available stock !");
      location.replace("cart.php");
    } else {
      $.ajax({
        url: 'ajax_editcart.php',
        type: 'POST',
        data: 'cart_id=' + cart_id + '&qty=' + qty,
        dataType: 'json',
        success: function(json) {

          if (json['success']) {
            alert(json['success']);
            location.replace("cart.php");
          }

        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }

  }


  function numberWithCommas(x) {
    return x.toString()?.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }
</script>

<?php include "common/footer.php";
?>