<?php
include "common/header.php";
require_once "model/product.php";
require_once 'model/home_new.php'; 
$home_new_mod=new home_new();
$product = new product();	
$custid = isset($_SESSION['user_login']) ? $_SESSION['user_login']: 0;

if(isset($_GET['searchvalue_h'])){
	$ProductFound =0;
	$searchvla	=strip_tags($_GET["searchvalue_h"]);	
	$searchvl=str_replace("_20"," ",$searchvla);
	if(empty($_GET['searchvalue_h'])){						
		$errorMsg[]="Please Enter Keyword...";	
	}
	$listdatawithcategory_id=array();	
	$dataProductRecommended=array();		
	if($searchvl==""){
	}else{
		if($_GET['pesomall']==1){
			//for golbalproducts
			$ProductFound=0;
			$explode_searchvalGP=explode(" ",trim($searchvl)); 
			$BrandSearchLengthGP = count($explode_searchvalGP);
			if($BrandSearchLengthGP!=1){
				// search value is  more than 1 word GP
				//echo "<br> search value is  more than 1 word GP";
				$findAllGlobalProduct=$product->findAllGlobalProduct(trim($searchvl));
				if(count($findAllGlobalProduct['products'])!=0){
					$listdatawithcategory_id[]=	$findAllGlobalProduct;
					$ProductFound++;
				}else{
					//search product in brand by word GP 
					//echo "<br>product in brand by word GP ";
					for ($i = 0; $i < $BrandSearchLengthGP; $i++) {
						if(strlen($explode_searchvalGP[$i])>3){
							$findAllGlobalProduct2=$product->findAllGlobalProduct(trim($explode_searchvalGP[$i]));
							if(count($findAllGlobalProduct2['products'])!=0){
								$listdatawithcategory_id[]=	$findAllGlobalProduct2;
								$ProductFound++;
							}
						}
					}
				}
			}else{
				///search value is 1 word	GP
				$findAllGlobalProduct=$product->findAllGlobalProduct(trim($searchvl));
				if(count($findAllGlobalProduct['products'])!=0){
					$listdatawithcategory_id[]=	$findAllGlobalProduct;
					$ProductFound++;
				}else{
					$ProductFound=0;
				}
					
			}
			$dataProductRecommended=$product->GlobalProductRecommended();
		}else{
			//forlocal products
			$Recommendedcategory_id=0;	
			$RecommendedBrand_id=0;	
			$counterForWildsearch=0;
			$ProductFound =0;
			$search_category = $product->search_category(trim($searchvl));	

			if($search_category){
				//search product in brand
				$counterForBrand=0;
				$explode_searchval=explode(" ",trim($searchvl)); 
				$BrandSearchLength = count($explode_searchval);
				if($BrandSearchLength!=1){
					// search value is  more than 1 word	
					$findPRDBrandBycat=$product->findPRDBrandBycat(trim($searchvl));
					if($findPRDBrandBycat){
						//whole search value have a Barand on the brand list	
						$RecommendedBrand_id=$findPRDBrandBycat['id'];				
						$brandproductData=$product->GetproductbyBrandNCat($findPRDBrandBycat['id'],trim($searchvl));
						if(count($brandproductData['products'])!=0){
							$listdatawithcategory_id[]=	$brandproductData;
							$counterForBrand++;	
						}else{
							$counterForBrand=0;
						}		
					}else{
						//whole search value have no Barand on the brand list				
						$brandNameOnTheList="";
						for ($x = 0; $x < $BrandSearchLength; $x++) {
							$BywordfindPRDBrandBycat=$product->findPRDBrandBycat(trim($explode_searchval[$x]));
							if($BywordfindPRDBrandBycat){							
								$brandNameOnTheList= $explode_searchval[$x];
								$counterForBrand++;	
								break;
							}else{
								$counterForBrand=0;
							}
						}
						if($brandNameOnTheList!=""){								
							$findPRDBrandBycat=$product->findPRDBrandBycat(trim($brandNameOnTheList));
							$RecommendedBrand_id=$findPRDBrandBycat['id'];
							$brandproductData=$product->GetproductbyBrandNCat($findPRDBrandBycat['id'],trim($searchvl));
							if(count($brandproductData['products'])!=0){
								//search product in brand all word
								$listdatawithcategory_id[]=	$brandproductData;
								$counterForBrand++;														
							}else{
								//search product in brand by word	
								$count_getbradval=0;							
							 	$explode_ch=explode(" ",trim($searchvl)); 
							 	$length = count($explode_ch);
							 	if($length!=1){
							 		for ($i = 0; $i < $length; $i++) {
										if(strlen($explode_ch[$i])>3){
											if($brandNameOnTheList!=$explode_ch[$i]){
												$searchvalue=$brandNameOnTheList." ".$explode_ch[$i];
												//echo" <br/>$searchvalue";
												$perWordData=$product->GetproductbyBrandNCat($findPRDBrandBycat['id'],$searchvalue);
												if(count($perWordData['products'])!=0){
													$listdatawithcategory_id[]=	$perWordData;
													$counterForBrand++;	
													$count_getbradval++;	
												}else{
													$perWordData2=$product->GetproductbyBrandNCat($findPRDBrandBycat['id'],$explode_ch[$i]);
													if(count($perWordData2['products'])!=0){
														$listdatawithcategory_id[]=	$perWordData2;
														$counterForBrand++;	
														$count_getbradval++;	
													}
												}	
											}
										}
									}
									if($count_getbradval==0){
								 		$perWordData3=$product->GetproductbyBrandNCat($findPRDBrandBycat['id'],trim($brandNameOnTheList));
								 		if(count($perWordData3['products'])!=0){
											$listdatawithcategory_id[]=	$perWordData3;
											$counterForBrand++;	
										}else{
											$counterForBrand=0;			
										}
								 	}
							 	}						 	
							}
						}

					}	
				}else{
					///search value is 1 word
					$findPRDBrandBycat=$product->findPRDBrandBycat(trim($searchvl));
					if($findPRDBrandBycat){
						//have a Barand on the brand list
						$RecommendedBrand_id=$findPRDBrandBycat['id'];						
						$brandproductData=$product->GetproductbyBrandNCat($findPRDBrandBycat['id'],trim($searchvl));
						if(count($brandproductData['products'])!=0){
							$listdatawithcategory_id[]=	$brandproductData;
							$counterForBrand++;
						}else{
							$counterForBrand=0;
						}					
					}else{
						//have no Barand on the brand list
						$counterForBrand=0;
					}
				}			
				if($counterForBrand==0){
					//search product in category
					$Recommendedcategory_id=$search_category['category_id'];
					$allWordData=$product->searchListDataWithcategory($search_category['category_id'],trim($searchvl));			
					if(count($allWordData['products'])==0){
						//search product in category by word
					 	$explode_ch=explode(" ",trim($searchvl)); 
					 	$length = count($explode_ch);
					 	if($length!=1){
					 		for ($i = 1; $i < $length; $i++) {
								if(strlen($explode_ch[$i])>3){
									$perWordData=$product->searchListDataWithcategory($search_category['category_id'],$explode_ch[$i]);	
									if(count($perWordData['products'])!=0){
										$listdatawithcategory_id[]=	$perWordData;
										$counterForWildsearch++;
									}	
								}
							}
					 	}
					}else{
						$listdatawithcategory_id[]=$allWordData;
						$counterForWildsearch++;
					}
				}
			}else{
				//For No category keywords found
				//echo "<br><br><br><br><br> For No category keywords found";
				$counterForWildsearch=0;
				$explode_searchvalCNF=explode(" ",trim($searchvl)); 
				$BrandSearchLengthCNF = count($explode_searchvalCNF);
				if($BrandSearchLengthCNF!=1){
					// search value is  more than 1 word CNF
					//echo "<br> search value is  more than 1 word CNF";		
					$findPRDBrandBycatCNF=$product->findPRDBrandBycat(trim($searchvl));
					if($findPRDBrandBycatCNF){
						//whole search value have a Barand on the brand list CNF	
						$RecommendedBrand_id=$findPRDBrandBycatCNF['id'];		
						$brandproductDataCNF=$product->GetproductbyBrandNCat($findPRDBrandBycatCNF['id'],trim($searchvl));
						if(count($brandproductDataCNF['products'])!=0){
							$listdatawithcategory_id[]=	$brandproductDataCNF;
							$counterForWildsearch++;	
						}else{
							$counterForWildsearch=0;
						}		
					}else{
						//whole search value have no Barand on the brand list CNF
						//echo "<br>whole search value have no Barand on the brand list CNF";			
						$brandNameOnTheListCNF="";
						for ($x = 0; $x < $BrandSearchLengthCNF; $x++) {
							$BywordfindPRDBrandBycatCNF=$product->findPRDBrandBycat(trim($explode_searchvalCNF[$x]));
							if($BywordfindPRDBrandBycatCNF){							
								$brandNameOnTheListCNF= $explode_searchvalCNF[$x];
								$counterForWildsearch++;	
								break;
							}else{
								$counterForWildsearch=0;
							}
						}
						if($brandNameOnTheListCNF!=""){
							$findPRDBrandBycatCNF=$product->findPRDBrandBycat(trim($brandNameOnTheListCNF));
							$RecommendedBrand_id=$findPRDBrandBycatCNF['id'];	
							$brandproductDataCNF=$product->GetproductbyBrandNCat($findPRDBrandBycatCNF['id'],trim($searchvl));
							if(count($brandproductDataCNF['products'])!=0){
								//search product in brand all word CNF
								//echo "<br>search product in brand all word CNF";		
								$listdatawithcategory_id[]=	$brandproductDataCNF;
								$counterForWildsearch++;														
							}else{
								//search product in brand by word CNF 
								$count_getbradval=0;		
							 	$explode_ch=explode(" ",trim($searchvl)); 
							 	$length = count($explode_ch);
							 	if($length!=1){
							 		for ($i = 0; $i < $length; $i++) {
										if(strlen($explode_ch[$i])>3){
											if($brandNameOnTheListCNF!=$explode_ch[$i]){
												$searchvalueCNF=$brandNameOnTheListCNF." ".$explode_ch[$i];
												//echo "<br>$searchvalueCNF ";	
												$perWordDataCNF=$product->GetproductbyBrandNCat($findPRDBrandBycatCNF['id'],$searchvalueCNF);
												if(count($perWordDataCNF['products'])!=0){
													$listdatawithcategory_id[]=	$perWordDataCNF;
													$counterForWildsearch++;	
													$count_getbradval++;	
												}else{
													$perWordDataCNF2=$product->GetproductbyBrandNCat($findPRDBrandBycatCNF['id'],$explode_ch[$i]);
													if(count($perWordDataCNF2['products'])!=0){
														$listdatawithcategory_id[]=	$perWordDataCNF2;
														$counterForWildsearch++;	
														$count_getbradval++;
													}
												}	
											}
										}
									}
									if($count_getbradval==0){
								 		$perWordData3=$product->GetproductbyBrandNCat($findPRDBrandBycatCNF['id'],trim($brandNameOnTheListCNF));
								 		if(count($perWordData3['products'])!=0){
											$listdatawithcategory_id[]=	$perWordData3;
											$counterForWildsearch++;	
										}else{
											$counterForWildsearch=0;			
										}
								 	}
							 	}
							}
						}

					}	
				}else{
					///search value is 1 word				
					$findPRDBrandBycatCNF=$product->findPRDBrandBycat(trim($searchvl));
					if($findPRDBrandBycat){
						//have a Barand on the brand list CNF
						$RecommendedBrand_id=$findPRDBrandBycatCNF['id'];							
						$brandproductDataCNF=$product->GetproductbyBrandNCat($findPRDBrandBycatCNF['id'],trim($searchvl));
						if(count($brandproductDataCNF['products'])!=0){
							$listdatawithcategory_id[]=	$brandproductDataCNF;
							$counterForWildsearch++;
						}else{
							$counterForWildsearch=0;
						}					
					}else{
						//have no Barand on the brand list CNF
						$counterForWildsearch=0;
					}
				}	
			}
			if($counterForWildsearch==0){
				//for wild search
				//echo "<br><br><br><br><br> wild search";
				$ProductFound=0;
				$explode_searchvalWS=explode(" ",trim($searchvl)); 
				$BrandSearchLengthWS = count($explode_searchvalWS);
				if($BrandSearchLengthWS!=1){
					// search value is  more than 1 word WS
					//echo "<br> search value is  more than 1 word WS";
					$findAllProduct=$product->findAllProduct(trim($searchvl));
					if(count($findAllProduct['products'])!=0){
						$listdatawithcategory_id[]=	$findAllProduct;
						$ProductFound++;
					}else{
						//search product in brand by word WS 
						//echo "<br>product in brand by word WS ";
						for ($i = 0; $i < $BrandSearchLengthWS; $i++) {
							if(strlen($explode_searchvalWS[$i])>3){
								$findAllProduct2=$product->findAllProduct(trim($explode_searchvalWS[$i]));
								if(count($findAllProduct2['products'])!=0){
									$listdatawithcategory_id[]=	$findAllProduct2;
									$ProductFound++;
								}
							}
						}
					}
				}else{
					///search value is 1 word	WS
					$findAllProduct=$product->findAllProduct(trim($searchvl));
					if(count($findAllProduct['products'])!=0){
						$listdatawithcategory_id[]=	$findAllProduct;
						$ProductFound++;
					}else{
						$ProductFound=0;
					}
						
				}
			}
			//for recomended Products
			if($RecommendedBrand_id!=0){
				$dataProductRecommended=$product->LocalProductRecBybrand($RecommendedBrand_id);
			}else if($Recommendedcategory_id!=0){
				$dataProductRecommended=$product->LocalProductRecBycategory($Recommendedcategory_id);
			}else{
				$dataProductRecommended=$product->LocalProductRecByDeff();
			}	
		}
		
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
      height: 320px;

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
                 	<?php foreach($listdatawithcategory_id as $datalist) { $ProductFound=0;?>
                 		<?php if(count($datalist['products'])==0){  
                 			$ProductFound=0; 
                 		}else{
							$ProductFound++;
							foreach($datalist['products'] as $product) { $p_name=utf8_encode($product['name']);?>
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
				                             	 	<a type="button" data-toggle="modal" data-target="#LoginModal" class="btn btn-pink"   style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i data-feather="shopping-cart"></i> Add to cart</a>
				                             	 <?php } ?>
							               	</div>
							            </div>
							         </div>	
								</div>				 
							<?php
							}
						}?>

                 	<?php }?>
                 	<?php if($ProductFound==0){?>
                 	<div class="alert alert-success">
						<strong>No ProductFound..</strong>
						<div id="sugges_div" > Did You Mean: <a id='suggestionLink' href='#' onclick='fixSuggestions(this);'></a></div>
					</div>
					<?php }?>
                 </div>
            </div>
        </div> 
        <!--  peso_mall -->
	    <div class="row">        
            <div class="col-lg-12 recommended-cat">
              <h5>Recommended for you</h5>
            </div>
            <div class="col-lg-12 recommended-cat">
                 <div class="row" style="margin-left: 10px;">
                 	<?php foreach($dataProductRecommended['products'] as $product) { $p_name=utf8_encode($product['name']);?>
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
							    		<a type="button" data-toggle="modal" data-target="#LoginModal" class="btn btn-pink"   style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i data-feather="shopping-cart"></i> Add to cart</a>
							    	<?php } ?>
							    </div>
							</div>
						</div>	
					</div>	
					<?php }?>
                </div>
            </div>      
        </div>    
		<!--  peso_mall -->      
    </div>
</div>
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
        var pesomall='<?php echo $pesomall?>';
        window.location.href = "welcome.php?searchvalue_h=" +res+'&pesomall='+pesomall; 
	}
 </script>

