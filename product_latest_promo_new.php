
  <style type="text/css">
   .slider-pr_lp .slick-slide {
    height: auto;
    margin: 5px
    }
     #div-plpn_img:hover {
  border: 1px solid #777;
}
.slick-slider .slick-track, .slick-slider .slick-list{
      transition-timing-function: linear !important;
}
    
  </style>
  <section class="center-pr_lp slider-pr_lp">
      <?php 
      $get_latest_promonew=$home_new_mod->get_latest_promonew(1);
      foreach ($get_latest_promonew as $list) : ?>
        <div data-toggle="tooltip" title="Click for more details" >
            <?php                
            $b64name = base64_encode('cat_id');
            ?>
             <?php $img =$list['thumb']; ?> 
          <a href="product_category_new.php?promo_id=<?php echo $list['id']; ?>">
             <img data-lazy="<?php echo $img; ?>" class="img-responsive"/ style="border-radius: 10px;" id="div-plpn_img">
          </a>             
        </div>
      <?php endforeach; ?>
  </section>      
  <script>
      $(".center-pr_lp").slick({
        lazyLoad: 'ondemand',
        dots: false,
        infinite: true,
        slidesToShow: 5,
        nextArrow: '<i class="fa fa-angle-right icon-next"></i>',
        prevArrow: '<i class="fa fa-angle-left icon-prev"></i>',
        arrows: true,
        autoplay: true,
        autoplaySpeed: 1,
        slidesToScroll: .03,
        loop: true,
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
