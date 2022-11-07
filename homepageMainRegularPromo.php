<style type="text/css">
  .swiper-regularPromopagination span.swiper-pagination-bullet.swiper-pagination-bullet-active {
    background-color: #FFF;
    opacity: 1;
  }
  .swiper-regularPromopagination .swiper-pagination-bullet {
    background-color: #b4afc5;
    opacity: 1;
  }
</style>
<div class="swiper regularPromoSwiper">
  <div class="swiper-wrapper">
    <?php  $GetRegularPromo=$home_new_mod->GetRegularPromo(1); ?>
    <?php foreach ($GetRegularPromo as $grp) { ?>
      <div class="swiper-slide">
        <?php  if($GetCutomerType=="2"){ ?>
          <!--  for 4gives Customers -->
            <?php if($gfsp['exclusive_for']=="1"){ ?>
              <!--  exclusive_for lanbank Customers -->
              <a  class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#LAnbankUserOnly">
            <?php }else{ ?>
               <a href="product_category_new.php?promo_id=<?php echo $grp['id']; ?>&t=<?php echo  uniqid();?>">
           <?php } ?>
           
          <?php }else if($GetCutomerType=="1"){ ?>
           <!--  for lanbank Customers -->
            <?php if($grp['exclusive_for']=="2"){ ?>
              <!--  exclusive_for lanbank Customers -->
              <a  class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#FgivesUserOnly">
            <?php }else{ ?>
              <a href="product_category_new.php?promo_id=<?php echo $grp['id']; ?>&t=<?php echo  uniqid();?>">
           <?php } ?>
          <?php }else{ ?>
            <!-- for regular Customers -->
            <?php if($grp['exclusive_for']=="1"){ ?>
              <!--  exclusive_for lanbank Customers -->
              <a  class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#LAnbankUserOnly">
            <?php }else if($grp['exclusive_for']=="2"){ ?>
              <!--  exclusive_for lanbank Customers -->
              <a  class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#FgivesUserOnly">
            <?php }else{ ?>
              <a href="product_category_new.php?promo_id=<?php echo $grp['id']; ?>&t=<?php echo  uniqid();?>"> 
           <?php } ?>
        <?php } ?>  
          <img src="<?php echo $grp['thumb'];?>"  class="img-fluid" style="border-radius: 10px;">     
        </a>  
      </div>
    <?php } ?>   
  </div>
</div>
<div class="swiper-regularPromopagination"  style="text-align: center; position: absolute;bottom: 0;right: 0;left: 0;"></div>
<script>
  var swiper = new Swiper(".regularPromoSwiper", {
    slidesPerView: 4.5,
    pagination: {
      el: ".swiper-regularPromopagination",
      clickable: true,
    },
    loop: true,
    spaceBetween: 5,
    autoplay: {
      delay: 0,
      disableOnInteraction: false
    },
    speed: 50000,
  });
</script>