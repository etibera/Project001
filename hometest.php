<?php 
	include "common/headertest.php";
	require_once 'model/home_new.php';     
    require_once "model/latest_promo.php";
    $home_new_mod=new home_new();
    $model_lp= new LatestPromo();
    $latestPromoList=$model_lp->getLatesPromolist_id(66); 
    $custid = isset($_SESSION['user_login']) ? $_SESSION['user_login']: 0;
   /* $home_new_mod->insertCustomerView();
    $home_new_mod->insertCustomerViewHourly();*/
/* $get_latest_promonew2=$home_new_mod->getproduct_brand_new();
 echo "<pre>";
 print_r($get_latest_promonew2);
*/
 if($is_log){ include "popup_ads.php";}else{ include "LatesloginModal.php";} 
?>
 <style>
 @media only screen and (max-width: 2560px) {
     .flCardBody{height: 275px !important;}
     .dlCardimg{height: 277px !important;width: 321px!important;}
     .cardFS{padding-bottom: 0px !important;}
 } 
 @media only screen and (max-width: 1400px) {
     .flCardBody{height: 239px !important;}
     .cardFS{padding-bottom: 6px !important;}
 }
 
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
	<div class="" style="margin-top: 203px;">
		<div class="row">
			 <div class="col-sm-9 p-1"><!-- Size 830X320 -->
			 	<div style="margin-top: -79px;"><?php include "homepageMainCarousel.php";?></div>
			 </div>
			 <div class="col-sm-3 p-1 justify-content-center" style="margin-top: -79px;padding: 0;">
			 	<?php  $GetFeaturedBrand=$home_new_mod->GetFeaturedPromo(3,330,390); ?>
			 	<a href="product_category_new.php?promo_id=<?php echo $GetFeaturedBrand[0]['id']; ?>">  
			 		<img src="<?php echo $GetFeaturedBrand[0]['thumb'];?>" class="img-fluid" style="border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;">
			 	</a>
			 </div>
		</div>
	</div>
	<div class="row-home">	
		<div class="row p-0">
			<div class="col-sm-6 p-1">
				<div class="card border-0"  style="background: linear-gradient(177deg, #09C6F9 10%, #045DE9 100%);border-radius: 25px;">
					<div class="card-header text-center border-0 text-dark" style="border-top-left-radius: 25px; border-top-right-radius: 25px;"> <img src="assets/FLAGHSHIP_white.png" class="img-fluid"></div>
				    <div class="card-body border-0" >
			 			<?php include "homepageMainFlagship.php";?>			    	
				    </div>
				</div>  
			 </div>
			 <div class="col-sm-3 p-1">
			 	<div class="card border-0"  style="background: linear-gradient(177deg, #09C6F9 10%, #045DE9 100%);border-radius: 25px;">
					<div class="card-header text-center border-0 text-dark" style="border-top-left-radius: 25px; border-top-right-radius: 25px;">Watch Out For</div>
				    <div class="card-body border-0 flCardBody" >
				    	<?php include "homepageMainWatchOutFor.php";?>		
				    </div>
				</div>
			 </div>
			 <div class="col-sm-3  p-1">
			 	<div class="card  border-0" style="background: linear-gradient(177deg, #09C6F9 10%, #045DE9 100%);border-radius: 25px;">
					<div class="card-header text-center  border-0 text-dark" style="border-top-left-radius: 25px; border-top-right-radius: 25px;">Brands</div>
				    <div class="card-body  border-0 overflow-auto flCardBody" >	
				    	<?php include "homepageMainBrands.php";?>
				    </div>
				</div>  
			 </div>
		</div>
	</div>
	
	<!-- DELARS -->
	<div class="row-home">	
		<div class="row p-0" style="height: 285px;">
			<div class="col-sm-9 p-1">
				<div class="card border-0"  style="background: linear-gradient(177deg, #09C6F9 10%, #045DE9 100%);border-radius: 25px;">
					<div class="card-header text-center text-dark border-0" style="border-top-left-radius: 25px; border-top-right-radius: 25px;">Dealers</div>
				    <div class="card-body border-0" >	
				    	<?php include "homepageMainDealers.php";?>	
				    </div>
				</div>  
			</div>
			<div class="col-sm-3 p-1">
				<?php  $GetFeaturedStore=$home_new_mod->GetFeaturedPromo(2,321,277);?>
				<?php include "homepageMainFeaturedStore.php";?>				
			 </div>
		</div>
	</div>
	<div class="row-home">
		<div class="row p-0">
			<?php  $GetFeaturedPromo=$home_new_mod->GetFeaturedPromo(1,518,246); $FeaturedPromocount=0; ?>
			<?php foreach ($GetFeaturedPromo as $gfp) { ?>
				<?php $count_selected_seller=$model_lp->getCount_selected_seller($gfp['id']); ?>
			 	<div class="col-sm-6 p-1">
			 		<div class="card border-0 cardFS" style="background:  linear-gradient(177deg, #ebdcdf 10%, #ff0844 100%); border-radius: 25px;">
						<div class="card-header text-center text-light border-0 p-0">
							<a href="product_category_new.php?promo_id=<?php echo $gfp['id']; ?>">
								<img src="<?php echo $gfp['thumb'];?>" class="img-fluid" style="width: 100%; height: 100%;border-top-left-radius: 25px; border-top-right-radius: 25px;">
							</a>
						</div>
						 <div class="card-body border-0" >
						 	<?php if($count_selected_seller!=0){ ?> 
						 		<?php $FeaturedPromoProducts = $model_lp->FeaturedPromoProducts($gfp['id'],6); ?>
						 		<?php include "homepageMainFeaturedPromoProducts.php";?>	
						 	<?php }else{}?>
						 </div>
					</div>
			 	</div>
			<?php  $FeaturedPromocount++ ;} ?>			
		</div>
	</div>
	<!-- Latest Promo -->
	<div class="row-home">	
		<div class="row p-0">			
			<div class="col-sm-12 p-1">
			    <div class="card border-0" style="background: linear-gradient(177deg, #09C6F9 10%, #045DE9 100%);border-radius: 25px;">
					<div class="card-header text-center text-dark border-0" style="border-top-left-radius: 25px; border-top-right-radius: 25px;">Promos</div>
				    <div class="card-body border-0">	
				    	<?php include "homepageMainRegularPromo.php";?>	
				    </div>
				</div> 		 
			 </div>
		</div>
	</div>
	<!-- FASH SALE -->
	<?php  $list_lppseller = $model_lp->GetHopgeSellerPromo(66);?>	
	<?php if(count($list_lppseller)){ ?>
		<div class="row-home">	
			<div class="row p-0">			
				<div class="col-sm-12 p-1">
				    <div class="card border-0" style="background: linear-gradient(177deg, #09C6F9 10%, #045DE9 100%);border-radius: 25px;">
						<div class="card-header text-center text-dark border-0" style="border-top-left-radius: 25px; border-top-right-radius: 25px;"><?php echo $latestPromoList['title'];?></div>
					    <div class="card-body border-0">					    	
					    	<?php include "homepageMainLatestPromo.php";?>	
					    </div>
					</div> 		 
				 </div>
			</div>
		</div>
	<?php }?>
	<!-- Recommended /Most Popular -->
	<div class="row-home">	
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
	</div>
	<div class="row-home">	
		<div class="row p-0">
			<div class="col-sm-12 p-1">
				<div class="card border-0" style="background: linear-gradient(177deg, #09C6F9 10%, #045DE9 100%); border-radius: 25px;">
					<div class="card-header text-center text-dark border-0" style="border-top-left-radius: 25px; border-top-right-radius: 25px;">Daily Discover</div>
				    <div class="card-body border-0">				    	
				    	<div class="row">			    	
				     		<?php include "homepageMainProductList.php";?>	
				     	</div>
				     	<div class="row">			    	
				     		<a type="button" class="btn btn-light text-decoration-none" href="DailyDiscover.php?pageNumber=2">See More</a>
				     	</div>			
				    </div>
				</div>  
			</div>			
		</div>
	</div>
</div>
<script type="text/javascript">
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
</script>
<?php include "common/footer.php"; ?>

