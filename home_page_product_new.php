<style type="text/css">
   
   .slider-hppn .slick-slide {
    height: auto;
    margin: 2px
    }
    #div_card-hppn {
      margin: auto;
      text-align: center;
      font-family: arial
      font-size: 6px;
      box-shadow: 0 0.0625rem 0.125rem 0 rgba(0,0,0,.6);
      border-radius:10px;background: #fff;
    }
    .caption-hppn {
       height:60px; 
    }
    #div_card-hppn:hover { border: 1px solid #777;}
  </style>
<?php 
 $gethomecategorylist = $home_new_mod->gethomecategorylist_new($custid); 
  if($gethomecategorylist['countid']==0){  
  $results1 = $home_new_mod->gethomecategorylistbydef_new(0);
  }else{     
    $results1 =  $home_new_mod->gethomecategorylistbydef_new($custid);
  }
  $data_home_page_category=array();  
  foreach ($results1 as $categoryhome) {
      $data_home_page_product=array();
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
     /* if($status=='1'){
        $results = $home_new_mod->getProductsbycategory_product_new($category_id,$show,$sort_order);
      }else{
        $results = $home_new_mod->getProductsbycategory_new($category_id,$show,$sort_order);
      }*/
     /* foreach ($results as $p_id) {
          $data_home_page_product[]= $home_new_mod->getproduct_new($p_id['product_id']);
      }*/
      $results = $home_new_mod->getProductsbycategory_new($category_id,$show,$sort_order);
      $data_home_page_category[] = array(
              'category_name' => $category_name,
              'product' => $results 
      );
  }
foreach ($data_home_page_category as $categoryhome) : ?>
  <div class="row">        
    <div class="col-lg-12 hpc-cat nav_header" >
      <h5><?php echo $categoryhome['category_name']; ?></h5>
    </div>
    <div class="col-lg-12 hpc-catv">
      <section class="center-hppn slider-hppn">
        <?php foreach ($categoryhome['product'] as $products_hppn) : ?>
        <?php  $getimg =$products_hppn['thumb']; ?>
        <div class="card-hppn" id="div_card-hppn">
            <div class="">
              <div class="image-container-pr">
                  <div data-toggle="tooltip" title="Click for more details" class="image">
                     <a  href="<?php echo $products_hppn['href']; ?>">
                       <?php if($getimg!=""): ?>
                          <img data-lazy="<?php echo $getimg; ?>" alt="<?php echo $products_hppn['name']; ?>" class="img-responsive" />
                        <?php else: ?>
                           <p class="no-image"><i class="fa fa-shopping-bag"></i></p>
                        <?php endif; ?>  
                    </a>                        
                  </div>
              </div>
              <div class="caption-hppn" >
              <?php $name = strlen($products_hppn['name']) > 25 ? substr($products_hppn['name'],0,25)."..." : $products_hppn['name'];?>
                <h6><a data-toggle="tooltip" title="<?php echo utf8_encode($products_hppn['name']); ?>" href="<?php echo $products_hppn['href']; ?>"><?php echo utf8_encode($name); ?></a></h6>
                <p  style="color:#e81b30; font-size: 12px"><b>₱<?php echo   number_format($products_hppn['price'],2);?></b></p>
                </p>
              </div> 
               <div>
                  <?php if($is_log){ ?>
                       <a type="button"class="btn btn-pink btn_addtocart_bg" href="<?php echo $products_hppn['href']; ?>"  style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i data-feather="shopping-cart"></i> Add to cart</a>                                  
                   <?php }else{ ?>
                       <a type="button" class="btn btn-pink btn_addtocart_bg"data-toggle="modal" data-target="#LoginModal"  style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i data-feather="shopping-cart" ></i> Add to cart</a>
                   <?php } ?>
              </div>
            </div> 
          </div>   
        <?php endforeach; ?>
      </section>
    </div>      
  </div>
<?php endforeach;?>
<script>
      $(".center-hppn").slick({
        lazyLoad: 'ondemand',
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