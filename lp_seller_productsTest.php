

<?php
include "common/header.php";
require_once "model/latest_promo.php";
require_once 'model/home_new.php'; 
$home_new_mod=new home_new();
$model_lp= new LatestPromo();
$promo_id = isset($_GET['promo_id']) ? $_GET['promo_id']: 0;  
$latestPromoList=$model_lp->getLatesPromolist_id($_GET['promo_id']); 
$lp_banner_new=$home_new_mod->get_Banner_new_lp(13,$_GET['promo_id']);
//$lp_Thumbnail_image=$model_lp->GetLp_Thumbnail_image($_GET['promo_id']); 
if(count($lp_banner_new)==0){
  $getBanner_new=$home_new_mod->getBanner_new(11) ;
}else{
   $getBanner_new=$lp_banner_new;
}

$list_lpp = $model_lp->get_lp_products($promo_id);
$count_selected_seller=$model_lp->getCount_selected_seller($promo_id);
$custid = isset($_SESSION['user_login']) ? $_SESSION['user_login']: 0;
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
    if($status=='1'){
      $results = $home_new_mod->getProductsbycategory_product_new($category_id,$show,$sort_order);
    }else{
      $results = $home_new_mod->getProductsbycategory_new($category_id,$show,$sort_order);
    }
    foreach ($results as $p_id) {
        $data_home_page_product[]= $home_new_mod->getproduct_new($p_id['product_id']);
    }
    $data_home_page_category[] = array(
            'category_name' => $category_name,
            'product' => $data_home_page_product
    );
  }
/*echo"<pre>";
print_r($list_lpp);*/
?>
<style>
.card-lpp {
  margin: auto;
  text-align: center;
  font-family: arial
  font-size: 6px;
  box-shadow: 0 0.0625rem 0.125rem 0 rgba(0,0,0,.6);
  border-radius: 5px;
}

.no-image{
    background-color: #e0e0e0;
    height: 150px;
}
.no-image i {
    font-size: 50px;
    margin-top: 50px;
    color: #333;
}
.caption-lpp{
      height:25px; 
    }
.image-container{
      height:200px; 
    }
     .container { width: 100%; }
     
 .ribbon {
  width: 48%;
  position: relative;
  float: left;
  margin-bottom: 30px;
  background-size: cover;
  text-transform: uppercase;
  color: white;
}
.ribbon3 {
    width: 100px;
    height: 25px;
    line-height: 25px;
    padding-left: 15px;
    position: absolute;
    left: -8px;
    top: 20px;
    background: red;
    font-size: 80%;
  }
  .ribbon3:before, .ribbon3:after {
    content: "";
    position: absolute;
  }
  .ribbon3:before {
    height: 0;
    width: 0;
    top: -8.5px;
    left: 0.1px;
    border-bottom: 9px solid black;
    border-left: 9px solid transparent;
  }
  .ribbon3:after {
    height: 0;
    width: 0;
    right: -17px;
    border-top: 15px solid transparent;
    border-bottom: 10px solid transparent;
    border-left: 18px solid red;
  } 
     
</style>



<div class="wrapper">
  <div class="container_home" >
    <div class="row">
      <div class="col-lg-12 ca-home" >
          <?php include "carousel_new.php";?>
      </div>     
    </div>
       <!--promo Products-->
      
    <div class="row">
      <div class="col-lg-12" >
        <div class="wrapper">
      <div class="container" style=" margin-top: -.5em;">
        <?php 
        $list_lppseller = $model_lp->get_lp_seller_products($promo_id);
        if(!$list_lppseller){}else{
        ?>
        <div class="row">
                <div class="col-xs-12">
                  <h5>Latest Promo (<?php echo $latestPromoList['title'];?>)</h5>
                </div>
            </div>  
        <div class="row">
        <?php
          foreach($list_lppseller as $product) { $p_name=utf8_encode($product['name']);?>
            <?php  $getimg =$product['thumb']; ?>
            <?php $name = strlen($p_name) > 25 ? substr($p_name,0,25)."..." : $p_name;?>
            <?php if($is_mobile){ ?>
               <div class="col-xs-6" style="padding:2px;">
            <?php }else{ ?>  
              <div class="col-xs-6 col-md-3 col-lg-2" style="padding:5px;">
            <?php }?> 
              <div class="card-lpp" >
                   <div class="ribbon">
                    <span class="ribbon3"><?php if($product['deduction_type']=="0"){ ?>
                                      <?php echo number_format($product['value']); ?>% OFF
                                  <?php }else { ?>
                                          ₱<?php echo number_format($product['value']); ?> OFF
                                  <?php } ?>
                                  </span>
                  </div>
                  <!--<div 
                                    style="width:80px;
                                    height:auto;
                                    background:#CC0000;
                                    color:#FFF;
                                    padding:1px 1px;
                                    text-align:center;
                                    top:0;font-size:12px;
                                    position:absolute; float:right;">
                                                    

                                </div>-->
                                <div 
                                        style="width: 70px;
                                        height: 50px;
                                        padding: 5px 2px;
                                        font-size: 12px;
                                        position: absolute;
                                        float: right;
                                        right: 0;
                                        bottom: 25px;">
                                             <img  style="width: 50px;
                                        height: 50px;" src="<?php echo $product['sellerimage']; ?>" alt="<?php echo $product['name']; ?>" class="img-responsive" />             
    
                                    </div>
                                    <?php if($product['promoImgVal']!=""){ ?> 
                                     <div 
                                        style="width:auto;
                                    height:auto;
                                        padding:1px 1px;
                                    text-align:center;
                                        font-size: 12px;
                                        position: absolute;
                                        float: right;
                                        right: 0;
                                        top: 0;">
                                             <img   src="<?php echo 'img/'.$product['promoImgVal']; ?>" alt="<?php echo $product['name']; ?>" class="img-responsive" />
                                    </div>
                                    <?php } ?>                                    
                      <div class="product-thumb transition">
                          <div class="image-container">
                    <div data-toggle="tooltip" title="Click for more details" class="image">
                              <a  href="<?php echo $product['href']; ?>">
                                 <?php if($getimg!=""): ?>
                                    <img src="<?php echo $getimg; ?>" alt="<?php echo $product['name']; ?>" class="img-responsive" />
                                  <?php else: ?>
                                     <p class="no-image"><i class="fa fa-shopping-bag"></i></p>
                                  <?php endif; ?>  
                              </a>           
                          </div>
                          </div>
                          <div class="caption-lpp" style="text-align:center;">
                            <h6 ><a data-toggle="tooltip" title="<?php echo utf8_encode($product['name']); ?>" href="<?php echo $product['href']; ?>"><?php echo $name; ?></a></h6>                             
                          </div>
                          <div class="price" style="text-align:center;">  
                            <p  style="color:#e81b30" ><b>₱
                            <?php if($product['deduction_type']=="1"){ ?> 
                               <?php echo number_format($product['price']-$product['rate'],2);?>
                            <?php }else{  ?>
                              <?php $deductval=$product['price']*$product['rate']; ?>
                              <?php echo number_format($product['price']-$deductval,2);?>
                            <?php } ?>
                            </b></p>
                            <p style="text-decoration: line-through; display: inline-block;color: #9e9e9e;">
                                        ₱<?php echo number_format($product['price'],2);?>
                                    </p>  
                                                          
                          </div>
                           
                          <div>
                            <?php if($is_log){ ?>
                               <a type="button"class="btn btn-pink btn_addtocart_bg" href="<?php echo $product['href']; ?>"  style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i data-feather="shopping-cart"></i> Add to cart</a>                             
                                 <?php }else{ ?>
                                   <a type="button" data-toggle="modal" data-target="#LoginModal"  style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i data-feather="shopping-cart" ></i> Add to cart</a>
                                 <?php } ?>
                          </div>
                      </div>
                   </div> 
            </div>         
            <?php
          }
        }
        ?>
        </div>
      </div>  
    </div>              
      </div>     
    </div>
   <!--  peso_mall -->
      <div class="row">
        <div class="col-xs-12">
          <ul class="nav nav-tabs">
            <li class="active">
              <a data-toggle="tab" href="#peso_mall"> 
                 <h5><b>Recommended For You</b></h5>
              </a>
            </li>
          </ul>
          <div class="tab-content">
            <div id="peso_mall" class="tab-pane fade in active">              
                  <?php include "product_homepage_new.php";?> 
            </div>
          </div>
        </div>
      </div>
  </div>
</div>
<?php include "model/customer.php"; ?>
<?php echo (new customer())->insertCustomerView(); ?>
<?php
include "common/footer.php";
?>



  
<?php
//include "common/footer.php";
?>
 <script>
    $(document).ready(function() {
      $(".btn-addtocart_bg").click(function(){
            var product=$(this).data('product_id');
            var cust_id=$(this).data('user_id');
            var name=$(this).data('name');
            
          $.ajax({
            url: 'ajax_add_to_cart.php',
            type: 'POST',
            data: 'product_bg=' + product + '&cust_id=' + cust_id,
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

