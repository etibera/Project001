<style>
.banner-slider .icon-next, .icon-next:hover {
  position: absolute;
  top: 41%;
  right: 14px;
  cursor: pointer;
  opacity: 0.3
}
.banner-slider .icon-prev, .icon-prev:hover {
  position: absolute;
  top: 41%;
  left: 5px;
  z-index: 1;
  cursor: pointer;
  opacity: 0.3
}
.banner-slider .slick-track{
  height: 400px !important;
}
.banner-slider .slick-list{
   margin: 0;
}
.image-container {
    height: 200px  !important;
}
</style>
<div class="row" id="ca1">
   <div class="banner-slider">
      <li>
        <div class="image-container">
          <a href="#"> 
          <img class="img-responsive" style="margin:auto;"src="<?php echo $imgvl;?>" alt="<?php echo utf8_encode($product_detail['name']);?>" />
          </a>
        </div>
      </li> 
      <?php foreach ($model_product->getimages3($get_product_id_val) as $imgval) {
        $image3=$imgval['thumb']; ?>
        <li>
          <div class="image-container">
            <a href="#"> 
            <img class="img-responsive" style="margin:auto;"src="<?php echo $image3;?>" alt="<?php echo utf8_encode($product_detail['name']);?>" />
            </a>
          </div>
        </li> 
      <?php } ?>
  </div>
</div>
<script>
    $('.banner-slider').slick({
        dots: false,
        rows: 0,
        infinite: true,
        speed: 500,
        fade: true,
        cssEase: 'linear',
        autoplaySpeed: 2000,
        autoplay: true,
        centerMode: true,
        nextArrow: '<i class="fa fa-angle-right icon-next"></i>',
        prevArrow: '<i class="fa fa-angle-left icon-prev"></i>',
        swipeToSlide: true,
        autoplaySpeed: 2000,
        arrows: true,
        centerPadding: '0px',
        responsive: [{
          breakpoint: 1023,
          settings: {
            swipeToSlide: true,
            centerMode: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true
          }
        },
        {
           breakpoint: 400,
           settings: {            infinite: true,
            speed: 300,
            slidesToShow: 1,
            adaptiveHeight: true,
            centerPadding: '0px',
            arrows: true
           }
        }]
    });
</script>