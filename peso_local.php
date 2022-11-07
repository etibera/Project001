<?php
  include "common/header.php";
  require_once 'model/home_new.php'; 
  $home_new_mod=new home_new();
  $custid = isset($_SESSION['user_login']) ? $_SESSION['user_login']: 0; 
  $getBanner_new=$home_new_mod->getBanner_new(11) ;
 
?>
<style type="text/css"> 
  .container { width: 100%; }
</style>
<div class="wrapper">
  <div class="container_home" >
      <div class="row">
       <div class="col-lg-12 ca-home" >
          <?php include "carousel_new.php";?>
        </div>     
      </div>
      <!--  Banner end -->  
        <!-- Categories -->
        <div class="row" > 
            <div class="col-lg-12 categoryhomes">
              <h5>Choose Categories </h5>
            </div>  
            <div class="col-lg-12 ">
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
            <div class="col-lg-12 ">
                <?php 
                include "product_latest_promo_new.php";?>  
            </div>
        </div>
         <!-- Product Brand -->
        <div class="row" > 
            <div class="col-lg-12 categoryhomes">
                <h5>Your Trusted Brands</h5>
            </div>  
            <div class="col-lg-12 ">
                 <?php  
                 include "product_homepage_brand.php";?>      
            </div>
        </div>
        <div class="row" > 
            <div class="col-lg-12 categoryhomes nav_header">
                <h5>Your Favorite Stores</h5>
            </div>  
            <div class="col-lg-12 Most-Popular">
                 <?php                   
                  include "homepage_sotres.php";?>      
            </div>
        </div>
        <!-- Product Brand End -->
        <!-- get_latest_promonew End -->
        <!--  for Recommended -->
        <div class="row">        
            <div class="col-lg-12 recommended-cat">
              <h5>Recommended for you</h5>
            </div>
            <div class="col-lg-12 recommended-cat">
                <?php
                 include "product_recommended_new.php";?> 
            </div>      
        </div>    
        <!--  for Recommended End--> 
        <!--  for Most Popula--> 
        <div class="row">  
            <div class="col-lg-12 categoryhomes">
              <h5>Most Popular</h5>
            </div>  
            <div class="col-lg-12 Most-Popular">
              <?php 
              include "product_most_popular_new.php";?>       
            </div> 
        </div> 
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
<?php include "model/customer.php"; ?>
<?php echo (new customer())->insertCustomerView(); ?>
<?php
include "common/footer.php";
?>s