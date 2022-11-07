<style type="text/css">
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
</style>
<?php $FSbanner=$FSmod->FSbanner($sellerId); ?>
<div class="swiper flsSwiper">
  <div class="swiper-wrapper">
    <?php foreach ($FSbanner as $FSBNN) { ?>
      <div class="swiper-slide">
        <img src="<?php echo "img/".$FSBNN['banner_web']?>" class="d-block img-fluid" >
      </div>
     <?php } ?>
  </div>
  <div class="swiper-pagination"></div>
</div>	
<script>
  var swiper = new Swiper(".flsSwiper", {
    slidesPerView: 1,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    autoplay:true,
    loop: true,
  });
</script>