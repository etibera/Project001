
<div class="swiper FeaturedStoreSwiper">
  <div class="swiper-wrapper">
    <?php foreach ($GetFeaturedStore as $gfsp) { ?>
      <div class="swiper-slide">
        <?php  if($GetCutomerType=="2"){ ?>
          <!--  for 4gives Customers -->
            <?php if($gfsp['exclusive_for']=="1"){ ?>
              <!--  exclusive_for lanbank Customers -->
              <a  class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#LAnbankUserOnly">
            <?php }else{ ?>
               <a href="product_category_new.php?promo_id=<?php echo $gfsp['id']; ?>&t=<?php echo  uniqid();?>">
           <?php } ?>
           
          <?php }else if($GetCutomerType=="1"){ ?>
           <!--  for lanbank Customers -->
            <?php if($gfsp['exclusive_for']=="2"){ ?>
              <!--  exclusive_for lanbank Customers -->
              <a  class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#FgivesUserOnly">
            <?php }else{ ?>
              <a href="product_category_new.php?promo_id=<?php echo $gfsp['id']; ?>&t=<?php echo  uniqid();?>">
           <?php } ?>
          <?php }else{ ?>
            <!-- for regular Customers -->
            <?php if($gfsp['exclusive_for']=="1"){ ?>
              <!--  exclusive_for lanbank Customers -->
              <a  class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#LAnbankUserOnly">
            <?php }else if($gfsp['exclusive_for']=="2"){ ?>
              <!--  exclusive_for lanbank Customers -->
              <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#FgivesUserOnly">
            <?php }else{ ?>
              <a href="product_category_new.php?promo_id=<?php echo $gfsp['id']; ?>&t=<?php echo  uniqid();?>"> 
           <?php } ?>
        <?php } ?>
        <a href="product_category_new.php?promo_id=<?php echo $gfsp['id']; ?>">   
          <img src="<?php echo $gfsp['thumb'];?>"  class="img-fluid dlCardimg" style="border-radius: 25px;">     
        </a>  
      </div>
    <?php } ?>  
  </div>
  <div class="swiper-button-next"></div>
  <div class="swiper-button-prev"></div>
</div>
<script>
  var swiper = new Swiper(".FeaturedStoreSwiper", {
   slidesPerView: 1,    
   freeMode: true,
    pagination: {
      el: ".swiper-paginationfs",
      clickable: true,
    },    
    loop: true,
     navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
  });
</script>