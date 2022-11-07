<?php
include "common/headertest.php";
require_once "model/discount_wallet.php";
// Insert Activity
require_once "model/customer_activity.php";
$customerActivity = new CustomerActivity();
$customerActivity->insertactivity($userid, 'Shipping Wallet');
$id = isset($_SESSION['user_login']) ? $_SESSION['user_login'] : 0;
$discount_wallet = new discount_wallet();

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
      <span style="font-size: 26px">Shipping Wallet</span>
    </div>
  </div>
  <div class="row mb-3">
    <div class="col-12">
      <div class="table-responsive">
        <table class="table table-bordered table-hover" id="table">
          <tbody>
            <?php
            $total = 0;

            foreach ($discount_wallet->getshippingwalletwallet($id) as $dwallet1) {

              $total += $dwallet1['amount'];
            }
            ?>

            <h5 class="float-end"><b>Your Total Shipping Wallet amount: <span style="font-size: 18px"><?php echo number_format($total, 2) ?> </span> </b></h5>


            <thead>
              <tr>
                <td class="text-left"><b>Particulars</b></td>
                <td class="text-center"><b>Date Added</b></td>
                <td class="text-center"><b>Amount</b></td>
              </tr>
            </thead>
            <?php foreach ($discount_wallet->getshippingwalletwallet($id) as $dwallet) { ?>
              <tr>
                <td class="text-left"><?php echo $dwallet['particulars']; ?></td>
                <td class="text-center"><?php echo $dwallet['date_added']; ?></td>
                <td class="text-center"><?php echo number_format($dwallet['amount'], 2); ?></td>
              </tr>

            <?php } ?>




          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(() => {
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
          title: 'Discount Wallet',
        },
        {
          extend: 'pdf',
          title: 'Discount Wallet',
        },
        {
          extend: 'print',
          title: 'Discount Wallet',
        }
      ]
    });
  })
</script>


<?php include "common/footer.php";


?>