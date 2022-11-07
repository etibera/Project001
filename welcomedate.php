<?php
include "common/header.php";
require_once "model/product.php";
require_once 'model/home_new.php'; 
$home_new_mod=new home_new();
$product = new product();	
$custid = isset($_SESSION['user_login']) ? $_SESSION['user_login']: 0;

if(isset($_GET['searchvalue_h'])){
	$searchvla	=strip_tags($_GET["searchvalue_h"]);	
	$searchvl=str_replace("_20"," ",$searchvla);
	if(empty($_GET['searchvalue_h'])){						
		$errorMsg[]="Please Enter Keyword...";	
	}

}
?>
<style>
.card_src {
      margin: auto;
      text-align: center;
      font-family: arial
      font-size: 6px;
      box-shadow: 0 0.0625rem 0.125rem 0 rgba(0,0,0,.6);
      border-radius:10px;background: #fff;
      height: 300px;

}
.card_src:hover { border: 1px solid #777;}
.no-image{
    background-color: #e0e0e0;
    height: 150px;
}

.nav_header{
   background: #f2faff;
    width: 100%;
    padding: 10px 15px !important;
    border-radius: 10px;
    margin-left: 0px !important;
}
</style>

<div class="wrapper">
	<div class="container_home" >
		<div class="row">
            <div class="col-lg-12 ca-home">  
            </div>
        </div> 
         <div class="row">
            <div class="col-lg-12 ca-home" >
                 <?php  $getBanner_new=$home_new_mod->getBanner_new(11);
                 include "carousel_new.php";?>
            </div>     
        </div>        
        <?php if(isset($errorMsg)) {
			foreach($errorMsg as $error){ ?>
				<div class="alert alert-danger">
					<strong><?php echo $error; ?></strong>
				</div>
            <?php }
		} ?>
		<div class="row" > 
            <div class="col-lg-12 categoryhomes nav_header">
                <h4>Result for <i><?php echo $searchvl;?></i></h4>
            </div>  
            <div class="col-lg-12 Most-Popular">
                 <div class="row" style="margin-left: 10px;">
				<?php if(isset($_GET['searchvalue_h'])) { 
					unset($errorMsg);
					if($searchvl==""){
					}else{	

													
						$list = $product->getproduct($searchvl);
						if(count($list['products'])==0){ ?>
							<div class="alert alert-success">
								<strong>No Product Found..</strong>
								<div id="sugges_div" > Did You Mean: <a id='suggestionLink' href='#' onclick='fixSuggestions(this);'></a></div>
							</div>
						<?php }else{
							/*echo "<pre>";s
							print_r(count($list['products']));*/
							foreach($list['products'] as $product) { $p_name=utf8_encode($product['name']);?>
								<?php  $getimg =$product['thumb']; ?>
								<?php $name = strlen($p_name) > 25 ? substr($p_name,0,25)."..." : $p_name;?>
								<?php if($is_mobile){ ?>
									 <div class="col-xs-6" style="padding:2px;">
								<?php }else{ ?>  
									<div class="col-xs-6 col-md-3 col-lg-2" style="padding:5px;">
								<?php }?> 
								 	<div class="card_src" >
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
							              	<div class="caption" style="text-align:center;">
							              		<h6 ><a data-toggle="tooltip" title="<?php echo utf8_encode($product['name']); ?>" href="<?php echo $product['href']; ?>"><?php echo $name; ?></a></h6>						              		
							              	</div>
							              	<div class="price" style="text-align:center;">	
							              		 <?php if($product['type']=="0"){ ?>
							              		 	<p  style="color:#e81b30" ><b>₱<?php echo   number_format($product['price'],2);?></b></p>
							              		  <?php }else if($product['type']=="2"){ 
							              		   		$bg_price_ragemin=$product['price']-50;
										            	if($bg_price_ragemin < 0){
										            		$bg_price_ragemin=$product['price'];
										            	}
									            		$bg_price_ragemax= $product['price']*1.25 ?>
									            		<p  style="color:#e81b30" ><b>₱<?php echo   number_format($bg_price_ragemin,2);?> - ₱<?php echo   number_format($bg_price_ragemax,2);?></b></p>
							              		  <?php }?>	  
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
					}
				} ?>
				</div>
            </div>
        </div>   		
		
		<!--  peso_mall -->
	      <div class="row" style="margin-left: 10px;">
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
		<!--  peso_mall -->
	</div>
</div>
<?php
include "common/footer.php";
?>										
 	
 <script>
    $(document).ready(function() {
    	ajaxDYM();
    	$(".btn-addtocart").click(function(){
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
    function ajaxDYM(){
        var SearchBoxText = "<?php echo $searchvl;?>";
        var data_search =$Spelling.SpellCheckSuggest(SearchBoxText);   
        var oSuggestionLink= document.getElementById('suggestionLink');
         if(data_search == undefined){
		     $('#sugges_div').css('display','none');
		 }else if(data_search.length == 1){
		 	oSuggestionLink.innerHTML =data_search[0][0];
		 	$('#sugges_div').css('display','block');
		}else{
			if(data_search[0]==undefined){
				$('#sugges_div').css('display','none');
			}else if(data_search[0]=="*PHP Spellcheck Trial*"){
				$('#sugges_div').css('display','none');
				
			}else{
				oSuggestionLink.innerHTML =data_search[0];
            	$('#sugges_div').css('display','block');
			}
			
		}
	}
	function fixSuggestions(link){
		var search_val_input=link.innerHTML;;   
        var res=search_val_input.split(' ').join('_20');
        window.location.href = "welcome.php?searchvalue_h=" +res;
	}
 </script>

