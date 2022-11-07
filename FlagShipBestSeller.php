<style type="text/css">
  .card-pr:hover { border: 1px solid #777;}
  .no-image{
      background-color: #e0e0e0;
      height: 96px;
    }
  .swiper-paginationBestSeller span.swiper-pagination-bullet.swiper-pagination-bullet-active {
    background-color: #FFF;
    opacity: 1;
  }
  .swiper-paginationBestSeller .swiper-pagination-bullet {
    background-color: #b4afc5;
    opacity: 1;
  }
}
</style>
<div class="swiper BestSellerSwiper">
  <div class="swiper-wrapper">    
    <?php foreach ($FlagShipBestSeller as $fsbs) { $fsbs_name=utf8_encode($fsbs['name']);?>    
        <?php  $rpimg =$fsbs['thumb']; ?>
        <?php $namefsbs = strlen($fsbs_name) > 25 ? substr($fsbs_name,0,25)."..." : $fsbs_name;?>  
      <div class="swiper-slide">
        <div class="card card-pr">
          <div class="card-header" >
            <a href="<?php echo $fsbs['href']; ?>" >
              <?php if($rpimg!=""): ?>
                <img src="<?php echo $rpimg; ?>" alt="<?php echo $fsbs['name']; ?>" class="rounded-3 bg-light   img-fluid" />
              <?php else: ?>
                 <p class="no-image"><i class="fa fa-shopping-bag"></i></p>
              <?php endif; ?>  
            </a>
          </div>
          <div class="card-body p-1">
            <div class="text-center"  style="height:25px;overflow: hidden;">
              <span style="font-size: 10px;">
                <a class="text-black  text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo utf8_encode($fsbs['name']); ?>" href="<?php echo $fsbs['href']; ?>"><?php echo $namefsbs; ?></a>        
              </span>
            </div> 
          </div>  
        </div>
      </div>
    <?php } ?>
  </div>  
</div>
<div class="swiper-paginationBestSeller"  style="justify-content: center;right: 0 !important;left: 0; margin: auto;width: 15%;color: #fff;font-weight: 300;margin-bottom: -12px;   text-align: center;"></div>
<script>
  var swiper = new Swiper(".BestSellerSwiper", {
    freeMode: true,
    pagination: {
      el: ".swiper-paginationBestSeller",
      clickable: true,
    },
    slidesPerView: 6.5,
    loop: true,
    spaceBetween: 5,

  });
</script>