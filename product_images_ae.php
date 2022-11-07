
<div class="row" id="ca1">
   <div class="banner-slider">
      <?php foreach ($product_detail['images'] as $imaages)  {?>
        <li>
          <div class="image-container">
             <a href="#"> 
				    <img class="img-responsive" style="margin:auto;  max-width: 100% !important; "src="<?php echo $imaages;?>" alt="<?php echo utf8_encode($product_detail['name']);?>" />
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
        arrows: false,
        centerPadding: '0px',
        responsive: [{
          breakpoint: 1023,
          settings: {
            swipeToSlide: true,
            centerMode: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false
          }
        },
        {
           breakpoint: 400,
           settings: {            infinite: true,
            speed: 300,
            slidesToShow: 1,
            adaptiveHeight: true,
            centerPadding: '0px',
            arrows: false
           }
        }]
    });
</script>