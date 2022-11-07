<?php
include_once "model/GenerateQr.php";

$order_id = $_GET['order_id'] ?? null;

$model = new GenerateQr();

$qrCode = str_replace(' ', '%20',  $model->generate($order_id)->qrph);
$qrdata=$model->getp2mData($order_id);
 $url="fgivesCallback.php?merc_token=".$qrdata['merc_token'].'&t='.uniqid();
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
              <img src="assets/img/logo-clean.png" alt="logo">
            </div>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-12" id="imgParent">
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-12">
            <div class="rounded-3 mx-3">
              <h2 class="text-center mb-4">Complete your payment</h2>
              <div class="text-danger d-flex justify-content-between mx-sm-5 my-3">
                <i class="fa-solid fa-sack-dollar fa-4x"></i>
                <i class="fa-solid fa-arrow-right-long fa-4x"></i>
                <i class="fa-solid fa-mobile-screen fa-4x"></i>
              </div>
              <div class="my-4">
                <p><i class="fa-solid fa-qrcode fa-xl"></i> <?php echo $model->isMobile() ? '<strong>Download</strong>  the QR <a href="#" id="download">here</a>' : '<strong>Scan</strong> the QR Code' ?></p>
                <!--<p><i class="fa-solid fa-building-lock fa-xl"></i> <strong>Validate</strong> Lorem ipsum dolor sit amet consectetur.</p>-->
                <!--<p><i class="fa-solid fa-square-check fa-xl"></i> <strong>Complete</strong> Lorem ipsum dolor sit amet consectetur, adipisicing elit.</p>-->
              </div>
             <d class="d-grid">
              <a href="<?php echo $url;?>" class="btn btn-primary">Done </a>
             </d>
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
<script>
  let link;
  $(document).ready(() => {
    link = `https://chart.googleapis.com/chart?cht=qr&chs=300x300&choe=UTF-8&chl=<?php echo $qrCode; ?>`;
    $('#imgParent').append(`<img src=${link} class='mx-auto d-block'>`);
  });

  $('#download').click('click', () => {
    var imagePath = link;
    var fileName = "qr.png";
    saveAs(imagePath, fileName);
  })
</script>

</html>