<?php
include 'template/header.php';
include "model/Coupon.php";
$model = new Coupon(); 


$perm = $_SESSION['permission'];
if (!strpos($perm, "'11';") !== false){
    header("Location: landing.php");
   
}
      
if(isset($_GET['cid'])) {
    $coupon=$_GET["cid"];
    $data = $model->coupon_details($coupon);
    $history = $model->coupon_history($coupon);
    $coupon_product = $model->coupon_product($coupon);
    $coupon_category = $model->coupon_category($coupon);
    $category = $model->category();
    $product = $model->product();

 }
if(isset($_POST['update_coupon'])){  
	if($_GET['cid'] !== '0') {
   		$update = $model->coupon_update($_POST);
	} else {
		$add = $model->coupon_add($_POST);
	}
	$data = $model->coupon_details($coupon);
 	$coupon_product = $model->coupon_product($coupon);
    $coupon_category = $model->coupon_category($coupon);
}
?>
<div class="container">
<form action=""method="post" enctype="multipart/form-data" id="form-coupon">
	<div class="row">
		<div class="form-group">
			<div class="col-lg-12">
				<?php $header = $_GET['cid'] !== '0' ? $header = 'Edit Coupon' : $header = 'Add New Coupon'; ?>
				 <span style="font-size: 26px" class="pull-left"><?php echo $header;?></span>
				 <div class="pull-right">
					 <a href="coupon_list.php" class="btn btn-danger" title="Back"><i data-feather="arrow-left"></i></a>
					 <button type="submit" class="btn btn-primary" name="update_coupon" title="Save"><i data-feather="save"></i></button>
				 </div>
			</div>
		</div>
	</div>
	<br>
   	<?php if(isset($errorMsg)){ ?>
	<div class="alert alert-danger">
    <?php foreach ($errorMsg as $error) : ?>  
    <strong><?php echo $error['name']?></strong></br>
    <?php  endforeach;?>
	</div>
		<?php } ?>
	<?php if(isset($_SESSION['message'])):?>
	<?php echo $_SESSION['message'];?>		
	<?php endif;?>
	<?php unset($_SESSION['message']); ?>
	<ul class="nav nav-tabs justify-content-center">
        <li class="nav-item active">
            <a class="nav-link" href="#General" data-toggle="tab" aria-expanded="true">General</a>
        </li>
        <?php if(isset($_GET['cid']) && $_GET['cid'] !== '0'): ?>
        <li class="nav-item">
            <a class="nav-link" href="#History" data-toggle="tab" aria-expanded="true">History</a>
        </li>
    	<?php endif; ?>
    </ul>
    <div class="tab-content">
    	<div class="tab-pane active" id="General">
    		<br>
    		<div class="row form-group">
				<div class="col-sm-3 control-label"><label>Coupon Name</label></div>					
				<div class="col-sm-6">
					<input type="hidden" name="coupon_id" class="form-control" value="<?php echo $_GET['cid']; ?>" required/>
					<input type="name" name="name" class="form-control" placeholder="Coupon Name" value="<?php echo isset($_SESSION['name'])? $_SESSION['name'] : $data['name']; ?>" required/>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-3 control-label"><label>Code</label></div>					
				<div class="col-sm-6">
					<input type="hidden" name="coupon_id" class="form-control" value="<?php echo $_GET['cid']; ?>" required/>
					<input type="text" name="code" class="form-control" placeholder="Code" value="<?php echo isset($_SESSION['code'])? $_SESSION['code'] : $data['code']; ?>" required/>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-3 control-label"><label>Type</label></div>	
				<div class="col-sm-6">
					<select name="type" id="type" class="form-control" required>
						<option value="">--Select Type--</option>
						<option value="P">Percentage</option>
						<option value="F">Fixed Amount</option>						
						<?php echo "<script>$('#type').val('".$data['type']."')".";</script>'";?>
					</select>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-3 control-label"><label>Discount</label></div>					
				<div class="col-sm-6">
					<input type="number" name="discount" class="form-control" placeholder="Discount" value="<?php echo isset($_SESSION['discount'])? $_SESSION['discount'] : $data['discount']; ?>" required/>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-3 control-label"><label>Total Amount</label></div>					
				<div class="col-sm-6">
					<input type="number" name="total" class="form-control" placeholder="Total Amount" value="<?php echo isset($_SESSION['total'])? $_SESSION['total'] : $data['total']; ?>" required/>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-3 control-label"><label>Customer Login</label></div>	
				<div class="col-sm-6">
					<select name="logged" id="logged" class="form-control" required>
						<option value="">--Select--</option>
						<option value="1">Yes</option>
						<option value="0">No</option>						
						<?php echo "<script>$('#logged').val('".$data['logged']."')".";</script>'";?>
					</select>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-3 control-label"><label>Free Shipping</label></div>	
				<div class="col-sm-6">
					<select name="shipping" id="shipping" class="form-control" required>
						<option value="">--Select--</option>
						<option value="1">Yes</option>
						<option value="0">No</option>						
						<?php echo "<script>$('#shipping').val('".$data['shipping']."')".";</script>'";?>
					</select>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-3 control-label"><label>Products</label></div>					
				<div class="col-sm-6">
					<input type="text" id="product" name="product" class="form-control" placeholder="Products"/>
					<div class="well" style="padding:5px;height: 170px;font-size:12px;overflow: auto;">
						<table id="product_list" style="width: 100%">
						<?php if(count($coupon_product) > 0){ 
								foreach($coupon_product as $o):
						?>
								<tr>
									<td id="prdct<?php echo $o['product_id'];?>" style="display:none;"><input type="text" name="product_id[]" value="<?php echo $o['product_id'];?>"></td>
									<td style="width:40px;padding-top:5px"><button type="button" class="btn btn-sm btn-danger prod-del"><i class="fas fa-times"></i></button></td>
									<td><?php echo $o['product_name'];?></td>
								</tr>
						<?php endforeach; }?>							
						</table>
					</div>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-3 control-label"><label>Category</label></div>					
				<div class="col-sm-6">
					<input type="text" id="category" name="category" class="form-control" placeholder="Category"/>
					<div class="well" style="padding:5px;height: 170px;font-size:12px;overflow: auto;">
						<table id="category_list" style="width: 100%">
						<?php if(count($coupon_category) > 0){ 
								foreach($coupon_category as $o):
						?>
								<tr>
									<td id="ctgry<?php echo $o['category_id'];?>" style="display:none;"><input type="text" name="category_id[]" value="<?php echo $o['category_id'];?>"></td>
									<td style="width:40px;padding-top:5px"><button type="button" class="btn btn-sm btn-danger cat-del"><i class="fas fa-times"></i></button></td>
									<td><?php echo $o['category_name'];?></td>
								</tr>
						<?php endforeach; }?>							
						</table>
					</div>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-3 control-label"><label>Date Start</label></div>					
				<div class="col-sm-6">
					<input type="date" name="date_start" class="form-control" placeholder="Date Start" value="<?php echo isset($_SESSION['date_start'])? $_SESSION['date_start'] : $data['date_start']; ?>" required/>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-3 control-label"><label>Date End</label></div>					
				<div class="col-sm-6">
					<input type="date" name="date_end" class="form-control" placeholder="Date End" value="<?php echo isset($_SESSION['date_end'])? $_SESSION['date_end'] : $data['date_end']; ?>" required/>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-3 control-label"><label>Uses Per Coupon</label></div>					
				<div class="col-sm-6">
					<input type="number" name="uses_total" class="form-control" placeholder="Uses Per Coupon" value="<?php echo isset($_SESSION['uses_total'])? $_SESSION['uses_total'] : $data['uses_total']; ?>" min="0" required/>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-3 control-label"><label>Uses Per Customer</label></div>					
				<div class="col-sm-6">
					<input type="number" name="uses_customer" class="form-control" placeholder="Uses Per Customer" value="<?php echo isset($_SESSION['uses_customer'])? $_SESSION['uses_customer'] : $data['uses_customer']; ?>" min="0" required/>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-3 control-label"><label>Status</label></div>	
				<div class="col-sm-6">
					<select name="status" id="status" class="form-control" required>
						<option value="">--Select Status--</option>
						<option value="0">Disable</option>
						<option value="1">Enable</option>
						<?php echo "<script>$('#status').val('".$data['status']."')".";</script>'";?>
					</select>
				</div>
			</div>
		</div>
		<div class="tab-pane" id="History">
			<br>
			<div class="row">
				<div class="col-sm-12">
					<div class="table-responsive">					
				 		<table class="table table-striped table-bordered table-hover">
				 			<thead>
								<th>Order ID</th>								
								<th>Customer</th>
								<th>Amount</th>
								<th>Date Added</th>										
							</thead>
							<tbody>
								<?php 
									if(count($history) > 0){ 
									foreach($history as $o):
								?>
								<tr>
									<td><?php echo $o['order_id'];?></td>
									<td><?php echo $o['customer'];?></td>
									<td><?php echo $o['amount'];?></td>
									<td><?php echo $o['date_added'];?></td>
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
		</div>
	</div>		
</form>
</div>  

<?php include 'template/footer.php';?> 
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>                                                            
<script>
	 
	$(document).ready(function() {		

		//PRODUCT AUTOCOMPLETE
        $.ajax({
	        url: 'ajax_coupon.php?searchproduct=1',
	        type: 'GET',
	        dataType: 'json',
	        success: function(json) {
	        	var prods =[];
	        	for(var i=0; i < json.length;i++)
	        	{		        		
	        		prods.push({
	        			label : json[i].name,
	        			value : json[i].product_id
	        		});
	        	}

	        	$("#product").autocomplete({
    			  onfocus: true,
				  source: function(request, response) {
				        var results = $.ui.autocomplete.filter(prods, request.term);

				        response(results.slice(0, 5));
					},
				  minLength: 0,
				  select: function(event, ui) {
				      event.preventDefault();	
      			      $('#product').blur();
      			      	if($('#prdct'+ ui.item.value).length == 0) {
      			      		//reizon
					      $('#product_list').append(
					      	'<tr>' +
					      		'<td id="prdct' + ui.item.value + '" style="display:none"><input type="text" name="product_id[]" value="' + ui.item.value + '"></td>' +
					      		'<td style="width:40px;padding-top:5px"><button type="button" class="btn btn-sm btn-danger prod-del"><i class="fas fa-times"></i></button></td>' +
					      		'<td>' + ui.item.label + '</td>' +				      		
					      	'</tr>'
					      );
			  			}
				  }
				}).bind('focus', function() {       
				      if(!$(this).val().trim())
				            $(this).keydown();
				});
	        	
	        },
	            error: function(xhr, ajaxOptions, thrownError) {
	                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });


        //CATEGORY AUTOCOMPLETE
        $.ajax({
	        url: 'ajax_coupon.php?searchcategory=1',
	        type: 'GET',
	        dataType: 'json',
	        success: function(json) {
	        	var prods =[];
	        	for(var i=0; i < json.length;i++)
	        	{		        		
	        		prods.push({
	        			label : json[i].name,
	        			value : json[i].category_id
	        		});
	        	}

	        	$("#category").autocomplete({
    			  onfocus: true,
				  source: function(request, response) {
				        var results = $.ui.autocomplete.filter(prods, request.term);

				        response(results.slice(0, 5));
					},
				  minLength: 0,
				  select: function(event, ui) {
				      event.preventDefault();	
      			      $('#category').blur();
      			      	if($('#ctgry'+ ui.item.value).length == 0) {

					      $('#category_list').append(
					      	'<tr>' +
					      		'<td id="ctgry' + ui.item.value + '" style="display:none"><input type="text" name="category_id[]" value="' + ui.item.value + '"></td>' +
					      		'<td style="width:40px;padding-top:5px"><button type="button" class="btn btn-sm btn-danger cat-del"><i class="fas fa-times"></i></button></td>' +
					      		'<td>' + ui.item.label + '</td>' +				      		
					      	'</tr>'
					      );
			  			}
				  }
				}).bind('focus', function() {       
				      if(!$(this).val().trim())
				            $(this).keydown();
				});
	        	
	        },
	            error: function(xhr, ajaxOptions, thrownError) {
	                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });


        $('#product_list').on('click', '.prod-del', function () {

           	$(this).parent('td').parent('tr').remove();
        });	

        $('#category_list').on('click', '.cat-del', function () {

           	$(this).parent('td').parent('tr').remove();
        });		
	});

</script>

