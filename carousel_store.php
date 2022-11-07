<div class="swiper brandMainSwiper">
  <div class="swiper-wrapper">
      <?php foreach ($store_banner as $banner) {?>
        <div class="swiper-slide">
          <img src="<?php echo $banner['imageWEB'];?>" class="d-block img-fluid" style="border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;">        
        </div>
      <?php } ?>      
  </div>
  <div class="swiper-brandMainpagination"></div>
</div>
<script>
  var swiper = new Swiper(".brandMainSwiper", {
    slidesPerView: 1,
    pagination: {
      el: ".swiper-brandMainpagination",
      clickable: true,
    },
    autoplay:true,
    loop: true,
  });
</script>
