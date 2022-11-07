<?php
// include "common/header.php";
// include "model/home.php";
// $model_home= new home();
?>
<style>
.cardch-bg {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  max-width: 300px;
  margin: auto;
  font-family: arial;
}
.pricech {
  color: grey;
  font-size: 20px;
}
.cardch-bg a {
  border: none;
  outline: 0;
  padding: 12px;  
  cursor: pointer;
  width: 100%;
  font-size: 18px;
}
.cardch-bg a:hover {
  opacity: 0.7;
}
.ch-slider .slick-track{
  height: 400px;
}
.image-containerch {
    height: 230px;
     overflow:hidden;
    
}
.no-imagech{
    background-color: #e0e0e0;
    height: 150px;
}
.no-imagech i {
    font-size: 50px;
    margin-top: 50px;
    color: #333;
}
.product-thumbch .caption{
  text-align: left;
  height: 90px;
}
</style>
<div class="wrapper">
	<div class="container">
		<div class="row">
            <div class="col-xs-12">
              	<h5>Global Products </h5>
         </div>  
		<div class="row">
		<?php										
		$list_bg = $model_home->get_banggood_products(1);
		if(!$list_bg){
			?>
			<div class="alert alert-danger">
				<strong>***.No data found.***</strong>
			</div>
			<?php
		}else{
			foreach($list_bg as $product_bg){ 
				$get_product_title_bg=$product_bg['product_name'];
				$name_bg = strlen($get_product_title_bg) > 25 ? substr($get_product_title_bg,0,25)."..." : $get_product_title_bg;
				$get_product_img_bg=$product_bg['img'];
				$bg_price_php=$product_bg['price']
					?>
					<?php if($is_mobile){ ?>
						 <div class="col-xs-6" style="padding:2px;">
					<?php }else{ ?>  
						<div class="col-xs-6 col-md-3 col-lg-2" style="padding:5px;">
					<?php }?> 
						<div class="cardch-bg" >
							<div class="product-thumbch transition">
								<div class="image-containerch">
									<div data-toggle="tooltip" title="Click for more details" class="imagech">
										<a  href="bg_product.php?product_idbg=<?php echo $product_bg['product_id']; ?>">
						                  <img src="<?php echo $get_product_img_bg; ?>" alt="<?php echo $get_product_title_bg; ?>" class="img-responsive" /></a>							                                 
									</div>
								</div>
								<div class="caption" style="text-align:center;">
					              	<h4 ><a data-toggle="tooltip" title="<?php echo $get_product_title; ?>" href="bg_product.php?product_idbg=<?php echo $product_bg['product_id']; ?>"><?php echo $name_bg; ?></a></h4>						              		
					            </div>
					            <div class="price" style="text-align:center;">						              		
	              					<p  style="color:#e81b30" ><b>₱<?php echo   number_format($bg_price_php,2);?></b></p>
	              				</div>
	              				<div>
					              	<?php if($is_log){?>							              		
					              		<a type="button" 
	                             			class="btn btn-pink btn-addtocart_bg" href="bg_product.php?product_idbg=<?php echo $product_bg['product_id']; ?>"><i data-feather="shopping-cart"></i> Add to cart</a>			                             		
	                             	 <?php }else{ ?>
	                             	 	 <a type="button" data-toggle="modal" data-target="#LoginModal"><i data-feather="shopping-cart"></i> Add to cart</a>
	                             	 <?php } ?>
				               	</div> 
							</div>
						</div>
					</div>
					<?php
				//}
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

