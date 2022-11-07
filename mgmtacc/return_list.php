<?php
 include 'template/header.php'; 
 include "model/Returns.php";

$perm = $_SESSION['permission'];
if (!strpos($perm, "'16';") !== false){
    header("Location: landing.php");
   
}

 $model=new Returns();
 $data=$model->return_historyA();
?>
<script type="text/javascript">
  var islog='<?php echo $is_log;?>';
      if(islog=="0"){
      location.replace("home.php");
    }
</script>
<div class="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<center><h2>Product Return List</h2></center><br>			 	
			</div> 
		</div>
 		<?php if(isset($_SESSION['message'])):?>
        <?php echo $_SESSION['message'];?>
        <?php endif; unset($_SESSION['message']);?>
		<div class="row">
			<div class="col-sm-12">
				<div class="table-responsive">					
			 		<table class="table table-striped table-bordered table-hover return-table">
			 			<thead>
							<th>Return ID</th>
							<th>Order ID</th>
							<th>Customer</th>
							<th>Product</th>
							<th>Model</th>
							<th>Serial</th>
							<th>Status</th>
							<th>Date Added</th>
							<th>Date Modified</th>
							<th>Action</th>
						</thead>
						<tbody>
							<?php
								if(count($data) > 0){ 
								foreach($data as $o):
							?>
							<tr>
								<td><?php echo '#'.$o['return_id'];?></td>
								<td><?php echo '#'.$o['order_id'];?></td>
								<td><?php echo $o['customer'];?></td>
								<td><?php echo $o['product'];?></td>
								<td><?php echo $o['model'];?></td>
								<td><?php echo $o['serial'];?></td>
								<td><?php echo $o['return_status'];?></td>
								<td><?php echo $o['date_added'];?></td>
								<td><?php echo $o['date_modified'];?></td>

								<td>						
									 <a class="btn btn-sm btn-primary"
					                     href="return_info.php?retid=<?php echo  $o['return_id'];?>" >
					                     <i data-feather="edit-2"></i>
					                 </a>
					             	
				                 </td>
							</tr>
						<?php 
							endforeach;
							}
							else { ?>
									<tr><td colspan="6" align="center">No data found.</td></tr>
								<?php } ?>
						</tbody>
			 		</table>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include 'template/footer.php';?>
<script>
	$(document).ready(function() {
		$('.return-table').paging({	 
			limit: 30,
			rowDisplayStyle: 'block',
			activePage: 0,
			rows: []
		});
	});
</script>
