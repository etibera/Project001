<?php
    require_once("include/init.php");
    require_once "model/Review.php";
    if($session->is_signed_in()){
        $is_log=1;
        $userid=$_SESSION['user_login'];
        $mobile_stats=1;//$_SESSION['mobile_statuscode'];
        $review = new Review();
        $reviewCount = $review->getReviewCount("all");
    }else{
        $is_log=0;
        $userid='_null';
        $mobile_stats='_null';
        $reviewCount = 0;
    }
    $pesomall=0;
    if(isset($_GET['active'])){
      $pesomall=1;
    }
    if(isset($_GET['pesomall'])){
      $pesomall=$_GET['pesomall'];
    }
    if($is_log==1){
        require_once "model/message.php";
        require_once "model/count_cart.php";
        $msg=new message;
        $CountCart=new CountCart;
        $totalCVal=$CountCart->GetTotaCart($userid);
        $Getdefaultaddress=$CountCart->Getdefaultaddress($userid);
        $unreads=$msg->GetTotalUnreads($userid);
        $unreads2=$msg->GetTotalUnreadsCA($userid);
    }else{
         $totalCVal=0; 
         $unreads['unreads']=0;
         $unreads2['unreads']=0;
    }
    $CounttotalCVal = $totalCVal > 0 ? $totalCVal:'';
    $count = $unreads['unreads'] + $unreads2['unreads']  > 0 ? $unreads['unreads'] + $unreads2['unreads']:'';
    $useragent=$_SERVER['HTTP_USER_AGENT'];
    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
    {        $is_mobile=true; 
    include("include/webapp-redirect.php");
    
        /*if(isset($_GET['product_id'])){
            $urlapp="https://mb.pesoapp.ph/product/product/".$_GET['product_id']."/reg";
            header("Location:".$urlapp );
            
        }else if(isset($_GET['promo_id'])){
            require_once 'model/home_new.php'; 
            $model_shareSH=new home_new();
            $SHTitle="";
            $get_latest_SHid=$model_shareSH->get_latest_promonew(1); 
            foreach ($get_latest_SHid as $listSH) :
                if($listSH['id']==$_GET['promo_id']){
                     $SHTitle=urlencode($listSH['title']);
                }
            
            endforeach;
            $urlapp="https://mb.pesoapp.ph/promo-products/".$_GET['promo_id']."/".$SHTitle;
           // print_r($urlapp);
            header("Location:".$urlapp );
        }else{
            header("Location: https://mb.pesoapp.ph/tabs/home");
        }*/
        
       
    }else{$is_mobile=false;}

  //for google account
  require 'composer/vendor/autoload.php';
  $google_client= new Google_Client();
  $google_client->setClientId('1064903567645-977t3me9dbk0bnqhttkpa629scq7b0gh.apps.googleusercontent.com');
  $google_client->setClientSecret('8b-GG1A0vO6I_B4W1hGpWmkD');
  $google_client->setRedirectUri('https://pesoapp.ph/googleAcc.php');
  $google_client->addScope('email');
  $google_client->addScope('profile');
  $login_buttonGoogle = '';
  //end googel account implementationl

  
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="./slick/slick.css">
<link rel="stylesheet" type="text/css" href="./slick/slick-theme.css">
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.6/css/swiper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.6/js/swiper.min.js"></script>

<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="facebook-domain-verification" content="3rnq697iggh9lhnps1vcq70lgqimpf" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500&display=swap" rel="stylesheet">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>
<script type="text/javascript" src='JavaScriptSpellCheck/include.js' ></script>
<script src="js/paging.js"></script>
<script src="./slick/slick.js" type="text/javascript" charset="utf-8"></script>
<script type='text/javascript' src='JavaScriptSpellCheck/extensions/fancybox/jquery.fancybox-1.3.4.pack.js' ></script>
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

<title>100% Filipino Owned Electronic Store App I Full Warranty Shopping</title>

<?php 
  $get_product_id_val=0;
  $get_product_id =isset($_GET['product_id'])?$_GET['product_id']:0;
  if($get_product_id!="0"){
    $charSetArray = explode("_fb_cust_id_", $_GET['product_id']);
    $count_charSetArray =count($charSetArray);
    if($count_charSetArray==1){
      $get_product_id_val=$_GET['product_id'];
    }else{
      $get_product_id_val=$charSetArray[0];
      $_SESSION['product_id_seller_session'] =$charSetArray[1];  
      $_SESSION['product_id_fbshare_session'] = $charSetArray[0];
    }
  }
?>

<?php if( $get_product_id!="0"){ 
    include "model/product.php";
    $model_product = new product();
    $productdesc25 = $model_product->getproduct2($get_product_id_val);
    $getimg25 =str_replace(" ", "%20","img/".$productdesc25['image']);
    $name_fb = $productdesc25['name'];
    $price_fb =  number_format($productdesc25['price'],2);
  ?>
  

<meta property="og:type" content="product"/>
  <meta property="og:title" content=" <?php echo $name_fb; ?> | <?php echo $price_fb; ?>"/>
  <meta property="og:description" content="100% Filipino Owned Electronic Store App I Full Warranty Shopping" />
  <meta property="og:url" content="https://pesoapp.ph/product.php?product_id=<?php echo $get_product_id; ?>"/>
  <meta property="og:image" content="<?php echo $getimg25; ?>" />
  <meta property="og:image:width" content="400">
<meta property="og:image:height" content="400">

 <?php }else if(isset($_GET['promo_id'])){?>
 <?php require_once 'model/home_new.php'; 
    $model_sharePR=new home_new();
    $PRTitle="";
    $PRImg="";
    $get_latest_PRid=$model_sharePR->get_latest_promonew(1); 
    foreach ($get_latest_PRid as $listPR) :
        if($listPR['id']==$_GET['promo_id']){
             $PRTitle=$listPR['title'];
             $PRImg=$listPR['thumb'];
        }
    endforeach;
 ?>
   
  <meta property="og:type" content="Promo"/>
  <meta property="og:title" content=" <?php echo $PRTitle;?> "/>
  <meta property="og:description" content="<?php echo $PRTitle; ?>" />
  <meta property="og:url" content="https://pesoapp.ph/product_category_new.php?promo_id=<?php echo $_GET['promo_id']; ?>"/>
  <meta property="og:image" content="<?php echo $PRImg; ?>" />
  <meta property="og:image:width" content="400">
    <meta property="og:image:height" content="400">
    
<?php }else{?>
  <meta property="og:image" content=".//assets/peso_header.png">
  <meta property="og:image:type" content="image/png">
  <meta property="og:image:width" content="924">
  <meta property="og:image:height" content="924">
<?php }?>


<link rel="icon" href="https://mb.pesoapp.ph/assets/icon/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="JavaScriptSpellCheck/extensions/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
<!--<link rel="stylesheet" href="./assets/css/newstyles.css">-->
<link rel="stylesheet" href="./assets/css/styles.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="./assets/css/fontawesome-pro/fontawesome.css">
<link rel="stylesheet" type="text/css" href="fonts/flaticons/flaticon.css">
</head>
<style>
  .bootbox-close-button{
    border: none;
    font-size: 25px;
    float: right;
    background: #fff;
  }
  #subhead {background: #4B6ED6}
  #logo-head{position: relative;height: 80px;top: -3px;}


#btn_head_search{z-index: 10;margin-left: -40px;background: #fff;border: none;}
#row-home{margin-left: -70px;}
.containerH{ margin-top: 130px; }
.closeBtn:focus {
  outline: none;
}
.close{
  color: gray;
}
.wrapper {
    position:relative;
    margin:0 auto;
    overflow:hidden;
  padding:5px;
    height:50px;
}
.list {
    position:absolute;
    left:0px;
    top:0px;
    min-width:3500px;
    
    margin-top:0px;
}
.list li{
  display:table-cell;
    position:relative;
    text-align:center;
    cursor:grab;
    cursor:-webkit-grab;
    color:#efefef;
    vertical-align:middle;
}

.scroller {
  text-align:center;
  cursor:pointer;
  display:none;
  padding:7px;
  padding-top:11px;
  white-space:no-wrap;
  vertical-align:middle;
  background-color:#fff;
}

.scroller-right{
  float:right;
}

.scroller-left {
  float:left;
}

body{
  font-family: 'Montserrat', sans-serif !important;
}
</style>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '782967435543539');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=782967435543539&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
<body style="font-family: 'Montserrat', sans-serif !important; background: #E9E9E9;">
  <nav class="navbar navbar-expand-sm fixed-top mb-xl-5" id="subhead" >
    <div class="container-fluid">
      <div class="col-sm-2" class="navbar-brand">   
        <a  href="home.php"><img src="assets/NEW PESO GADGET MALL 1 - WHITE.png" id="logo-head"></a>  
      </div>
      <div class="col-sm-10">
        <div class="row">
          <div class="col-sm-5">
          <!-- for local global  -->          
          </div>
          <div class="col-sm-7">
            <ul class="nav float-end">
              <li class="nav-item">
                <a href="home.php"  class="nav-link text-light">Home</a>
              </li>
              <li class="nav-item dropdown ">
                <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Account
                </a>
                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                  <li><a href="account.php" class="dropdown-item" >Account</a></li>                    
                  <li><a href="manage_homepage.php" class="dropdown-item">Manage Home page</a></li>
                  <li><a href="PESO_Partner_Program.php" class="dropdown-item">PESO Partner Program</a></li>
                  <li><a href="order_history.php" class="dropdown-item">Order History</a></li>
                  <li><a href="return_history.php" class="dropdown-item">Return History</a></li>
                  <li><a href="generate.php"class="dropdown-item">Invite a Friend</a></li>
                  <li><a href="invitees.php" class="dropdown-item">Invites</a></li>
                  <li><a href="change_pass.php" class="dropdown-item">Change Password</a></li>
                  <li><a href="logout.php" class="dropdown-item">Logout</a></li>
                </ul>
              </li>
              <li class="nav-item">
               <div class="position-relative">
                	<a href="order_history.php" class="nav-link text-light">Orders</a>
                	<span class="position-absolute start-100 translate-middle badge rounded-pill bg-danger" style="top: 10px"><?php echo intval($reviewCount) > 0 ? $reviewCount : "" ?></span>
                </div> 
              </li>
              <li class="nav-item dropdown ">
                <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDarkDropdownMenuLinkW" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Wallet
                </a>
                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLinkW">  
                  <li><a href="discount_wallet.php" class="dropdown-item">Discount Wallet</a></li>
                  <li><a href="cash_wallet.php" class="dropdown-item">Cash Wallet</a></li>
                  <li><a href="ShippingWallet.php" class="dropdown-item">Shipping Wallet</a></li>
                </ul>
              </li>
              <li class="nav-item">
                <?php if ($mobile_stats) { ?>
                   <a href="wishlist.php" class="nav-link text-light">Wishlist</a>   
                <?php }else{ ?>
                   <a  href="reg_activatemobile.php?RegIdVal=<?php echo $userid;?>" class="nav-link text-light">Wishlist</a>  
                <?php }?>   
              </li>
            </ul>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-9">
            <div class="input-group mt-1 ">
              <input type="search" class="form-control rounded-pill" id="search_val_input_h"  value="<?php if(isset($_GET['searchvalue_h'])){ echo str_replace("_20"," ",$_GET['searchvalue_h']);} ?>" placeholder="Search for product name,brand or category" aria-describedby="basic-addon2" disabled>
              <span class="input-group-text  rounded-pill " id="btn_head_search"><i class="fa fa-search fa-sm"></i>
            </div>
          </div>
          <div class="col-sm-3 mt-1" >
            <a href="cart.php" class="position-relative mt-1 mx-3 text-decoration-none">
              <!-- <i style="color: white;font-size: 28px; " class="fas fa-shopping-cart"></i> -->
              <img src="assets/CART.png" class="img-fluid mb-2">
              <span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-danger"><?php echo $CounttotalCVal;?></span>
            </a> 
            <a href="message.php" class="position-relative mt-1 mx-2 text-decoration-none">
              <img src="assets/SMS_2.png" class="img-fluid mb-2">
             <!--  <i style="color: white;font-size: 28px; " class="fas fa-sms mt-1 mx-1"></i> -->
               <span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-danger"><?php echo $count;?></span></a>
              <?php if ($is_log) { ?>
                <a data-bs-toggle="collapse" data-bs-target="#addressdeff" class="text-light text-decoration-none">  <img src="assets/LOC.png" class="img-fluid mb-2"> Deliver To</a>
                <div id="addressdeff" class="collapse">
                  <?php if($Getdefaultaddress['region']==""){ ?>  
                    <a href="address_mod_update.php?aid=0" class="btn location-adr text-end text-decoration-none " >
                      <?php if(isset($_SESSION['user_image'])){ ?>
                        <img src="<?php echo $_SESSION['user_image'];?>" class="img-fluid" style="box-shadow: 0 0.0625rem 0.125rem 0 rgba(0,0,0,.6); border-radius: 100% !important;background: #fff; color: black;height: 36px;" />
                      <?php } ?>                 
                        <span class="text-light "> Set your Address</span>
                      </a>
                  <?php }else{ ?>
                    <a href="address_mod.php" type="button" class="btn btn-light">
                      <?php if(isset($_SESSION['user_image'])){ ?>
                        <img src="<?php echo $_SESSION['user_image'];?>"  class="img-fluid"  style="box-shadow: 0 0.0625rem 0.125rem 0 rgba(0,0,0,.6); border-radius: 100% !important;background: #fff; color: black;height: 36px;" />
                      <?php }?>
                        <span><?php echo  $Getdefaultaddress['city']?></span> -
                        <span><?php echo  $Getdefaultaddress['region'];?></span>
                      </a>
                  <?php }?>
                  </div>
                <?php }?>  
               </div>
             </div>
          </div>
        </div>
      </div>  
    </div>
  </nav>
<div class="row fixed-top text-black-50 bg-light" style="margin-top: 75px;z-index: 11;">
  <div class="col-sm-12">
    <div class="w-100 py-1">
      <div class="scroller scroller-left mt-2" style="background:transparent;"><i class="fa fa-chevron-left"></i></div>
      <div class="scroller scroller-right mt-2" style="background:transparent;"><i class="fa fa-chevron-right"></i></div>
      <div class="wrapper mt-3" style="max-height: 25px">
        <nav class="nav nav-tabs list border border-0" id="myTab" role="tablist">
          <?php
          require_once 'model/home_new.php';   
          $headmodelH=new home_new();
          $getCategories_new=$headmodelH->getCategoriesNew(0);
          foreach ($getCategories_new as $category) :
            $b64catid = base64_encode($category['category_id']);
            $b64name = base64_encode('cat_id');?>
            <a class="nav-item nav-link text-dark" style="font-size: 12px;" href="product_category.php?<?php echo $b64name;?>=<?php echo $b64catid;?>"  role="tab" data-toggle="tab"><?php echo $category['name'];?></a>            
          <?php endforeach; ?>
        </nav>
      </div>
    </div>
  </div>
</div>

 <!-- Large modal -->

<div class="modal fade" id="LoginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog"  >
    <div class="modal-content" style="background: linear-gradient(#031a3d, #3f1c59, #7f005b, #b60040, #d30000); border-radius: 20px;">
      <div class="modal-header">
        <h5 class="modal-title text-light" id="staticBackdropLabel">This is an exclusive membership store</h5>
        <!-- <a type="button"  class="float-end" id="closed_loginmod"   style="float: right;"><i class="fa fa-times-circle " style="color: #FFF;font-size: 25px;" ></i></a> -->
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group m-1">
                      <input type="text" id="txtuser" name="txtuser" placeholder="Username or Mobile No." class="full-text form-control "  />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
              <div class="form-group m-1">
                    <input type="password" id="txtpassword" name="txtpassword" placeholder="Password" class="full-text form-control "  />
              </div>
            </div>
         </div>
         <div class="row mt-3">
           <div class="col-lg-12 ">
              <button class="btn btn-primary p-1" id="btnlogin"  style="font-size: 15px;">Login</button>
              <button class="btn btn-info p-1"  data-bs-toggle="modal" data-bs-target="#LoginModalNew" style="font-size: 15px;">Cancel</button>
           </div>
        </div>
      </div>
      <div class="modal-footer">        
        <hr><br>
        <div class="row">
           <div class="col-lg-12">
             <?php if(!isset($_SESSION['access_token'])){ ?>
              <a class="btn btn" style="box-shadow: 0 0.0625rem 0.125rem 0 rgba(0,0,0,.6);border-radius: 4px;background: #fff; color: black;font-size: 13px;" href="<?php echo $google_client->createAuthUrl();?>"><img src="assets/sign-in-with-google.png"  style="height: 18px;" /> Login with Google</a>
             <?php } ?>
            
            <a class="btn btn-info"   style="font-size: 13px;"  href="register.php">Register Now <i class="fas fa-chevron-right"></i></a>
            <button class="btn btn-danger"   style="font-size: 13px;"  id="forgot_password"> Forgot Password </i></button>
           </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">

var hidWidth;
var scrollBarWidths = 0;
var widthOfList = function(){
  var itemsWidth = 0;
  jQuery('.list a').each(function(){
    var itemWidth = jQuery(this).outerWidth();
    itemsWidth+=itemWidth;
  });
  return itemsWidth;
  
};
var widthOfHidden = function(){
  return ((jQuery('.wrapper').outerWidth())-widthOfList()-getLeftPosi())-scrollBarWidths;
};
var getLeftPosi = function(){
  return jQuery('.list').position().left;
};
var reAdjust = function(){
  if ((jQuery('.wrapper').outerWidth()) < widthOfList()) {
    jQuery('.scroller-right').show().css('display', 'flex');
  }
  else {
    jQuery('.scroller-right').hide();
  }
  
  if (getLeftPosi()<0) {
    jQuery('.scroller-left').show().css('display', 'flex');
  }
  else {
    jQuery('.item').animate({left:"-="+getLeftPosi()+"px"},'slow');
    jQuery('.scroller-left').hide();
  }
}

reAdjust();

$(window).on('resize',function(e){  
    reAdjust();
});

$('.scroller-right').click(function() {  
  var widthOfHidden2= getLeftPosi()*-1;
  if(widthOfHidden2>2000){
   jQuery('.scroller-right').fadeOut('slow');
  }else{
    jQuery('.scroller-right').fadeIn('slow');
  } 
  jQuery('.scroller-left').fadeIn('slow');  
  jQuery('.list').animate({left:"-=200px"},'slow',function(){

  });
  //console.log(widthOfHidden2);
});
$('.scroller-left').click(function() {
  var startgetLeftPosi= getLeftPosi()*-1;
  if(startgetLeftPosi<200){
   jQuery('.scroller-left').fadeOut('slow');
  }else{
    jQuery('.scroller-left').fadeIn('slow');
  }  
  jQuery('.scroller-right').fadeIn('slow');
  jQuery('.list').animate({left:"-=-200px"},'slow',function(){});
});

$(document).delegate('#btn_head_search', 'click', function() {
  var search_val_input=document.getElementById('search_val_input_h').value   
  var res=search_val_input.split(' ').join('_20');
  var pesomall='<?php echo $pesomall?>';
  window.location.href = "welcome.php?searchvalue_h=" +res+'&pesomall='+pesomall;
}); 
$(document).delegate('#forgot_password', 'click', function() {
   var username =document.getElementById('txtuser').value
   bootbox.confirm({
    message: "Are you sure you want to change your password?",
    buttons: {
      confirm: {
        label: 'Yes',
        className: 'btn-success'
      },
      cancel: {
        label: 'No',
        className: 'btn-danger'
      }
    },
    callback: function (result) {
      if(result==true){
        if(username == ""){
          bootbox.alert("Please Enter Username");
          return false;
        }else{
          $.ajax({
            url: 'ajax_forgot_password.php?action=save_token',
            type: 'POST',
            data: 'username=' + username,
            dataType: 'json',
            beforeSend: function() {
                bootbox.dialog({
                      title: "Please Wait.",
                      message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
                });
            },
            success: function(json) {
              if (json['success']) {
                if(json['success']=="Username not exist"){
                  bootbox.alert(json['success'], function(){ 
                    bootbox.hideAll();
                  });
                }else{
                  bootbox.alert(json['success'], function(){ 
                  bootbox.hideAll();
                   location.reload();                     
                  });
                }
                
              }else{
                bootbox.hideAll();
                  location.reload();           
              }
            },
            error: function(xhr, ajaxOptions, thrownError) {
              alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
          });
        }
        
      }
    }
    }); 
  });



$(document).ready(function(){
  $('#search_val_input_h').prop('disabled', false);
   getmessage(); 
});

function getmessage(){
    var customer_id = '<?php echo $userid ?>';
    if (customer_id!='_null'){
      jQuery.ajax({
        url: 'ajax_get_message.php',
        type: 'GET',
        data: 'customer_id=' + customer_id,
        dataType: 'json',
        success: function(json) {
           for (var i = 0; i < json.length; i++) {
              $("#dmsg").append('<li><span style="background-color:red;vertical-align:baseline;" class="badge">'+json[i].unread+'</span> <a href="javascript:void(0);"  onclick="getmessagedetails('+json[i].sender+');" class="msgmodal" data-sender="'+json[i].sender+'"   style="margin-left:5px;" > '+json[i].fname+'</a></li>');
              //alert( json[i].sender + " " +  json[i].unread + " " +  json[i].fname );
           }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }
  }   
</script>
<script type="text/javascript">
$(document).delegate('#btnlogin', 'click', function() {
   // var username=$("#txtuser").val();
    var username=document.getElementById('txtuser').value 
    var email=document.getElementById('txtuser').value 
    var password=document.getElementById('txtpassword').value 
   if(username == ""){
      bootbox.alert("Please Enter Username or Mobile Number.");
      return false;
    }else if (password == "" ){
     bootbox.alert("Please Enter Username or Mobile Number.");
     return false;
    }else{
      jQuery.ajax({
        url: 'ajax_landbankreg.php?action=manualLogin&t=' + new Date().getTime(),
        type: 'POST',
        data: 'username=' + username+'&password=' + password,
        dataType: 'json',
        success: function(json) {
          
          if(json['status']==300){
             bootbox.alert(json['success']);
          }else{
            //console.log(json);
            bootbox.alert(json['success'], function(){ 
              location.replace("lanbankLogin.php?regCustid="+json['customer_id']+"&t="+ new Date().getTime());
            });
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }
}); 
$(document).delegate('#search_val_input_h', 'keypress', function(e) {
  var search_val_input=document.getElementById('search_val_input_h').value  
   var pesomall='<?php echo $pesomall?>';
   if(e.which == 13){    
       var res=search_val_input.split(' ').join('_20');
       window.location.href = "welcome.php?searchvalue_h=" +res+'&pesomall='+pesomall; 
  }
});

</script>



