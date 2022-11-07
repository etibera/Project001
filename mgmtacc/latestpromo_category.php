<?php
include 'template/header.php'; 
include "model/homecategory.php";
$edit_id=0;

$model=new homecategory();
$lp_cat_list=$model->getLatesPromolist_category($_GET['lp_id']); 	
if(isset($_GET['lp_delete_cat'])){
	//echo  $_GET['lp_delete_cat'];
	$delete=$model->lp_delete_cat($_GET['lp_delete_cat']);
	if($delete=="200"){
	 $sMsg="Successfully Deleted";
	 $latestPromoList=$model->getLatesPromolist_product($_GET['lp_id']); 
	 $lp_cat_list=$model->getLatesPromolist_category($_GET['lp_id']); 
	}
}
if(isset($_GET['lp_id'])){
	$latestPromoList=$model->getLatesPromolist_id($_GET['lp_id']); 
	$lp_cat_list=$model->getLatesPromolist_category($_GET['lp_id']); 
	$edit_id=$_GET['lp_id'];
	
}
if(isset($_POST['save_cat_ids'])){
	if($_POST['category_add']!="0"){
	 // echo $_POST['category_add'];
	   	$result_save=$model->save_pl_cat($_POST);
	   	if($result_save=="200"){
	   		$sMsg="Products Successfully Added";
			$lp_cat_list=$model->getLatesPromolist_category($_GET['lp_id']); 
	   	}else{
	   		$sMsg_failed="add producst Failed!! ".$result_save;
			$lp_cat_list=$model->getLatesPromolist_category($_GET['lp_id']); 
	   	}
	}else{
		 $sMsg_failed="Please Select Categogy First";
		 $lp_cat_list=$model->getLatesPromolist_category($_GET['lp_id']); 
	}
}
?>
<div id="content">
	<div class="page-header">
      <h2 class="text-center">Category list (<?php if (isset($latestPromoList)){echo $latestPromoList['title'];} ?>) </h2>
    </div>
    <div class="container-fluid">
    	<div class="panel panel-default">
    		<div class="panel-heading" style="padding:20px;">
    		 	<div class="row">
          			<div class="col-lg-6">
          				 <p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i> Category list (<?php if (isset($latestPromoList)){echo $latestPromoList['title'];} ?>)</p>
          			</div>
          			<div class="col-lg-6">
          				<button class="btn btn-primary pull-right"  id="add_cat_ids" title="Add category"><i data-feather="plus-square"></i></button>
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
		                      	<th class="">Category Name</th>
		                      	<th class="">Action</th>
		                    </tr>
		                </thead>
		                <tbody>
		                	<?php foreach ($lp_cat_list as $lis_p ) { ?>
		                	
		                		<tr>
			                      	<td class=""><?php echo $lis_p['name'];?></td>
			                      
			                      	<td class=""><a href="latestpromo_category.php?lp_delete_cat=<?php echo $lis_p['id'];?>&lp_id=<?php echo $edit_id;?>" class="btn btn-danger btn-edit"  title="Delete"><i data-feather="delete"></i></a></td>
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
<div  class="modal" id="add_cat_lp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
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
          <h3>Select Category</h3>
            </div><!-- /.panel-heading -->
            <div class="panel-body">
	            <form action="latestpromo_category.php?lp_id=<?php echo $edit_id;?>" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
		            <input type="hidden" name="lp_id" value="<?php echo $edit_id;?>">	
	            	<div class="row">
						<div class="col-lg-12">
				            <div class="">
				                <select class="form-control" id ="category_add" name="category_add">
		                            <option value="0" >Select Here</option>
		                         </select>
				            </div>
						</div>
					</div><br>
					<button class="btn btn-primary " type="submit" name="save_cat_ids" title="save"><i class="fa fa-save"></i> Save</button>
	            </form>
            </div><!-- /.panel-body -->
        </div><!--modal-content-->
      </div><!-- modal-dialog modal-error-dialog-->
  </div><!-- open_category_module_update_modal-->
<script>
$(document).ready(function(){
	 
	
  	
});
$(document).delegate('#add_cat_ids', 'click', function() {
	$.ajax({
      url:'ajax_homecategory.php?action=getcategory',
      type: 'post',
      dataType: 'json',
      success: function(json) {
        var options_category_add = "";
          for (var i = 0; i < json['getcategories'].length; i++) {
              options_category_add =  options_category_add + '<option value="'+json['getcategories'][i].category_id+'">'+json['getcategories'][i].name+'</option>';
          }
        $('#category_add').append(options_category_add);
      },
      error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
	$('#add_cat_lp').modal('show'); 
});
</script>