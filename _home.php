<?php 
    include "common/header.php";
    require_once 'model/home_new.php'; 
    $home_new_mod=new home_new();
    $custid = isset($_SESSION['user_login']) ? $_SESSION['user_login']: 0;
   
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
        <!-- Categories -->
        <div class="row" > 
            <div class="col-lg-12 categoryhomes">
                <h5>Categories</h5>
            </div>  
            <div class="col-lg-12">
                 <?php                 
                 include "product_homepage_category_new.php";?>      
            </div>
        </div>
        <!-- Categories End -->
       
         <!-- get_latest_promonew -->
        <div class="row" > 
            <div class="col-lg-12 categoryhomes">
                <h5>Latest Promo</h5>
            </div>  
            <div class="col-lg-12">
                <?php                 
                include "product_latest_promo_new.php";?>  
            </div>
        </div>
        <!-- get_latest_promonew End -->
        <!-- Product Brand -->
        <div class="row" > 
            <div class="col-lg-12 categoryhomes">
                <h5>Brands</h5>
            </div>  
            <div class="col-lg-12 Most-Popular">
                 <?php                   
                  include "product_homepage_brand.php";?>      
            </div>
        </div>
        <!-- Product Brand End -->
         <div class="row" > 
            <div class="col-lg-12 categoryhomes">
                <h5>Stores</h5>
            </div>  
            <div class="col-lg-12 Most-Popular">
                 <?php                   
                  include "homepage_sotres.php";?>      
            </div>
        </div>
        <!--  for Recommended -->
        
        <!--  for Most Popula End-->
         <!--  peso_mall and pesoglobal -->
         <?php include "home_page_product_new.php";?> 
      <div class="row">
        <div class="col-xs-12">
          <ul class="nav nav-tabs">
            <li class="active">
              <a data-toggle="tab" href="#pesoglobal">
                  <img src="https://pesoapp.ph/img/mobile/discover/peso-global.webp" style="width: 120px; height: 20px">        
              </a>  
            </li>
          </ul>
          <div class="tab-content">
            <div id="pesoglobal" class="tab-pane fade in active">  
             <?php
             include "bg_new_product_list.php";?>  
            </div>
           
          </div>
        </div>
      </div>
    </div>
</div>
<?php include "popup_ads.php"; ?>
<?php include "model/customer.php"; ?>
<?php echo (new customer())->insertCustomerView(); ?>

<?php include "common/footer.php"; ?>