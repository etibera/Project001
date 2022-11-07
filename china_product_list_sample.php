<?php
include "common/header.php";
include "model/home.php";
$model_home= new home();
$date = new DateTime("now", new DateTimeZone('Asia/Manila'));
$date_now=$date->format('Y-m-d');
include "include/china_token.php";
 $currency_active=$model_home->currency_active();
 if($currency_active){
	$BASE=$currency_active['base'];
	$CNTO=$currency_active['exchange_currency'];
	$api_date=$date_now;
	$count_fer=$model_home->count_fer($date_now);
	if($count_fer==0){
		//insert
		$curl_exchangerates =curl_init('http://api.currencylayer.com/live?access_key=a24fe932b4671dfc479a0457a965e7f6&source='.$BASE);
		curl_setopt($curl_exchangerates, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl_exchangerates, CURLOPT_RETURNTRANSFER, 1);
		$result_excr = curl_exec($curl_exchangerates); 
		curl_close($curl_exchangerates);
		$res_excr= json_decode($result_excr);
		$RATE=$res_excr->quotes->{$CNTO};
		$model_home->insert_fer($date_now,$BASE,$CNTO,$RATE,$api_date);
	
	}
}
$exchange_currency_RATE=0;
$exchange_currency_RATE=$model_home->get_fer_today($date_now);
$othercharges=1.3;
?>
<style>
.cardch {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  max-width: 300px;
  margin: auto;
  font-family: arial;
}
.pricech {
  color: grey;
  font-size: 20px;
}
.cardch a {
  border: none;
  outline: 0;
  padding: 12px;  
  cursor: pointer;
  width: 100%;
  font-size: 18px;
}
.cardch a:hover {
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
              	<center><h2>China Product </h2></center>              
            </div>
         </div>  
		<div class="row">
		<?php										
		$list = $model_home->get_china_products(1);
		if(!$list){
			?>
			<div class="alert alert-danger">
				<strong>***.No data found.***</strong>
			</div>
			<?php
		}else{
			foreach($list as $product){ 
				$p_id_ch =$product['goods_sn']; 
				$goods_sn = $p_id_ch;
				$get_product_title=$product['product_title'];
				$name = strlen($get_product_title) > 25 ? substr($get_product_title,0,25)."..." : $get_product_title;
				$get_product_img=$product['product_img'];
				$china_pricw_php=($product['price']*$exchange_currency_RATE)*$othercharges;
					?>
					<?php if($is_mobile){ ?>
						 <div class="col-xs-6" style="padding:2px;">
					<?php }else{ ?>  
						<div class="col-xs-6 col-md-3 col-lg-2" style="padding:5px;">
					<?php }?> 
						<div class="cardch" >
							<div class="product-thumbch transition">
								<div class="image-containerch">
									<div data-toggle="tooltip" title="Click for more details" class="imagech">
										<a  href="product_china.php?product_idcb=<?php echo $p_id_ch; ?>">
						                  <img src="<?php echo $get_product_img; ?>" alt="<?php echo $get_product_title; ?>" class="img-responsive" /></a>							                                 
									</div>
								</div>
								<div class="caption" style="text-align:center;">
					              	<h4 ><a data-toggle="tooltip" title="<?php echo $get_product_title; ?>" href="product_china.php?product_idcb=<?php echo $p_id_ch; ?>"><?php echo $name; ?></a></h4>						              		
					            </div>
					            <div class="price" style="text-align:center;">						              		
	              					<p  style="color:#e81b30" ><b>₱<?php echo   number_format($china_pricw_php,2);?></b></p>
	              				</div>
	              				<div>
					              	<?php if($is_log){?>							              		
					              		<a type="button" 
	                             			class="btn btn-pink btn-addtocart_china" data-product_id="<?php echo $p_id_ch;?>" data-name="<?php echo $get_product_title;?>" data-user_id="<?php echo $_SESSION['user_login'];?>"><i data-feather="shopping-cart"></i> Add to cart</a>			                             		
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
include "common/footer.php";
?>
 <script>
    $(document).ready(function() {
    	$(".btn-addtocart_china").click(function(){
            var product=$(this).data('product_id');
          	var cust_id=$(this).data('user_id');
          	var name=$(this).data('name');
          	
         	$.ajax({
            url: 'ajax_add_to_cart.php',
            type: 'POST',
            data: 'product_china=' + product + '&cust_id=' + cust_id,
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

