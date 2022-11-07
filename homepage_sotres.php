
  <style type="text/css">
   .slider-hps .slick-slide {
    height: auto;
    margin: 2px
    }
     #div-hs{
    background: #fff;
    border-radius: 10px;
    margin: 5px 5px;
    height: 110px;
    box-shadow: 0px 0px 9px -2px rgb(0 0 0 / 35%)
  }
   #div-hs:hover {
  border: 1px solid #777;
}
  </style>
  <section class="center-hps slider-hps">
    <?php
      foreach ($home_new_mod->getstores_new() as $hps) : 
      ?> 
       <div id="div-hs">
        <div data-toggle="tooltip" title="<?php echo $hps['shop_name'];?>" class="image-hps">
          <a href="product_store.php?Y2F0X2lk=<?php echo $hps['seller_id'];?>" style="margin:auto;color:#333">
             <img data-lazy="<?php echo $hps['thumb'];?>" style="width: 100px;height: 100px;margin:auto"/>
          </a>
        </div>
        <!-- <div style="text-align: center;">
           <small ><?php echo $hps['shop_name'];?></small>
        </div> -->
      </div>
     <?php endforeach; ?>
  </section>
  <script >
    $(".center-hps").slick({
      lazyLoad: 'ondemand',
      dots: false,
      infinite: true,
      slidesToShow: 10,
      nextArrow: '<i class="fa fa-angle-right icon-next"></i>',
      prevArrow: '<i class="fa fa-angle-left icon-prev"></i>',
      arrows: true,
      slidesToScroll: 10,
      responsive: [{
        breakpoint: 1023,
        settings: {
          slidesToShow: 4,
          slidesToScroll: 4,
          arrows: false
        }
      },
      {
         breakpoint: 767,
         settings: {
            arrows: false,
            slidesToShow: 4,
           slidesToScroll: 4,
            arrows: false
         }
      },
       {
         breakpoint: 425,
         settings: {
            arrows: false,
            slidesToShow: 4,
            slidesToScroll: 4,
            arrows: false
         }
      }
      ]
    });
</script>
