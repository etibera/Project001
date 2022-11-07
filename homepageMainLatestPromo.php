<style type="text/css">
  .card-Mprl:hover { border: 1px solid #777;}
  .LPSwiper {
      width: 100%;
      height: 100%;
      margin-left: auto;
      margin-right: auto;
    }
    .swiper-slide {
      text-align: center;
      /* Center slide text vertically */
      display: -webkit-box;
      display: -ms-flexbox;
      display: -webkit-flex;
      display: flex;
      -webkit-box-pack: center;
      -ms-flex-pack: center;
      -webkit-justify-content: center;
      justify-content: center;
      -webkit-box-align: center;
      -ms-flex-align: center;
      -webkit-align-items: center;
      align-items: center;
    }
    .no-image{
      background-color: #e0e0e0;
      height: 200px;
    }
    .card-Mprl:hover { border: 1px solid #777;}
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
<div class="swiper LPSwiper">
  <div class="swiper-wrapper">
    <?php if(count($list_lppseller)==0){}else{?>
      <?php foreach ($list_lppseller  as $product) { $p_name=utf8_encode($product['name']); ?>
        <?php  $getimg =$product['thumb']; ?>
        <?php $name = strlen($p_name) > 25 ? substr($p_name,0,25)."..." : $p_name;?>
        <div class="swiper-slide"> 
          <div class="card card-Mprl">
            <!-- ribbon -->
            <div class="ribbon">
              <span class="ribbon3">
                <?php if($product['deduction_type']=="0"){ ?>
                  <?php echo number_format($product['value']); ?>% OFF
                <?php }else { ?>
                  ₱<?php echo number_format($product['value']); ?> OFF
                <?php } ?>
              </span>
            </div>
            <!--  END ribbon -->
            <!-- promoImgVal -->
            <?php if($product['promoImgVal']!=""){ ?> 
              <div style="width:auto;height:auto;text-align:center;
                    font-size: 12px;
                    position: absolute;
                    float: right;
                    right: 0;
                    top: 0;">
                <img   src="<?php echo 'img/'.$product['promoImgVal']; ?>" alt="<?php echo $product['name']; ?>" class="img-fluid" />
              </div>
            <?php } ?> 
            <!-- END promoImgVal -->
            <!-- card-header -->
            <div class="card-header" >
              <a href="<?php echo $product['href']; ?>" >
                <?php if($getimg!=""): ?>
                  <img src="<?php echo $getimg; ?>" alt="<?php echo $product['name']; ?>" class="rounded-3 bg-light   img-fluid" />
                <?php else: ?>
                   <p class="no-image"><i class="fa fa-shopping-bag"></i></p>
                <?php endif; ?>  
              </a>
            </div>
            <!-- END card-header -->
            <!-- card-body -->
            <div class="card-body p-1">
              <div class="row">
                <div class="col-sm-9">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="text-center" style="height:18px;overflow: hidden;">
                              <span style="font-size: 10px;">
                                <a class="text-black  text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo utf8_encode($product['name']); ?>" href="<?php echo $product['href']; ?>"><?php echo $name; ?></a>        
                              </span>
                            </div> 
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="text-center text-danger" style="height:19px;overflow: hidden;">
                              <span style="font-size: 12px;"><b class="">₱<?php if($product['deduction_type']=="1"){ ?> 
                                         <?php echo number_format($product['price']-$product['rate'],2);?>
                                      <?php }else{  ?>
                                        <?php $deductval=$product['price']*$product['rate']; ?>
                                        <?php echo number_format($product['price']-$deductval,2);?>
                                      <?php } ?></b>  
                              </span>
                            </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="text-center text-danger" style="height:19px;overflow: hidden;">
                              <span style="font-size: 12px;"><b class="" style="text-decoration: line-through; display: inline-block;color: #9e9e9e;" >₱<?php echo number_format($product['price'],2);?></b>  
                              </span>
                            </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-3 p-0">
                  <div class="row" style="height:38px;">
                    <div class="col-sm-12"  style="    width: 52px;
                          height: 43px;
                          padding: 0px;
                          position: absolute;
                          float: right;
                          right: 0;
                          bottom: 8px;">
                      <img  src="<?php echo $product['sellerimage']; ?>" alt="<?php echo $product['name']; ?>" class="img-fluid" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- END card-body -->
          </div>
        </div>
      <?php } ?>        
    <?php }?>    
  </div>
  <div class="swiper-button-next"></div>
  <div class="swiper-button-prev"></div>

</div>
<div>
  <div class="swiper-pagination1" style="    background: #fb1b1b6b;justify-content: center;right: 0 !important;left: 0; margin: auto;width: 15%;color: #fff;font-weight: 300;margin-bottom: -12px;   text-align: center;"></div>
</div>
<script>
   var swiper = new Swiper('.LPSwiper', {
      slidesPerView: 6.5,
      slidesPerColumn: 3,
      spaceBetween: 2,
      slidesPerColumnFill: 'column',
      pagination: {
          el: ".swiper-pagination1",
          type: "fraction",
        },
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
   
   
    });
</script> 