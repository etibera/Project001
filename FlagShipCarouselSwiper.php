<style type="text/css">
  .FlagShipCategorySwiper {
      width: 100%;
      height: 100%;
      margin-left: auto;
      margin-right: auto;
    }
    .swiper-slide {
      text-align: center;
      /* Center slide text vertically */
      display: -webkit-box;
      display: -ms-flexbox;
      display: -webkit-flex;
      display: flex;
      -webkit-box-pack: center;
      -ms-flex-pack: center;
      -webkit-justify-content: center;
      justify-content: center;
      -webkit-box-align: center;
      -ms-flex-align: center;
      -webkit-align-items: center;
      align-items: center;
    }   
    .image-overlay {
    height: 100px;
    width: 100%;
    background-image: linear-gradient(
     rgba(11, 0, 197,0.5),
      rgba(231, 76, 60, 0.5)
      ),
      url(https://media.istockphoto.com/photos/quality-control-certification-checked-guarantee-of-standard-picture-id1282804749?b=1&k=20&m=1282804749&s=170667a&w=0&h=pxajgIoOB8XGjTPHwWAsnVS3PCoUZWxBwCYBdpqCVk8=);
    background-size: cover;
    background-position: top;
    position: relative; 
}
  
  .overlay-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #aaa;
    white-space: nowrap;
  }
</style>
<div class="swiper FlagShipCategorySwiper" style="border-radius: 20px;">
  <div class="swiper-wrapper" >
     <?php
     $FSBrand_id=$FSmod->GetFlasShipSelectedBrand($sellerId);
     $getCategories_new=$home_new_mod->getbrandcategory($FSBrand_id);
      foreach ($getCategories_new as $category2) :?>
        <div class="swiper-slide">
          <div class="image-overlay" >
            <div class="overlay-text">
              <div  style="z-index: 2">
                <a class="nav-item nav-link text-light" style="font-size: 14px;" href="FlagShipCategoryItems.php?sellerId=<?php echo  $sellerId;?>&cat_id=<?php echo $category2['category_id'];?>&catname=<?php echo $category2['name'];?>&t=<?php echo  uniqid();?>"  role="tab" data-toggle="tab"><?php echo $category2['name'];?></a>
              </div>
            </div>
          </div>
        </div>            
      <?php endforeach; ?>         
  </div>
  <div class="swiper-button-next"></div>
  <div class="swiper-button-prev"></div>

</div>
<script>
   var swiper = new Swiper('.FlagShipCategorySwiper', {
      slidesPerView: 5,
      spaceBetween: 2,
      slidesPerColumnFill: 'column',     
      navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
   
   
    });
</script> 