<?php 
	include 'template/header.php'; 
	include "model/Specification.php";
	$model = new Specification(); 

  $perm = $_SESSION['permission'];
if (!strpos($perm, "'14';") !== false){
    header("Location: landing.php");
   
}
?>

<div id="content">
	<div class="page-header">
      <h2 class="text-center">Attribute Maintenance</h2>      
    </div>
    <div class="container-fluid">
    	 <div class="panel panel-default">
    	 	<div class="panel-heading" style="padding:20px;">
    	 		<div class="row">
          			<div class="col-lg-6">
          				<p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i> Attribute List </p>
          			</div>
          			<div class="col-lg-6">
			            <div class="pull-right">
			                <a href="add_Attribute.php"class="btn btn-primary pull-right"  data-toggle="tooltip" title="Add Attribute"><i data-feather="plus-circle"></i></a>
			            </div>
			          </div>
          		</div>
    	 	</div>
    	 	<div class="panel-body">
    	 		<div class="table-responsive">
    	 			<table class="table table-bordered table-hover">
    	 			 	<thead>
		                    <tr>
			                    <th>Image</th>
			                    <th>Name</th>
			                    <th>Title</th>
			                    <th>Action</th>
		                    </tr>
		                </thead>
		                <tbody>
		                	 <?php foreach($model->get_specification() as $specification):?>
		                	 	<tr>
				                    <td class="text-left" >
				                    	<?php  $getimg ="../img/".$specification['image'];?>
										<?php if(file_exists($getimg)): ?>
						                  <img src="<?php echo $getimg; ?>"  class="img-responsive" />
						                <?php else: ?>
						                   <span><i class="fa fa-image"style=" font-size: 70px;margin-top: 30px;color: #333;"></i></span>
						                <?php endif; ?>   	
				                    </td>
				                    <td class="text-left" ><?php echo $specification['name'];?></td>
				                    <td class="text-left" ><?php echo $specification['title'];?></td> 
				                    <td> 
				                    	<button class="btn btn-info" data-id="<?php echo $specification['id'];?>"data-name="<?php echo $specification['name'];?>" id="btn_view" title="View Details"><i data-feather="eye" ></i></button>
							            <a class="btn btn-primary" href="edit_Attribute.php?att_id=<?php echo $specification['id'];?>" title="Edit Attribute" ><i data-feather="edit-2"></i></a>
							             <button type="button" onclick="delete_attribute(<?php echo  $specification['id'];?>);"data-toggle="tooltip" title="Remove" class="btn btn-danger"><i data-feather="trash-2"></i></button>
							           
							        </td>
				                </tr>
		                	 <?php endforeach;?>
		                </tbody>
    	 			</table>
    	 		</div>    	 		
    	 	</div>
    	 </div>
    </div>
</div>
<?php include 'template/footer.php'; ?>
<div  class="modal" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-error-dialog">
    <div class="modal-content" >
      <div class="col-sm-12">
        <div class="well">
          <a type="button" style="float: right;
              font-size: 25px;
              font-weight: 700;
              line-height: 1;
              color: #000;
              text-shadow: 0 1px 0 #fff;"
             data-dismiss="modal" ><i class="fa fa-times-circle " style="color: black;font-size: 25px;" ></i></a>
          <h2 id="hedername1"></h2>
           <div class="table-responsive">
            <table id="item_det_tbl" class="table table-striped table-bordered">
              <thead>
                <tr>
                    <th class="text-center">Item Name</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Sort Order</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>       
    </div><!--modal-content-->
  </div><!-- modal-dialog modal-error-dialog-->
</div><!-- addItemModal2-->
<script type="text/javascript">
	$(document).ready(function() {
		$('#btn_view').on('click', function () {
			$('#detailsModal').modal('show');
			var id=$(this).data('id'); 
	       	var name=$(this).data('name');
	       	$("#hedername1").html(name+"  Details");
	       	$.ajax({
	            url: 'ajx_ppp_rep.php?action=attribute_Details',
	            type: 'post',
	            data: 'id=' + id,
	            dataType: 'json',
	            success: function(json) {
	                for (var i = 0; i < json['items'].length; i++) {
		                $("#item_det_tbl tbody").append("<tr><td class='text-center'>"+json['items'][i].name+"</td><td class='text-center'>"+json['items'][i].description+"</td><td class='text-center' >"+json['items'][i].sort_order+"</td></tr>");
		            }
	                 
	            }
	        });

		});
	});
	function delete_attribute(deletid) {
        $.ajax({
            url: 'ajx_ppp_rep.php?action=delete_attribute',
            type: 'post',
            data: 'deletid=' + deletid,
            dataType: 'json',
            success: function(json) {
                if (json['success']) {
                    bootbox.alert(json['success'], function(){ 
                       window.location.reload();
                    });
                }   
                 
            }
        });
    }
</script>