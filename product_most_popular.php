 <style type="text/css">
   
   .slider-mp .slick-slide {
    height: auto;
    margin: 2px
    }
    .card-mp {
      margin: auto;
      text-align: center;
      font-family: arial
      font-size: 6px;
      box-shadow: 0 0.0625rem 0.125rem 0 rgba(0,0,0,.6);
      border-radius: 5px;

    }
   
  </style>
  <section class="center-mp slider-mp">
        <?php foreach ($most_popular as $products_most) : ?>
          <?php if($products_most['type']=='0'){ ?> 
            <?php $productdesc_rec = $model_home->getproduct($products_most['product_id']);?>
            <?php  $getimg ="img/".$productdesc_rec['image']; ?>
              <div class="card-mp" style="margin: 0px 15px">
                <div>
                  <div class="image-container-mp">
                  <div data-toggle="tooltip" title="Click for more details" class="image">
                    <a  href="product.php?product_id=<?php echo $productdesc_rec['product_id']; ?>">
                    <?php if(file_exists($getimg)): ?>
                      <img src="<?php echo $getimg; ?>" alt="<?php echo $productdesc_rec['name']; ?>" class="img-responsive" />
                    <?php else: ?>
                       <p class="no-image"><i class="fa fa-shopping-bag"></i></p>
                    <?php endif; ?>                    
                  </div>
                  </div>
                  <div class="caption-mp" >
                  <?php $name = strlen($productdesc_rec['name']) > 25 ? substr($productdesc_rec['name'],0,25)."..." : $productdesc_rec['name'];?>
                    <h6><a data-toggle="tooltip" title="<?php echo utf8_encode($productdesc_rec['name']); ?>" href="product.php?product_id=<?php echo $productdesc_rec['product_id']; ?>"><?php echo utf8_encode($name); ?></a></h6>
                    <p  style="color:#e81b30"><b>₱<?php echo   number_format($productdesc_rec['price'],2);?></b></p>
                    </p>
                  </div> 
                  <div  class="button-group" >
                    <?php if($is_log): ?>
                      <a type="button" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"
                        class="btn btn-pink btn-addtocart_home" data-product_id="<?php echo $productdesc_rec['product_id'];?>" data-name="<?php echo $productdesc['name'];?>" data-user_id="<?php echo $_SESSION['user_login'];?>"><i data-feather="shopping-cart"></i> Add to cart</a>
                    <?php else: ?>
                      <a type="button" data-toggle="modal" data-target="#LoginModal" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i data-feather="shopping-cart"></i> Add to cart</a>
                     
                    <?php endif;?>
                  </div>     
                </div> 
              </div>             
               
            <?php }else if($products_most['type']=='2'){ ?> 
              <?php $productdesc_rec = $model_home->getproduct_bg($products_most['product_id']);?>
              <?php  $getimg =$productdesc_rec['image']; ?>
              <div class="card-mp" style="margin: 0px 15px">
                <div>
                  <div class="image-container-mp">
                  <div data-toggle="tooltip" title="Click for more details" class="image">
                    <a  href="bg_product.php?product_idbg=<?php echo $productdesc_rec['product_id']; ?>">
                      <img src="<?php echo $getimg; ?>" alt="<?php echo $productdesc_rec['name']; ?>" class="img-responsive" />
                  </div>
                  </div>
                  <div class="caption" >
                  <?php $name = strlen($productdesc_rec['name']) > 25 ? substr($productdesc_rec['name'],0,25)."..." : $productdesc_rec['name'];?>
                    <h6><a data-toggle="tooltip" title="<?php echo utf8_encode($productdesc_rec['name']); ?>" href="bg_product.php?product_idbg=<?php echo $productdesc_rec['product_id']; ?>"><?php echo utf8_encode($name); ?></a></h6>
                    <p  style="color:#e81b30"><b>₱<?php echo   number_format($productdesc_rec['price'],2);?></b></p>
                    </p>
                  </div> 
                  <div  class="button-group" >
                    <?php if($is_log): ?>
                      <a type="button" 
                                    class="btn btn-pink btn-addtocart_bg" href="bg_product.php?product_idbg=<?php echo $productdesc_rec['product_id']; ?>"><i data-feather="shopping-cart"></i> Add to cart</a>  
                    <?php else: ?>
                      <a type="button" data-toggle="modal" data-target="#LoginModal" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 16px;"><i data-feather="shopping-cart"></i> Add to cart</a>
                     
                    <?php endif;?>
                  </div>     
                </div> 
              </div>             
            <?php }else{} ?> 
       
        <?php endforeach; ?>
    </section>  
 <script>
      $(".center-mp").slick({
        dots: true,
        infinite: true,
        slidesToShow: 8,
        nextArrow: '<i class="fa fa-angle-right icon-next"></i>',
        prevArrow: '<i class="fa fa-angle-left icon-prev"></i>',
        arrows: true,
        slidesToScroll: 8,
        responsive: [{
          breakpoint: 1023,
          settings: {
            slidesToShow: 2,
            arrows: false
          }
        },
        {
           breakpoint: 767,
           settings: {
              arrows: false,
              slidesToShow: 2,
              slidesToScroll: 2,
              arrows: false
           }
        },
         {
           breakpoint: 425,
           settings: {
              arrows: false,
              slidesToShow: 2,
              slidesToScroll: 2,
              arrows: false
           }
        }
        ]
      });
</script>      
<script>
    $(document).ready(function() {
      $(".btn-addtocart_home").click(function(){
            var product=$(this).data('product_id');
            var cust_id=$(this).data('user_id');
            var name=$(this).data('name');
          $.ajax({
            url: 'ajax_add_to_cart.php',
            type: 'POST',
            data: 'product=' + product + '&cust_id=' + cust_id,
            dataType: 'json',
            success: function(json) {
             
              if (json['success']) {
                  bootbox.alert(json['success']+" ("+name+")");
              }
            },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
          });
         
        });
    });
 </script>

