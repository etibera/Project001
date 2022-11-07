<style type="text/css">
  .DealerSwiper {
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
</style>
<div class="swiper DealerSwiper">
  <div class="swiper-wrapper">
    <?php foreach ($home_new_mod->getstores_new()  as $hps) { ?>
      <div class="swiper-slide">
        <a href="product_store.php?Y2F0X2lk=<?php echo $hps['seller_id'];?>" >
          <img src="<?php echo $hps['thumb'];?>" class="rounded-3 bg-light" style="width: 100px;height: 100px;margin:auto">
        </a>
      </div>
    <?php } ?>
  </div>
  <!-- <div class="swiper-pagination"></div> -->
</div>
<script>
   var swiper = new Swiper('.DealerSwiper', {
      slidesPerView: 7.5,
      slidesPerColumn: 2,
      spaceBetween: 2,
      loop:true,
      slidesPerColumnFill: 'column',
    });
</script> 