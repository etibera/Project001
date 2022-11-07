
  <style type="text/css">
   .slider-phcg .slick-slide {
    height: auto;
    margin: 2px
    }
    #div_pcg{
      background: #fff;
      border-radius: 10px;
      margin: 5px 5px;
      height: 110px;
      box-shadow: 0px 0px 9px -2px rgb(0 0 0 / 35%)
    }
    #div_pcg:hover {border: 1px solid #777;}
  </style>
  <section class="center-phcg slider-phcg">
    <?php
      foreach ($first_cat as $categoryg) :
      $image=$categoryg['thumb']; //for live
      ?> 
      <div id="div_pcg">
        <div data-toggle="tooltip" title="<?php echo $categoryg['cat_name'];?>" class="image-plp">
          <a href="peso_global.php?cat_id=<?php echo $categoryg['cat_id']?>" style="margin:auto;color:#333">
             <img src="<?php echo $image;?>"  style="margin:auto"/>
          </a>
        </div>
        <div style="text-align: center;">
            <small><?php echo $categoryg['cat_name'];?></small>
        </div>
      </div>
     <?php endforeach; ?>
  </section>
  <script >
    $(".center-phcg").slick({
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
