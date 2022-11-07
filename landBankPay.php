<?php 
  require_once "model/credit_card.php";
  require_once "include/landBankApi.php";
  require_once "model/checkoutLatest.php";  
  $checkout = new CheckoutLatest();
  $model_CR= new credit_card();
  $LandBank=new LandBank;
  $credentials =$LandBank->getLandBankUAT();
  $LanBankDomain =$LandBank->LanBankDomain();
  $LandBankUrl = $LanBankDomain['sandbox'];
  
  if(isset($_GET['orderId'])){
    $order_id = trim($_GET['orderId']);
  }else{
    $order_id = base64_decode($_GET['token']);
  }
  if(isset($_GET['userId'])){
    $cust_id =  (int) trim($_GET['userId']);
  }else{
    $cust_id = (int) trim($_GET['userId']);
  }
    
  $order_info = $model_CR->order_details($order_id);
  $landbankacc=$checkout->Getlandbankacc($cust_id);
  $amountval = 0;
  $shipping_title=$order_info['shipping_method'];
  $payment_method=$order_info['payment_method'];
  $shipping_rate=0;
  foreach($order_info['total'] as $totalval):
    if($totalval['title']=="Total"){
        $amountval=$totalval['value'];
    }
  endforeach;  
  //api get lanbank token
  $token="";
  $tokenRequestData = array(
      'clientId' => $credentials['ClientID'],
      'secret' => $credentials['Secret'],
  );
  $lburltoken = curl_init($LandBankUrl.'/api/Ecommerce/Token');
  curl_setopt($lburltoken, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($lburltoken, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Identity: 6d5a74c940694d668aaaae6b402b4ee71cca906bc6ce48c39002fae9d72ae384-IDTK45E291-112701'
  ));
  curl_setopt($lburltoken, CURLOPT_POST, 1);
  curl_setopt($lburltoken, CURLOPT_POSTFIELDS,json_encode($tokenRequestData));
  curl_setopt($lburltoken, CURLOPT_FOLLOWLOCATION, 1);
  $datalburltoken = curl_exec($lburltoken);
  curl_close($lburltoken);
  $Responsedatatoken = json_decode($datalburltoken);
  $token=$Responsedatatoken->body->token;

  //api place order
  $MobileNumber=$landbankacc['telephone'];
  $amount=floatval($amountval);
  $orderRequestData = array(
    'clientId' => $credentials['ClientID'],
    'secret' => $credentials['Secret'],
    'customerMobileNumber' => $MobileNumber,
    'amount' => $amount,
    'requestOrderNumber1' =>  $order_id,
    'requestOrderNumber2' =>"",
    'requestOrderNumber3'=> ""
  ); 
  $lburl = curl_init($LandBankUrl.'/api/PaymentGateway/Order');
  curl_setopt($lburl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($lburl, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token
  ));
  curl_setopt($lburl, CURLOPT_POST, 1);
  curl_setopt($lburl, CURLOPT_POSTFIELDS,json_encode($orderRequestData));
  curl_setopt($lburl, CURLOPT_FOLLOWLOCATION, 1);
  $datalburl = curl_exec($lburl);
  curl_close($lburl);
  $Responsedata = json_decode($datalburl);
    
?>

<style>
  .logo-bg {
    background: red;
    border-radius: 50%;
    width: 200px;
    height: 200px;
  }

  .logo-bg img {
    object-fit: contain;
    width: 180px;
    height: 180px;
    margin: 0 0 5px 1px;
  }
</style>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="icon" href="https://mb.pesoapp.ph/assets/icon/favicon.ico" type="image/x-icon" />
  <title>100% Filipino Owned Electronic Store App I Full Warranty Shopping</title>

</head>

<body class="d-flex justify-content-center align-items-center">
  <div class="container">
    <div class="row justify-content-center my-3 mx-3">
      <div class="col-xxl-5 col-xl-6 col-lg-8 col-md-10 col-sm-10 rounded-3 py-3 shadow-lg">
        <div class="row justify-content-center">
          <div class="col-12">
            <div class="logo-bg mx-auto d-flex justify-content-center align-items-center">
               <a  href="home.php"><img src="assets/img/logo-clean.png" alt="logo"></a>
            </div>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-12" id="imgParent">
           <div class="container">               
                <div class="card">
                    <div class="card-content">
                         <div class="text-center py-5">
                          <?php  if($Responsedata->code=="200"){
                            $res=$checkout->saveLandBankResponse($Responsedata->body,$order_id);
                            if($res['code']==200){ ?>
                               <?php if($Responsedata->body->code=="00"){ ?> 
                                <i class="fas fa-check-circle text-success" style="font-size: 70px;"></i>
                                <h1 class="text-success text-center" style="font-size: 50px">
                                  <?php echo $Responsedata->body->remarks; ?>                    
                                </h1> 
                                <script type="text/javascript">
                                 location.replace("<?php echo $Responsedata->body->url;?>");
                                </script>
                              <?php }else{  ?>
                                <i class="fas fa-times-circle text-danger" style="font-size: 70px;"></i>
                                <h1 class="text-danger text-center" style="font-size: 50px"> <?php echo $Responsedata->body->remarks; ?>
                               <?php }?> 
                            <?php }else{ ?>
                               <i class="fas fa-times-circle text-danger" style="font-size: 70px;"></i>
                               <h1 class="text-danger text-center" style="font-size: 50px"> <?php echo $res['data']; ?>
                            <?php }?>        
                          <?php }else{ ?>
                             <i class="fas fa-times-circle text-danger" style="font-size: 70px;"></i>
                            <h1 class="text-danger text-center" style="font-size: 50px"> <?php echo $Responsedata->description; ?></h1>
                          <?php } ?>   
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js" integrity="sha512-n/4gHW3atM3QqRcbCn6ewmpxcLAHGaDjpEBu4xZd47N0W2oQ+6q7oc3PXstrJYXcbNU1OHdQ1T7pAP+gi5Yu8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.0/FileSaver.min.js" integrity="sha512-csNcFYJniKjJxRWRV1R7fvnXrycHP6qDR21mgz1ZP55xY5d+aHLfo9/FcGDQLfn2IfngbAHd8LdfsagcCqgTcQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</html>