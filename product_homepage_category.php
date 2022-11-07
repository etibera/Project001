
  <style type="text/css">
   .slider-phc .slick-slide {
    height: auto;
    margin: 2px
    }
  </style>
  <section class="center-phc slider-phc">
    <?php
      foreach ($model_home->getCategories(0) as $category) :   
      //$image="https://pesoapp.ph/img/".$category['image'];
      $image=$category['thumb']; //for live
      $b64catid = base64_encode($category['category_id']);
      $b64name = base64_encode('cat_id');
      ?> 
      <div data-toggle="tooltip" title="<?php echo $category['name'];?>" class="image-plp">
        <a href="product_category.php?<?php echo $b64name;?>=<?php echo $b64catid;?>" style="margin:auto;color:#333">
           <img src="<?php echo $image;?>" style="width: 50px;height: 50px" style="margin:auto"/>
           <p><small><?php echo $category['name'];?></small></p>
        </a>
      </div>
     <?php endforeach; ?>
  </section>
  <script >
    $(".center-phc").slick({
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
