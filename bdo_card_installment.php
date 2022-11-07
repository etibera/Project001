<?php 

	include "model/credit_card.php";
	$model_CR= new credit_card();
if(!$model_CR->isMobileDevice()):
?>
<?php 
include "common/header.php";
?>
<?php endif;?>
<style>
.loader {
  margin:auto;
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #f04141;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
  position: absolute;
  left: 0;
  right:0;
  top: 0;
  bottom: 0;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

<?php
  if(isset($_GET['orderId'])){
    $_SESSION['order_id_maxx_p'] = (int) trim($_GET['orderId']);
  }
  if(isset($_GET['userId'])){
    $_SESSION['user_login'] = (int) trim($_GET['userId']);
  }
	$cust_id = $_SESSION['user_login'];
	$order_id = $_SESSION['order_id_maxx_p'];
  $order_info = $model_CR->order_details($order_id);
  $amount = 0;     

    if($_GET['live'] == '1'){
       
        $SC_MC ='SC000169';//live
        $live_val=1;//live
        $SC_SUCCESSURL="https://pesoapp.ph/success.php";
        $SC_FAILURL="https://pesoapp.ph/SC_FAILURL.php";
        $SC_CANCELURL="https://pesoapp.ph/payment_cancel.php";
    }else{
        $SC_MC ='T0000175';//sanbox
        $live_val=0;//sanbox
        $SC_SUCCESSURL="http://localhost/peso-web-new/success.php";
        $SC_FAILURL="http://localhost/peso-web-new/SC_FAILURL.php";
        $SC_CANCELURL="http://localhost/peso-web-new/payment_cancel.php"; 
       
    }
   
    $requestid = $order_id;

    $shipping_title=$order_info['shipping_method'];
    $payment_method=$order_info['payment_method'];
    $shipping_rate=0;
     foreach($order_info['total'] as $totalval):
       if($totalval['title']=="Total"){
            $amount=$totalval['value'];
        }
    endforeach;
    // foreach($order_info['total'] as $total):
    //     if($total['title']==$shipping_title){
    //         $shipping_rate=$total['value'];
    //     }
    // endforeach;
    // $amount+=$shipping_rate;  
   
    // foreach ($order_info['products'] as $product):
    //     $total = $product['price']*0.015;
    //     $totaldeduc = $total+$product['price'];
    //     $totalamount =   $totaldeduc * $product['quantity'];
    //     $amount+=$totalamount;
    // endforeach;
    // if(isset($_SESSION['digitalwallet'])){
    //     $redeem_amount=$_SESSION['digitalwallet'];
    //     $amount=$amount-$redeem_amount;
    // }
    // if(isset($_SESSION['digitalwallet_cash'])){
    //     $redeem_amountc=$_SESSION['digitalwallet_cash'];
    //     $amount=$amount-$redeem_amountc;
    // }
   
    $ch = curl_init('https://secure.maxxpayment.com/api/mp/?live='.$live_val.'&SC_MC='. $SC_MC.'&SC_AMOUNT='.$amount.'&SC_REF='.$requestid.'&SC_SUCCESSURL='.$SC_SUCCESSURL.'&SC_FAILURL='.$SC_FAILURL.'&SC_CANCELURL='.$SC_CANCELURL.'&SC_FREDIRECT=1');
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($ch);
    $Linkurl =trim($json, '1|');

    var_dump($amount);
   
?>
<div class="loader"></div>
<script type="text/javascript">
     location.replace("<?php echo $Linkurl;?>");
    // alert(<?php echo $Linkurl;?>);
</script>
<?php 
include "common/footer.php";
?>

