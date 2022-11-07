<style type="text/css">
   
   .slider-prho .slick-slide {
    height: auto;
    margin: 2px
    }
    .card-prho {
      margin: auto;
      text-align: center;
      font-family: arial
      font-size: 6px;
      box-shadow: 0 0.0625rem 0.125rem 0 rgba(0,0,0,.6);
      border-radius: 5px;

    }
  </style>
<?php foreach ($results1 as $categoryhome) : ?>
  <?php 
    $category_name=$categoryhome['name'];
    $category_id=$categoryhome['cid'];
    $show_limit=$categoryhome['show_limit'];
    $sort_order=$categoryhome['sort_order'];
    $status=$categoryhome['status'];

    if($show_limit==0){
      $show=12;
    }else{
      $show=$show_limit;
    }
    if($status=='1'){
      $results = $model_home->getProductsbycategory_product($category_id,$show,$sort_order);
    }else{
      $results = $model_home->getProductsbycategory($category_id,$show,$sort_order);
    }
  ?> 
  <div class="row">        
    <div class="col-lg-12 hpc-cat">
      <h5><?php echo $category_name; ?></h5>
    </div>
    <div class="col-lg-12 hpc-catv">
      <section class="center-prho slider-prho">
        <?php foreach ($results as $products) : ?>
        <?php  $productdesc = $model_home->getproduct($products['product_id']); ?>
          <?php  $getimg ="img/".$productdesc['image']; ?>
          <div class="card-prho" >
            <div class="">
              <div class="image-container-prho">
              <div data-toggle="tooltip" title="Click for more details" class="image">
                <a  href="product.php?product_id=<?php echo $productdesc['product_id']; ?>">
                <?php if(file_exists($getimg)): ?>
                  <img src="<?php echo $getimg; ?>" alt="<?php echo $productdesc['name']; ?>" class="img-responsive" />
                <?php else: ?>
                   <p class="no-image"><i class="fa fa-shopping-bag"></i></p>
                <?php endif; ?>                    
              </div>
              </div>
              <div class="caption-prho" >
              <?php $name = strlen($productdesc['name']) > 25 ? substr($productdesc['name'],0,25)."..." : $productdesc['name'];?>
                <h6><a data-toggle="tooltip" title="<?php echo utf8_encode($productdesc['name']); ?>" href="product.php?product_id=<?php echo $productdesc['product_id']; ?>"><?php echo utf8_encode($name); ?></a></h6>
                <p  style="color:#e81b30; font-size: 12px"><b>₱<?php echo   number_format($productdesc['price'],2);?></b></p>
                </p>
              </div> 
              <div  class="button-group">
                <?php if($is_log): ?>
                  <a type="button" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"
                    class="btn btn-pink btn-addtocart_home" data-product_id="<?php echo $productdesc['product_id'];?>" data-name="<?php echo $productdesc['name'];?>" data-user_id="<?php echo $_SESSION['user_login'];?>"><i data-feather="shopping-cart"></i> Add to cart</a>
                <?php else: ?>
                  <a type="button" data-toggle="modal" data-target="#LoginModal" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i data-feather="shopping-cart"></i> Add to cart</a>
                 
                <?php endif;?>
              </div>     
            </div> 
          </div>       
        <?php endforeach; ?>
      </section>
    </div>      
  </div>
<?php endforeach;?>
<script>
      $(".center-prho").slick({
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
            slidesToScroll: 2,
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