
 <style type="text/css">
  .slider-pbrand .slick-slide {
    height: auto;
    margin: 2px
    }
  .no-image2{
      background-color: #e0e0e0;
      height: 75px;
  }
  .no-image2 i {
      font-size: 50px;
      margin-top: 5px;
      text-align: center;
      color: #333;
  }
  #div-phb{
    background: #fff;
    border-radius: 10px;
    margin: 5px 5px;
    height: 85px;
    box-shadow: 0px 0px 9px -2px rgb(0 0 0 / 35%)
  }
    #div-phb:hover {
  border: 1px solid #777;
}
  </style>
  <section class="center-pbrand slider-pbrand">
    <?php
     $getproduct_brand_new=$home_new_mod->getproduct_brand_new();
      foreach ($getproduct_brand_new as $p_brand) :
      $mggval=$p_brand['thumb']; //for live
      $b64catid = base64_encode($p_brand['id']);
      ?> 
      <div data-toggle="tooltip" title="<?php echo $p_brand['name'];?>" class="image-plp" id="div-phb">

        <a href="product_brand.php?Y2F0X2lk=<?php echo $b64catid;?>" style="margin:auto;color:#333">
          <?php if($mggval!=""): ?>
              <img data-lazy="<?php echo $mggval; ?>" alt="<?php echo $p_brand['name']; ?>" style="margin:auto" class="img-responsive" />
          <?php else: ?>
          <p class="no-image2"><i class="fa fa-shopping-bag"></i></p>
          <?php endif; ?>  
         <!--   <p><small><?php echo $p_brand['name'];?></small></p> -->
        </a>
      </div>
     <?php endforeach; ?>
  </section>
  <script >
    $(".center-pbrand").slick({
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
