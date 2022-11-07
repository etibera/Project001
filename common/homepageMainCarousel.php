
<div class="swiper MainSwiper">
  <div class="swiper-wrapper">
    <?php $getBanner_new=$home_new_mod->getBanner_new2(11); $countbnn=0; ?>
    <?php foreach ($getBanner_new as $hbnn) { ?>
      <div class="swiper-slide">
        <img src="<?php echo $hbnn['thumb'];?>" class="d-block img-fluid" > 
        <!-- <img src="https://pesoapp.ph/assets/BANNER.jpg" class="d-block img-fluid" > -->
      </div>
    <?php } ?>
  </div>
  <div class="swiper-pagination"></div>
</div>
<script>
  var swiper = new Swiper(".MainSwiper", {
    slidesPerView: 1,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    autoplay:true,
    loop: true,
  });
</script>