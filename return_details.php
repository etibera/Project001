<?php
include "common/header.php";
include "model/returns.php";
$session->check_the_login2();
 $model=new Returns();
 if(isset($_GET['retid']))	
{			
	$data=$model->return_details($_GET['retid']);
	$return_history=$model->return_history_details($_GET['retid']);
} 
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
				<h2>Return Information</h2>
			</div>
		</div>	
		<div class="row">
			<div class="col-sm-12">
				<div class="table-responsive">					
			 		<table class="table table-striped table-bordered table-hover">
						<tr>
							<td colspan="2">Return Details</td>
						</tr>
						<tr>
							<td>
								<address>
									<b>Return ID:</b><?php echo ' #'.$data['return_id'];?><br>
									<b>Date Added:</b><?php echo ' '.$data['date_added'];?>
								</address>
							</td>
							<td>
								<address>
									<b>Order ID:</b><?php echo ' #'.$data['order_id'];?><br>
									<b>Order Date:</b><?php echo ' '.$data['date_ordered'];?>
								</address>
							</td>
						</tr>
			 		</table>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<h2>Product Information & Reason for Return</h2>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="table-responsive">					
			 		<table class="table table-striped table-bordered table-hover">
						<tr>
							<td>Product Name</td>
							<td>Model</td>
							<td>Serial</td>
							<td>Quantity</td>
						</tr>
						<tr>
							<td><?php echo $data['product'];?></td>
							<td><?php echo $data['model'];?></td>
							<td><?php echo $data['serial'];?></td>
							<td><?php echo $data['quantity'];?></td>
						</tr>
			 		</table>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="table-responsive">					
			 		<table class="table table-striped table-bordered table-hover">
						<tr>
							<td>Reason</td>
							<td>Opened</td>
							<td>Action</td>
						</tr>
						<tr>
							<td><?php echo $data['return_reason'];?></td>
							<td><?php echo $data['opened'];?></td>
							<td><?php echo $data['return_action'];?></td>
						</tr>
			 		</table>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="table-responsive">					
			 		<table class="table table-striped table-bordered table-hover">
						<tr>
							<td>Return Comment</td>
						</tr>
						<tr>
							<td><?php echo $data['comment'];?></td>
						</tr>
			 		</table>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<h2>Return History</h2>			
			</div>
		</div>	
		<div class="row">
			<div class="col-sm-12">
				<div class="table-responsive">					
			 		<table class="table table-striped table-bordered table-hover">
			 			<thead>
							<th>Date Added</th>
							<th>Status</th>
							<th>Comment</th>
						</thead>
						<tbody>
							<?php
								if(count($return_history) > 0){ 
								foreach($return_history as $o):
							?>
							<tr>
								<td><?php echo $o['added_date'];?></td>
								<td><?php echo $o['return_status'];?></td>
								<td><?php echo $o['comment'];?></td>
							</tr>
						<?php endforeach; }
							else { ?>
									<tr><td colspan="6" align="center">No data found.</td></tr>
								<?php } ?>
						</tbody>
			 		</table>
				</div>
			</div>
		</div>
		<br>
		<div class="form-group pull-right">
			<a href="./index.php" title="Continue" class="btn btn-primary">Continue</a>
		</div>
	</div>
</div>
<?php
include "common/footer.php";
?>										

 <script>
    $(document).ready(function() {
  	

    });
 </script>
