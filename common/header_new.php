<?php
    require_once("include/init.php");
    if($session->is_signed_in()){
        $is_log=1;
        $userid=$_SESSION['user_login'];
        $mobile_stats=1;//$_SESSION['mobile_statuscode'];
    }else{
        $is_log=0;
        $userid='_null';
        $mobile_stats='_null';
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
    $CounttotalCVal = $totalCVal > 0 ? '<span class="badge">'.$totalCVal.'</span>':'';
    $count = $unreads['unreads'] + $unreads2['unreads']  > 0 ? '<span class="badge">'.($unreads['unreads'] + $unreads2['unreads']).'</span>':'';
    $useragent=$_SERVER['HTTP_USER_AGENT'];
    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
    {
        $is_mobile=true; 
       // header("Location: https://mb.pesoapp.ph/");
    }else{$is_mobile=false;}
  /*  echo  $is_log;
    echo  $userid;*/
?>
<style>
  .slider-container {overflow: hidden;}
  .slider-phc .slick-slide:nth-of-child(n+1) {display: none;}
  .slick-slide:nth-of-child(n+1) {display: none;}
  .slick-initialized,.slick-slide:first-child {display: block;}
  ul.ui-autocomplete {position: fixed; z-index: 90;}
  .ca-home{margin-top: 27px;}
  #subhead {background: linear-gradient(rgb(3, 26, 61), rgb(63, 28, 89), rgb(127, 0, 91), rgb(182, 0, 64), rgb(211, 0, 0));}
  .notification{ position: relative;display: inline-block;}
  .notification .badge {
    position: absolute !important;
    top: -10px !important;
    right: -10px !important;
    padding: 5px 10px !important;
    border-radius: 100% !important;
    background: red !important;
    color: white !important;
  }
  .speech-bubble {position: relative;background: #a7acaf;border-radius: .4em;}
  .speech-bubble:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 75%;
    width: 0;
    height: 0;
    border: 55px solid transparent;
    border-top-color: #a7acaf;
    border-bottom: 0;
    border-right: 0;
    margin-left: -27.5px;
    margin-bottom: -55px;
  }
  .container_home{margin-top: 5em;}
  .container{ margin-top: 10em;}
  .location-adr {color:  white;position: absolute;
    right: 0;font-size: 14px;}
  .InviteAfreindMOB{margin-bottom: 50px;width: 20px;margin-left: auto;}
  .InviteAfreindDT{margin-bottom: 70px !important; width: 20px;margin-left: auto;}
  .sheremesdiconLIMOB{background: red; border: none ;float:right;border-radius: 50%; width: 60px;height: 60px;}
  .sheremesdiconLIDT{background: red; border: none ;float:right;border-radius: 50px;}
  .pesoCircleMOB{float:right; width: 60px;height: 60px;margin:0px;padding:0px;}
  .pesoCircleDT{float:right; width: 80px;height: 80px;margin:0px;padding:0px; }
  .navemenust{text-align: center;margin: 10 10px;margin-left: -5px;}
  .navemenustUL{display: flex; list-style: none ; }
  .navemenust_UL_LI_A_B{color:  white;opacity: .90; font-size: 16px !important;}
  .navemenust-UL-LIdropdown-A{background: transparent; text-align: center;}
  .ATCenter{text-align: center;text-decoration: none !important;}
  @media (max-width: 575.98px) {
    #col-2{
        display: none;
    }
    .lacationAdd{
        display: none;
    }
    .ulNavMalls{
      display: flex; list-style: none ;justify-content: space-around;
    }
    #divsearch{
    vertical-align: middle  !important;;
    }
  } 
  @media (max-width: 768px) {   
    .lacationAdd{
        display: none;
    }
  }
</style>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="./slick/slick.css">
<link rel="stylesheet" type="text/css" href="./slick/slick-theme.css">

<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="facebook-domain-verification" content="3rnq697iggh9lhnps1vcq70lgqimpf" />

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src='JavaScriptSpellCheck/include.js' ></script>
<script src="js/paging.js"></script>
<script src="./slick/slick.js" type="text/javascript" charset="utf-8"></script>
<script type='text/javascript' src='JavaScriptSpellCheck/extensions/fancybox/jquery.fancybox-1.3.4.pack.js' ></script>

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

 
<?php }else{?>
  <meta property="og:image" content=".//assets/peso_header.png">
  <meta property="og:image:type" content="image/png">
  <meta property="og:image:width" content="924">
  <meta property="og:image:height" content="924">
<?php }?>


<link rel="icon" href="https://mb.pesoapp.ph/assets/icon/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="JavaScriptSpellCheck/extensions/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./assets/css/styles.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="./assets/css/fontawesome-pro/fontawesome.css">
<link rel="stylesheet" type="text/css" href="fonts/flaticons/flaticon.css">
</head>
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
<body>
  <?php if($is_mobile){ ?> 
    <div class="navbar-fixed-bottom InviteAfreindMOB" id="invite_a_freind">
      <?php if ($is_log) { ?>
        <?php if ($mobile_stats) { ?>
          <a id ="sheremesdiconLI" class="sheremesdiconLIMOB" type="button">
        <?php }else{ ?>
          <a href="reg_activatemobile.php?RegIdVal=<?php echo $userid;?>" class="sheremesdiconLIMOB" type="button" >
        <?php }?>
            <img src='img/peso_circle.png' class="pesoCircleMOB"/>
          </a>
      <?php }else{ ?>
        <a data-toggle="modal" data-target="#LoginModal"  title="Invite a Freind" class="sheremesdiconLIMOB"> 
          <img src='img/peso_circle.png' class="pesoCircleMOB"/>
        </a>
      <?php }?> 
    </div>
  <?php }else{ ?> 
    <div  class="navbar-fixed-bottom InviteAfreindDT" id="invite_a_freind">
      <?php if ($is_log) { ?>
        <?php if ($mobile_stats) { ?>
          <a href="generate.php" title="Invite a Freind" class="sheremesdiconLIDT">
        <?php }else{ ?>
          <a href="reg_activatemobile.php?RegIdVal=<?php echo $userid;?>" title="Invite a Freind" class="sheremesdiconLIDT">
        <?php }?>
            <img src='img/peso_circle.png' class="pesoCircleDT"/> 
          </a>
      <?php }else{ ?> 
        <a data-toggle="modal" data-target="#LoginModal"  title="Invite a Freind" class="sheremesdiconLIDT"> 
            <img src='img/peso_circle.png' class="pesoCircleDT"/> 
        </a>
      <?php }?> 
     </div>
  <?php }?>  
  <div class="navbar-fixed-top" id="subhead">
    <div class="row">
      <div id="navemenu" class="nav navemenust" >
        <ul  class="navbar-right navemenustUL"> 
          <li style="margin: 0 10px">    
            <a href="home.php"  class="ATCenter">
              <b class="navemenust_UL_LI_A_B" >Home </b>  
            </a>
          </li> 
          <li class="dropdown" style="margin: 0 10px"  >
            <a href="home.php" title="Account" class="dropdown-toggle navemenust-UL-LIdropdown-A" data-toggle="dropdown" >
              <b class="navemenust_UL_LI_A_B">Account </b>              
            </a>
            <ul class="dropdown-menu dropdown-menu-center" >
              <?php if ($is_log) { ?>
                <?php if ($mobile_stats) { ?>
                  <li><a href="account.php" class="ATCenter">Account</a></li>                    
                  <li><a href="manage_homepage.php" class="ATCenter">Manage Home page</a></li>
                  <li><a href="PESO_Partner_Program.php" class="ATCenter">PESO Partner Program</a></li>
                  <li><a href="order_history.php" class="ATCenter">Order History</a></li>
                  <li><a href="return_history.php" class="ATCenter">Return History</a></li>
                  <li><a href="#" class="ATCenter">Transactions</a></li>
                  <li><a href="generate.php" class="ATCenter">Invite a Friend</a></li>
                  <li><a href="invitees.php" class="ATCenter">Invites</a></li>
                  <?php if($_SESSION['mobile_statuscode']==0){?>
                    <li><a href="reg_activatemobile.php?RegIdVal=<?php echo $userid;?>" class="ATCenter">Verify Your Mobile No</a></li>
                  <?php }?>
                  <li><a href="change_pass.php" class="ATCenter">Change Password</a></li>
                  <li><a href="logout.php" class="ATCenter">Logout</a></li>
                <?php }else{ ?>
                  <li><a href="reg_activatemobile.php?RegIdVal=<?php echo $userid;?>" class="ATCenter">Account</a></li>
                  <li><a href="reg_activatemobile.php?RegIdVal=<?php echo $userid;?>" class="ATCenter">Manage Home page</a></li>
                  <li><a href="reg_activatemobile.php?RegIdVal=<?php echo $userid;?>" class="ATCenter">PESO Partner Program</a></li>
                  <li><a href="reg_activatemobile.php?RegIdVal=<?php echo $userid;?>" class="ATCenter">Order History</a></li>
                  <li><a href="reg_activatemobile.php?RegIdVal=<?php echo $userid;?>" class="ATCenter">Return History</a></li>
                  <li><a href="#">Transactions</a></li>
                  <li><a href="reg_activatemobile.php?RegIdVal=<?php echo $userid;?>" class="ATCenter">Invite a Friend</a></li>
                  <li><a href="reg_activatemobile.php?RegIdVal=<?php echo $userid;?>" class="ATCenter">Invites</a></li>
                  <li><a href="reg_activatemobile.php?RegIdVal=<?php echo $userid;?>" class="ATCenter">Change Password</a></li>
                  <li><a href="logout.php" class="ATCenter">Logout</a></li>
                <?php }?>                 
              <?php } else { ?>
              <li><a data-toggle="modal"  href="#LoginModal" class="ATCenter">Login</a></li>
              <?php } ?>
            </ul>
          </li>           
          <li style="margin: 0 10px">  
            <?php if ($is_log) { ?>
              <?php if ($mobile_stats) { ?>
                 <a href="order_history.php" class="ATCenter">   
              <?php }else{ ?>
                 <a  href="reg_activatemobile.php?RegIdVal=<?php echo $userid;?>" class="ATCenter">   
              <?php }?>                             
                <b  class="navemenust_UL_LI_A_B">Orders</b>          
            <?php }else{ ?>
              <a title="Order History" class="navemenust-UL-LIdropdown-A" data-toggle="modal"  href="#LoginModal" >    <b  class="navemenust_UL_LI_A_B">Orders</b>
              </a>
            <?php }?>     
          </li>
          <li class="dropdown" style="margin: 0 10px"> 
            <?php if ($is_log) { ?>
              <a href="" title="Digital Wallet" class="dropdown-toggle navemenust-UL-LIdropdown-A" data-toggle="dropdown" >
                <b  class="navemenust_UL_LI_A_B" >Wallet </b>
              </a>
            <?php }else{ ?>
              <a title="Digital Wallet" data-toggle="modal"  href="#LoginModal" class="navemenust-UL-LIdropdown-A">
                <b  class="navemenust_UL_LI_A_B">Wallet </b> 
              </a>
            <?php }?>
            <ul class="dropdown-menu pull-right" >
              <?php if ($mobile_stats) { ?>
                <li><a href="discount_wallet.php" title="Discount Wallet" >Discount Wallet</a></li>
                <li><a href="cash_wallet.php" class="ATCenter">Cash Wallet</a></li>
                <li><a href="ShippingWallet.php" class="ATCenter">Shipping Wallet</a></li>
              <?php }else{ ?>
                <li><a  href="reg_activatemobile.php?RegIdVal=<?php echo $userid;?>" title="Discount Wallet" class="ATCenter">Discount Wallet</a></li>
                <li><a  href="reg_activatemobile.php?RegIdVal=<?php echo $userid;?>" title="Cash Wallet" class="ATCenter">Cash Wallet</a></li>
                <li><a  href="reg_activatemobile.php?RegIdVal=<?php echo $userid;?>" title="Cash Wallet" class="ATCenter">Shipping Wallet</a></li>
              <?php }?>                
            </ul>
          </li>
          <li style="margin: 0 20px">
            <?php if ($is_log) { ?>
              <?php if ($mobile_stats) { ?>
              <a href="wishlist.php" class="ATCenter">  
              <?php }else{ ?>
                <a href="reg_activatemobile.php?RegIdVal=<?php echo $userid;?>" class="ATCenter">  
              <?php }?>
              <b  class="navemenust_UL_LI_A_B" >Wishlist </b>    
              </a>
            <?php }else{ ?>
              <a data-toggle="modal"  href="#LoginModal" class="ATCenter">
                <b  class="navemenust_UL_LI_A_B" >Wishlist </b>          
              </a>
            <?php } ?>
          </li>    
        </ul>          
      </div><!-- end For id="navemenu" class="nav navemenust" -->  
      <div id="navmalls" class="nav" style="text-align: center;margin: 10 10px;margin-left: -5px;display: none" >
        <!-- For Location -->
        <?php if ($is_log) { ?>
          <div class="lacationAdd">               
              <?php if($Getdefaultaddress['region']==""){ ?>
                 <a href="address_mod_update.php?aid=0" class="location-adr" >
                  <?php if(isset($_SESSION['user_image'])){ ?>
                    <img src="<?php echo $_SESSION['user_image'];?>"  style="box-shadow: 0 0.0625rem 0.125rem 0 rgba(0,0,0,.6); border-radius: 100% !important;background: #fff; color: black;height: 30px;" />
                  <?php }else{ ?>
                     <i data-feather="map-pin"></i> 
                  <?php }?>                 
                    <span> Set your Address</span>
                  </a>
              <?php }else{ ?>
                 <a href="address_mod.php" class="location-adr" >
                  <?php if(isset($_SESSION['user_image'])){ ?>
                    <img src="<?php echo $_SESSION['user_image'];?>"  style="box-shadow: 0 0.0625rem 0.125rem 0 rgba(0,0,0,.6); border-radius: 100% !important;background: #fff; color: black;height: 30px;" />
                  <?php }else{ ?>
                     <i data-feather="map-pin"></i> 
                  <?php }?>
                    <span>Deliver To  </span>-
                    <span><?php echo  $Getdefaultaddress['city']?></span> -
                    <span><?php echo  $Getdefaultaddress['region'];?></span>
                  </a>
              <?php }?>
             
            </div><!-- class="lacationAdd" --> 
          <?php }?> 
          <!-- end For Location --> 
        <ul  class="ulNavMalls" style= "display: flex; list-style: none ; justify-content: center;"  >
          <li style="margin: 0 10px">
            <a href="peso_local.php" class="ATCenter"> 
              <b style="color:  white;opacity: .90; font-size: 16px !important;" >LOCAL</b>
            </a>
          </li>
          <li style="margin: 0 20px">
            <a href="peso_global.php?active=0" class="ATCenter">  
              <b style="color:  white;opacity: .90; font-size: 16px !important;" >GLOBAL</b>
            </a>
          </li> 
        </ul>   
      </div><!-- end For id="navmalls" class="nav" -->      
    </div><!-- end For class="row" --> 
    <div class="row">
      <div class="col-sm-2 col-xs-2" >
        <div id="logo" style="margin-top: -35px;" >
          <a class="" rel="welcome" href="home.php" style="margin-left: 20px !important;" id="col-2" >
            <img src="assets/PESO trans2.png"  style="position:relative; height: 80px; width: 160px;">
          </a>
        </div><!-- id="logo" -->
      </div><!-- class="col-sm-2 col-xs-2" -->   
      <div class="col-sm-10 col-xs-10" style="margin-left: -50px; margin-top:10px" id="divsearch">
        <div class="col-sm-9 col-xs-9" id="search_lbl">
          <div  class="input-group" >
            <input style=" font-size: 15px; height: 30.5px;
              padding: 11px 10px;
              border-radius: 1px;
              margin:0 auto;
              opacity: .90; margin-left: 30px;" 
              type="text" id="search_val_input_h"  value="<?php if(isset($_GET['searchvalue_h'])){ echo str_replace("_20"," ",$_GET['searchvalue_h']);} ?>" placeholder="Search for product name,brand or category" class="form-control"/>
            <span class="input-group-btn">
              <a type="button" class="btn btn-default btn-sm" id="btn_head_search" style="padding: 9px 10px; margin:0 auto;  opacity: .90;margin-left: 30px;"><i class="fa fa-search fa-sm" style=" font-size: 12px;"></i></a>             
            </span>
          </div><!-- class="input-group" -->
        </div><!-- class="col-sm-9 col-xs-9" -->
        <div class="col-sm-3 col-xs-3" >
          <div id="cart_home" class="input-group" >
            <?php if ($is_log) { ?>
              <?php if ($mobile_stats) { ?>
                 <a href="cart.php"  title="Cart"  class="notification"><i style="color:  white;opacity: .90; font-size: 12px; margin-left: -10px !important;" data-feather="shopping-cart"></i><?php echo $CounttotalCVal;?></a> 
              <?php }else{ ?>
                 <a href="reg_activatemobile.php?RegIdVal=<?php echo $userid;?>"  title="Cart"  class="notification">
                  <i style="color:  white;opacity: .90; font-size: 12px; margin-left: -10px !important;" data-feather="shopping-cart"></i>
                  <?php echo $CounttotalCVal;?>
                </a> 
              <?php }?>                     
            <?php } else { ?>
              <a data-toggle="modal"  href="#LoginModal"  title="Cart" ><i style="color:  white;opacity: .90; font-size: 12px; margin-left: -10px !important;" data-feather="shopping-cart"></i></a> 
            <?php }?>
            <div  style=" margin-top:-25px">
              <ul style= "list-style:none">
                <li class="dropdown" >
                  <?php if ($is_log) { ?>
                     <a href="message.php" title="Message" style="background: transparent; text-align: center;" class="notification">
                      <i style="color:  white;opacity: .90; font-size: 12px; margin-left: -10px !important;" data-feather="message-square"></i><?php echo $count;?>
                    </a>
                  <?php }else{ ?> 
                    <a title="Message" data-toggle="modal"  href="#LoginModal" style="background: transparent; text-align: center;">
                      <?php if($is_mobile){ ?>
                        <i style="color:  white;opacity: .90; font-size: 12px; margin-left: -10px !important;" data-feather="message-square"></i>
                      <?php }else{ ?>
                        <i style="color:  white;opacity: .90; font-size: 12px; margin-left: -10px !important;" data-feather="message-square"></i>
                      <?php }?> 
                    </a>
                  <?php }?>                    
                </li>
              </ul>    
            </div><!-- style=" margin-top:-25px" -->
          </div><!-- id="cart_home" class="input-group" -->
        </div> <!-- class="col-sm-3 col-xs-3" --> 
      </div><!--class="col-sm-10 col-xs-10"  -->
    </div><!-- end For class="row" -->    
  </div><!-- end For  class="navbar-fixed-top" id="subhead" --> 

<div class="modal fade bd-example-modal-lg" id="Modalrefund" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
       <div class="modal-header">
        <a type="button"  data-dismiss="modal"   style="float: right;
                  font-size: 25px;
                  font-weight: 700;
                  line-height: 1;
                  color: #000;
                  text-shadow: 0 1px 0 #fff;
                "><i class="fa fa-times-circle " style="color: black;font-size: 25px;" ></i></a>
        <br>
        <div id="ReturnRefund_head">
          <p style="font-size: 20px" class="modal-title" >
            <strong>Return and Refund</strong>
          </p>
        </div>   
       
      </div>
      <div class="modal-body">
         <div class="panel panel-default" id="info_div_ReturnRefundy">
            <div class="panel-body" >         
            <h3>RETURN, REFUND OR CANCELLATION POLICY</h3>
            <p>
              a.7 days return, replace and refund
            </p>
            <p>
              Pinoy Electronic Store Online will automatically replace or refund defective item without question within 7 days from date of confirmed delivery provided that the unit will not fall under the voidable warranty clause and that the customer will bring at his own expense the unit to the proper product repair channel as advised by the product manufacturer and has officially notified Pinoy Electronic Store using the website page “RETURNS” (approval procedure applies) or if the customer has physically brought the item to any of our physical stores for official technical evaluation and recording.
            </p>
            <p>
              ***If the item is not returned thru the physical store then a return arrangement will be presented to the customer. In the proposed agreement the return back fee will be handled by the customer while the return to customer package will be handled by Pinoy Electronic Store Online however, before the item is approved for processing the customer must justify the return or refund request thru our website account page  called “returns” by properly documenting the defective item and submitting photos as needed. Pinoy electronic store may or may not at its own discretion personally contact the customer to perform technical evaluation such as remote session for diagnostic checking and technical report. This activity should determine if the item is indeed defective or if it’s just a problem involving wrong set up, usage related problem but not limited to limited warranty cases on certain products such as software operating system, desktop or mobile application and or virus or malware problems. The limited warranty will cover parts and services of the hardware and its built in functionalities and I also in compliance with the world wide warranty provided by the manufacturer of the product.
            </p>
            <p>
              *** Pinoy Electronic Store Online is an online service provider and therefore manufacturing defect must be taken up in consideration with manufacturers specifications and warranty conditions.
            </p>
            <p>
              NOTE : No return or exchange request will be entertained or accepted due to customers sudden change of mind.
            </p>
            <b><p>b.    After 7 days from date of purchase or regular warranty return, replacement or refund conditions.</p></b>
            <p>
              Anything bought from Pinoy Electronic Store Online unless stated in the specifications or receipt otherwise will have a 12 months limited warranty period. When the 7 days contestability period is over your unit will automatically be subjected to this warranty agreement. The agreement states that your unit if under regular warranty will be subject to repair or replacement of parts or the entire unit itself depending on the recommendation of the product service center free of charge with in the entire warranty period provided that the unit will not fall under the voidable warranty clause and that the customer will bring at his own expense the unit to the proper product repair channel as advised by the product manufacturer.
            </p>
            <p>
              As part of the warranty policy, customers must complete a request log to avail of the regular warranty thru the website under “YOUR WARRANTIES”. After filling up all the required information Pinoy Electronic Store Online will issue a warranty recommendation to the customer to be presented to our assign physical store, partner stores or official service center. Customer may now bring his unit to the assigned location for official service works.
            </p>
            <p>NOTE : No return or exchange request will be entertained or accepted due to customers sudden change of mind.</p>
            <b><p>
              c.     Approved replacement, refund  or order cancellation
            </p></b>
            <p>
              After complying and satisfying all necessary procedures customers may avail of the replacement or payment refund immediately using the “REFUND & REPLACE” page. For replacements, upon receiving the defective item Pinoy Electronic Store Online will send the new replacement unit to the customer totally free of charge with the next available business day subject to the availability of the stocks. For refunds and order cancellations, Pinoy Electronic Store Online will return the entire purchase value thru the customers Digital Wallet account system as a first option (subject to customers choice). The customer may then use this refund value immediately to purchase any item from the website. For complete cash or payment refund requests as the customers second option. Pinoy Electronic Store Online upon customer full compliance of the procedure will approve and return the entire amount to the customer by means of an official bank account deposit named under the customer. No other means of refund payment will be entertained by Pinoy Electronic Store as part of its internal security and fraud protection.
            </p>
            <b><p>7 days return, replace and refund</p></b>
            <p>Pinoy Electronic Store Online will automatically replace or refund defective item without question within 7 days from date of confirmed delivery provided that the unit will not fall under the voidable warranty clause and that the customer will bring at his own expense the unit to the proper product repair channel as advised by the product manufacturer and has officially notified Pinoy Electronic Store using the website page “RETURNS” (approval procedure applies) or if the customer has physically brought the item to any of our physical stores for official technical evaluation and recording.
 
            ***If the item is not returned thru the physical store then a return arrangement will be presented to the customer. In the proposed agreement the return back fee will be handled by the customer while the return to customer package will be handled by Pinoy Electronic Store Online however, before the item is approved for processing the customer must justify the return or refund request thru our website account page  called “returns” by properly documenting the defective item and submitting photos as needed. Pinoy electronic store may or may not at its own discretion personally contact the customer to perform technical evaluation such as remote session for diagnostic checking and technical report. This activity should determine if the item is indeed defective or if it’s just a problem involving wrong set up, usage related problem but not limited to limited warranty cases on certain products such as software operating system, desktop or mobile application and or virus or malware problems. The limited warranty will cover parts and services of the hardware and its built in functionalities and I also in compliance with the world wide WARRANTIES”ty provided by the manufacturer of the product.</p>
            <p>
              *** Pinoy Electronic Store Online is an online service provider and therefore manufacturing defect must be taken up in consideration with manufacturers specifications and warranty conditions.
            </p>
            <p>
              NOTE : No return or exchange request will be entertained or accepted due to customers sudden change of mind.
            </p>
            <b><p>
              b.    After 7 days from date of purchase or regular warranty return, replacement or refund conditions.
            </p></b>
            <p>
              Anything bought from Pinoy Electronic Store Online unless stated in the specifications or receipt otherwise will have a 12 months limited warranty period. When the 7 days contestability period is over your unit will automatically be subjected to this warranty agreement. The agreement states that your unit if under regular warranty will be subject to repair or replacement of parts or the entire unit itself depending on the recommendation of the product service center free of charge with in the entire warranty period provided that the unit will not fall under the voidable warranty clause and that the customer will bring at his own expense the unit to the proper product repair channel as advised by the product manufacturer.
            </p>
            <p>
              As part of the warranty policy, customers must complete a request log to avail of the regular warranty thru the website under “YOUR WARRANTIES”. After filling up all the required information Pinoy Electronic Store Online will issue a warranty recommendation to the customer to be presented to our assign physical store, partner stores or official service center. Customer may now bring his unit to the assigned location for official service works.
            </p>
            <p>NOTE : No return or exchange request will be entertained or accepted due to customers sudden change of mind.</p>
            <b><p>
              c.     Approved replacement, refund  or order cancellation
            </p></b>
            <p>
              After complying and satisfying all necessary procedures customers may avail of the replacement or payment refund immediately using the “REFUND & REPLACE” page. For replacements, upon receiving the defective item Pinoy Electronic Store Online will send the new replacement unit to the customer totally free of charge with the next available business day subject to the availability of the stocks. For refunds and order cancellations, Pinoy Electronic Store Online will return the entire purchase value thru the customers Digital Wallet account system as a first option (subject to customers choice). The customer may then use this refund value immediately to purchase any item from the website. For complete cash or payment refund requests as the customers second option. Pinoy Electronic Store Online upon customer full compliance of the procedure will approve and return the entire amount to the customer by means of an official bank account deposit named under the customer. No other means of refund payment will be entertained by Pinoy Electronic Store as part of its internal security and fraud protection.
            </p>

            </div>
          </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade bd-example-modal-lg" id="ModalreShippingPolicy" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
       <div class="modal-header">
        <a type="button"  data-dismiss="modal"   style="float: right;
                  font-size: 25px;
                  font-weight: 700;
                  line-height: 1;
                  color: #000;
                  text-shadow: 0 1px 0 #fff;
                "><i class="fa fa-times-circle " style="color: black;font-size: 25px;" ></i></a>
        <br>
        <div id="ShippingPoilcy_head">
          <p style="font-size: 20px" class="modal-title"  >
            <strong>Shipping and Delivery Poilcy</strong>
          </p>
        </div>
       
      </div>
      <div class="modal-body">
         <div class="panel panel-default" id="info_div_ReturnRefundy">
            <div class="panel-body" > 
              <b><p>
                Shipping and Delivery Policy
              </p></b>
              <p>
                Once your order is checked and completed, Pinoy Electronic Store Online will send a shipping dispatch notice to our internal delivery team. Depending on the location of the client the team will determine if the method of shipping will be thru our in house team or thru our delivery couriers and partners
              </p>
              <b><p>
                  Time of Delivery :
              </p></b>
              <p>
                The time of delivery starting from a message notification on your website account is estimated to be 2-3 days depending on the customer’s location and address. The delivery lead  time does not include fortuitous events such as weather conditions, natural disasters, public disturbances, civil disobedience and etc. Pinoy Electronic Store Online relies on our shipping partners for fulfilment and will act representing the customer until the item has been fully delivered to the end user. Lead time status is available for view by the end user under “Accounts/delivery Tracking” page.
              </p>
              <b><p>
                Delivery Receiving Condition :
              </p></b>
              <p>
                Items delivered must meet the “agreement upon delivery condition” as part of our standard process. The said upon delivery condition means that the item must be properly packed, properly sealed and checked for unofficial opening or tampering before the shipper picks the item and as well the end user confirms the received item.
              </p>
              <b><p>
                Shipper’s Responsibility :
              </p></b>
              <p>
                The agreed delivery condition above will be under the responsibility of the shipper or partner courier company once the item has been picked up from our warehouse. The Shipper as part of their service responsibility guarantees that the item will be delivered to the end user in perfect condition as to how they picked it up from Pinoy Electronic Store. Damaged, complete loss, incomplete parts/accessories or incorrect item delivery will be fully covered by Pinoy Electronic Store Online granting that the item has been cleared and checked for tampering by the customer.
              </p>
              <b><p>
                Customer’s Responsibility :
              </p></b>
              <p>
                It is the customers or end user’s responsibility to check and asses for package damage, opening or unauthorised tampering before they sign a receiving confirmation from the shipper or partner courier company. Once the customer signs the receiving paper, Pinoy Electronic Store Online will consider the delivery as “item received in complete and good working condition”. Under no circumstances will Pinoy Electronic Store Online honor any customer complain regarding bad delivery or related issues and will therefore interpret the case as part of our regular warranty or return and exchange case. (*** Manufacturing warranty conditions apply)
              </p>
              <b><p>
                Disputes and Complain :
              </p></b>
              <p>
                For any legal dispute or complains regarding the topic above you may inform us thru our dedicated mail service account at support@pinoyelectronicstore.com.
              </p>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>
 <!-- Large modal -->
<div class="modal fade bd-example-modal-lg" id="LoginModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
       <div class="modal-header">
        <a type="button"  data-dismiss="modal" id="closed_loginmod"   style="float: right;
                  font-size: 25px;
                  font-weight: 700;
                  line-height: 1;
                  color: #000;
                  text-shadow: 0 1px 0 #fff;
                "><i class="fa fa-times-circle " style="color: black;font-size: 25px;" ></i></a>
        <br>
        <p style="font-size: 23px" class="modal-title"><strong>This is an exclusive membership store.</strong></p>
        <small><b>Please login to continue</b></small>
       
      </div>
      <div class="modal-body">
        <div class="row">
              <div class="col-lg-12">
                  <div class="form-group">
                      <label class="text-body">Username / Mobile No.</label>
                        <input type="text" id="txtuser" name="" placeholder="Username or Mobile No." class="full-text form-control "  />
                  </div>
              </div>
                <div class="col-lg-12">
                  <div class="form-group">
                      <label class="text-body">Password</label>
                        <input type="password" id="txtpassword" name="" placeholder="Password" class="full-text form-control "  />
                  </div>
              </div>
        </div>
          <button class="btn btn-primary " id="btnlogin"  style="font-size: 15px;">Login</button>
          <button class="btn btn-default"  id="btncancel"  style="font-size: 15px;">Cancel</button>

         <hr>
         <hr>
        

        
        <a class="btn btn-info"   style="font-size: 13px;"  href="register.php">Register Now <i class="fas fa-chevron-right"></i></a>
        <button class="btn btn-danger"   style="font-size: 13px;"  id="forgot_password"> Forgot Password </i></button>
      </div>
    </div>
  </div>
</div>

<div  class="modal" id="addItemModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-error-dialog">
      <!-- <form role="form"> -->
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class='fa fa-times-circle fa-fw'></i></button>
                <div class="row">
                  <diV class="col-sm-6 ">
                    <label ><h2 id="msgname">asdf</h2></label><input type="hidden" id="id_send" name="">

                  </diV>
                  <diV class="col-sm-6">
                    <div class="form-group navbar-right" style=" margin-left: 20px; margin-right: 20px;">
                      <!-- <a class="btn btn-danger btn-delete-allconvo"  >Delete All</a> -->
                    </div>
                  </diV>
                </div>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body" id="modalbody" style=" margin-left: 10px; margin-right: 10px; max-height: calc(70vh - 200px); overflow-y: auto;">
                <div class="row">
                  <diV class="col-sm-12 ">
                    <?php
                    $TOTALCON=5;
                     if($TOTALCON>='3'){?>
                    <diV class="row" id="convo">
                       <diV class="col-sm-11"></diV>
                       <diV class="col-sm-1"><a class="btn btn-default down" style="width:30px; display:none;"><i class="fa fa-angle-double-down"></i></a></diV>                    
                    </diV>
                    <?php
                    } 
                    ?>
                     <?php if($TOTALCON>='3'){?>
                    <diV class="row">
                       <diV class="col-sm-11 "></diV>
                       <diV class="col-sm-1"> <a id ="up" style="width:30px;"><!--<i class="fa fa-angle-double-up"></i>--></a></diV>
                    </diV>
                     <?php }?>                
                </diV>
              </div>
            </div><!--modal-body-->
          <div class="modal-footer">
            <diV class="row">
              <diV class="col-sm-12">
                <div class="form-group" style=" margin-left: 10px; margin-right: 10px;" >
                  <textarea  class="form-control" id ="Message" name="message" placeholder="this is a sample message." rows="4" cols="50" required autofocus></textarea>
                </div>
              </div>
            </diV>
            <diV class="row">
              <diV class="col-sm-6">
                <input class="form-control" type="hidden" id ="id" name="customer_receiver_id"   value ="" required/>
              </diV>
              <diV class="col-sm-6">
                <button type="button" class="btn btn-primary btn-change-attr" id="sendmsg">Send</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              </diV>
            </diV>
          </div><!--modal-footeR-->
      </div><!--modal-content-->
    </div><!-- modal-dialog modal-error-dialog-->
</div><!-- addItemModal2-->


<div  class="modal fade bd-example-modal-lg" id="modal_ask_fb_mesg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" >
  <div class="modal-dialog modal-error-dialog">
    <div class="modal-header " style="border: none; margin-top: 400px;">
    </div>
    <div class="" style="width: 300px; margin: auto;">
    <a type="hidden"  data-dismiss="modal" id="modal_ask_fb_mesgclosed"  
      style="float: right; display: none;font-size: 25px;font-weight: 700;line-height: 1;color: #000;text-shadow: 0 1px 0 #fff;">
        <i class="fa fa-times-circle " style="color: white;font-size: 25px;" ></i> </a>
      <div class="speech-bubble">
        <div class="social-icon-message">
          <div class="row">
            <div class="col-xs-6">
              <a id="fbshare" data-toggle="tooltip" title="Share to facebook"> 
                <img src='./assets/FBlogo.png' style="width:70px;height: 55px;margin:0px;padding:0px; " /> 
              </a></br>
              <span>Post it on FB</span>
            </div>
            <div class="col-xs-6">
              <a id="messenger_share" data-toggle="tooltip" title="Share to facebook messenger">
                <img src='./assets/FBM.png' style="width:70px;height: 55px;margin:0px;padding:0px;" /> 
              </a></br>
              <span>Invite via Messenger</span>
            </div>
          </div>
        </div>
      </div>
    </div><!--modal-content-->
  </div><!-- modal-dialog modal-error-dialog-->
</div><!-- addItemModal2-->
<script src="fonts/feathericons/feather.min.js"></script>

<script type="text/javascript">
//var nav_malls = document.getElementById("nav_malls");
var $win = $(window);
window.onscroll = function() {myFunction()};
function myFunction() {
 if($win.scrollTop() == 0) { 
   $('#navemenu').css('display','block');
   $('#navmalls').css('display','none');
  }
  if($win.scrollTop() > 0) { 
       $('#navemenu').css('display','none');
        $('#navmalls').css('display','block');
  }
}
  $(document).delegate('#sheremesdiconLI', 'click', function() {
     jQuery.noConflict();
    $('#modal_ask_fb_mesg').modal('show');
  });
  
  feather.replace();
  $(document).delegate('#fbshare', 'click', function() {
   // $('#modal_ask_fb_mesgclosed').click();
    $.ajax({
        url: 'ajax_invite_a_friend.php?action=addsendFBacct',
        type: 'post',
        dataType: 'json',
        success: function(json) {
          if (json['success']) {
            location.replace("https://www.facebook.com/sharer.php?u=https://pesoapp.ph/register.php?%26cust_id=<?php echo $userid; ?>");
          }   
        }
    });
  });
  $(document).delegate('#messenger_share', 'click', function() {
    $('#modal_ask_fb_mesgclosed').click();
    $.ajax({
          url: 'ajax_invite_a_friend.php?action=addsendFBacct',
          type: 'post',
          dataType: 'json',
          success: function(json) {
            if (json['success']) {
              location.replace("fb-messenger://share/?link=https://pesoapp.ph/register.php?%26cust_id=<?php echo $userid; ?>");
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          }
        });
  });
  $(document).delegate('#BtnAboutUs', 'click', function() {
     jQuery.noConflict();
      $('#modal_info_data').modal('show');
      $("#About_Us_head").css("display", "block");
      $("#info_div_AboutUs").css("display", "block");
      $("#TandC_head").css("display", "none");
      $("#info_div_TandC").css("display", "none");
     
  });
  $(document).delegate('#modal_info_TermsCondition', 'click', function() {
     jQuery.noConflict();
      $('#modal_info_data').modal('show');
      $("#TandC_head").css("display", "block");
      $("#info_div_TandC").css("display", "block");
      $("#About_Us_head").css("display", "none");
      $("#info_div_AboutUs").css("display", "none");
  });
  $(document).delegate('#modal_info_ShippingPolicy', 'click', function() {
     jQuery.noConflict();
      $('#ModalreShippingPolicy').modal('show');
  });
  $(document).delegate('#modal_info_Return', 'click', function() {
     jQuery.noConflict();
      $('#Modalrefund').modal('show');
     
  });
  $(document).ready(function() { 
   
   $('#navmalls').css('display','none');
    getmessage(); 
    $( "#btnlogin" ).click(function() {
      jQuery.noConflict(); 
      var username =  $( "#txtuser" ).val();
      var email =  $( "#txtuser" ).val();
      var password =  $( "#txtpassword" ).val();
      if(username == ""){
        bootbox.alert("Please Enter Username or Mobile Number.");
        return false;
      }else if (password == "" ){
       bootbox.alert("Please Enter Username or Mobile Number.");
       return false;
      }else{
        $.ajax({
          url: 'ajax_login.php?action=login&t=' + new Date().getTime(),
          type: 'POST',
          data: 'username=' + username  +'&password=' + password,
          dataType: 'json',
          success: function(json) {
            if (json['success']=="Successfully Login...") {
                bootbox.alert(json['success'], function(){ 
                 window.location.reload();
                });
            }else{
              bootbox.alert(json['success']);
              return false;
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          }
        });
      }
    });
    $( "#forgot_password" ).click(function() {
     var username =  $( "#txtuser" ).val();  
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
                    $( "#closed_loginmod" ).click();
                    });
                  }
                  
                }else{
                  bootbox.hideAll();
                  $( "#closed_loginmod" ).click();
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
      
    $( "#btncancel" ).click(function() {    
          $('#LoginModal').modal('hide');
    });
    $( "#sendmsg" ).click(function() {    
      var senderid=$( "#id_send" ).val();
      var msgbox=$( "#Message" ).val();
      var customer_id = '<?php echo $userid ?>';
      sendmsg(customer_id,msgbox  ,senderid);
    });
    $( ".btn-delete-allconvo" ).click(function() {
      var senderid=$( "#id_send" ).val();
      var customer_id = '<?php echo $userid ?>';
      bootbox.confirm({
        message: "Do you like to delete all of conversation ?",
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
            if (result==true){
                alert("oks");

            };
        }
      });
    });
  });

  function deletemessage(id){
    bootbox.confirm({
      message: "Do you like to delete this message ?",
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
        if (result==true){           
          $.ajax({
            url: 'ajax_deletemessage.php',
            type: 'POST',
            data: 'id=' + id ,
            dataType: 'json',
            success: function(json) {
              if (json['success']=="Successfully Deleted.") {
                bootbox.alert(json['success'], function(){ 
                 location.reload();
                });
              }else{
                bootbox.alert(json['success']);
                return false;
              }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
          });
        };
      }
    });
  }
  function sendmsg(userid,msg,cid){
    if(msg=="" || cid=="" || userid ==""){
      bootbox.alert("Message must not be empty!!");
      return false;
    }else{
    $.ajax({
      url: 'ajax_sendmessage.php',
      type: 'POST',
      data: 'userid=' + userid  + '&msg='+ msg+ '&cid=' + cid,
      dataType: 'json',
      success: function(json) {
          if (json['success']=="Successfully Send.") {
             
            bootbox.alert(json['success'], function(){ 
              location.reload();
            });
          }else{
            bootbox.alert(json['success']);
            return false;
          }
      },
      error: function(xhr, ajaxOptions, thrownError) {
              alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          }
      });
    }
  }
  function getmessage(){
    var customer_id = '<?php echo $userid ?>';
    if (customer_id!='_null'){
      $.ajax({
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
  function getmessagedetails(senderid){
    var customer_id = '<?php echo $userid ?>';
    var sender = senderid;
    //alert(sender+ " " + customer_id );
    $.ajax({
      url: 'ajax_get_messagedetails.php',
      type: 'POST',
      data: 'customer_id=' + customer_id+'&sender_id=' + senderid,
      dataType: 'json',
      success: function(json) {
        // alert(json[0].fname);
        var j =-1;
        do{
          j++;
          $( "#msgname" ).text("");
          $( "#id_send" ).val("");
          $( "#msgname" ).text(json[j].fname);
          $( "#id_send" ).val(json[j].sender);
        }
        while(customer_id == json[j].sender);
          $('.convomessage').empty();
          for (var i = 0; i < json.length; i++) {
            if(json[i].sender==senderid){
                $("#convo").append('<diV class="row convomessage"><diV class="col-sm-6"><div class="panel panel-danger"><div class="panel-heading"  style="text-align: justify;/*word-break: break-all;">'+json[i].message+'</div></diV></diV><diV class="col-sm-6 "></diV></div><diV class="row convomessage"><diV class="col-sm-6 "><label>'+json[i].timestamp+'</label></diV><diV class="col-sm-6 "></diV></diV>');
            }else{
            $("#convo").append('<diV class="row convomessage"><diV class="col-sm-6 "></diV><diV class="col-sm-6"><div class="panel panel-success" style="text-align: right;"><div class="panel-heading" style="text-align: justify;/*word-break: break-all;">'+json[i].message+'</div></diV></diV></diV><diV class="row convomessage"><diV class="col-sm-6 "></diV><diV class="col-sm-6 " style="text-align: right;"><label>'+json[i].timestamp+'</label><a href="javascript:void(0);" onclick="deletemessage('+json[i].id+');">Delete</a></diV></diV>');
            }
          }
          $( "#Message" ).val("");
           jQuery.noConflict();
          $('#addItemModal2').modal('show');
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }

  $(document).delegate('#btn_head_search', 'click', function() {
    var search_val_input=$('#search_val_input_h').val();   
    var res=search_val_input.split(' ').join('_20');
    var pesomall='<?php echo $pesomall?>';
    window.location.href = "welcome.php?searchvalue_h=" +res+'&pesomall='+pesomall;
  });
  //search
  var availableTags=[];
  $(document).ready(function(){
    $("#search_val_input_h").keypress(function(e) {
      var search_val_input=$('#search_val_input_h').val();  
       var pesomall='<?php echo $pesomall?>';
       if(e.which == 13){    
           PopulateSearch(search_val_input);      
           var res=search_val_input.split(' ').join('_20');
           window.location.href = "welcome.php?searchvalue_h=" +res+'&pesomall='+pesomall; 
      }
    });    
  });
  function PopulateSearch(sval){
     var arr_search=$Spelling.SpellCheckSuggest(sval);   
    if(arr_search == undefined){
      arr_search = [];
    }else if(arr_search.length == 1){
      if(arr_search[0][0]!="*PHP Spellcheck Trial*"){
        for (var i = 0; i < arr_search[0].length; i++) {
          if(!availableTags.includes(arr_search[0][i])){
            availableTags.push(arr_search[0][i]);
          };
        }
      }
    }else{
      if(arr_search[0]!="*PHP Spellcheck Trial*"){
        for (var i = 0; i < arr_search.length; i++) {
          if(!availableTags.includes(arr_search[i])){
             availableTags.push(arr_search[i])
          }     
        }
      }   
    }
    $( "#search_val_input_h" ).autocomplete({       
          source: availableTags
      });
  }
</script>











