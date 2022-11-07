<?php
include "common/headertest.php"; 
require_once 'model/product_store.php';
require_once 'model/home_new.php'; 
require_once 'model/SellerLatestPromo.php'; 
$model_SLP=new SellerLatestPromo();
$home_new_mod=new home_new();
$model_store=new product_store();
$custid = isset($_SESSION['user_login']) ? $_SESSION['user_login']: 0;
$storeid = isset($_GET['Y2F0X2lk']) ? $_GET['Y2F0X2lk']: 0;
$cat_id = isset($_GET['cat_id']) ? $_GET['cat_id']: 0;
$store_banner=$model_store->get_store_banner($storeid);
$store_name=$model_store->get_store_name($storeid);
if(isset($_GET['cat_id'])){
  $getSellerLatestPromo = $model_SLP->getSellerLatestPromoWithCategory($storeid,$_GET['cat_id']);
  $StoreProduct=$model_store->GetStoreProductByCat($storeid,$_GET['cat_id']);
}else{
  $getSellerLatestPromo = $model_SLP->getSellerLatestPromo($storeid);
  $StoreProduct=$model_store->GetStoreProduct($storeid);  
}



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
    <div class="" style="margin-top: 123px;">
      <!-- For store Banner -->
      <?php if(count($store_banner)==0){ ?>
          <div class="row">
            <div class="col-sm-9 p-0"><!-- Size 830X320 -->
                <div ><?php include "homepageMainCarousel.php";?></div>
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
                <div><?php include "carousel_store.php";?></div>
            </div>
          </div>
      <?php } ?>
      <!-- end For store Banner -->
      <!-- For Categories -->
      <div class="row p-0">
          <div class="col-sm-12 p-1"> 
            <div class="card border-0" style="background: linear-gradient(177deg, #09C6F9 10%, #045DE9 100%); border-radius: 25px;">
                <div class="card-header text-center text-light border-0" style="border-top-left-radius: 25px; border-top-right-radius: 25px;">           
                  <span class="align-middle"><h5>Categories</h5></span> 
                </div>
              <div class="card-body border-0">  
                <?php include "product_store_category.php";?>
              </div>
            </div>                                      
          </div>
      </div>
      <!-- end For Categories --> 
      <!-- For store Latest Promos --> 
      <?php if(count($getSellerLatestPromo)!=0){ ?> 
        <div class="row p-0">
            <div class="col-sm-12 p-1"> 
              <div class="card border-0" style="background: linear-gradient(177deg, #09C6F9 10%, #045DE9 100%);border-radius: 25px;">
                <div class="card-header text-center text-light border-0" style="border-top-left-radius: 25px; border-top-right-radius: 25px;">           
                  <span class="align-middle"><a href="product_store.php?Y2F0X2lk=<?php echo $storeid;?>" class="text-decoration-none  text-light"> Latest Promos </a> <?php if(isset($_GET['cat_id'])){ echo " > ".$_GET['cat_name']; } ?></span> 
                </div>
                <div class="card-body border-0">  
                  <div class="row">
                    <?php $list_lppseller = $getSellerLatestPromo; ?>    
                    <?php include "PromoMainSellerProductList.php"; ?>
                  </div>
                               
                   
                </div>
              </div>                                      
            </div>
          </div>
      <?php }?>
      <!-- end store Latest Promos -->
      <!-- For store Products -->
      <div class="row p-0">
          <div class="col-sm-12 p-1"> 
            <div class="card border-0" style="background: linear-gradient(177deg, #09C6F9 10%, #045DE9 100%);border-radius: 25px;">
              <div class="card-header text-center text-light border-0" style="border-top-left-radius: 25px; border-top-right-radius: 25px;">           
                <span class="align-middle"><span class="align-middle"><h5>
                <a href="product_store.php?Y2F0X2lk=<?php echo $storeid;?>" class="text-decoration-none  text-light">
                    <?php echo $store_name[0]['shop_name'];?>
                </a> 
                <?php if(isset($_GET['cat_id'])){ echo " > ".$_GET['cat_name']; }else{ echo" Products";} ?>
               </h5> </span> </span> 
              </div>
              <div class="card-body border-0">  
                <div class="row">
                  <?php $list_lpp = $StoreProduct; ?> 
                   <?php include "PromoMainProductList.php"; ?>
                </div>
              </div>
            </div>                                      
          </div>
        </div>
        <!-- end For store Products -->
    </div>
</div>
<?php include "model/customer.php"; ?>
<?php echo (new customer())->insertCustomerView(); ?>
<?php
include "common/footer.php";
?>

