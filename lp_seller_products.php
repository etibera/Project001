
<style>
  .card-lpp {
      margin: auto;
      text-align: center;
      font-family: arial
      font-size: 6px;
      box-shadow: 0 0.0625rem 0.125rem 0 rgba(0,0,0,.6);
      border-radius:10px;background: #fff;
      height: 332px;

}
.card-lpp:hover { border: 1px solid #777;}
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
  <div class="container" style=" margin-top: -.5em;">
    <?php 
    if(count($list_lppseller)==0){}else{
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
                        <span class="ribbon3">
                          <?php if($product['deduction_type']=="0"){ ?>
                                  <?php echo number_format($product['value']); ?>% OFF
                                <?php }else { ?>
                                    ₱<?php echo number_format($product['value']); ?> OFF
                                <?php } ?>
                            </span>
                      </div>
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
                                     <div style="width:auto;height:auto;text-align:center;
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

