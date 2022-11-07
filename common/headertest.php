<?php
require_once "include/init.php";
require_once "model/Review.php";
if ($session->is_signed_in()) {
  $is_log = 1;
  $userid = $_SESSION['user_login'];
  $mobile_stats = 1; //$_SESSION['mobile_statuscode'];
  $review = new Review();
  $reviewCount = $review->getReviewCount("all");
} else {
  $is_log = 0;
  $userid = 0;
  $mobile_stats = 0;
  $reviewCount = 0;
}
$pesomall = 0;
if (isset($_GET['active'])) {
  $pesomall = 1;
}
if (isset($_GET['pesomall'])) {
  $pesomall = $_GET['pesomall'];
}
if ($is_log == 1) {
  require_once "model/message.php";
  require_once "model/count_cart.php";
  $msg = new message;
  $CountCart = new CountCart;
  $totalCVal = $CountCart->GetTotaCart($userid);
  $Getdefaultaddress = $CountCart->Getdefaultaddress($userid);
  $unreads = $msg->GetTotalUnreads($userid);
  $unreads2 = $msg->GetTotalUnreadsCA($userid);
} else {
  $totalCVal = 0;
  $unreads['unreads'] = 0;
  $unreads2['unreads'] = 0;
}
$CounttotalCVal = $totalCVal > 0 ? $totalCVal : '';
$count = $unreads['unreads'] + $unreads2['unreads']  > 0 ? $unreads['unreads'] + $unreads2['unreads'] : '';
$useragent = $_SERVER['HTTP_USER_AGENT'];
if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
  $is_mobile = true;
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
} else {
  $is_mobile = false;
}

//for google account
require_once 'composer/vendor/autoload.php';
$google_client = new Google_Client();
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.6/css/swiper.min.css">
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
  <script type="text/javascript" src='./JavaScriptSpellCheck/include.js'></script>
  <script src="js/paging.js"></script>
  <script src="./slick/slick.js" type="text/javascript" charset="utf-8"></script>
  <script type='text/javascript' src='JavaScriptSpellCheck/extensions/fancybox/jquery.fancybox-1.3.4.pack.js'></script>

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

  <!-- Jquery Simple Pagination -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.4/jquery.simplePagination.js" integrity="sha512-D8ZYpkcpCShIdi/rxpVjyKIo4+cos46+lUaPOn2RXe8Wl5geuxwmFoP+0Aj6wiZghAphh4LNxnPDiW4B802rjQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.4/simplePagination.css" integrity="sha512-emkhkASXU1wKqnSDVZiYpSKjYEPP8RRG2lgIxDFVI4f/twjijBnDItdaRh7j+VRKFs4YzrAcV17JeFqX+3NVig==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Jquery Simple Pagination -->

  <script>
    const getCategory = () => {
      return new Promise(resolve => {
        $.ajax({
          url: `ajax_category.php?action=get&t=` + new Date().getTime(),
          dataType: 'json',
          success: response => {
            const max = Math.max(...response.map(({
              level
            }) => level));

            for (let i = 1; i <= max; i++) {
              response.map(res => {
                if (parseInt(res.level) === i) {
                  if (parseInt(res.parent_id) === 0) {
                    if ($('#myTab').children().length === 0) {
                      $('#myTab').append(`
                        <ul class="p-0 mb-0 nav flex-nowrap">
                        </ul>
                      `);
                    }
                    $('#myTab>ul').append(`
                      <li class="position-relative" data-id="${res.id}" data-parent="${res.parent_id.split(',')[0]}" data-level="${res.level}">
                        <a href="product-category.php?Y2F0X2lk=${btoa(res.id)}" class="nav-item nav-link text-dark category-item" style="font-size: 12px; white-space: nowrap">${res.title}</a>
                      </li>
                    `);
                  } else {
                    if ($(`[data-id="${res.parent_id.split(',')[0]}"]`).children().length === 1) {
                      if (parseInt(res.level) === 2) {
                        $(`[data-id="${res.parent_id.split(',')[0]}"]`).append(`
                            <ul class="p-0 start-0 top-100 list-unstyled w-100 bg-white d-none shadow-sm">
                            </ul>
                          `)
                      } else {
                        $(`[data-id="${res.parent_id.split(',')[0]}"]`).append(`
                            <ul class="p-0 position-absolute start-100 top-0 list-unstyled w-100 bg-white d-none shadow-sm">
                            </ul>
                          `)
                      }
                    }
                    $(`[data-id="${res.parent_id.split(',')[0]}"] > ul`).append(`
                      <li class="position-relative" data-id="${res.id}" data-parent="${res.parent_id.split(',')[0]}" data-level="${res.level}">
                        <a href="product-category.php?Y2F0X2lk=${btoa(res.id)}" class="nav-item nav-link text-dark category-item" style="font-size: 12px; white-space: nowrap">${res.title} <i class="far fa-angle-right position-absolute top-50 translate-middle" style="left: calc(100% - 10px); z-index: 1;"></i></a>
                      </li>
                    `)
                  }
                }
              })
            }
          }
        }).done(() => resolve())
      })
    }
    $.getScript("./slick/slick.js")
      .done(function(script, textStatus) {
        getCategory().then(() => {
          $('#myTab>ul').slick({
            infinite: true,
            slideToScroll: 2,
            variableWidth: true,
            prevArrow: '<span class="mt-2 me-2"><i class="fas fa-chevron-left"></i></span>',
            nextArrow: '<span class="mt-2 ms-2"><i class="fas fa-chevron-right"></i></span>'
          });
        });
      });
  </script>

  <!-- Google Tag Manager -->
  <script>
    (function(w, d, s, l, i) {
      w[l] = w[l] || [];
      w[l].push({
        'gtm.start': new Date().getTime(),
        event: 'gtm.js'
      });
      var f = d.getElementsByTagName(s)[0],
        j = d.createElement(s),
        dl = l != 'dataLayer' ? '&l=' + l : '';
      j.async = true;
      j.src =
        'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
      f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-PSGVPL5');
  </script>
  <!-- End Google Tag Manager -->

  <!-- Facebook Pixel Code -->
  <script>
    ! function(f, b, e, v, n, t, s) {
      if (f.fbq) return;
      n = f.fbq = function() {
        n.callMethod ?
          n.callMethod.apply(n, arguments) : n.queue.push(arguments)
      };
      if (!f._fbq) f._fbq = n;
      n.push = n;
      n.loaded = !0;
      n.version = '2.0';
      n.queue = [];
      t = b.createElement(e);
      t.async = !0;
      t.src = v;
      s = b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
      'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '782967435543539');
    fbq('track', 'PageView');
  </script>
  <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=782967435543539&ev=PageView&noscript=1" /></noscript>
  <!-- End Facebook Pixel Code -->

  <!-- <title>100% Filipino Owned Electronic Store App I Full Warranty Shopping</title> -->
  <title>All Original Online Gadget Mall</title>

  <?php
  $get_product_id_val = 0;
  $get_product_id = isset($_GET['product_id']) ? $_GET['product_id'] : 0;
  if ($get_product_id != "0") {
    $charSetArray = explode("_fb_cust_id_", $_GET['product_id']);
    $count_charSetArray = count($charSetArray);
    if ($count_charSetArray == 1) {
      $get_product_id_val = $_GET['product_id'];
    } else {
      $get_product_id_val = $charSetArray[0];
      $_SESSION['product_id_seller_session'] = $charSetArray[1];
      $_SESSION['product_id_fbshare_session'] = $charSetArray[0];
    }
  }
  ?>

  <?php if ($get_product_id != "0") {
    include "model/product.php";
    $model_product = new product();
    $productdesc25 = $model_product->getproduct2($get_product_id_val);
    $getimg25 = str_replace(" ", "%20", "img/" . $productdesc25['image']);
    $name_fb = $productdesc25['name'];
    $price_fb =  number_format($productdesc25['price'], 2);
  ?>


    <meta property="og:type" content="product" />
    <meta property="og:title" content=" <?php echo $name_fb; ?> | <?php echo $price_fb; ?>" />
    <meta property="og:description" content="100% Filipino Owned Electronic Store App I Full Warranty Shopping" />
    <meta property="og:url" content="https://pesoapp.ph/product.php?product_id=<?php echo $get_product_id; ?>" />
    <meta property="og:image" content="<?php echo $getimg25; ?>" />
    <meta property="og:image:width" content="400">
    <meta property="og:image:height" content="400">

  <?php } else if (isset($_GET['promo_id'])) { ?>
    <?php require_once 'model/home_new.php';
    $model_sharePR = new home_new();
    $PRTitle = "";
    $PRImg = "";
    $get_latest_PRid = $model_sharePR->get_latest_promonew(1);
    foreach ($get_latest_PRid as $listPR) :
      if ($listPR['id'] == $_GET['promo_id']) {
        $PRTitle = $listPR['title'];
        $PRImg = $listPR['thumb'];
      }
    endforeach;
    ?>

    <meta property="og:type" content="Promo" />
    <meta property="og:title" content=" <?php echo $PRTitle; ?> " />
    <meta property="og:description" content="<?php echo $PRTitle; ?>" />
    <meta property="og:url" content="https://pesoapp.ph/product_category_new.php?promo_id=<?php echo $_GET['promo_id']; ?>" />
    <meta property="og:image" content="<?php echo $PRImg; ?>" />
    <meta property="og:image:width" content="400">
    <meta property="og:image:height" content="400">

  <?php } else { ?>
    <meta property="og:image" content=".//assets/peso_header.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="924">
    <meta property="og:image:height" content="924">
  <?php } ?>


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
  body {
    font-family: 'Montserrat', sans-serif !important;
  }

  .bootbox-close-button {
    border: none;
    font-size: 25px;
    float: right;
    background: #fff;
  }

  #subhead {
    background: #1d3c87
  }

  #logo-head {
    position: relative;
    height: 80px;
    top: -3px;
  }

  #btn_head_search {
    z-index: 10;
    margin-left: -40px;
    background: #fff;
    border: none;
  }

  #row-home {
    margin-left: -70px;
  }

  .containerH {
    margin-top: 130px;
  }

  .closeBtn:focus {
    outline: none;
  }

  .close {
    color: gray;
  }

  .suggestions {
    border-radius: 0 0 5px 5px !important;
  }

  .suggestions a:hover {
    color: #4b6ed6 !important;
    font-weight: bold;
  }

  /* clears the 'X' from Internet Explorer */
  input[type=search]::-ms-clear {
    display: none;
    width: 0;
    height: 0;
  }

  input[type=search]::-ms-reveal {
    display: none;
    width: 0;
    height: 0;
  }

  .ribbon {
    width: 48%;
    position: relative;
    float: left;
    margin-bottom: 30px;
    background-size: cover;
    text-transform: uppercase;
    color: white;
  }

  .ribbon3 {
    min-width: 100px;
    width: fit-content;
    white-space: nowrap;
    height: 25px;
    line-height: 25px;
    padding-left: 15px;
    position: absolute;
    left: -8px;
    top: 15px;
    background: red;
    font-size: 80%;
  }

  .ribbon3:before,
  .ribbon3:after {
    content: "";
    position: absolute;
  }

  .ribbon3:before {
    height: 0;
    width: 0;
    top: -8.5px;
    left: 0.1px;
    border-bottom: 9px solid black;
    border-left: 9px solid transparent;
  }

  .ribbon3:after {
    height: 0;
    width: 0;
    right: -17px;
    border-top: 15px solid transparent;
    border-bottom: 10px solid transparent;
    border-left: 18px solid red;
  }

  .light-theme>* {
    padding: 2px 12px !important;
    font-size: 1rem !important;
    border-radius: 3px !important;
    border: 1px solid #dee2e6 !important;
    box-shadow: none !important;
  }

  .light-theme .current {
    background-color: #212529 !important;
  }

  .light-theme a:hover {
    color: #666 !important;
  }

  .product-card .product-name {
    display: -webkit-box;
    height: 36px;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 12px;
  }

  .product-card>a:hover {
    box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
  }

  /* clears the 'X' from Chrome */
  input[type="search"]::-webkit-search-decoration,
  input[type="search"]::-webkit-search-cancel-button,
  input[type="search"]::-webkit-search-results-button,
  input[type="search"]::-webkit-search-results-decoration {
    display: none;
  }

  .category-item:hover {
    background: #bdc3c7;
  }

  .modal {
    z-index: 99999;
  }
</style>

<body style="font-family: 'Montserrat', sans-serif !important; background: #E9E9E9;">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top mb-xl-5" id="subhead" style="z-index: 9999">
    <div class="container">
      <a class="navbar-brand" href="home.php"><img src="assets/NEW PESO GADGET MALL 1 - WHITE.png" id="logo-head"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse flex-column" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto align-items-center">
          <!-- <li class="nav-item mx-1">
            <a href="home.php" class="nav-link text-light">Home</a>
          </li> -->
          <li class="nav-item dropdown mx-1">
            <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Account
            </a>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
              <li><a href="account.php" class="dropdown-item">Account</a></li>
              <li><a href="address_mod.php" class="dropdown-item">Address</a></li>
              <li><a href="manage_homepage.php" class="dropdown-item">Manage Home page</a></li>
              <li><a href="PESO_Partner_Program.php" class="dropdown-item">PESO Partner Program</a></li>
              <li><a href="order_history.php" class="dropdown-item">Order History</a></li>
              <li><a href="return_history.php" class="dropdown-item">Return History</a></li>
              <li><a href="generate.php" class="dropdown-item">Invite a Friend</a></li>
              <li><a href="invitees.php" class="dropdown-item">Invites</a></li>
              <li><a href="change_pass.php" class="dropdown-item">Change Password</a></li>
              <li><a href="logout.php" class="dropdown-item">Logout</a></li>
            </ul>
          </li>
          <li class="nav-item mx-1">
            <div class="position-relative">
              <a href="order_history.php" class="nav-link text-light">Orders</a>
              <span class="position-absolute start-100 translate-middle badge rounded-pill bg-danger" style="top: 10px"><?php echo intval($reviewCount) > 0 ? $reviewCount : "" ?></span>
            </div>
          </li>
          <li class="nav-item dropdown mx-1">
            <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDarkDropdownMenuLinkW" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Wallet
            </a>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLinkW">
              <li><a href="discount_wallet.php" class="dropdown-item">Discount Wallet</a></li>
              <li><a href="cash_wallet.php" class="dropdown-item">Cash Wallet</a></li>
              <li><a href="ShippingWallet.php" class="dropdown-item">Shipping Wallet</a></li>
            </ul>
          </li>
          <li class="nav-item mx-1">
            <?php if ($mobile_stats) { ?>
              <a href="wishlist.php" class="nav-link text-light">Wishlist</a>
            <?php } else { ?>
              <a href="reg_activatemobile.php?RegIdVal=<?php echo $userid; ?>" class="nav-link text-light">Wishlist</a>
            <?php } ?>
          </li>
        </ul>
        <div class="d-flex w-100 justify-content-between align-items-center flex-lg-row flex-column">
          <div class="input-group position-relative m-0 w-100" style="max-width: 750px;">
            <div class="suggestions position-absolute shadow w-100" style="top: 36px;left: -1px" id="suggestions">
              <ul class="list-group rounded-0">
                <li class="list-group-item border-start-0 border-end-0 p-0 d-none" id="suggestedFlagship">
                  <ul class="list-group">
                    <li class="list-group-item border-start-0 border-end-0 fw-bold border-0 border-2 border-bottom border-dark"><i class="far fa-store"></i> Flagship Store / Brand </li>
                  </ul>
                </li>
                <li class="list-group-item border-start-0 border-end-0 p-0 d-none" id="suggestedStore">
                  <ul class="list-group">
                    <li class="list-group-item border-start-0 border-end-0 fw-bold border-0 border-2 border-bottom border-dark">Store </li>
                  </ul>
                </li>
                <li class="list-group-item border-start-0 border-end-0 p-0 d-none" id="suggestedProduct">
                  <ul class="list-group">
                    <li class="list-group-item border-start-0 border-end-0 fw-bold border-0 border-2 border-bottom border-dark">Products</li>
                  </ul>
                </li>
              </ul>
            </div>
            <div class="position-relative w-100">
              <input type="search" class="form-control rounded-pill shadow-none border-0" id="search_val_input_h" value="<?php if (isset($_GET['searchvalue_h'])) {
                                                                                                                            echo str_replace("_20", " ", $_GET['searchvalue_h']);
                                                                                                                          } ?>" placeholder="Search for product name,brand or category" aria-describedby="basic-addon2" disabled>
              <span class="position-absolute end-0 top-50 translate-middle-y bg-transparent d-flex align-items-center justify-content-center" style="cursor: pointer; width: 40px; height: 40px" id="btn_head_search"><i class="fa fa-search fa-sm"></i>
            </div>
          </div>
          <div class="d-flex justify-content-between align-items-center mt-lg-2 mt-3">
            <a href="cart.php" class="position-relative text-decoration-none mx-2">
              <i class="fal fa-shopping-cart text-white" style="font-size: 26px"></i>
              <span class="position-absolute start-100 translate-middle badge rounded-pill bg-danger"><?php echo $CounttotalCVal; ?></span>
            </a>
            <a href="message.php" class="position-relative text-decoration-none mx-2">
              <i class="fal fa-comment text-white" style="font-size: 26px"></i>
              <span class="position-absolute start-100 translate-middle badge rounded-pill bg-danger"><?php echo $count; ?></span>
            </a>
            <div class="d-inline-block mx-2">
              <?php if ($is_log) { ?>
                <?php if (!isset($Getdefaultaddress['region'])) { ?>
                  <a class="text-decoration-none text-white p-0" href="address_mod_update.php?aid=0">
                    <!-- <?php if (isset($_SESSION['user_image'])) { ?>
                      <img src="<?php echo $_SESSION['user_image']; ?>" class="img-fluid" style="box-shadow: 0 0.0625rem 0.125rem 0 rgba(0,0,0,.6); border-radius: 100% !important;background: #fff; color: black;height: 36px;" />
                    <?php } ?> -->
                    <span class="text-nowrap d-flex align-items-center">
                      <i class="fal fa-map-marker-alt" style="font-size: 24px"></i>
                      <span>Set your Address</span>
                    </span>
                  </a>
                <?php } else { ?>
                  <a class="text-decoration-none text-white p-0" href="address_mod.php" type="button" class="btn btn-light">
                    <!-- <?php if (isset($_SESSION['user_image'])) { ?>
                      <img src="<?php echo $_SESSION['user_image']; ?>" class="img-fluid" style="box-shadow: 0 0.0625rem 0.125rem 0 rgba(0,0,0,.6); border-radius: 100% !important;background: #fff; color: black;height: 36px;" />
                    <?php } ?> -->
                    <span class="text-nowrap d-flex align-items-center">
                      <i class="fal fa-map-marker-alt" style="font-size: 24px"></i>
                      <span class="ms-1"><?php echo  $Getdefaultaddress['city'] ?></span>
                    </span>
                    <!-- <span> - </span>
                    <span><?php echo  $Getdefaultaddress['region']; ?></span> -->
                  </a>
                <?php } ?>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>
  <!-- Navbar -->

  <!-- Categories -->
  <div class="row fixed-top text-black-50" id="nav_cat" style="margin-top: 75px;z-index: 9998;">
    <div class="col-sm-12 bg-white" style="height: 50px;">
      <div class="container">
        <!-- <div class="scroller scroller-left mt-2" style="background:transparent;"><i class="fa fa-chevron-left"></i></div>
        <div class="scroller scroller-right mt-2" style="background:transparent;"><i class="fa fa-chevron-right"></i></div> -->
        <div class="mt-3 p-0" id="myTab">
        </div>
      </div>
    </div>
  </div>
  <!-- Categories -->

  <!-- Large modal -->
  <div class="modal fade" id="LoginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content" style="background: #4b6ed6; border-radius: 20px;">
        <div class="modal-header">
          <h5 class="modal-title text-light" id="staticBackdropLabel">This is an exclusive membership store</h5>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group m-1">
                <input type="text" id="txtuser" name="txtuser" placeholder="Username or Mobile No." class="full-text form-control " />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group m-1">
                <input type="password" id="txtpassword" name="txtpassword" placeholder="Password" class="full-text form-control " />
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-lg-12 ">
              <button class="btn btn-primary p-1" id="btnlogin" style="font-size: 15px;">Login</button>
              <button class="btn btn-info p-1" data-bs-toggle="modal" data-bs-target="#LoginModalNew" style="font-size: 15px;">Cancel</button>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <hr><br>
          <div class="row">
            <div class="col-lg-12">
              <?php if (!isset($_SESSION['access_token'])) { ?>
                <a class="btn btn" style="box-shadow: 0 0.0625rem 0.125rem 0 rgba(0,0,0,.6);border-radius: 4px;background: #fff; color: black;font-size: 13px;" href="<?php echo $google_client->createAuthUrl(); ?>"><img src="assets/sign-in-with-google.png" style="height: 18px;" /> Login with Google</a>
              <?php } ?>

              <a class="btn btn-info" style="font-size: 13px;" href="register.php">Register Now <i class="fas fa-chevron-right"></i></a>
              <button class="btn btn-danger" style="font-size: 13px;" id="forgot_password"> Forgot Password </i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Large modal -->

  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PSGVPL5" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->

  <script>
    $(document).ready(() => {
      $('#search_val_input_h').prop('disabled', false);
      getmessage();
    });

    $(document).on('mouseenter', '.category-item', function() {
      // $(this).siblings('ul').removeClass('d-none');
      // $(this).closest('ul').addClass('active');

      $(this).siblings('ul').removeClass('d-none active');
      $(this).closest('ul').addClass('active').trigger('classChange');
    });

    $(document).on('mouseleave', '.category-item', function() {
      $(this).siblings('ul').removeClass('active').trigger('classChange');
    });

    $(document).on('mouseleave', '#myTab', function() {
      $(this).find('ul:not(.nav)').addClass('d-none');
    });

    $(document).on('classChange', '#myTab ul', function() {
      if ($(this).hasClass('active')) $(this).removeClass('d-none');
      else $(this).addClass('d-none');
    })

    $(document).delegate('#btn_head_search', 'click', function() {
      var search_val_input = document.getElementById('search_val_input_h').value
      var res = search_val_input.split(' ').join('_20');
      var pesomall = '<?php echo $pesomall ?>';
      window.location.href = "welcome.php?searchvalue_h=" + res + '&pesomall=' + pesomall;
    });

    $(document).delegate('#forgot_password', 'click', function() {
      var username = document.getElementById('txtuser').value
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
        callback: function(result) {
          if (result == true) {
            if (username == "") {
              bootbox.alert("Please Enter Username");
              return false;
            } else {
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
                    if (json['success'] == "Username not exist") {
                      bootbox.alert(json['success'], function() {
                        bootbox.hideAll();
                      });
                    } else {
                      bootbox.alert(json['success'], function() {
                        bootbox.hideAll();
                        location.reload();
                      });
                    }

                  } else {
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

    $(document).delegate('#btnlogin', 'click', function() {
      var username = document.getElementById('txtuser').value
      var email = document.getElementById('txtuser').value
      var password = document.getElementById('txtpassword').value
      if (username == "") {
        bootbox.alert("Please Enter Username or Mobile Number.");
        return false;
      } else if (password == "") {
        bootbox.alert("Please Enter Username or Mobile Number.");
        return false;
      } else {
        $.ajax({
          url: 'ajax_landbankreg.php?action=manualLogin&t=' + new Date().getTime(),
          type: 'POST',
          data: 'username=' + username + '&password=' + password,
          dataType: 'json',
          success: function(json) {

            if (json['status'] == 300) {
              bootbox.alert(json['success']);
            } else {
              bootbox.alert(json['success'], function() {
                location.replace("lanbankLogin.php?regCustid=" + json['customer_id'] + "&t=" + new Date().getTime());
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
      var search_val_input = document.getElementById('search_val_input_h').value
      var pesomall = '<?php echo $pesomall ?>';
      if (e.which == 13) {
        var res = search_val_input.split(' ').join('_20');
        window.location.href = "welcome.php?searchvalue_h=" + res + '&pesomall=' + pesomall;
      }
    });

    $('#search_val_input_h').keyup(function() {
      if (this.value == "") {
        $(this).attr('style', '');
        $('.suggestions>ul>li').addClass('d-none');
        $('.suggestions>ul>li>ul>li:not(:first-child)').remove();
        return;
      } else {
        $(this).attr('style', 'border-radius: 5px 5px 0 0 !important');
      }
      debounce(async () => {
        await suggestion($('#search_val_input_h'));
      }, 500);
    });

    $(document).click(function(e) {
      if (!$(e.target).is("#suggestions")) {
        $('#search_val_input_h').attr('style', '');
        $('.suggestions>ul>li').addClass('d-none');
        $('.suggestions>ul>li>ul>li:not(:first-child)').remove();
      }
    });

    function getmessage() {
      var customer_id = '<?php echo $userid ?>';
      if (customer_id != 0) {
        $.ajax({
          url: 'ajax_get_message.php',
          type: 'GET',
          data: 'customer_id=' + customer_id,
          dataType: 'json',
          success: function(json) {
            for (var i = 0; i < json.length; i++) {
              $("#dmsg").append('<li><span style="background-color:red;vertical-align:baseline;" class="badge">' + json[i].unread + '</span> <a href="javascript:void(0);"  onclick="getmessagedetails(' + json[i].sender + ');" class="msgmodal" data-sender="' + json[i].sender + '"   style="margin-left:5px;" > ' + json[i].fname + '</a></li>');
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          }
        });
      }
    }

    const debounce = (() => {
      let timer = 0;
      return (callback, milliseconds) => {
        clearTimeout(timer);
        timer = setTimeout(callback, milliseconds);
      };
    })();

    const suggestion = that => {
      return new Promise(resolve => {
        const search = that.val();
        $.ajax({
          url: `ajax_search.php?action=suggestion&t=${new Date().getTime()}`,
          method: 'POST',
          dataType: 'json',
          data: {
            search
          },
          success: response => {
            $('.suggestions>ul>li').addClass('d-none');
            $('.suggestions>ul>li>ul>li:not(:first-child)').remove();
            if (response) {
              response.map((res, index) => {
                if (parseInt(res.type) === 0) {
                  $('#suggestedProduct').removeClass('d-none');
                  $('#suggestedProduct ul').append(`
                  <li class="list-group-item border-start-0 border-end-0" style="background: rgba(255, 255, 255, 0)">
                    <a class="d-block text-decoration-none text-dark" href="${res.link}" tabindex="${index + 1}">
                      <img src="${res.seller_image}" style="width: 40px; height: 40px;">
                      <span class="ms-1">${res.search}</span>
                    </a>
                  </li>`);
                } else {
                  if (parseInt(res.seller_type) === 2) {
                    $('#suggestedFlagship').removeClass('d-none');
                    $('#suggestedFlagship ul').append(`
                    <li class="list-group-item border-start-0 border-end-0">
                      <a class="d-block text-decoration-none text-dark" href="${res.link}" tabindex="${index + 1}">
                        <img src="${res.seller_image}" style="width: 40px; height: 40px;">
                        <span class="ms-1">${res.search}</span>
                      </a>
                    </li>
                    `)
                  }
                  if (parseInt(res.seller_type) === 0 || parseInt(res.seller_type) === 1) {
                    $('#suggestedStore').removeClass('d-none');
                    $('#suggestedStore ul').append(`
                    <li class="list-group-item border-start-0 border-end-0">
                      <a class="d-block text-decoration-none text-dark" href="${res.link}" tabindex="${index + 1}">
                        <img src="${res.seller_image}" style="width: 40px; height: 40px;">
                        <span class="ms-1">${res.search}</span>
                      </a>
                    </li>
                    `)
                  }
                }
              })
            } else {
              return;
            }
          }
        }).done(() => {
          resolve();
        })
      })
    }
  </script>