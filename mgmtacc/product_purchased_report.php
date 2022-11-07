<?php
include 'template/header.php';
include "model/product_purchased_report.php";


if(!$session->is_signed_in()){redirect("index");}


$perm = $_SESSION['permission'];
if (!strpos($perm, "'21';") !== false){
    header("Location: landing.php");
   
}

$product = new product_purhased();	
if(isset($_REQUEST['btn_search'])) {
	$datefrom=$_REQUEST["datefrom"];
	$dateto=$_REQUEST["dateto"];
	$status=$_REQUEST["status"];
	
					
	$list = $product->productpurchased_list($datefrom,$dateto,$status);
 }
 else{
 	$list = $product->productpurchased_list('','','0');
 }
?>
<div class="wrapper">
	<div class="container">
		<div class="row">
			<center><h2>Product Purchased Report</h2></center><br>
            <div class="col-xs-12 well">
              	<form method="post" class="form-horizontal" action="product_purchased_report.php">
					<div class="form-group">
						<div class="col-sm-6">
							<label>Date From</label>
							<input type="date" name="datefrom"  class="form-control" value="<?php if(isset($_REQUEST['btn_search'])){echo $_REQUEST["datefrom"]; }else { echo ''; }?>"/ >
						</div>
						<div class="col-sm-6">
							<label>Date To</label>
							<input type="date" name="dateto"  class="form-control" value="<?php if(isset($_REQUEST['btn_search'])){echo $_REQUEST["dateto"]; }else { echo ''; }?>"/ >
						</div>				
					</div>
					<div class="form-group">
						<div class="col-sm-6">
							<label>Order Status</label>
								<select name="d_status" id="d_status" class="form-control">
								<option value="0">--Select Status--</option>
								<?php
									foreach($product->order_status() as $status) {
										echo '<option value='.$status['order_status_id'].'>'.$status['name'].'</option>';
									}
								?>
							</select>
							<input type="hidden" name="status" id="status" value="<?php if(isset($_REQUEST['btn_search'])){echo $_REQUEST["status"]; }else { echo '0'; }?>"/ >
						</div>
					
						<div class="col-sm-6">
							<br>
							<input type="submit" name="btn_search" class="btn btn-success" value="Search">
						</div>
					</div>
				</form>
            </div>
         </div>
		<div class="row">
		<div class="col-xs-12">
			 <div class="table-responsive" style="overflow-x: auto">
			 	<table class="table table-striped table-bordered table-hover" id="table-product">
			 	  	<thead>
	                    <tr>
	                     <th>Product Name</th>
	                     <th>Model</th>
	                     <th>Quantity</th>
	                     <th>Total Price</th>
	                   
	                    </tr>
                    </thead> 
                    <tbody>
                   	 <?php					
						if(count($list) == 0){
							?>
							 <tr>
							 	<td colspan="6" align="center">No data found.</td>
							 	
							 </tr>
							<?php
						}else{
							foreach($list as $product)
							{
								?>
								 <tr>
								 	<td><?php echo  $product['name']; ?></td>
								 	<td><?php echo  $product['model'];?></td>
								 	<td><?php echo  $product['total_quantity'];?></td>
								 	<td><?php echo  number_format($product['total'],2);?></td>
								 	
								 	
								 	
								 </tr>

								<?php
							}
						}									    
					?>
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

    	if($("#status") != '0')
    	{
    		$("#d_status").val($("#status").val());
    	}

    	$("#d_status").on('change', function(){
            $('#status').val($(this).val());

        });
    });
 </script>

