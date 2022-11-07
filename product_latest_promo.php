
  <style type="text/css">
   .slider-pr_lp .slick-slide {
    height: auto;
    margin: 5px
    }
  </style>
  <section class="center-pr_lp slider-pr_lp">
    <?php                   
    $list_lp = $model_home->get_latest_pro_pic(1);?>
     <?php foreach ($list_lp as $list) : ?>
      <div data-toggle="tooltip" title="Click for more details" class="image-plp">
          <?php                
          $b64name = base64_encode('cat_id');
          ?> 
          <!--  <?php $img ="https://pesoapp.ph/img/".$list['image']; ?> -->
           <?php $img ="img/".$list['image']; ?> 
        <a href="product_category_new.php?promo_id=<?php echo $list['id']; ?>">
           <img src="<?php echo $img; ?>" class="img-responsive"/>
        </a>             
      </div>
    <?php endforeach; ?>
  </section>
  <script>
      $(".center-pr_lp").slick({
        dots: true,
        infinite: true,
        slidesToShow: 6,
        nextArrow: '<i class="fa fa-angle-right icon-next"></i>',
        prevArrow: '<i class="fa fa-angle-left icon-prev"></i>',
        arrows: true,
        slidesToScroll: 3,
        responsive: [{
          breakpoint: 1023,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
            arrows: false
          }
        },
        {
           breakpoint: 767,
           settings: {
              arrows: false,
              slidesToShow: 3,
              slidesToScroll: 3,
              arrows: false
           }
        },
         {
           breakpoint: 425,
           settings: {
              arrows: false,
              slidesToShow: 3,
              slidesToScroll: 3,
              arrows: false
           }
        }
        ]
      });
</script>
