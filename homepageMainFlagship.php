<?php $getFlagshipStores=$home_new_mod->getFlagshipStores() ;?>
<div class="swiper FlagShipSwiper">
  <div class="swiper-wrapper">    
    <?php foreach ($getFlagshipStores as $gfs) { ?>
      <div class="swiper-slide">
         <a class="text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $gfs['shop_name']; ?>"  href="FlagshipStoresHome.php?sellerId=<?php echo  $gfs['seller_id'];?>&t=<?php echo  uniqid();?>"> 
          <img src="<?php echo $gfs['thumb'];?>" class="img-fluid rounded-circle bg-light">
        </a>
      </div>
    <?php } ?>
  </div>
  <div class="swiper-pagination"></div>
</div>
<script>
  var swiper = new Swiper(".FlagShipSwiper", {
    slidesPerView: 2.5,
    spaceBetween: 10,
    freeMode: true,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    autoplay: {
      delay: 0,
      disableOnInteraction: false
    },
    speed: 50000,
    loop: true,
  });
</script>