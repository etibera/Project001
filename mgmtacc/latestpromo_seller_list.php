<?php
include 'template/header.php'; 
include "model/homecategory.php";
$edit_id=0;

$model=new homecategory();
$lp_seller_list=$model->getLatesPromoSellerList($_GET['lp_id']); 	
$seller_list=$model->get_seller_list(); 	
	

if(isset($_GET['lp_id'])){
	$latestPromoList=$model->getLatesPromolist_id($_GET['lp_id']); 
	$lp_seller_list=$model->getLatesPromoSellerList($_GET['lp_id']); 	
	$edit_id=$_GET['lp_id'];
	
}
if(isset($_POST['save_product_ids'])){
	if(isset($_POST['p_ids'])){
		/*echo '<pre>';
	    print_r($_POST['p_ids']);*/
	   	$result_save=$model->save_pl_producst($_POST);
	   	if($result_save=="200"){
	   		$sMsg="Products Successfully Added";
			$lp_seller_list=$model->getLatesPromoSellerList($_GET['lp_id']); 	
	   	}else{
	   		$sMsg_failed="add producst Failed!! ".$result_save;
			$lp_seller_list=$model->getLatesPromoSellerList($_GET['lp_id']); 	
	   	}
	}else{
		 $sMsg_failed="Please Select Produc First";
		$lp_seller_list=$model->getLatesPromoSellerList($_GET['lp_id']); 	
	}
}
/*echo "<pre>";
print_r($getpromoperseller);*/
?>
<div id="content">
	<div class="page-header">
      <h2 class="text-center">Seller list (<?php if (isset($latestPromoList)){echo $latestPromoList['title'];} ?>) </h2>
    </div>
    <div class="container-fluid">
    	<div class="panel panel-default">
    		<div class="panel-heading" style="padding:20px;">
    		 	<div class="row">
          			<div class="col-lg-6">
          				 <p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i> Seller list (<?php if (isset($latestPromoList)){echo $latestPromoList['title'];} ?>)</p>          			
          			</div>
          			<div class="col-lg-6">
          				<a class="btn btn-danger pull-right" id="delete_seller" title="Delete Seller" style="margin-left:5px; "><i data-feather="trash-2"></i> Delete Seller</a>
			        	<a class="btn btn-danger pull-right" id="delete_seller_promo" title="Delete Seller Promo" style="margin-left:5px; "><i data-feather="trash-2"></i> Delete Seller Promo</a>
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
				<div class="table-responsive" style="overflow-x: auto">
					<table class="table table-striped table-bordered table-hover" id="tbl_deductlist">
						<?php  $countdata = 0; foreach ($lp_seller_list as $lis_r ) {  $countdata ++?>
							<tbody>
								<tr>
			            			<th data-toggle="collapse" data-target="#accordiontbl_<?php echo $lis_r['id']?>" class="clickable" colspan="2"> 
			            				<input type="checkbox" name="chk_deduc_id" value="<?php echo $lis_r['id'];?>"/>
			            				<img src="<?php echo $lis_r['image'];?>" style="width: 50px;height: 50px" style="margin:auto"/> 
			            				 <?php echo $lis_r['shop_name']?>
			            			</th>
			            		</tr>
							<tbody>
							<tbody id="accordiontbl_<?php echo $lis_r['id'];?>" class="<?php if($countdata!=1){ echo "collapse";}?>">
								<?php if (count($lis_r['seller_promo_list'])==0) { ?>
				                    <tr><th class="text-center" colspan="7">***No Data Found***</th></tr>
				                <?php }else{ ?>
					                <tr>
					                	<th class="text-center" style="width: 120px;"style="vertical-align: middle;">
						                    <a class="btn btn-success" id="tbl_select_all_<?php echo $lis_r['id'];?>" title="Select All Product" onclick="tbl_CheckAllProducts(<?php echo $lis_r['id'];?>)" style="width: 90px;">
						                       <i class="fa fa-check-square"></i>
						                    </a>
						                    <span id="tbl_select_all_span<?php echo $lis_r['id'];?>"><b>Select All</b></span>
						                    <a style="display: none;width: 90px;"class="btn btn-success" id="tbl_unselect_all_<?php echo $lis_r['id'];?>" title="Un Select All Product" onclick="tbl_UnCheckAllProducts(<?php echo $lis_r['id'];?>)" >
						                        <i class="fa fa-square"></i>
						                    </a>
						                    <span id="tbl_unselect_all_span<?php echo $lis_r['id'];?>"style="display: none;"><b> Un Select All</b></span> 

						                      
						                </th>
					                    <th class="text-center">Promo discription</th>
					                    <th class="text-center">Deduction Type</th>
					                    <th class="text-center">Value </th>
					                    <th class="text-center">Duration </th>
				                    </tr>
				                    <?php foreach ($lis_r['seller_promo_list'] as  $spList) { ?>
				                    	<tr>
				                    		<td >
					                          	<div class="image-container2 tdchkProduct_div_tbl<?php echo $lis_r['id'];?>"  id="product-div_tbl<?php echo $spList['id'];?>">
					                            	<input type="checkbox" name="chk_prod_idtbl[]" value="<?php echo $spList['id'];?>" />
					                          	</div>
					                        </td>
					                         <td><?php echo $spList['description'];?></td>
					                        <td><?php echo $spList['d_type'];?></td>
					                        <td><?php echo $spList['value'];?></td>
					                        <td><?php echo $spList['date_from']."-". $spList['date_to']?></td>
				                    	</tr>
				                    <?php } ?>
				                <?php } ?>
							</tbody>
						<?php } ?>
						
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
          <h3>Select Seller</h3>
            </div><!-- /.panel-heading -->
            <div class="panel-body">
	           
		            <button class="btn btn-primary " id="save_seller" name="save_seller" title="save"><i class="fa fa-save"></i> Save</button>
		            <input type="hidden" name="lp_id" id="lp_id"  value="<?php echo $edit_id;?>">
		            </br> </br>	
	            	<div class="row">
						<div class="col-lg-12">
				               <select name="seller_id_data" id="seller_id_data" class="form-control" required>
				               	 <option value="" selected>Select Seller</option> 
				               	 <?php foreach ($seller_list as  $sl) { ?>				               	  
				               	   <option value="<?php echo $sl['seller_id'];?>"><?php echo $sl['shop_name'];?></option> 
				               	 <?php } ?>
				               </select>
						</div>
					</div><br>
		            <div class="table-responsive">
						<table class="table table-bordered table-hover" id="product_all">
							<thead>
			                    <tr>
			                      <th class="text-center">Promo discription</th>
			                      <th class="text-center">Deduction Type</th>
			                      <th class="text-center">Value</th>
			                      <th class="text-center">Duration</th>
			                    </tr>
			                </thead>
			                <tbody></tbody>
						</table>
					</div>
            </div><!-- /.panel-body -->
        </div><!--modal-content-->
      </div><!-- modal-dialog modal-error-dialog-->
  </div><!-- open_category_module_update_modal-->
<script>
function tbl_CheckAllProducts(brandid) {
    $("#tbl_unselect_all_"+brandid).css("display","block")
    $("#tbl_unselect_all_span"+brandid).css("display","block")
    $("#tbl_select_all_"+brandid).css("display","none")
    $("#tbl_select_all_span"+brandid).css("display","none")

    $("#tbl_unselect_all_src"+brandid).css("display","none");
    $("#tbl_unselect_all_src_span"+brandid).css("display","none");    
    $("#tbl_select_all_src"+brandid).css("display","none");
    $("#tbl_select_all_src_span"+brandid).css("display","none");

    $(".tdchkProduct_div_tbl"+brandid).find('input[type=checkbox]').each(function () {
      this.checked = true;
    });
}
function tbl_UnCheckAllProducts(brandid) {
    $("#tbl_unselect_all_"+brandid).css("display","none")
    $("#tbl_unselect_all_span"+brandid).css("display","none")
    $("#tbl_select_all_"+brandid).css("display","block")
    $("#tbl_select_all_span"+brandid).css("display","block")

    $("#tbl_unselect_all_src"+brandid).css("display","none");
    $("#tbl_unselect_all_src_span"+brandid).css("display","none");    
    $("#tbl_select_all_src"+brandid).css("display","none");
    $("#tbl_select_all_src_span"+brandid).css("display","none");
    $(".tdchkProduct_div_tbl"+brandid).find('input[type=checkbox]').each(function () {
      this.checked = false;
    });
 }
$(document).ready(function(){
	 $(document).delegate('#delete_seller', 'click', function() {
	 	
    	$('#delete_seller').prop('disabled', true);
    	var chk_deduc_id = [];        	
    	$.each($("input[name='chk_deduc_id']:checked"), function(){
              chk_deduc_id.push($(this).val());
        });	       
        if(chk_deduc_id.length==0){
          bootbox.alert("Please select 	Seller To Delete ");
          $('#delete_seller').prop('disabled', false);
        }else{
        	//console.log(JSON.stringify(chk_deduc_id));
        	$.ajax({
	            url: 'ajax_homecategory.php?action=delete_seller&t=' + new Date().getTime(),
	            type: 'post',
	            data: 'chk_deduc_id=' + JSON.stringify(chk_deduc_id),
	            dataType: 'json',
	            success: function(json) {
	              bootbox.alert(json['success'], function(){ 
	                    location.reload();
	              });

	            }
	        });     
        }
    });
	$(document).delegate('#delete_seller_promo', 'click', function() {
    	$('#delete_seller_promo').prop('disabled', true);
    	var chk_deduc_pid = [];
    	
        $.each($("input[name='chk_prod_idtbl[]']:checked"), function(){
              chk_deduc_pid.push($(this).val());
        });
        console.log(chk_deduc_pid);
        if(chk_deduc_pid.length==0){
          bootbox.alert("Please select Seller Promo To Delete ");
          $('#delete_seller_promo').prop('disabled', false);
        }else{

        	$.ajax({
	            url: 'ajax_homecategory.php?action=delete_seller_promo&t=' + new Date().getTime(),
	            type: 'post',
	            data: 'chk_deduc_id=' + JSON.stringify(chk_deduc_pid),
	            dataType: 'json',
	            success: function(json) {
	              bootbox.alert(json['success'], function(){ 
	                    location.reload();
	              });

	            }
	        });     
        }
    });
    $(document).delegate('#save_seller', 'click', function() {
    	$('#save_seller').prop('disabled', true);
    	var chk_deduc_pid = [];
    	var lpid=$('#lp_id').val();
    	var seller_id=$('#seller_id_data').val();
    	
    	
        $.each($("input[name='p_ids[]']:checked"), function(){
              chk_deduc_pid.push($(this).val());
        });
        console.log(chk_deduc_pid);
        console.log(seller_id);
        console.log(lpid);
        if(chk_deduc_pid.length==0){
          bootbox.alert("Please select Seller Promo To Asave");
          $('#save_seller').prop('disabled', false);
        }else{
        	$.ajax({
	            url: 'ajax_homecategory.php?action=save_seller&t=' + new Date().getTime(),
	            type: 'post',
	            data: 'chk_deduc_id=' + JSON.stringify(chk_deduc_pid)+'&seller_id='+seller_id+'&lpid='+lpid,
	            dataType: 'json',
	            success: function(json) {
	              bootbox.alert(json['success'], function(){ 
	                    location.reload();
	              });

	            }
	        });  
        }
    });
 
	$(document).delegate('#add_product_ids', 'click', function() {
		$('#add_product_lp').modal('show'); 
	});

	$('#seller_id_data').on('change', function() {
		$('#product_all tbody').empty();
		var seller_id=this.value;		   
        $.ajax({
            url: 'ajax_homecategory.php?action=getpromoperseller&t=' + new Date().getTime(),
            type: 'post',
            data: 'seller_id=' + seller_id,
            dataType: 'json',
            success: function(json) {
            	$('#product_all tbody').empty();
                var promo_per_seller = "";
		        if(json['seller_promo'].length==0){
		          	promo_per_seller+='<tr><td calspan="2">*** No Promo Found ***<td></tr>';
		        }else{		        	
		        	for (var i = 0; i < json['seller_promo'].length; i++) {
				               promo_per_seller =  promo_per_seller + '<tr><td><input type="checkbox" value="'+json['seller_promo'][i].id+'" name="p_ids[]"> '+json['seller_promo'][i].description+'</td><td>'+json['seller_promo'][i].d_type+'</td><td>'+json['seller_promo'][i].value+'</td><td>'+json['seller_promo'][i].date_from+'-'+json['seller_promo'][i].date_to+'</td></tr>';
				           	}

		        }
		        console.log(json['seller_promo']);

		        $('#product_all tbody').append(promo_per_seller);

            }
	    });     
        

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
				               product_per_category =  product_per_category + '<tr><td><input type="checkbox" value="'+json['data_product'][i].product_id+'--'+json['data_product'][i].type+'" name="p_ids[]">'+json['data_product'][i].name+'</td><td><img style="height: 100px; width: 100px" src="'+json['data_product'][i].image+'" /></td><td>'+json['data_product'][i].typedesc+'</td></tr>';
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