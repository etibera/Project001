<?php
include 'template/header.php';
include "model/reviews.php";
if(!$session->is_signed_in()){redirect("index");}

$perm = $_SESSION['permission'];
if (!strpos($perm, "'13';") !== false){
    header("Location: landing.php");
   
}

$product = new Reviews();	
if(isset($_REQUEST['btn_search'])) {
	$product_name=$_REQUEST["product_name"];
	$status=$_REQUEST["status"];
	$author=$_REQUEST["author"];
	$date=$_REQUEST["date"];
					
	$list = $product->review_list($product_name,$status,$author,$date);
 }
 else{
 	$list = $product->review_list('','2','','');
 }
?>
<div class="wrapper">
	<div class="container">
		<div class="row">
			<center><h2>Reviews</h2></center><br>
			<a href="add_reviews.php"class="btn btn-primary pull-right" >Add Review</a></br>
            <div class="col-xs-12 well">
              	<form method="post" class="form-horizontal" action="reviews.php">
					<div class="form-group">
						<div class="col-sm-6">
							<input type="text" name="product_name"  class="form-control" placeholder="Product Name" value="<?php if(isset($_REQUEST['btn_search'])){echo $_REQUEST["product_name"]; }else { echo ''; }?>"/ >
						</div>
						<div class="col-sm-6">
							<select name="d_status" id="d_status" class="form-control">
								<option value="2">--Select Status--</option>
								<option value="1">Enabled</option>
								<option value="0">Disabled</option>
							</select>
							<input type="hidden" name="status" id="status" value="<?php if(isset($_REQUEST['btn_search'])){echo $_REQUEST["status"]; }else { echo '2'; }?>"/ >
						</div>				
					</div>
					<div class="form-group">
						<div class="col-sm-6">
							<input type="text" name="author"  class="form-control" placeholder="Author" value="<?php if(isset($_REQUEST['btn_search'])){echo $_REQUEST["author"]; }else { echo ''; }?>"/ >
						</div>
						<div class="col-sm-5">
							<input type="date" name="date"  class="form-control" value="<?php if(isset($_REQUEST['btn_search'])){echo $_REQUEST["date"]; }else { echo ''; }?>"/ >
						</div>
						<div class="col-sm-1">
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
	                     <th>Product</th>
	                     <th>Author</th>
	                     <th>Ratings</th>
	                     <th>Status</th>
	                     <th>Date Added</th>
	                      <th>Action</th>
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
								 	<td><?php echo  $product['model'];?></td>
								 	<td><?php echo  $product['author'];?></td>
								 	<td><?php echo  $product['rating'];?></td>
								 	<td><?php if($product['status'] == '1') { 
								 			echo '<span style="color:green;">Enabled</span>'; 
								 		} else {
								 			echo '<span style="color:red;">Disabled</span>';
								 		}?>
								 	</td>
							 		<td><?php echo  $product['date_added'];?></td>
								 	<td> 
								 	<a href="edit_reviews.php?review_id=<?php echo $product['review_id'];?>" class="btn btn-primary btn-edit" data-product_id="<?php echo $product['prod_id'];?>">Edit</a>
	                                </td>
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

    	if($("#status") != '')
    	{
    		$("#d_status").val($("#status").val());
    	}

    	$("#d_status").on('change', function(){
            $('#status').val($(this).val());

        });
    });
 </script>

