<?php 
    include "common/header.php";
    require_once 'model/home_new.php';     
    require_once "model/latest_promo.php";
    $home_new_mod=new home_new();
    $model_lp= new LatestPromo();
    $latestPromoList=$model_lp->getLatesPromolist_id(66); 
    $custid = isset($_SESSION['user_login']) ? $_SESSION['user_login']: 0;
    $home_new_mod->insertCustomerView();
    $home_new_mod->insertCustomerViewHourly();
   
?>
<style type="text/css">
.container { width: 100%; }
.speech-bubble-pagesample {
  position: relative;
  background: #0078D7;
  padding: 30px;
  opacity: 0.90;
}
.btn.app-download:hover{
  color: #fff;
}
.no-image{
    background-color: #e0e0e0;
    height: 150px;
}
.no-image i {
    font-size: 50px;
    margin-top: 50px;
    color: #333;
}
.store-card {
  margin: 0 10px;
  background-repeat: no-repeat; 
  background-size: cover;
  background-position: center !important;
}
.nav_header{
   background: #f2faff;
    width: 100%;
    padding: 10px 15px !important;
    border-radius: 10px;
    margin-left: 0px !important;
}
.ribbonhp {
  width: 100%;
  position: relative;
  float: left;
  margin-bottom: 30px;
  background-size: cover;
  color: white;
  margin-bottom: 78px;
}
.ribbonhpTimer {
  width: 100%;
  position: relative;
  float: left;
  margin-bottom: 30px;
  background-size: cover;
  color: white;
  margin-bottom: 10px;
}
.ribbon3hp {
    width: 300px;
    height: 25px;
    line-height: 25px;
    padding-left: 15px;
    position: absolute;
    left: -8px;
    top: -69px;
    background: #fa1c05;
    font-size: 20px;
    border-radius: 5px;
    text-align: center;
  }
  .ribbon3hp:before, .ribbon3hp:after {
    content: "";
    position: absolute;
  }
  .ribbon3hp:before {
    height: 0;
    width: 0;
    top: -8.5px;
    left: 0.1px;
    border-bottom: 9px solid black;
    border-left: 9px solid transparent;
  }
  
  ribbonhpTimert {
  width: 100%;
  position: relative;
  float: left;
  margin-bottom: 30px;
  background-size: cover;
  color: white;
  margin-bottom: 10px;
}
.ribbon3hpt {
    width: 371px;
    height: 25px;
    line-height: 25px;
    padding-left: 15px;
    position: absolute;
    left: -8px;
    top: -32px;
    font-size: 20px;
      z-index: 2;
  }
  .ribbon3hpt:before, .ribbon3hp:after {
    content: "";
    position: absolute;
  }
  .ribbon3hpt:before {
    height: 0;
    width: 0;
    top: -8.5px;
    left: 0.1px;
    border-bottom: 9px solid black;
    border-left: 9px solid transparent;
  }
  .ribbonnew {
  width: 100%;
  position: relative;
  float: left;
  margin-bottom: 30px;
  background-size: cover;
  color: white;
  margin-bottom: 78px;
}

.ribbon3new {
    width: 308px;
    height: 25px;
    line-height: 25px;
    padding-left: 15px;
    position: absolute;
    left: -8px;
    top: -10px;
    background: linear-gradient(rgb(3, 26, 61), rgb(63, 28, 89), rgb(127, 0, 91), rgb(182, 0, 64), rgb(211, 0, 0));
    font-size: 20px;
  }
  .ribbon3new:before, .ribbon3new:after {
    content: "";
    position: absolute;
  }
</style>
<div class="wrapper">
    <div class="container_home" >
        <!--  Banner -->  
        <div class="row">
            <div class="col-lg-12 ca-home" >
                 <?php  $getBanner_new=$home_new_mod->getBanner_new(11);
                 include "carousel_new.php";?>
            </div>     
        </div>
        <!--  Banner end -->  
         <!-- get_latest_promonew -->
         <!-- <div class="row" > 
             <?php                 
                include "sample_timer.php";?> 
        </div> -->
        <div class="row" > 
            <div class="col-lg-12 categoryhomes nav_header">
                  
               <!--<div class="ribbonhpTimer">
                    <span class="ribbon3hp">
                           <?php// echo $latestPromoList['title'];?></span>
                           
                </div>
                <div class="ribbonhpTimert">
                    <span class="ribbon3hpt">
                            <?php    //include "sample_timer.php";?> Lates Promo</span>
                           
                </div>-->
             Lates Promo
            </div>  
            <div class="col-lg-12">
                <?php                 
                include "product_latest_promo_new.php";?>  
            </div>
        </div>
        <!-- get_latest_promonew End -->
        <!-- Categories -->
        <div class="row" >
             
          <?php if(isset($_SESSION['regok_mobile_verify'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['regok_mobile_verify']; unset($_SESSION['regok_mobile_verify']);?></div>
          <?php endif;?>
            <div class="col-lg-12 categoryhomes nav_header">
                <h5>Choose Categories </h5>
            </div>  
            <div class="col-lg-12">
                 <?php                 
                 include "product_homepage_category_new.php";?>      
            </div>
        </div>
        <!-- Categories End -->
       
        
        <!-- Product Brand -->
        <div class="row" > 
            <div class="col-lg-12 categoryhomes nav_header">
                <h5>Your Trusted Brands</h5>
            </div>  
            <div class="col-lg-12 Most-Popular">
                 <?php                   
                  include "product_homepage_brand.php";?>      
            </div>
        </div>
        <!-- Product Brand End -->
         <div class="row" > 
            <div class="col-lg-12 categoryhomes nav_header">
                <h5>Your Favorite Stores</h5>
            </div>  
            <div class="col-lg-12 Most-Popular">
                 <?php                   
                  include "homepage_sotres.php";?>      
            </div>
        </div>
        <!--  for Recommended -->
        <div class="row">        
            <div class="col-lg-12 recommended-cat nav_header">
              <h5>Recommended for you</h5>
            </div>
            <div class="col-lg-12 recommended-cat ">
                <?php                 
                include "product_recommended_new.php";?> 
            </div>      
        </div>    
        <!--  for Recommended End--> 
        <!--  for Most Popula--> 
        <div class="row">  
            <div class="col-lg-12 categoryhomes nav_header">
              <h5>Most Popular</h5>
            </div>  
            <div class="col-lg-12 Most-Popular">
              <?php                 
              include "product_most_popular_new.php";?>       
            </div> 
        </div> 

         <div class="row">  
          <?php  $list_lppseller = $model_lp->GetHopgeSellerPromo(66); ?>
            <div class="col-lg-12 categoryhomes nav_header">
               <div class="ribbonnew">
                    <span class="ribbon3new">
                           <?php echo $latestPromoList['title'];?></span>
                </div>
            </div>  
            <div class="col-lg-12 Most-Popular">
              <?php  
                  include "LPSellerPromoHomepage.php"; 
              ?>   
            </div> 
        </div> 
        <!--  for Most Popula End-->
         <!--  peso_mall and pesoglobal -->
         <?php include "home_page_product_new.php";?> 
    
       <!--  peso_mall and pesoglobal -->
      <div class="row">
        <div class="col-xs-12">
          <ul class="nav nav-tabs">
            <li class="active">
              <a data-toggle="tab" href="#peso_mall">                
                  <img src="https://pesoapp.ph/img/mobile/discover/peso-mall.webp" style="width: 120px; height: 20px">
              </a>
            </li>
          </ul>
          <div class="tab-content">
            <div id="peso_mall" class="tab-pane fade in active">              
                  <?php                   
                  include "product_homepage_new.php";?> 
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

<?php if($is_log){ include "popup_ads.php";}else{ include "loginNewmodal.php";} ?>

<?php include "common/footer.php"; 


?>
