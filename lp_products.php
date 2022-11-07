
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
</style>
<div class="wrapper">
	<div class="container" style=" margin-top: -.5em;">
		<?php	
		if(!$list_lpp){}else{
		?>
		<div class="row">
            <div class="col-xs-12">
              <h5>Latest Promo (<?php echo $latestPromoList['title'];?>)</h5>
            </div>
        </div>  
		<div class="row">
		<?php
			foreach($list_lpp as $product) { $p_name=utf8_encode($product['name']);?>
			 	<?php  $getimg =$product['thumb']; ?>
				<?php $name = strlen($p_name) > 25 ? substr($p_name,0,25)."..." : $p_name;?>
				<?php if($is_mobile){ ?>
					 <div class="col-xs-6" style="padding:2px;">
				<?php }else{ ?>  
					<div class="col-xs-6 col-md-3 col-lg-2" style="padding:5px;">
				<?php }?> 
				 	<div class="card-lpp" >
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
			              		 	<p  style="color:#e81b30" ><b>₱<?php echo   number_format($product['price'],2);?></b></p>
			              	</div>
			              	<div>
				              	<?php if($is_log){ ?>
				              		 <?php if($product['type']=="0"){ ?>
				              		 	<a type="button" 
	                         			class="btn btn-pink btn-addtocart" data-product_id="<?php echo $product['product_id'];?>" data-name="<?php echo $product['name'];?>" data-user_id="<?php echo $_SESSION['user_login'];?>" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i data-feather="shopping-cart"></i> Add to cart</a>
				              		 <?php }else if($product['type']=="2"){ ?>
				              		 	<a type="button"class="btn btn-pink btn_addtocart_bg" href="<?php echo $product['href']; ?>"  style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i data-feather="shopping-cart"></i> Add to cart</a>	
				              		 <?php }else{ ?>
				              		 	<a type="button"class="btn btn-pink btn_addtocart_cb" href="<?php echo $product['href']; ?>"  style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i data-feather="shopping-cart"></i> Add to cart</a>
				              		<?php }?>	                        		
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

