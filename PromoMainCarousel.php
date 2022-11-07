
<div class="swiper PromoMainSwiper">
  <div class="swiper-wrapper">
    <?php foreach ($lp_banner_new as $hbnn) { ?>
      <div class="swiper-slide">
        <img src="<?php echo $hbnn['thumb'];?>" class="d-block img-fluid" style="border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;">        
      </div>
    <?php } ?>   
  </div>
  <div class="swiper-PromoMainpagination"></div>
</div>
<script>
  var swiper = new Swiper(".PromoMainSwiper", {
    slidesPerView: 1,
    pagination: {
      el: ".swiper-PromoMainpagination",
      clickable: true,
    },
    autoplay:true,
    loop: true,
  });
</script>