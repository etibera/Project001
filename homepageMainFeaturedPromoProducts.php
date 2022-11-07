<style type="text/css">    
    .no-image{
      background-color: #e0e0e0;
      height: 200px;
    }
    .card-lpp:hover { border: 1px solid #777;}
</style>

<div class="swiper gfpSwiper" style="margin-top: -30px;">
  <div class="swiper-wrapper">
    <?php if(count($FeaturedPromoProducts)==0){}else{?>
      <?php foreach ($FeaturedPromoProducts  as $product) { $p_name=utf8_encode($product['name']); ?>
        <?php  $getimg =$product['thumb']; ?>
        <?php $name = strlen($p_name) > 25 ? substr($p_name,0,25)."..." : $p_name;?>

         <?php   $btnForexclusive="";
                 $btnForexclusive2="";
          if($GetCutomerType=="2"){ 
            if($gfp['exclusive_for']=="1"){ 
               $btnForexclusive='<a data-bs-toggle="modal" data-bs-target="#LAnbankUserOnly">';
               $btnForexclusive2='<a data-bs-toggle="modal" class="text-black  text-decoration-none"  data-bs-target="#LAnbankUserOnly">'.$name.'</a>';
               }else{ 
                 $btnForexclusive='<a href="'.$product['href'].'&t='.uniqid().'">';
                 $btnForexclusive2='<a class="text-black  text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="'.utf8_encode($product['name']).'" href="'.$product['href'].'&t='.uniqid().'">'.$name.'</a>';
               } 
            }else if($GetCutomerType=="1"){ 
              if($gfp['exclusive_for']=="2"){ 
                $btnForexclusive='<a data-bs-toggle="modal" data-bs-target="#FgivesUserOnly">';
                $btnForexclusive2='<a data-bs-toggle="modal" class="text-black  text-decoration-none"  data-bs-target="#FgivesUserOnly">'.$name.'</a>';
              }else{
                $btnForexclusive='<a href="'.$product['href'].'&t='.uniqid().'">';
               $btnForexclusive2='<a class="text-black  text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="'.utf8_encode($product['name']).'" href="'.$product['href'].'&t='.uniqid().'">'.$name.'</a>';
              }
            }else{ 
              if($gfp['exclusive_for']=="1"){
                $btnForexclusive='<a data-bs-toggle="modal" data-bs-target="#LAnbankUserOnly">';
                $btnForexclusive2='<a data-bs-toggle="modal" class="text-black  text-decoration-none"  data-bs-target="#LAnbankUserOnly">'.$name.'</a>';
              }else if($gfp['exclusive_for']=="2"){ 
                 $btnForexclusive='<a data-bs-toggle="modal" data-bs-target="#FgivesUserOnly">';
                  $btnForexclusive2='<a data-bs-toggle="modal" class="text-black  text-decoration-none"  data-bs-target="#FgivesUserOnly">'.$name.'</a>';
              }else{ 
                $btnForexclusive='<a href="'.$product['href'].'&t='.uniqid().'">';
                $btnForexclusive2='<a class="text-black  text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="'.utf8_encode($product['name']).'" href="'.$product['href'].'&t='.uniqid().'">'.$name.'</a>';
              } 
            } 
          ?>              
        <div class="swiper-slide"> 
          <div class="card card-lpp">
            <div class="card-header" >
              <?php echo $btnForexclusive;?>
                <?php if($getimg!=""): ?>
                  <img src="<?php echo $getimg; ?>" alt="<?php echo $product['name']; ?>" class="rounded-3 bg-light   img-fluid" />
                <?php else: ?>
                   <p class="no-image"><i class="fa fa-shopping-bag"></i></p>
                <?php endif; ?>  
              </a>
            </div>
            <div class="card-body p-1">
              <div style="height:25px;overflow: hidden;">
                <span style="font-size: 10px;">
                  <?php echo $btnForexclusive2;?>       
                </span>
              </div>
              <div class="text-light" style="background-image: linear-gradient(135deg, #b92b27 10%, #1565C0 100%);height: 100%;font-size: 10px;">
                <?php if($product['deduction_type']=="0"){ ?>
                  <?php echo 'Save up to '.number_format($product['value']); ?>% OFF
                <?php }else { ?>
                    <?php echo 'Save up to  ₱'.number_format($product['value']); ?> OFF
                <?php } ?>
              </div>                
            </div>  
          </div> 
        </div>
      <?php } ?>        
    <?php }?>    
  </div>
</div>
<div>
  <div class="swiper-paginationgfp<?php echo $FeaturedPromocount;?>"  style="justify-content: center;right: 0 !important;left: 0; margin: auto;width: 15%;color: #fff;font-weight: 300;margin-bottom: -12px;   text-align: center;"></div>
</div>
<script>
   var swiper = new Swiper('.gfpSwiper', {
      	freeMode: true,
	    pagination: {
	      el: ".swiper-paginationgfp<?php echo $FeaturedPromocount;?>",
	      clickable: true,
	    },
	    slidesPerView: 4.5,
	    loop: true,
	    spaceBetween: 10,
    });
</script> 