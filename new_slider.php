
<?php include "model/home.php";
$model_home= new home();

$custid = isset($_SESSION['user_login']) ? $_SESSION['user_login']: 0;  
$gethomecategorylist = $model_home->gethomecategorylist($custid);
if($gethomecategorylist['countid']==0){  
  $results1 = $model_home->gethomecategorylistbydef(0);
}else{     
  $results1 =  $model_home->gethomecategorylistbydef($custid);
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Slick Playground</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="./slick/slick.css">
  <link rel="stylesheet" type="text/css" href="./slick/slick-theme.css">

</head>
<body>
  <style type="text/css">
   .slider-ph .slick-slide {
    height: auto;
    margin: 2px
    }
    .card-ph {
      margin: auto;
      text-align: center;
      font-family: arial
      font-size: 6px;
      box-shadow: 0 0.0625rem 0.125rem 0 rgba(0,0,0,.6);
      border-radius: 5px;
    }
    .image-container-ph {
    height: 150px;
     /*overflow:hidden;*/
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
    <div class="col-lg-12 categoryhomes">
      <h5><?php echo $category_name; ?></h5>
    </div>
    <section class="center-ph slider-ph">
      <?php foreach ($results as $products_rec) : ?>            
              <?php $productdesc_rec = $model_home->getproduct($products_rec['product_id']);?>
              <?php  $getimg ="img/".$productdesc_rec['image']; ?>
                <div class="card-ph" >
                  <div class="product-thumb transition">
                    <div class="image-container-ph">
                    <div data-toggle="tooltip" title="Click for more details" class="image">
                      <a  href="product.php?product_id=<?php echo $productdesc_rec['product_id']; ?>">
                      <?php if(file_exists($getimg)): ?>
                        <img src="<?php echo $getimg; ?>" alt="<?php echo $productdesc_rec['name']; ?>" class="img-responsive" />
                      <?php else: ?>
                         <p class="no-image"><i class="fa fa-shopping-bag"></i></p>
                      <?php endif; ?>                    
                    </div>
                    </div>
                    <div class="caption" >
                    <?php $name = strlen($productdesc_rec['name']) > 25 ? substr($productdesc_rec['name'],0,25)."..." : $productdesc_rec['name'];?>
                      <h4><a data-toggle="tooltip" title="<?php echo utf8_encode($productdesc_rec['name']); ?>" href="product.php?product_id=<?php echo $productdesc_rec['product_id']; ?>"><?php echo utf8_encode($name); ?></a></h4>
                      <p  style="color:#e81b30"><b>₱<?php echo   number_format($productdesc_rec['price'],2);?></b></p>
                      </p>
                    </div> 
                    <div  class="button-group" >
                      <?php if($is_log): ?>
                        <a type="button" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 18px;"
                          class="btn btn-pink btn-addtocart_home" data-product_id="<?php echo $productdesc_rec['product_id'];?>" data-name="<?php echo $productdesc['name'];?>" data-user_id="<?php echo $_SESSION['user_login'];?>"><i data-feather="shopping-cart"></i> Add to cart</a>
                      <?php else: ?>
                        <a type="button" data-toggle="modal" data-target="#LoginModal" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 18px;"><i data-feather="shopping-cart"></i> Add to cart</a>
                        <?php endif;?>
                    </div>     
                  </div> 
                </div>    
          <?php endforeach; ?>
    </section> 
  <?php endforeach;?>
 

  <script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
  <script src="./slick/slick.js" type="text/javascript" charset="utf-8"></script>
  <script type="text/javascript">
    $(document).on('ready', function() {
      $(".center-ph").slick({
        dots: true,
        infinite: false,
        slidesToShow: 6,
        nextArrow: '<i class="fa fa-angle-right icon-next"></i>',
        prevArrow: '<i class="fa fa-angle-left icon-prev"></i>',
        arrows: true,
        slidesToScroll: 6,
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
    
    });
</script>


</body>
</html>
