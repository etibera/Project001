<?php
include 'template/header.php'; 
include "model/homecategory.php";
$edit_id=0;

$model=new homecategory();
$lp_prod_list=$model->getLatesPromolist_product($_GET['lp_id']); 	
if(isset($_GET['lp_delete_p'])){
	$delete=$model->lp_delete_pr($_GET['lp_delete_p']);
	if($delete=="200"){
	 $sMsg="Successfully Deleted";
	 $latestPromoList=$model->getLatesPromolist_product($_GET['lp_id']); 
	}
}
if(isset($_GET['lp_id'])){
	$latestPromoList=$model->getLatesPromolist_id($_GET['lp_id']); 
	$lp_prod_list=$model->getLatesPromolist_product($_GET['lp_id']); 
	$edit_id=$_GET['lp_id'];
	
}
if(isset($_POST['save_product_ids'])){
	if(isset($_POST['p_ids'])){
		/*echo '<pre>';
	    print_r($_POST['p_ids']);*/
	   	$result_save=$model->save_pl_producst($_POST);
	   	if($result_save=="200"){
	   		$sMsg="Products Successfully Added";
			$lp_prod_list=$model->getLatesPromolist_product($_GET['lp_id']); 
	   	}else{
	   		$sMsg_failed="add producst Failed!! ".$result_save;
			$lp_prod_list=$model->getLatesPromolist_product($_GET['lp_id']); 
	   	}
	}else{
		 $sMsg_failed="Please Select Produc First";
		$lp_prod_list=$model->getLatesPromolist_product($_GET['lp_id']); 
	}
}
?>
<div id="content">
	<div class="page-header">
      <h2 class="text-center">Products list (<?php if (isset($latestPromoList)){echo $latestPromoList['title'];} ?>) </h2>
    </div>
    <div class="container-fluid">
    	<div class="panel panel-default">
    		<div class="panel-heading" style="padding:20px;">
    		 	<div class="row">
          			<div class="col-lg-6">
          				 <p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i> Products list (<?php if (isset($latestPromoList)){echo $latestPromoList['title'];} ?>)</p>
          			</div>
          			<div class="col-lg-6">
          				<button class="btn btn-primary pull-right"  id="add_product_ids" title="Add Products"><i data-feather="plus-square"></i></button>
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
					<table class="table table-bordered table-hover" id="product_list">
						<thead>
		                    <tr>
		                      	<th class="">Product Name</th>
		                      	<th class="">Image</th>
		                      	<th class="">Type</th>
		                      	<th class="">Action</th>
		                    </tr>
		                </thead>
		                <tbody>
		                	<?php foreach ($lp_prod_list as $lis_p ) { ?>
		                	<?php $lisp_data=$model->get_products_by_type($lis_p['product_id'],$lis_p['type']); ?>
		                		<tr>
			                      	<td class=""><?php echo $lisp_data['name'];?></td>
			                      	<td class=""><img style="height: 100px; width: 100px" src="<?php echo $lisp_data['image']?>" /></td>
			                      	<td class="">
			                      		<?php if($lis_p['type']=="0"){ echo "Local Product ";}else if($lis_p['type']=="1"){echo "China Brands";}else{echo "Banngoods Product";}?>
			                      	</td>
			                      	<td class=""><a href="latestpromo_products.php?lp_delete_p=<?php echo $lis_p['id'];?>&lp_id=<?php echo $edit_id;?>" class="btn btn-danger btn-edit"  title="Delete"><i data-feather="delete"></i></a></td>
		                    	</tr>
		                	<?php } ?>
		                </tbody>
					</table>
				</div>

    		</div>
    	</div>
    </div>
</div>


<?php include 'template/footer.php'; ?>
<div  class="modal" id="add_product_lp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-error-dialog">
        <!-- <form role="form"> -->
        <div class="modal-header " style="border: none;">
        </div>
        <div class="modal-content" style="margin: auto;">
            <div class="panel-heading">

            <a type="button" id="homecategoryclosed"  data-dismiss="modal" style="float: right;
            font-size: 18px;
            font-weight: 700;
            line-height: 1;
            color: #000;
            text-shadow: 0 1px 0 #fff;
          ">x</a>
          <h3>Select Product</h3>
            </div><!-- /.panel-heading -->
            <div class="panel-body">
	            <form action="latestpromo_products.php?lp_id=<?php echo $edit_id;?>" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
		            <button class="btn btn-primary " type="submit" name="save_product_ids" title="save"><i class="fa fa-save"></i> Save</button>
		            <input type="hidden" name="lp_id" value="<?php echo $edit_id;?>">
		            </br> </br>	
	            	<div class="row">
						<div class="col-lg-12">
				            <div class="">
				                <input style=" font-size: 15px; height: 50.5px;
				                  padding: 10px 10px;
				                  border-radius: 1px;
				                  margin:0 auto;
				                  opacity: .90;" 
				                  type="text" id="search_val_input"  value="" placeholder="Search Products" class="form-control "/>
				            </div>
						</div>
					</div><br>
		            <div class="table-responsive">
						<table class="table table-bordered table-hover" id="product_all">
							<thead>
			                    <tr>
			                      <th class="text-center">Product Name</th>
			                      <th class="text-center">Model</th>
			                      <th class="text-center">Image</th>
			                      <th class="text-center">Type</th>
			                    </tr>
			                </thead>
			                <tbody></tbody>
						</table>
					</div>
		           
	            </form>
            </div><!-- /.panel-body -->
        </div><!--modal-content-->
      </div><!-- modal-dialog modal-error-dialog-->
  </div><!-- open_category_module_update_modal-->
<script>
$(document).ready(function(){
	 
	$(document).delegate('#add_product_ids', 'click', function() {
		$('#add_product_lp').modal('show'); 
	});
  	$("#search_val_input").keyup(function(){
  		$('#product_all tbody').empty();
	  	var sval=$("#search_val_input").val();
	  	setTimeout(() => {
	  		if(sval!=""){
			    $.ajax({
			        url:'ajax_homecategory.php?action=search_all_products',
			        type: 'post',
			        data: 'searc_val=' + sval,
			        dataType: 'json',
			        success: function(json) {
			        	$('#product_all tbody').empty();
			          	var product_per_category = "";
			          	if(json['data_product'].length==0){
			          		product_per_category+='<tr><td calspan="2">*** No data Found ***<td></tr>';
			          	}else{
			          		for (var i = 0; i < json['data_product'].length; i++) {
				               product_per_category =  product_per_category + '<tr><td><input type="checkbox" value="'+json['data_product'][i].product_id+'--'+json['data_product'][i].type+'" name="p_ids[]">'+json['data_product'][i].name+'</td><td>'+json['data_product'][i].model+'</td><td><img style="height: 100px; width: 100px" src="'+json['data_product'][i].image+'" /></td><td>'+json['data_product'][i].typedesc+'</td></tr>';
				           	}
			          	}	          	
			          	$('#product_all tbody').append(product_per_category);
			        },
			        error: function(xhr, ajaxOptions, thrownError) {
			            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			        }
			    });
		        //$("#product_content").text(sval);
		    }else{
		    	$('#product_all tbody').empty();
		    	$('#product_all tbody').append('<tr><td calspan="2">*** No data Found ***<td></tr>');
		    }
		}, 300);
	  	
	  	
	});
});
</script>