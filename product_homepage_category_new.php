
  <style type="text/css">
   .slider-phc .slick-slide {
    height: auto;
    margin: 2px
    }
    #div-phcn{
    background: #fff;
    border-radius: 10px;
    margin: 5px 5px;
    height: 95px;
    box-shadow: 0px 0px 9px -2px rgb(0 0 0 / 35%)
  }
  #div-phcn:hover {
  border: 1px solid #777;
}
  </style>
  <section class="center-phc slider-phc">
    <?php
     $getCategories_new=$home_new_mod->getCategoriesNew(0);
      foreach ($getCategories_new as $category) :
      $imageval=$category['thumb']; //for live
      $b64catid = base64_encode($category['category_id']);
      $b64name = base64_encode('cat_id');
      ?> 
      <div id="div-phcn">
        <div data-toggle="tooltip" title="<?php echo $category['name'];?>">
          <a href="product_category.php?<?php echo $b64name;?>=<?php echo $b64catid;?>" style="margin:auto;color:#333">
             <img data-lazy="<?php echo $imageval;?>"  style="margin:auto"/>
          </a>
        </div>
        <div style="text-align: center;">
           <small ><?php echo $category['name'];?></small>
        </div>
      </div>
     <?php endforeach; ?>
  </section>
  <script >
    $(".center-phc").slick({
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
