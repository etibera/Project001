<?php

 	include "../include/banggoodAPI.php"; 
 	include "model/bg_save_model.php";
 	include 'template/header.php'; 
 	$model = new BG_Model_db(); 
 	$page=0;  
 	if(isset($_GET['parent_id_cat'])){ 	
 		$page=$_GET['page_no']+1;
 		
 		$params_gp = array('cat_id' => $_GET['parent_id_cat'],'page' => $_GET['page_no']);
		$banggoodAPI->setParams($params_gp);
		$result_gp = $banggoodAPI->getProductList();
		$category_bg_name=$_GET['cat_name'];
		 $code_pdl=$result_gp['code'];
		 if($code_pdl==0){
		 	 $page_pdl=$result_gp['page'];
			 $page_total_pdl=$result_gp['page_total'];
			 $product_total_pdl=$result_gp['product_total'];
			 $product_list_pdl=$result_gp['product_list'];
		}else{
			$sMsg_failed=$result_gp['msg'];
		}
		

 	}
 ?>
 <div id="content">
	<div class="page-header">
		 <h2 class="text-center">Banggoods Product list</h2>
	</div>
	<div class="container-fluid">
		<div class="panel panel-success">
		 	<div class="panel-heading" style="padding:20px;">
		 		<div class="row">
		 			<div class="col-lg-6">
          				<p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i> Product List Of
          					<?php if(isset($category_bg_name)){ echo " ($category_bg_name)"; }?>
          				</p>
          			</div>
          		</div>
          	</div>
        
	        <div class="panel-body">
	        	<?php if(isset($sMsg_failed)){ ?>
				    <div class="alert alert-danger">
				        <strong><?php echo $sMsg_failed;?></strong></br>
				    </div>
				<?php } ?>
				<?php if(isset($sMsg_ok)){ ?>
				    <div class="alert alert-success">
				        <strong><?php echo $sMsg_ok;?></strong></br>
				    </div>
				<?php } ?>
	        	<?php if(isset($product_list_pdl)){  ?>
	        		
	        		<div class="row">
	          			<div class="col-lg-6">
	          				<label>Page No: <?php echo $page_pdl;?></label></br>
			        		<label>Total Pages: <?php echo $page_total_pdl;?></label></br>
			        		<label>Total Product: <?php echo $product_total_pdl;?></label></br>
	          			</div>
	          			<div class="col-lg-6">
				            <div class="pull-right">
				            	<?php if($page_total_pdl>$_GET['page_no']){ ?> 
				            		<a href="bg_download_product.php?parent_id_cat=<?php echo $_GET['parent_id_cat'];?>&cat_name=<?php echo $_GET['cat_name'];?>&page_no=<?php echo $page;?>" class="btn btn-success btn-edit"  title="Download  Page No: <?php echo $page;?> " ><i data-feather="download"></i> Page No: <?php echo $page;?></a> 
				            	<?php } ?>
				                
				            </div>
				        </div>
	          		</div>
					<div class="table-responsive">
					 	<table class="table table-bordered table-hover">
							</thead>
					 		<thead >
								<th class="text-center">Image</th>
								<th class="text-center">product_id</th>
								<th class="text-center">cat_id</th>
								<th class="text-center">product_name</th>
								<th class="text-center">Warehouse</th>
								<th class="text-center">Stock</th>
								<th class="text-center">Price</th>
								<th class="text-center">Date Added</th>
								<th class="text-center">modify_date</th>
								<th class="text-center">Status</th>								
							</thead>
							<tbody>
							<?php foreach($product_list_pdl as $bg_prod): ?>
								<?php 
									$params_gps = array('product_id' => $bg_prod['product_id']);
									$banggoodAPI->setParams($params_gps);
									$result_gps = $banggoodAPI->getstocks();
									if($result_gps['code']==0){										
										$warehouse_gps=$result_gps['stocks'][0]['warehouse'];
										$stock_gps=$result_gps['stocks'][0]['stock_list'][0]['stock'];
										$stock_msg_gps=$result_gps['stocks'][0]['stock_list'][0]['stock_msg'];

										$params_pp = array('product_id' => $bg_prod['product_id'],'warehouse' => $warehouse_gps,'currency' => 'PHP');
										$banggoodAPI->setParams($params_pp);
										$result_pp = $banggoodAPI->getproductprice();
										if($result_pp['code']==0){
											$Accessrestrictions=0;
											$price_pp=$result_pp['productPrice'][0]['price'];
											$stats_bg_chk=$model->save_bgproducts_check($bg_prod['product_id']);
											if($stats_bg_chk==0){												
												$model->save_bgproducts($bg_prod['product_id'],$bg_prod['cat_id'],$bg_prod['product_name'],$bg_prod['img'],$bg_prod['meta_desc'], $bg_prod['add_date'],$bg_prod['modify_date'],$warehouse_gps,$price_pp);
												$sMsg_ok="Successfully Downloaded Page No.".$_GET['page_no'];

											}
										}else{
											$Accessrestrictions=1;
										}
									}else{
										 $stock_msg_gps=$result_gps['msg'];
										 $stock_gps=0;
										 $Accessrestrictions=1;
									}

									

									

									

								?>
								<tr>
									<td class="text-center" ><img src="<?php echo $bg_prod['img']; ?>"  class="img-responsive" style="width: 80px; width: 80px;" /></td>
									<td class="text-left" ><?php echo $bg_prod['product_id'];?></td>
									<td class="text-left" ><?php echo $bg_prod['cat_id'];?></td>
									<td class="text-left" ><?php echo $bg_prod['product_name'];?></td>
									<?php if($Accessrestrictions==1){ ?>
										<td colspan="6"><span style="color:red;">You have exceeded the maximum number of calls, please visit again in second days</span></td>
									<?php }else{ ?>
										<td class="text-left" ><?php echo $warehouse_gps;?></td>
										<td class="text-left" ><?php echo $stock_gps." (".$stock_msg_gps.")";?></td>
										<td class="text-left" >₱<?php echo number_format($price_pp,2);?></td>
										<td class="text-left" ><?php echo $bg_prod['add_date'];?></td>
										<td class="text-left" ><?php echo $bg_prod['modify_date'];?></td>
										<td class="text-left" >
											<?php 
												if($stats_bg_chk == '0') { 
										 			echo '<span style="color:green;">Successfully Downloaded</span>'; 
										 		} else {
										 			echo '<span style="color:red;">Product Already Exist</span>';
										 		}	
									 		?>
										</td>
									<?php } ?>
									
								</tr>
							<?php endforeach;?>							
							</tbody>
							
					 	</table>
					 </div>
				<?php }?>
	        </div>
	    </div>
    </div>
</div>

 

<?php include 'template/footer.php'; ?>