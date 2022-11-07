<style type="text/css">
  .card-pr:hover { border: 1px solid #777;}
  .no-image{
      background-color: #e0e0e0;
      height: 96px;
    }
  .swiper-pagination3 span.swiper-pagination-bullet.swiper-pagination-bullet-active {
    background-color: #FFF;
    opacity: 1;
  }
  .swiper-pagination3 .swiper-pagination-bullet {
    background-color: #b4afc5;
    opacity: 1;
  }
}
</style>
<div class="swiper RFYSwiper">
  <div class="swiper-wrapper">    
    <?php foreach ($FlagShipRecommendedForYou as $rp) { $rp_name=utf8_encode($rp['name']);?>    
        <?php  $rpimg =$rp['thumb']; ?>
        <?php $namerp = strlen($rp_name) > 25 ? substr($rp_name,0,25)."..." : $rp_name;?>  
      <div class="swiper-slide">
        <div class="card card-pr">
          <div class="card-header" >
            <a href="<?php echo $rp['href']; ?>" >
              <?php if($rpimg!=""): ?>
                <img src="<?php echo $rpimg; ?>" alt="<?php echo $rp['name']; ?>" class="rounded-3 bg-light   img-fluid" />
              <?php else: ?>
                 <p class="no-image"><i class="fa fa-shopping-bag"></i></p>
              <?php endif; ?>  
            </a>
          </div>
          <div class="card-body p-1">
            <div class="text-center"  style="height:25px;overflow: hidden;">
              <span style="font-size: 10px;">
                <a class="text-black  text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo utf8_encode($rp['name']); ?>" href="<?php echo $rp['href']; ?>"><?php echo $namerp; ?></a>        
              </span>
            </div> 
          </div>  
        </div>
      </div>
    <?php } ?>
  </div>  
</div>
<div class="swiper-pagination3"  style="justify-content: center;right: 0 !important;left: 0; margin: auto;width: 15%;color: #fff;font-weight: 300;margin-bottom: -12px;   text-align: center;"></div>
<script>
  var swiper = new Swiper(".RFYSwiper", {
    freeMode: true,
    pagination: {
      el: ".swiper-pagination3",
      clickable: true,
    },
    slidesPerView: 6.5,
    loop: true,
    spaceBetween: 5,

  });
</script>