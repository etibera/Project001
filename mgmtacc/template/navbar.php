<?php require_once("includes/init.php"); 
 if(!$session->is_signed_in()){redirect("index");}
  require_once "model/message.php";
  $msg=new message;
  $unreads=$msg->GetTotalUnreadsCA(0);
  $count = $unreads['unreads'] > 0 ? '<span class="badge">'.$unreads['unreads'].'</span>':'';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PESO ADMIN</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="../assets/css/styles.css">
<script src="../js/jquery-ui.min.js"></script>
<script src="../js/paging.js"></script>
<link rel="stylesheet" type="text/css" href="../fonts/flaticons/flaticon.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" rel="stylesheet">
<link rel="icon" href="https://mb.pesoapp.ph/assets/icon/favicon.ico" type="image/x-icon" />
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<link rel="stylesheet" href="../assets/css/jquery-ui.css">
<script src="../js/jquery-autocomplete.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="https://cdn.tiny.cloud/1/4ht0sgq3apbnbyqvf9h73ef3o0i02niv8smfw8qympkohuyn/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<!-- forsummernote -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<!-- Datatables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

  <!-- Button Datatables -->
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

<style> 
  .notification{
    position: relative;
    display: inline-block;
  }
  .notification .badge {
  position: absolute !important;
  top: -10px !important;
  right: -10px !important;
  padding: 5px 10px !important;
  border-radius: 100% !important;
  background: red !important;
  color: white !important;
}
.loading {
    width: 100%;
    height: 100%;
    text-align: center;
    background-color: rgba(255, 255, 255, 0.7);
    position: absolute;
    z-index: 99999;
    top: 0;
    left: 0;
  }

  .load {
    position: relative;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }

  .load .dot:nth-last-child(1) {
    animation: loadingC 0.6s 0.1s linear infinite;
  }

  .load .dot:nth-last-child(2) {
    animation: loadingC 0.6s 0.2s linear infinite;
  }

  .load .dot:nth-last-child(3) {
    animation: loadingC 0.6s 0.3s linear infinite;
  }

  .dot {
    display: inline-block;
    width: 15px;
    height: 15px;
    border-radius: 15px;
    background-color: #4b9cdb;
  }

  @keyframes loadingC {
    0 {
      transform: translate(0, 0);
    }

    50% {
      transform: translate(0, 15px);
    }

    100% {
      transform: translate(0, 0);
    }
  }
</style>
</head>
<body>
  <nav class="navbar navbar-default" style="background: #fff">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    <a class="navbar-brand" rel="home" href="home.php" >
        <img 
             src="../assets/peso-logo.png">
    </a>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="home.php">Dashboard</a></li>
        <li class="dropdown">
          <a href="#" title="File Maintenance" class="dropdown-toggle" data-toggle="dropdown" style=" text-align: center;">File Maintenance</a>
          <ul class="dropdown-menu dropdown-menu-center" >  
            <li><a href="permission.php">User Administration</a></li>    
            <li><a href="homecategory.php">Home Page Category</a></li>    
            <li><a href="latestpromo.php">Latest Promo</a></li>    
            <li><a href="delivery_charge.php">Manage Delivery Charge</a></li>    
            <li><a href="category_list.php">Categories</a></li> 
            <li><a href="banner_list.php">Banners</a></li>
            <li><a href="productbrand.php">Product Brand</a></li>
            <li><a href="customer_list.php?type=Verified">Customers</a></li>                
            <li><a href="voucher_list.php">Gift Vouchers</a></li>     
            <li><a href="voucher_theme_list.php">Voucher voucher_theme_list</a></li>     
            <li><a href="coupon_list.php">Coupons</a></li>     
            <li><a href="country.php">Country List</a></li>     
            <li><a href="reviews.php">Manage Reviews</a></li>     
            <li><a href="specification.php">Manage Attribute</a></li>
            <li><a href="return_status.php">Return Status List</a></li>     
            <li><a href="return_action.php">Return Action List</a></li>     
            <li><a href="selected_currency.php">selected Currency</a></li>     
            <li><a href="manage_store.php">Manage Store</a></li>     
            <li><a href="manage_order_status.php">Manage Order Status</a></li>     
            <li><a href="popup_ads.php">Manage Popup Ads</a></li>     
            <li><a href="manageWallet.php">Manage Wallet</a></li>     
            <li><a href="sms_admin.php">Send SMS to Customer</a></li>     
            <li><a href="AdminSendEmail.php">Send Email to Customer</a></li>     
          </ul>
        </li>
        
        <li class="dropdown">
          <li class="dropdown">
            <a href="#" title="Products" class="dropdown-toggle" data-toggle="dropdown" style=" text-align: center;">Products</a>
            <ul class="dropdown-menu dropdown-menu-center" >            
              <li><a href="productlist.php">Products List</a></li>              
              <li><a href="AdminProductPending.php">Pending Products</a></li>             
            </ul>
        </li>
        <li class="dropdown">
          <li class="dropdown">
            <a href="#" title="Products" class="dropdown-toggle" data-toggle="dropdown" style=" text-align: center;">Finance</a>
            <ul class="dropdown-menu dropdown-menu-center" >            
              <li><a href="SellerPendingPayables.php">Pending Payables</a></li>              
              <li><a href="Pendingreceivables.php">Pending receivables</a></li>             
            </ul>
        </li>
        <li><a href="return_list.php">Returns</a></li>
        <li><a href="cash_out_request.php">Cash Out Request</a></li>
        <li class="dropdown">
          <a href="#" title="Reports" class="dropdown-toggle" data-toggle="dropdown" style=" text-align: center;">Reports</a>
          <ul class="dropdown-menu dropdown-menu-center" >            
            <li><a href="sales_report_new.php">Sales Reports</a></li>             
            <li><a href="ppp_report.php">PESO Partner Program Report</a></li>
            <li><a href="customer_activity.php">Customer Activity Log</a></li>
            <li><a href="product_purchased_report.php">Products Purchased Report</a></li>
            <li><a href="product_viewed_list.php">Products Viewed Report</a></li>
            <li><a href="customer.php">Registration Report</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" title="Reports" class="dropdown-toggle" data-toggle="dropdown" style=" text-align: center;">China Brands</a>
          <ul class="dropdown-menu dropdown-menu-center" >            
            <li><a href="china_products.php">Download China Products</a></li>             
            <li><a href="china_orders.php">China Orders</a></li>
            <li><a href="china_pendig_orders.php">China Pending Orders</a></li>
            <li><a href="batch_china_order.php">Batch Orders</a></li>
          </ul>
        </li>
       <li class="dropdown">
        <a href="#" title="Reports" class="dropdown-toggle" data-toggle="dropdown" style=" text-align: center;">Banggoods</a>
        <ul class="dropdown-menu dropdown-menu-center" >            
          <li><a href="bg_category_list.php">Banggoods Category</a></li>        
          <li><a href="bg_products.php">Banggoods Products</a></li>
          <li><a href="https://www.banggood.com/index.php?com=account&t=apiOrderList" target="_blank">Verify Orders</a></li>
          <li><a href="https://www.banggood.com/index.php?com=account&t=ordersList&bid=41617" target="_blank">Pay Orders</a></li>
        </ul>
      </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="message_admin.php"><div class="btn btn-warning notification">Messages<?php echo $count?></div></a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>