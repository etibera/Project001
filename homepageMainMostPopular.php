<style type="text/css">
  .card-mpp:hover { border: 1px solid #777;}
  .no-image{
      background-color: #e0e0e0;
      height: 96px;
    }
  .swiper-pagination-mpp span.swiper-pagination-bullet.swiper-pagination-bullet-active {
    background-color: #FFF;
    opacity: 1;
  }
  .swiper-pagination-mpp .swiper-pagination-bullet {
    background-color: #b4afc5;
    opacity: 1;
  }
}
</style>
<div class="swiper MPPSwiper">
  <div class="swiper-wrapper">    
    <?php $most_popular=$home_new_mod->most_popular_new(9);?>  
    <?php foreach ($most_popular as $mpp) { $mpp_name=utf8_encode($mpp['name']);?>    
        <?php  $rpimg =$mpp['thumb']; ?>
        <?php $namempp = strlen($mpp_name) > 25 ? substr($mpp_name,0,25)."..." : $mpp_name;?>  
      <div class="swiper-slide">
        <div class="card card-mpp">
          <div class="card-header" >
            <a href="<?php echo $mpp['href']; ?>" >
              <?php if($rpimg!=""): ?>
                <img src="<?php echo $rpimg; ?>" alt="<?php echo $mpp['name']; ?>" class="rounded-3 bg-light   img-fluid" />
              <?php else: ?>
                 <p class="no-image"><i class="fa fa-shopping-bag"></i></p>
              <?php endif; ?>  
            </a>
          </div>
          <div class="card-body p-1">
            <div style="height:25px;overflow: hidden;">
              <span style="font-size: 10px;">
                <a class="text-black  text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo utf8_encode($mpp['name']); ?>" href="<?php echo $mpp['href']; ?>"><?php echo $namempp; ?></a>
              </span>
            </div> 
          </div>  
        </div>
      </div>
    <?php } ?>
  </div>  
</div>
<div class="swiper-pagination-mpp"  style="justify-content: center;right: 0 !important;left: 0; margin: auto;width: 15%;color: #fff;font-weight: 300;margin-bottom: -12px;   text-align: center;"></div>
<script>
  var swiper = new Swiper(".MPPSwiper", {
    freeMode: true,
    pagination: {
      el: ".swiper-pagination-mpp",
      clickable: true,
    },
    slidesPerView: 3.5,
    loop: true,
    spaceBetween: 2,
  });
</script>