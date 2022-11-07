<?php
include "common/headertest.php"; 
require_once "model/latest_promo.php";
require_once 'model/home_new.php'; 
$home_new_mod=new home_new();
$model_lp= new LatestPromo();
$promo_id = isset($_GET['promo_id']) ? $_GET['promo_id']: 0;  
$latestPromoList=$model_lp->getLatesPromolist_id($_GET['promo_id']); 


$list_lpp = $model_lp->get_lp_products($promo_id);
$count_selected_seller=$model_lp->getCount_selected_seller($promo_id);
$custid = isset($_SESSION['user_login']) ? $_SESSION['user_login']: 0;
?>
<style> 
  .swiper {
    width: 100%;
    height: 100%;
  }
  .swiper-slide img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  body{
    overflow-x: hidden;
  }
</style>
<div class="container">
  <!-- For Promo Banner -->
  <div class="" style="margin-top: 123px;">
    <?php $lp_banner_new=$home_new_mod->get_Banner_new_lp(13,$_GET['promo_id']); ?>
    <?php if(count($lp_banner_new)==0){ ?>
      <div class="row">
        <div class="col-sm-9 p-0"><!-- Size 830X320 -->
          <div><?php include "homepageMainCarousel.php";?></div>
        </div>
        <div class="col-sm-3 p-0 justify-content-center">
          <?php  $GetFeaturedBrand=$home_new_mod->GetFeaturedPromo(3,330,390); ?>
          <a href="product_category_new.php?promo_id=<?php echo $GetFeaturedBrand[0]['id']; ?>&t=<?php echo  uniqid();?>">  
            <img src="<?php echo $GetFeaturedBrand[0]['thumb'];?>" class="img-fluid" style="border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;">
          </a>
        </div>
      </div>
    <?php }else{ ?>
      <div class="row">
        <div class="col-sm-12 p-1">
            <div ><?php include "PromoMainCarousel.php";?></div>
        </div>
      </div>
    <?php } ?>
    <!-- end For Promo Banner -->
    <div class="row p-0">
      <div class="col-sm-12 p-1">
        <div class="card border-0" style="background: linear-gradient(177deg, #09C6F9 10%, #045DE9 100%); border-radius: 25px;">
          <div class="card-header text-center text-dark border-0" style="border-top-left-radius: 25px; border-top-right-radius: 25px;">
            <?php if($latestPromoList['promo_title_image']!=""){ ?> 
              <img   src="<?php echo 'img/'.$latestPromoList['promo_title_image']; ?>" alt="<?php echo $latestPromoList['title']; ?>" class="img-fluid" />
            <?php }else{ ?> 
              <?php echo $latestPromoList['title'];?>
            <?php }?>
          </div>
            <div class="card-body border-0">              
              <div class="row"> 
                <?php  if($count_selected_seller!=0){ ?>
                  <?php $list_lppseller = $model_lp->get_lp_seller_products($promo_id); ?>                 
                  <?php include "PromoMainSellerProductList.php"; ?>
                <?php }else{  //echo "<pre>"; print_r($list_lpp) ?>

                  <?php include "PromoMainProductList.php"; ?>
                <? }?> 
              </div>                    
            </div>
        </div>  
      </div>      
    </div>
    <!-- Recommended /Most Popular -->
    <div class="row p-0">
      <div class="col-sm-6 p-1">
        <div class="card border-0" style="background: linear-gradient(177deg, #09C6F9 10%, #045DE9 100%); border-radius: 25px;">
          <div class="card-header text-center text-dark border-0" style="border-top-left-radius: 25px; border-top-right-radius: 25px;">           
            <span class="align-middle">Recommended for you</span>  
              <button type="button" class="btn float-end text-dark" style="font-size: 10px;">See All></button>
          </div>
          <div class="card-body border-0">  
            <?php include "homepageMainRecommendedForYou.php";?>  
          </div>
        </div>                                      
      </div>
      <div class="col-sm-6 p-1">
        <div class="card border-0" style="background: linear-gradient(177deg, #09C6F9 10%, #045DE9 100%);border-radius: 25px;">
          <div class="card-header text-center text-dark border-0" style="border-top-left-radius: 25px; border-top-right-radius: 25px;">           
            <span class="align-middle">Most Popular Products</span>  
              <button type="button" class="btn float-end text-dark" style="font-size: 10px;">See All></button>
          </div>
          <div class="card-body border-0">
            <?php include "homepageMainMostPopular.php";?>     
          </div>
        </div> 
      </div>
    </div> 
    <!-- end Recommended /Most Popular -->    
  </div>

  
</div>
<?php include "model/customer.php"; ?>
<?php echo (new customer())->insertCustomerView(); ?>
<?php
include "common/footer.php";
?>

