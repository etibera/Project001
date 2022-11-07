
<?php
require_once("includes/init.php"); 
if(!$session->is_signed_in()){redirect("index");}
include "model/Invoice.php";


if(isset($_GET['orderid']))
{
  $in = new Invoice();
  $order_details = $in->order_details($_GET['orderid']);
  $order_product = $in->order_product_details($_GET['orderid']);
}			
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">
<title>PESO</title>
		
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<script src="../js/jquery-1.12.4-jquery.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>	

<body class="font-print" onload="window.print()" style="margin: 0mm 0mm 0mm 0mm;">
<div class="container">
  <div style="page-break-after: always;">
  	<?php foreach($order_details as $o):?>
  	<div>
      <table>
        <tr>
    	       <td width="500px"><span style="font-size: 35px"><?php echo $o['store_name']; ?></span></td>
             <td style="padding-left: 20px"><span style="font-size: 25px; color:gray;">  Dispatch Note # <?php echo $o['order_id'] ?></span></td>
        </tr>
      </table>
    </div>
    <table class="table table-bordered" style="font-size: 12px">
      <thead>
        <tr>
          <td colspan="2">Order Details</td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="width: 50%;"><address>
            <strong><?php echo $o['store_name'] ?></strong><br />
            201 Del Monte Avenue, Masambong, Quezon City, Philippines
            </address>
            <b>Telephone: </b>+63 02 3741880<br />
            <b>E-Mail: </b> support@accenthub.com.ph<br />
            <b>Website: </b> <a><?php echo $o['store_url'] ?></a></td>
          <td style="width: 50%;"><b>Date Added: </b> <?php echo $o['date_added'] ?><br />
            <b>Order ID: </b> <?php echo $o['order_id'] ?><br />
            <b>Shipping Method</b> <?php echo $o['shipping_method'] ?><br />
        </tr>
      </tbody>
    </table>
    <table class="table table-bordered" style="font-size: 12px">
      <thead>
        <tr>
          <td style="width: 50%;"><b>Payment Address</b></td>
          <td style="width: 50%;"><b>Contact</b></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><address>
            <?php echo $o['shipping_firstname'].' '.$o['shipping_lastname'] ?><br>
            <?php echo $o['shipping_company'] ?><br>
            <?php echo $o['shipping_address_1'] ?><br>
            <?php echo $o['shipping_address_2'] ?><br>
            <?php echo $o['shipping_city'].' '.$o['shipping_postcode'] ?><br>
            <?php echo $o['shipping_zone'] ?><br>
            <?php echo $o['shipping_country'] ?><br>
            </address></td>
          <td style="vertical-align: middle;"><address>
             <?php echo $o['email'] ?><br>
             <?php echo $o['telephone'] ?><br>
            </address></td>
        </tr>
       <?php endforeach;?>
      </tbody>
    </table>
    <table class="table table-bordered" style="font-size: 12px">
      <thead>
        <tr>
          <td><b>Location</b></td>
          <td><b>Reference</b></td>
          <td><b>Product</b></td>
          <td><b>Prod Wgt</b></td>
          <td><b>Model</b></td>
          <td class="text-right"><b>Serial</b></td>
          <td class="text-right"><b>Quantity</b></td>
        </tr>
      </thead>
      <tbody>
     <?php 
     $total = 0;
     foreach($order_product as $p):?>
        <tr>
          <td><?php echo $p['location'] ?></td>
          <td><?php if($p['sku'] != '') echo 'SKU: '.$p['sku'] ?></td>
          <td><?php echo $p['name'] ?></td>
          <td><?php echo number_format($p['weight'],2).'kg' ?></td>
          <td><?php echo $p['model'] ?></td>
           <td class="text-right">
          <?php $order_product_serial = $in->order_product_serial($_GET['orderid'],$p['order_product_id']);
          $serial = '';
          foreach($order_product_serial as $s):  ?>
           <?php $serial.=$s['serial'].', ' ; ?>
           <?php endforeach; echo rtrim($serial, ", ");?>
           </td>
          <td class="text-right"><?php echo $p['quantity'] ?></td>
        </tr>
        <?php  endforeach;?>
      
      </tbody>
    </table>
  </div>
</div>
</body>
</html>