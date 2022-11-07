<div class="row">        
    <div class="col-lg-12 recommended-cat">
      <h5>Recommended for you</h5>
    </div>
    <div class="col-lg-12 recommended-cat">
    <?php
    	require_once 'model/recommended_product.php'; 
    	$rec_prod_model=new rec_product();
    	$custid = isset($_SESSION['user_login']) ? $_SESSION['user_login']: 0;  
		$count_val_rec = $rec_prod_model->gethomecategorylist($custid);
		
		if($count_val_rec['countid']==0){  
		  $results_rec = $rec_prod_model->gethomecategorylistbydef(0);
		}else{     
		  $results_rec =  $rec_prod_model->gethomecategorylistbydef($custid);
		}
		foreach ($results_rec as $categoryhome) : 
		    $category_name=$categoryhome['name'];
		    $category_id=$categoryhome['cid'];
		    $show_limit=$categoryhome['show_limit'];
		    $sort_order=$categoryhome['sort_order'];
		    $status=$categoryhome['status'];
		    if($show_limit==0){
		      $show=12;
		    }else{
		      $show=$show_limit;
		    }
		    if($status=='1'){
		     $product_rec= $rec_prod_model->getProductsbycategory_product($category_id,$show,$sort_order);
		    }else{
		      $product_rec= $rec_prod_model->getProductsbycategory($category_id,$show,$sort_order);
		    }
		    foreach($product_rec as $product_recval) {
		    	$p_name=utf8_encode($product_recval['name']);
				 	$name = strlen($p_name) > 25 ? substr($p_name,0,25)."..." : $p_name;
				 	if($is_mobile){ ?>
				 		<div class="col-xs-6" style="padding:2px;">
				 	<?php }else{ ?> 
				 		<div class="col-xs-6 col-md-3 col-lg-2" style="padding:5px;">
				 	<?php } ?> 
					 	<div class="card" >
					 		<div class="product-thumb transition">
					 		 	<div class="image-container">
					 		 		<div data-toggle="tooltip" title="Click for more details" class="image">
					                <a  href="<?php echo $p_det['href']; ?>">
					                <?php  $getimg =$product_recval['image']; ?>
					                <?php if($product_recval['type']=="0"){?>									              
						                <?php if(file_exists($getimg)): ?>
						                  <img src="<?php echo $getimg; ?>" alt="<?php echo $product_recval['name']; ?>" class="img-responsive" />
						                <?php else: ?>
						                   <p class="no-image"><i class="fa fa-shopping-bag"></i></p>
						                <?php endif; ?>
					                 <?php }else{ ?>
					                 	<img src="<?php echo $getimg; ?>" alt="<?php echo $product_recval['name']; ?>" class="img-responsive" />
					                 <?php } ?>
					                </a>                    
					            </div>
					 		 	</div>
					 		 	<div class="caption" style="text-align:center;">
			              		<h6 ><a data-toggle="tooltip" title="<?php echo utf8_encode($product_recval['name']); ?>" href="<?php echo $product_recval['href']; ?>"><?php echo $name; ?></a></h6>						              		
			              	</div>
			              	<div class="price" style="text-align:center;">	
			              		 	<p  style="color:#e81b30" ><b>₱<?php echo   number_format($product_recval['price'],2);?></b></p>
			              	</div>
			              	<div>
				              	<?php if($is_log){ ?>
				              		 <?php if($product_recval['type']=="0"){ ?>
				              		 	<a type="button" 
                             			class="btn btn-pink btn-addtocart" data-product_id="<?php echo $product_recval['product_id'];?>" data-name="<?php echo $product_recval['name'];?>" data-user_id="<?php echo $_SESSION['user_login'];?>" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i data-feather="shopping-cart"></i> Add to cart</a>
				              		 <?php }else if($p_det['type']=="2"){ ?>
				              		 	<a type="button"class="btn btn-pink btn_addtocart_bg" href="<?php echo $product_recval['href']; ?>"  style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i data-feather="shopping-cart"></i> Add to cart</a>	
				              		 <?php }else{ ?>
				              		 	<a type="button"class="btn btn-pink btn_addtocart_cb" href="<?php echo $product_recval['href']; ?>"  style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i data-feather="shopping-cart"></i> Add to cart</a>
				              		<?php }?>	                        		
                             	 <?php }else{ ?>
                             	 	 <a type="button" data-toggle="modal" data-target="#LoginModal"  style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i data-feather="shopping-cart" ></i> Add to cart</a>
                             	 <?php } ?>
			               	</div>
					 		</div>
					 	</div>
					 </div>	
		    <?php }
		endforeach; ?> 
    </div>      
</div>    