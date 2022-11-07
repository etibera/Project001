
<div class="swiper MainSwiper">
  <div class="swiper-wrapper">
    <?php $getBanner_new=$home_new_mod->getBanner_new2(11); $countbnn=0; ?>
    <?php foreach ($getBanner_new as $hbnn) { ?>
      <div class="swiper-slide">
        <img src="<?php echo $hbnn['thumb'];?>" class="d-block img-fluid" style="border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;">        
      </div>
    <?php } ?>
   <!--  <div class="swiper-slide" >
      <img src="https://pesoapp.ph/img/testpic/ipesomo830-x-320.jpg" class="d-block img-fluid" style="border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;">
    </div>
    <div class="swiper-slide">
      <img src="https://pesoapp.ph/img/testpic/HC830-x-320.jpg" class="d-block img-fluid" style="border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;">
    </div>   
    <div class="swiper-slide">
      <img src="https://pesoapp.ph/img/testpic/PM830-x-320.jpg" class="d-block img-fluid" style="border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;">
    </div>  
    <div class="swiper-slide">
      <img src="https://pesoapp.ph/img/testpic/0interest830-x-320.jpg" class="d-block img-fluid" style="border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;">
    </div>  -->    
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