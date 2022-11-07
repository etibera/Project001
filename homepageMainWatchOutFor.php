<style type="text/css">
  .swiper-WatchOutpagination span.swiper-pagination-bullet.swiper-pagination-bullet-active {
    background-color: #FFF;
    opacity: 1;
  }
  .swiper-WatchOutpagination .swiper-pagination-bullet {
    background-color: #b4afc5;
    opacity: 1;
  }
</style>
<div class="swiper WatchOutForSwiper">
  <div class="swiper-wrapper">
    <?php  $GetWatchOutFor=$home_new_mod->GetFeaturedPromo(4,200,200);; ?>
    <?php foreach ($GetWatchOutFor as $GWOF) { ?>
      <div class="swiper-slide">
        <?php  if($GetCutomerType=="2"){ ?>
          <!--  for 4gives Customers -->
            <?php if($GWOF['exclusive_for']=="1"){ ?>
              <!--  exclusive_for lanbank Customers -->
              <a  data-bs-toggle="modal" data-bs-target="#LAnbankUserOnly">
            <?php }else{ ?>
               <a href="product_category_new.php?promo_id=<?php echo $GWOF['id']; ?>&t=<?php echo  uniqid();?>">   
           <?php } ?>
           
          <?php }else if($GetCutomerType=="1"){ ?>
           <!--  for lanbank Customers -->
            <?php if($GWOF['exclusive_for']=="2"){ ?>
              <!--  exclusive_for lanbank Customers -->
              <a  data-bs-toggle="modal" data-bs-target="#FgivesUserOnly">
            <?php }else{ ?>
              <a href="product_category_new.php?promo_id=<?php echo $GWOF['id']; ?>&t=<?php echo  uniqid();?>">   
           <?php } ?>
          <?php }else{ ?>
            <!-- for regular Customers -->
            <?php if($GWOF['exclusive_for']=="1"){ ?>
              <!--  exclusive_for lanbank Customers -->
              <a  data-bs-toggle="modal" data-bs-target="#LAnbankUserOnly">
            <?php }else if($GWOF['exclusive_for']=="2"){ ?>
              <!--  exclusive_for lanbank Customers -->
              <a   data-bs-toggle="modal" data-bs-target="#FgivesUserOnly">
            <?php }else{ ?>
             <a href="product_category_new.php?promo_id=<?php echo $GWOF['id']; ?>&t=<?php echo  uniqid();?>">   
           <?php } ?>
        <?php } ?>  
        
          <img src="<?php echo $GWOF['thumb'];?>"  class="img-fluid" style="border-radius: 10px;">     
        </a>  
      </div>
    <?php } ?>   
  </div>
</div>
<div class="swiper-WatchOutpagination"  style="text-align: center; position: absolute;bottom: 0;right: 0;left: 0;"></div>
<script>
  var swiper = new Swiper(".WatchOutForSwiper", {
    slidesPerView: 1,
    pagination: {
      el: ".swiper-WatchOutpagination",
      clickable: true,
    },
    autoplay: true,
    loop: true,
  });
</script>