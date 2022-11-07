<?php 
session_start();
include "model/credit_card.php";
$model_CR= new credit_card();
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
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
</head>
<body>
<?php
  if(isset($_GET['orderId'])){
    $_SESSION['order_id_CR'] = (int) trim($_GET['orderId']);
  }
  if(isset($_GET['userId'])){
    $_SESSION['user_login'] = (int) trim($_GET['userId']);
  }
  if(isset($_GET['dw'])){
    $_SESSION['digitalwallet'] = (float) trim($_GET['dw']);
  }
    $cust_id = $_SESSION['user_login'];
    $order_id = $_SESSION['order_id_CR'];
    $_mid = '';
    $_paymenturl = '';
    $cert = '';
    $credit_url = '';
    if($_GET['live'] == '1'){
        $_mid = "0000002409195A0BDB10"; //<-- your merchant id live
        $_paymenturl = "https://ptiapps.paynamics.net/webpayment/Default.aspx"; //live
        $cert = "FD96B5543476FB7D904B8A21C3993531"; //<-- your merchant key live
        $credit_url ="https://ptiapps.paynamics.net/webpayment/Default.aspx";//live
        $resurl="https://pesoapp.ph/success.php";
        $noturl="https://pesoapp.ph/notify.php";
        $cancelurl="https://pesoapp.ph/cancel.php";
    }else{
        $_mid = "000000100719831E10B2"; //<-- your merchant id
        $_paymenturl = "https://testpti.payserv.net/webpayment/Default.aspx";
        $cert = "D6ED2D5E1EA172BF0272DF675301FCAA";
        $credit_url ="https://testpti.payserv.net/webpayment/Default.aspx";
        $resurl="https://pesoapp.ph/success.php";
        $noturl="https://pesoapp.ph/notify.php";
        $cancelurl="https://pesoapp.ph/cancel.php";
    }

    echo "</br></br>Request ID:".$order_id."</br>";


    $customer_info = $model_CR->getCustomer($cust_id);
    $order_info = $model_CR->order_details($order_id);

    $str = $customer_info['telephone'];
    $str2 = substr($str, 2);
    $str3="0".$str2;
    //customer info
    $fname = utf8_encode($customer_info['firstname']);
    $mname = "";
    $lname = utf8_encode($customer_info['lastname']);
    $email = utf8_encode($customer_info['email']);
    $phone = "";
    $mobile = $str3;
    $amount = 0;
    $amountTotal_new = 0;
    //address info
    $address1 = utf8_encode($order_info['shipping_address_1'] .' '.$order_info['shipping_address_2'].' '. $order_info['shipping_district']);//.'Ñ';
    $address2 = "";
    $city = utf8_encode($order_info['shipping_city']);
    $state = utf8_encode($order_info['shipping_region']);
    $country =utf8_encode($order_info['shipping_country']);
    $zip = utf8_encode($order_info['shipping_postcode']);


    //$shipping_rate=$shipping_rate_val;
    $shipping_title=$order_info['shipping_method'];
    $payment_method=$order_info['payment_method'];
    $shipping_rate=0;
    $shipping_insurance=0;
    $shipping_wallet=0;
    $Cash_Wallet=0;
    $Discount_Wallet=0;
    $Convenience_Fee=0;
    foreach($order_info['total'] as $total):
        if($total['code']=="shipping_fee"){
            $shipping_rate=$total['value'];
            //echo $total['value'];
        }elseif($total['title']=="Shipping Wallet"){
            $shipping_wallet=$total['value'];
        }elseif($total['title']=="Cash Wallet"){
           $Cash_Wallet=$total['value'];
        }elseif($total['title']=="Discount Wallet"){
            $Discount_Wallet=$total['value'];
        }elseif($total['title']=="Convenience Fee"){
            $Convenience_Fee=$total['value'];
        }elseif($total['code']=="insurance_fee"){
            $shipping_insurance=$total['value'];
        }
    endforeach;
    foreach($order_info['total'] as $totalval):
       if($totalval['title']=="Total"){
            $amount=$totalval['value'];
        }
    endforeach;
   // $amount+=$shipping_rate;    
   /* foreach ($order_info['products'] as $product):
        $total = $product['price']*0.028;
        $totaldeduc= $total+$product['price'];
        $totalamount =   $totaldeduc * $product['quantity'];
        $amount+=$totalamount;
    endforeach;*/
   /* if(isset($_SESSION['digitalwallet'])){
        $redeem_amount=$_SESSION['digitalwallet'];
        $amount=$amount-$redeem_amount;
    }
    if(isset($_SESSION['digitalwallet_cash'])){
        $redeem_amount_c=$_SESSION['digitalwallet_cash'];
        $amount=$amount-$redeem_amount_c;
    }*/
    $_requestid = $order_id; //<-- unique id transaction number
    $_ipaddress = "https://pesoapp.ph";
    $_noturl = $noturl; // url where response is posted //refer to sample notification receiver
    $_resurl =  $resurl; //url of merchant landing page
    $_cancelurl = $cancelurl;//"http://130.105.85.82:8081/paynamicsDemo/cancel.php"; //url of merchant landing page
    $_fname = $fname; // kindly set this to first name of the customer
    $_mname = $mname; // kindly set this to middle name of the customer
    $_lname = $lname; // kindly set this to last name of the customer
    $_addr1 = $address1; // kindly set this to address1 of the customer
    $_addr2 = $address2; // kindly set this to address2 of the customer
    $_city = $city; // kindly set this to city of the customer
    $_state = $state; // kindly set this to state of the customer
    $_country = $country; // kindly set this to country of the customer
    $_zip = $zip; // kindly set this to zip/postal of the customer
    $_sec3d = "try3d";
    $_trxtype = "sale"; 
    $_email = $email; // kindly set this to email of the customer
    $_phone = $phone; // kindly set this to phone number of the cutomer
    $_mobile = $mobile; // kindly set this to mobile number of the cutomer
    $_clientip = $_SERVER['REMOTE_ADDR'];
    $_amount = number_format(($amount), 2, '.', $thousands_sep = ''); // kindly set this to the total amount of the transaction
    $_currency = "PHP"; //PHP or USD
    $_descriptor = $payment_method." PESO STORE"; // descriptor note to appear on billing statement
    $_mlogourl = "https://pesoapp.ph/assets/peso-logo.png"; // merchant logo url
    $_mtacurl = "https://pesoapp.ph/terms_and_conditions.php"; // merchant terms and condition link
    $_pmethod ="";
   
    $xmlstr = "";
    $strxml = "";
    $strxml = $strxml . "<?xml version=\"1.0\" encoding=\"utf-8\" ?>";
    $strxml = $strxml . "<Request>";    
    $strxml = $strxml . "<orders>";
    $strxml = $strxml . "<items>";
    foreach ($order_info['products'] as $product) {
        $bg_shipinng= $product['price'];
        $total = round(($bg_shipinng/.972)-$bg_shipinng,2);
        $totaldeduc= $total+$bg_shipinng;
        $total_amountp=$totaldeduc*$product['quantity'];
        // echo "bg_shipinng: ".$bg_shipinng."</br>";
        echo "Item name: ".utf8_encode($product['name'])."</br>";
        echo "Quantity: ".$product['quantity']."</br>";
        echo "Amount: ".number_format(($totaldeduc), 2, '.', $thousands_sep = '')."</br></br>";
        $strxml = $strxml . "<Items><itemname>".utf8_encode($product['name'])."</itemname><quantity>".$product['quantity']."</quantity><amount>".number_format(($totaldeduc), 2, '.', $thousands_sep = '')."</amount></Items>";
        $amountTotal_new+=number_format(($total_amountp), 2, '.', $thousands_sep = '');
    }
    if($shipping_wallet==0){   
    $strxml = $strxml . "<Items><itemname>".$shipping_title."</itemname><quantity>1</quantity><amount>".number_format(($shipping_rate), 2, '.', $thousands_sep = '')."</amount></Items>";
        echo "Item name: ".$shipping_title."</br>";
        echo"Quantity: 1"."</br>";
        echo"Amount: ".number_format(($shipping_rate), 2, '.', $thousands_sep = '')."</br></br>";
         $amountTotal_new+=number_format(($shipping_rate), 2, '.', $thousands_sep = '');
    }
    if($shipping_insurance!=0){   
    $strxml = $strxml . "<Items><itemname>Shipping Insurance</itemname><quantity>1</quantity><amount>".number_format(($shipping_insurance), 2, '.', $thousands_sep = '')."</amount></Items>";
        echo "Item name: Shipping Insurance</br>";
        echo"Quantity: 1"."</br>";
        echo"Amount: ".number_format(($shipping_insurance), 2, '.', $thousands_sep = '')."</br></br>";
         $amountTotal_new+=number_format(($shipping_insurance), 2, '.', $thousands_sep = '');
    }
   /* if(isset($_SESSION['digitalwallet'])){
        if($_SESSION['digitalwallet'] > 0){
            $redeem_amount=$_SESSION['digitalwallet'];
            $strxml = $strxml . "<Items><itemname>Digital Wallet</itemname><quantity>1</quantity><amount>".number_format(-($redeem_amount), 2, '.', $thousands_sep = '')."</amount></Items>";
            echo "Item name: Digital Wallet</br>";
            echo"Quantity: 1</br>";
            echo"Amount: ".number_format(-($redeem_amount), 2, '.', $thousands_sep = '')."</br></br>";
             $amountTotal_new+=number_format(-($redeem_amount), 2, '.', $thousands_sep = '');
        }
    }
    if(isset($_SESSION['digitalwallet_cash'])){
        if($_SESSION['digitalwallet_cash'] > 0){
            $redeem_amount_c=$_SESSION['digitalwallet_cash'];
            $strxml = $strxml . "<Items><itemname>Cash Wallet</itemname><quantity>1</quantity><amount>".number_format(-($redeem_amount_c), 2, '.', $thousands_sep = '')."</amount></Items>";
            echo "Item name: Cash Wallet</br>";
            echo"Quantity: 1</br>";
            echo"Amount: ".number_format(-($redeem_amount_c), 2, '.', $thousands_sep = '')."</br></br>";
           $amountTotal_new+=number_format(-($redeem_amount_c), 2, '.', $thousands_sep = '');
        }
    }*/
    if($Cash_Wallet!=0){   
        $strxml = $strxml . "<Items><itemname>Cash Wallet</itemname><quantity>1</quantity><amount>".number_format(($Cash_Wallet), 2, '.', $thousands_sep = '')."</amount></Items>";
        echo "Item name: Cash Wallet</br>";
        echo"Quantity: 1"."</br>";
        echo"Amount: ".number_format(($Cash_Wallet), 2, '.', $thousands_sep = '')."</br></br>";
         $amountTotal_new+=number_format(($Cash_Wallet), 2, '.', $thousands_sep = '');
    }
    if($Discount_Wallet!=0){   
       $strxml = $strxml . "<Items><itemname>Digital Wallet</itemname><quantity>1</quantity><amount>".number_format(($Discount_Wallet), 2, '.', $thousands_sep = '')."</amount></Items>";
        echo "Item name: Digital Wallet</br>";
        echo"Quantity: 1"."</br>";
        echo"Amount: ".number_format(($Discount_Wallet), 2, '.', $thousands_sep = '')."</br></br>";
         $amountTotal_new+=number_format(($Discount_Wallet), 2, '.', $thousands_sep = '');
    }
  
        
           
    $strxml = $strxml . "</items>";
    $forSign = $_mid . $_requestid . $_ipaddress . $_noturl . $_resurl . $_fname . $_lname . $_mname . $_addr1 . $_addr2 . $_city . $_state . $_country . $_zip . $_email . $_phone . $_clientip . number_format(($amountTotal_new), 2, '.', $thousands_sep = '') . $_currency . $_sec3d;
   // echo $forSign."</br></br></br>";
    $_sign = hash("sha512", $forSign . $cert);
    $strxml = $strxml . "</orders>";
    $strxml = $strxml . "<mid>" . $_mid . "</mid>";
    $strxml = $strxml . "<request_id>" . $_requestid . "</request_id>";
    $strxml = $strxml . "<ip_address>" . $_ipaddress . "</ip_address>";
    $strxml = $strxml . "<notification_url>" . $_noturl . "</notification_url>";
    $strxml = $strxml . "<response_url>" . $_resurl . "</response_url>";
    $strxml = $strxml . "<cancel_url>" . $_cancelurl . "</cancel_url>";
    $strxml = $strxml . "<mtac_url>" . $_mtacurl . "</mtac_url>"; // pls set this to the url where your terms and conditions are hosted
    $strxml = $strxml . "<descriptor_note>" . $_descriptor . "</descriptor_note>"; // pls set this to the descriptor of the merchant
    $strxml = $strxml . "<fname>" . $_fname . "</fname>";
    $strxml = $strxml . "<lname>" . $_lname . "</lname>";
    $strxml = $strxml . "<mname>" . $_mname . "</mname>";
    $strxml = $strxml . "<address1>" . $_addr1 . "</address1>";
    $strxml = $strxml . "<address2>" . $_addr2 . "</address2>";
    $strxml = $strxml . "<city>" . $_city . "</city>";
    $strxml = $strxml . "<state>" . $_state . "</state>";
    $strxml = $strxml . "<country>" . $_country . "</country>";
    $strxml = $strxml . "<zip>" . $_zip . "</zip>";
    $strxml = $strxml . "<secure3d>" . $_sec3d . "</secure3d>";
    $strxml = $strxml . "<trxtype>" . $_trxtype . "</trxtype>";
    $strxml = $strxml . "<email>" . $_email . "</email>";
    $strxml = $strxml . "<phone>" . $_phone . "</phone>";
    $strxml = $strxml . "<mobile>" . $_mobile . "</mobile>";
    $strxml = $strxml . "<client_ip>" . $_clientip . "</client_ip>";
    $strxml = $strxml . "<amount>" . number_format(($amountTotal_new), 2, '.', $thousands_sep = '') . "</amount>";
    $strxml = $strxml . "<currency>" . $_currency . "</currency>";
    $strxml = $strxml . "<mlogo_url>" . $_mlogourl . "</mlogo_url>"; // pls set this to the url where your logo is hosted
    $strxml = $strxml . "<pmethod>" . $_pmethod . "</pmethod>";
    $strxml = $strxml . "<signature>" . $_sign . "</signature>";
    $strxml = $strxml . "</Request>";
    $b64string = base64_encode($strxml);
    echo "<h4>Total Amount: ".number_format(($amountTotal_new), 2, '.', $thousands_sep = '')."</h4></br>";

  
   
?>

<div class="loader"></div>
<form name="myform" action="<?php echo $credit_url;?>" method="post">
<input type="hidden" name="paymentrequest" value="<?php echo $b64string;?>" />
</form>

<script type="text/javascript">
 document.myform.submit();
 </script>
</body>
</html>

