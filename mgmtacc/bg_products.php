<?php
 	// include "../include/banggoodAPI.php"; 
 	include "model/bg_save_model.php";
 	include 'template/header.php'; 
 	$model = new BG_Model_db();   
 	
	if(isset($_GET['prod_disable'])){
		$delete=$model->bg_prod_disable($_GET['prod_disable']);
		if($delete=="200"){
		 $sMsg="Successfully Disabled"; 
		}
	}
	if(isset($_GET['prod_enable'])){
		$delete=$model->bg_prod_enable($_GET['prod_enable']);
		if($delete=="200"){
		 $sMsg="Successfully Enabled"; 
		}
	}
	$product_list=$model->get_bg_product();
 	
	
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
          				<p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i> Product list 
          					<?php if(isset($category_bg_name)){ echo " ($category_bg_name)"; }?>
          				</p>
          			</div>
          		</div>
          	</div>        
	        <div class="panel-body">
	        	<?php if(isset($sMsg)){ ?>
				    <div class="alert alert-success">
				        <strong><?php echo $sMsg;?></strong></br>
				    </div>
				<?php } ?>
				<?php if(isset($sMsg_failed)){ ?>
				    <div class="alert alert-danger">
				        <strong><?php echo $sMsg_failed;?></strong></br>
				    </div>
				<?php } ?>
				<div class="table-responsive">
		 			<table class="table table-bordered table-hover">
		 				<thead >
							<th class="text-center">Image</th>
							<th class="text-center">product_id</th>
							<th class="text-center">cat_id</th>
							<th class="text-center">product_name</th>
							<th class="text-center">Warehouse</th>
							<th class="text-center">Price</th>
							<th class="text-center">Date Added</th>
							<th class="text-center">modify_date</th>
							<th class="text-center">Status</th>	
							<th class="text-center">Action</th>			
						</thead>
						<tbody>
							<?php foreach($product_list as $bg_prod): ?>								
								<tr>
									<td class="text-center" ><img src="<?php echo $bg_prod['img']; ?>"  class="img-responsive" style="width: 80px; width: 80px;" /></td>
									<td class="text-left" ><?php echo $bg_prod['product_id'];?></td>
									<td class="text-left" ><?php echo $bg_prod['cat_id'];?></td>
									<td class="text-left" ><?php echo $bg_prod['product_name'];?></td>
									<td class="text-left" ><?php echo $bg_prod['warehouse'];?></td>
									<td class="text-left" >₱<?php echo  number_format( $bg_prod['price'], 2);?></td>
									<td class="text-left" ><?php echo $bg_prod['add_date'];?></td>
									<td class="text-left" ><?php echo $bg_prod['modify_date'];?></td>
									<td class="text-center"><?php if($bg_prod['status'] == '1') { 
								 			echo '<span style="color:green;">Enabled</span>'; 
								 		} else {
								 			echo '<span style="color:red;">Disabled</span>';
								 		}?>
								 	</td>
								 	<td class="text-center">
								 		<?php if($bg_prod['status'] == '1') { ?>
								 			<a href="bg_products.php?prod_disable=<?php echo $bg_prod['id'];?>" class="btn btn-warning btn-edit"  title="Disable"><i data-feather="x-circle"></i></a> 
								 		<?php } else { ?>
								 			<a href="bg_products.php?prod_enable=<?php echo $bg_prod['id'];?>" class="btn btn-primary btn-edit" title="Enable"><i data-feather="check"></i></a> 
								 		<?php }?>
								 		
	                                </td>

								</tr>
							<?php endforeach;?>							
							</tbody>
					</table>
				</div>
	        </div>
	    </div>
    </div>
</div>

<?php include 'template/footer.php'; ?>