<?php
include 'template/header.php';
require_once "model/Product.php";

$perm = $_SESSION['permission'];
if (!strpos($perm, "'15';") !== false){
    header("Location: landing.php");
   
}

$product = new Product();	
if(isset($_REQUEST['btn_search'])) {
	$product_name=$_REQUEST["product_name"];
	$model=$_REQUEST["model"];
	$price=$_REQUEST["price"];
	$status=$_REQUEST["status"];
	$quantity=$_REQUEST["quantity"];
					
	$list = $product->product_list($product_name,$model,$price,$status,$quantity);
 }
 else{
 	$list = $product->product_list('','','',"2",'');
 }
?>

<div class="content">
	<div class="container-fluid">
		<div class="panel panel-default">
		 	<div class="panel-heading" style="padding:20px;">
		 		<div class="row">
	          		<div class="col-lg-2">
	             		<p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i> Product List</p>
	          		</div>
	          		<div class="col-lg-10">
	             		<a href="uploadexcel.php"class="btn btn-success pull-right" ><i class="fas fa-upload"></i> Upload Product</a>
				 		<a href="add_product.php"class="btn btn-primary pull-right" ><i class="fas fa-plus-square"></i> Add Product</a>
				 		<a class="btn btn-danger pull-right" id="del_product"  title="Batch Delete"><i class="fas fa-trash-alt"></i> Batch Delete</a>
	          		</div>
		        </div>
		 	</div>
		 	<div class="panel-body">
		 		<div class="col-xs-12 well">
	              	<form method="post" class="form-horizontal" action="productlist.php">
						<div class="form-group">
							<div class="col-sm-6">
								<input type="text" name="product_name"  class="form-control" placeholder="Product Name" value="<?php if(isset($_REQUEST['btn_search'])){echo $_REQUEST["product_name"]; }?>"/ >
							</div>
							<div class="col-sm-6">
								<input type="text" name="model"  class="form-control" placeholder="Model" value="<?php if(isset($_REQUEST['btn_search'])){echo $_REQUEST["model"]; }?>"/ >
							</div>						
						</div>
						<div class="form-group">
							<div class="col-sm-3">
								<input type="text" name="price"  class="form-control" placeholder="Price" value="<?php if(isset($_REQUEST['btn_search'])){echo $_REQUEST["price"]; }?>"/ >
							</div>
							<div class="col-sm-3">
								<select name="d_status" id="d_status" class="form-control">
									<option value="2">--Select Status--</option>
									<option value="1">Enabled</option>
									<option value="0">Disabled</option>
								</select>
								<input type="hidden" name="status" id="status" value="<?php if(isset($_REQUEST['btn_search'])){echo $_REQUEST["status"]; }else { echo '2'; }?>"/ >
							</div>
							<div class="col-sm-3">
								<input type="number" name="quantity"  class="form-control" placeholder="Quantity" value="<?php if(isset($_REQUEST['btn_search'])){echo $_REQUEST["quantity"]; }?>"/ >
							</div>
							<div class="col-sm-3">
								<button type="submit" name="btn_search" class="btn btn-success"> <i class="fas fa-search"></i> Search</button> 
							</div>
						</div>
					</form>
	            </div>
	            
	            <div class="row">
					<div class="col-xs-12">
				 		<div class="table-responsive" style="overflow-x: auto">
				 			<table class="table table-striped table-bordered table-hover table-product" id="table-product">
						 	  	<thead>
				                    <tr>

				                     <th colspan="2">Product Id</th>
				                     <th>Product Name</th>
				                     <th>Model</th>
				                     <th>Price</th>
				                     <th>Quantity</th>
				                     <th>Status</th>
				                      <th>Action</th>
				                    </tr>
			                    </thead> 
			                    <tbody>
			                    	<script>showLoading();</script>
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
											 	<td><input type="checkbox" name="chk_id" value="<?php echo $product['prod_id'];?>" /></td>
											 	<td><?php echo  $product['prod_id'];?></td>
											 	<td><?php echo  $product['name'];?></td>
											 	<td><?php echo  $product['model'];?></td>
											 	<td><?php echo   number_format($product['price'],2);?></td>
											 	<td><?php echo  $product['quantity'];?></td>
											 	<td><?php if($product['status'] == '1') { 
											 			echo '<span style="color:green;">Enabled</span>'; 
											 		} else {
											 			echo '<span style="color:red;">Disabled</span>';
											 		}?></td>
											 	<td style="width: 120px;"> 
											 	<a href="editproduct.php?prod_id=<?php echo $product['prod_id'];?>" class="btn btn-primary btn-edit" data-product_id="<?php echo $product['prod_id'];?>"> <i class="fas fa-edit"></i></a>
											 	<a class="btn btn-danger " id="deleteProduct" data-product_id="<?php echo $product['prod_id'];?>" title="Delete" style="margin-left:5px;"><i class="fas fa-trash-alt"></i></a>

				                                </td>
											 </tr>

											<?php
										}
									}									    
								?>
								<script> hideLoading();</script>
	                  			</tbody>
					 		</table>
						</div>							
					</div>
		 		</div>
		 		
		 	</div>
		</div>
 	</div>
		
</div>

<?php include 'template/footer.php';?>									
 	
 <script>
    $(document).ready(function() {
    	$('.table-product').DataTable({"order": [],
		      "oLanguage": {
		        "sSearch": "Quick Search:"
		      },
		      "bSort": true,
		      "dom": 'Blfrtip',
		      "buttons": [{
		          extend: 'excel',
		          title: 'Product Report',
		        },
		        {
		          extend: 'pdf',
		          title: 'Product Report',
		         
		        },
		        {
		          extend: 'print',
		          title: 'Product Report',
		        },
		      ],
		      "lengthMenu": [
		        [15, 50, 100,-1],
		        [15, 50, 100,"All"]
		      ],});


    	if($("#status") != '')
    	{
    		$("#d_status").val($("#status").val());
    	}

    	$("#d_status").on('change', function(){
            $('#status').val($(this).val());
        });
    });
   /* $(document).delegate('#del_product', 'click', function() {
    	$('#del_product').prop('disabled', true);
    	var chk_id = [];
        $.each($("input[name='chk_id']:checked"), function(){
            chk_id.push($(this).val());
        });
        if(chk_id.length==0){
	    	bootbox.alert("Please select Product First ");
	    	$('#del_product').prop('disabled', false);
       }else{
	    	$.ajax({
			    url: 'ajax_sms_admin.php?action=deleteProductBatch',
			    type: 'post',
			    data: 'chk_id=' + JSON.stringify(chk_id),
			    dataType: 'json',
			    success: function(json) {
			     	 bootbox.alert(json['success'], function(){ 
	                  location.reload();
	                });
			    }
			});
        }

    });*/
    $(document).delegate('#del_product', 'click', function() {
	    $('#del_product').prop('disabled', true);
	    var chk_id = [];
	    $.each($("input[name='chk_id']:checked"), function(){
            chk_id.push($(this).val());
        });
     	bootbox.confirm({
		    message: "Are you sure you want to Delete All selected Products?",
		    buttons: {
		     	confirm: {
		          label: 'Yes',
		          className: 'btn-success'
		        },
		        cancel: {
		          label: 'No',
		          className: 'btn-danger'
		        }
		      },
	      	callback: function (result) {
		        if(result==true){
		        	if(chk_id.length==0){
				    	bootbox.alert("Please select Product First ");
				    	$('#del_product').prop('disabled', false);
				    }else{
				    	$.ajax({
						    url: 'ajax_sms_admin.php?action=deleteProductBatch',
						    type: 'post',
						    data: 'chk_id=' + JSON.stringify(chk_id),
						    dataType: 'json',
						    success: function(json) {
						     	 bootbox.alert(json['success'], function(){ 
				                  location.reload();
				                });
						    }
						});
				    }
		        }
	     	}
	    }); 
    });
    $(document).delegate('#deleteProduct', 'click', function() {
      $('#deleteProduct').prop('disabled', true);
      var product_id = $(this).data('product_id')
      //console.log(product_id);
     	bootbox.confirm({
		    message: "Are you sure you want to Delete This Product?",
		    buttons: {
		     	confirm: {
		          label: 'Yes',
		          className: 'btn-success'
		        },
		        cancel: {
		          label: 'No',
		          className: 'btn-danger'
		        }
		      },
	      	callback: function (result) {
		        if(result==true){
		        	$.ajax({
			            url: 'ajax_sms_admin.php?action=deleteProduct&t=' + new Date().getTime(),
			            type: 'post',
			            data: {
			                product_id: product_id
			            },
			            dataType: 'json',
			            beforeSend: function() {
			                bootbox.dialog({
			                      title: "Deleting Product",
			                      message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
			              });
			           },
			            success: function(json) {
			                bootbox.alert(json['success'], function(){ 
			                    window.location.reload();
			                }); 
			            }
			        });
		        }
	     	}
	    }); 
    });
 </script>

