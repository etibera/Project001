<?php 
	include 'template/header.php'; 
	include "model/china_PO.php";
	$model = new China_PO(); 
	$perm = $_SESSION['permission'];
	if (!strpos($perm, "'28';") !== false){
	    header("Location: landing.php");
	}  
	if(isset($_GET['batch_id'])){
		$data_batch_order=$model->get_batch_order_id($_GET['batch_id']);
	}else{
		$data_batch_order=$model->get_batch_order();
	}
?>

<div id="content">
	<div class="page-header">
		 <h2 class="text-center">Batch Orders</h2>
	</div>
	<div class="container-fluid">
		<div class="panel panel-success">
			<div class="panel-heading" >
				<div class="row">
		 			<div class="col-lg-6">
          				<p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i> Batch Order List</p>
          			</div>
          		</div>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
    	 			<table class="table table-bordered table-hover">
    	 				<thead >	
							<th class="text-left" >Batch Order Id</th>
							<th class="text-left">Order id</th>						
							<th class="text-left">Name</th>							
						</thead>
						<tbody>
							<?php if(!$data_batch_order){?>
							 	<tr>
							 		<td class="text-center" colspan="3">**No Da Found**</td>
							 	</tr>
						 	<?php }?>
						 	<?php foreach($data_batch_order as $BO): ?>
						 		<tr>
					 				<td class="text-left" colspan="3"><?php echo $BO['id']." (".$BO['date_added'].")";?></td>
						 		</tr>
						 		<?php foreach($model->get_batch_order_det($BO['id']) as $BO_det): ?>
						 		<tr>
						 			<td class="text-left" ></td>
						 			<td class="text-left" >
						 				<a  title="View"
	                    				 href="view_order.php?order_id=<?php echo  $BO_det['order_id'];?>" >
	                    				<?php echo $BO_det['order_id'];?>
	                 					</a>
	                 				</td>
						 			<td class="text-left" ><?php echo $BO_det['name'];?></td>
					 				
						 		</tr>
						 		<?php endforeach;?>
						 	<?php endforeach;?>
						</tbody>
    	 			</table>
    	 		</div>
			</div>
		</div>
	</div>
</div>
<?php include 'template/footer.php'; ?>