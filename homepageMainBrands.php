
<style type="text/css">
  .masonry-column {
  padding: 0 1px;
}

.masonry-grid > div .thumbnail {
  margin: 5px 1px;
}
</style>
<div class="row masonry-grid" >
    <?php
     $getproduct_brand_new=$home_new_mod->getproduct_brand_new();
      foreach ($getproduct_brand_new as $p_brand) :
     $mggval=$p_brand['thumb']; //for live
      //$mggval='https://pesoapp.ph/img/'.$p_brand['image']; //for live
      $b64catid = base64_encode($p_brand['id']);
      ?>
       <div class="w-25">
        <a data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $p_brand['name']; ?>" href="product_brand.php?Y2F0X2lk=<?php echo $b64catid;?>" class="thumbnail"><img src="<?php echo $mggval;?>" class="w-100 m-1 rounded-circle bg-light"></a>
      </div>
      <?php endforeach; ?>
</div>