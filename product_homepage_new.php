
<style>
#div_card-hppn {
      margin: auto;
      text-align: center;
      font-family: arial
      font-size: 6px;
      box-shadow: 0 0.0625rem 0.125rem 0 rgba(0,0,0,.6);
      border-radius:10px;background: #fff;
      height: 300px;

}
#div_card-hppn:hover { border: 1px solid #777;}
 .no-image{
    background-color: #e0e0e0;
    height: 150px;
}
}
.no-image i {
    font-size: 50px;
    margin-top: 50px;
    color: #333;
}
</style>
<?php 
$getDiscoverProducts=$home_new_mod->getDiscoverProducts(500) ;
?>
	<div class="row">
		 <?php foreach ($getDiscoverProducts as $products_hppn) : ?>
		 	<?php  $getimg =$products_hppn['thumb']; ?>
			<div class="col-xs-6 col-md-3 col-lg-2" style="padding:1px;">
				<div class="card-hppn" id="div_card-hppn">
	              <div class="image-container-pr" id="image_container_pr">
	                  <div data-toggle="tooltip" title="Click for more details" class="image">
	                     <a  href="<?php echo $products_hppn['href']; ?>">
	                       <?php if($getimg!=""): ?>
	                          <img src="<?php echo $getimg; ?>" alt="<?php echo $products_hppn['name']; ?>" class="img-responsive" />
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
	                     <a type="button" data-toggle="modal" data-target="#LoginModal"  style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i data-feather="shopping-cart" ></i> Add to cart</a>
	                 <?php } ?>
	            </div>
	          </div>   
			</div>
		<?php endforeach; ?> 
	</div>
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
