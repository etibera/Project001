<div class="swiper brandMainSwiper">
  <div class="swiper-wrapper">
      <div class="swiper-slide">
        <img src="<?php echo $productsBname[0]['banner_img'];?>" class="d-block img-fluid" style="border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;">        
      </div>        
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
