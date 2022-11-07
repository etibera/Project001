<?php
include 'template/header.php'; 
include "model/homecategory.php";


$model=new homecategory();			
     
$categoryhome=$model->gethomecategorylistbydef();

$perm = $_SESSION['permission'];
if (!strpos($perm, "'3';") !== false){
    header("Location: landing.php");
   
} 


?>
<div class="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<center><h2>Home Page Category</h2></center><br>	
				<button class="btn btn-primary pull-right" id="add-category-homepage"  data-toggle="tooltip" title="Add"><i data-feather="plus"></i></button>			
			</div>
		</div>	
		<div class="row">
			<div class="col-sm-12">
				<div class="table-responsive">
					 <table class="table table-bordered table-hover home-cat-table">
					 	 <thead>
			              <tr>
			                <th>Category Name</th>
			                 <th>Show Limit</th>
			                 <th>Sort Order</th>
			                <th style="text-align: center;">Action</th>
			              </tr>
			            </thead>
			            <tbody>
			            	 <?php foreach ($categoryhome as $result) { ?>
			            	 	<tr>
				                    <td class="text-left"><?php echo $result['name']; ?></td>
				                  	<td class="text-left"><?php echo $result['show_limit']; ?></td>
				                  	<td class="text-left"><?php echo $result['sort_order_name']; ?></td>
				                  	<td style="text-align: center;">
					                    <button id="btn-delete-category" data-id="<?php echo $result['ochcid']; ?>" type="button" class="btn btn-danger" data-toggle="tooltip" title="Delete">
					                      <i data-feather="trash-2"></i>
					                    </button>
					                     <button id="btn-edit-category" data-sort_order_name="<?php echo $result['sort_order_name']; ?>" data-sort_order="<?php echo $result['sort_order']; ?>" data-show_limit="<?php echo $result['show_limit']; ?>" data-catname="<?php echo $result['name']; ?>" data-id="<?php echo $result['ochcid']; ?>" data-catid="<?php echo $result['category_id']; ?>" type="button" class="btn btn-primary" data-toggle="tooltip" title="Update">
					                     <i data-feather="edit"></i>
					                    </button>
					                    <?php if($result['status']!="1"){?>
					                      <button id="btn-select-product" data-sort_order_name="<?php echo $result['sort_order_name']; ?>" data-sort_order="<?php echo $result['sort_order']; ?>" data-show_limit="<?php echo $result['show_limit']; ?>" data-catname="<?php echo $result['name']; ?>" data-id="<?php echo $result['ochcid']; ?>" data-catid="<?php echo $result['category_id']; ?>" type="button" class="btn btn-primary" data-toggle="tooltip" title="Select Producs">
					                     <i data-feather="chevron-down"></i>
					                    </button>
					                    <?php }else{ ?>
					                      <button id="btn-cancel_select-product" data-sort_order_name="<?php echo $result['sort_order_name']; ?>" data-sort_order="<?php echo $result['sort_order']; ?>" data-show_limit="<?php echo $result['show_limit']; ?>" data-catname="<?php echo $result['name']; ?>" data-id="<?php echo $result['ochcid']; ?>" data-catid="<?php echo $result['category_id']; ?>" type="button" class="btn btn-warning" data-toggle="tooltip" title="Cancel Selected Producs">
					                     <i data-feather="slash"></i>
					                    </button>
					                    <?php } ?>
					                    
					                  </td>
					                  </td>
				                </tr>
				            <?php } ?>
			            </tbody>
					 </table>
				</div>				
			</div>
		</div>	
	</div>
</div>
<?php include 'template/footer.php';?>

<div  class="modal" id="open_category_module_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-error-dialog">
        <!-- <form role="form"> -->
        <div class="modal-header " style="border: none;">
        </div>
        <div class="modal-content" style="margin: auto;">
          <div class="panel-heading">
              <button class="btn" id="homecategoryclosed"  data-dismiss="modal" style="float: right;
            font-size: 18px;
            font-weight: 700;
            line-height: 1;
            color: #000;
            text-shadow: 0 1px 0 #fff;
          ">x</button>
          <h3>Please Select Home Page Category</h3>
            </div><!-- /.panel-heading -->
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="particular-table">
                  <thead>
                      <tr>
                        <th>Category List</th>
                        <th>Show Limit</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="options-category">
                          <select class="form-control" id ="category_add">
                            <option value="" >Select Category</option>
                          </select>
                        </td>
                        <td><input class="form-control"  type="number" placeholder="Show Limit" id="show_limit"></td>
                      </tr>
                    </tbody>
                    <thead>
                      <tr>
                        <th colspan="2">Sort Order</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="options-category-sort" colspan="2">
                          <select class="form-control" id ="category_add_sort">
                            <option value="" >Select Sort Order</option>
                            <option value="p.price ASC" >Price (Low > High)</option>
                            <option value="p.price DESC" >Price (High > Low)</option>
                            <option value="pd.name ASC" >Name (A-Z)</option>
                            <option value="pd.name DESC" >Name (Z-A)</option>
                            <option value="p.model ASC" >Model (A-Z)</option>
                            <option value="p.model DESC" >Model (Z-A)</option>
                            <option value="p.date_added DESC" >Show latest Uploaded Items</option>
                            <option value="p.date_added ASC" >Show Oldest Uploaded Items</option>
                          </select>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </table>
              </div>
              <div class="form-group navbar-right" style="margin-right: 10px;">
                <button type="button" class="btn btn-primary btn-category-SAVE" ><i class="fa fa-save"></i> Save</button>
              </div>
            </div><!-- /.panel-body -->
        </div><!--modal-content-->
      </div><!-- modal-dialog modal-error-dialog-->
  </div><!-- open_category_module_modal-->

  <div  class="modal" id="open_category_module_update_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-error-dialog">
        <!-- <form role="form"> -->
        <div class="modal-header " style="border: none;">
        </div>
        <div class="modal-content" style="margin: auto;">
            <div class="panel-heading">

            <button id="homecategoryclosed"  data-dismiss="modal" style="float: right;
            font-size: 18px;
            font-weight: 700;
            line-height: 1;
            color: #000;
            text-shadow: 0 1px 0 #fff;
          ">x</i></button>
          <h3>Update Home Page Category</h3>
            </div><!-- /.panel-heading -->
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="particular-table-update">
                  <thead>
                      <tr>
                        <th>Category List</th>
                        <th>Show Limit</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="options-category-update">
                          <input type="hidden" id="homecatid">
                          <select class="form-control" id ="category-update">
                          </select>
                        </td>
                         <td><input class="form-control"  type="number" placeholder="Show Limit" id="show_limit_update"></td>
                      </tr>
                    </tbody>
                  </table>
                  <thead>
                      <tr>
                        <th colspan="2">Sort Order</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="options-category-sort-update" colspan="2">
                          <select class="form-control" id ="category_add_sort_update">
                          </select>
                        </td>
                      </tr>
                    </tbody>
                </table>
              </div>
              <div class="form-group navbar-right" style="margin-right: 10px;">
                <button type="button" class="btn btn-primary btn-category-update" ><i class="fa fa-save"></i> Save</button>
              </div>
            </div><!-- /.panel-body -->
        </div><!--modal-content-->
      </div><!-- modal-dialog modal-error-dialog-->
  </div><!-- open_category_module_update_modal-->


  <div  class="modal" id="open_category_module_selectproduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
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
          <h3>Select Product by Category</h3>
            </div><!-- /.panel-heading -->
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover " id="particular-table_select">
                  <thead>
                      <tr>
                        <th colspan="2">Category List</th>
                      </tr>                   
                      <tr>
                        <td colspan="2" class="options-category-update-select">
                          <input type="hidden" id="homecatid_select">
                          <select class="form-control" id ="category-update-select">
                          </select>
                        </td>
                         
                      </tr>
                 
                      <tr>
                        <th colspan="2">Select Products</th>
                      </tr>
                  </thead>
                  <tbody>
                     
                 </table>
              </div>
              <div class="form-group navbar-right" style="margin-right: 10px;">
                <button type="button" class="btn btn-primary btn-category-select-products" ><i class="fa fa-save"></i> Save</button>
              </div>
            </div><!-- /.panel-body -->
        </div><!--modal-content-->
      </div><!-- modal-dialog modal-error-dialog-->
  </div><!-- open_category_module_update_modal-->

<script>
 	$(document).ready(function() {


		$('.home-cat-table').paging({	 
			limit: 10,
			rowDisplayStyle: 'block',
			activePage: 0,
			rows: []
		});
		
	});


	$(document).delegate('#add-category-homepage', 'click', function() {
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
     	$('#open_category_module_modal').modal('show');
  	});
  	$(document).delegate('.btn-category-SAVE', 'click', function() {
	    var category_id=$('#category_add').val();
	    var add_sort=$('#category_add_sort').val();
	    var elt = document.getElementById('category_add_sort');
	    var add_sort_name=  elt.options[elt.selectedIndex].text;
	    var show_limit=$('#show_limit').val();
	   // alert(category_id+" add_sort:"+add_sort+" add_sort_name:"+add_sort_name+" show_limit:"+show_limit+" elt:"+elt);
	    if(category_id==""){
	       alert("Please Select Home Category");
	       return false;
	    }
	    if(show_limit==""){
	       alert("Show limit Is required");
	       return false;
	    }
	    if(add_sort==""){
	       alert("Please Select Sort Order");
	       return false;
	    }
	    
	    $.ajax({
	        url:'ajax_homecategory.php?action=savecategory',
	        type: 'post',
	        data: 'options_category=' + category_id + '&show_limit=' + show_limit + '&add_sort=' + add_sort + '&add_sort_name=' + add_sort_name,
	        dataType: 'json',
	        success: function(json) {
	          if (json['success']) {
	              location.replace(json['success']);
	          }
	        },
	        error: function(xhr, ajaxOptions, thrownError) {
	                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	        }
	    }); 
  	});
  	$(document).delegate('#btn-delete-category', 'click', function() {
	    var options_category_id = $(this).data('id');
	    
	    $.ajax({
	        url:'ajax_homecategory.php?action=deletecategory',
	        type: 'post',
	        data: 'catid=' + options_category_id ,
	        dataType: 'json',
	        success: function(json) {
	          if (json['success']) {
	              location.replace(json['success']);
	          }
	        },
	        error: function(xhr, ajaxOptions, thrownError) {
	                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	        }
	    }); 
	                        
  	});
  	$(document).delegate('#btn-edit-category', 'click', function() {
	    var options_category_id = $(this).data('id');
	    var category_id = $(this).data('catid');
	    var category_name = $(this).data('catname');
	    var show_limit = $(this).data('show_limit');
	    var sort_order = $(this).data('sort_order');
	    var sort_order_name = $(this).data('sort_order_name');
	    $('#homecatid').val(options_category_id);
	    $('#category-update').empty();
	    $('#category_add_sort_update').empty();

	    $('#category_add_sort_update').prepend('<option value="'+sort_order+'">'+sort_order_name+'</option> <option value="p.price ASC" >Price (Low > High)</option><option value="p.price DESC" >Price (High > Low)</option><option value="pd.name ASC" >Name (A-Z)</option><option value="pd.name DESC" >Name (Z-A)</option><option value="p.model ASC" >Model (A-Z)</option><option value="p.model DESC" >Model (Z-A)</option><option value="p.date_added DESC" >Show latest Uploaded Items</option><option value="p.date_added ASC" >Show Oldest Uploaded Items</option>');

	    $('#category-update').prepend('<option value="'+category_id+'">'+category_name+'</option>');
	      $.ajax({
	      url:'ajax_homecategory.php?action=getcategory',
	      type: 'post',
	      dataType: 'json',
	      success: function(json) {
	        var options_category_update = "";
	          for (var i = 0; i < json['getcategories'].length; i++) {
	            if(category_id!=json['getcategories'][i].category_id){
	              options_category_update =  options_category_update + '<option value="'+json['getcategories'][i].category_id+'">'+json['getcategories'][i].name+'</option>';
	            }
	          }
	        $('#category-update').append(options_category_update);
	      },
	      error: function(xhr, ajaxOptions, thrownError) {
	          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	      }
	    });	      
	    $('#show_limit_update').val(show_limit);
	    $('#open_category_module_update_modal').modal('show'); 
    });
    $(document).delegate('.btn-category-update', 'click', function() {
	    var category_id = $('#category-update').val();
	    var homecatid=$('#homecatid').val();
	    var show_limit_update=$('#show_limit_update').val();
	    var edit_sort=$('#category_add_sort_update').val();
	    var eltedit = document.getElementById('category_add_sort_update');
	    var edit_sort_name=  eltedit.options[eltedit.selectedIndex].text;

	    if(category_id==""){
	       alert("Please Select Home Category");
	       return false;
	     }
	      if(show_limit_update==""){
	       alert("Show limit Is required");
	       return false;
	     }
	    $.ajax({
	        url:'ajax_homecategory.php?action=updatecategory',
	        type: 'post',
	        data: 'homecatid=' + homecatid + '&category_id=' + category_id + '&show_limit_update=' + show_limit_update + '&edit_sort=' + edit_sort + '&edit_sort_name=' + edit_sort_name,
	        dataType: 'json',
	        success: function(json) {
	          	if (json['success']) {
	              location.replace(json['success']);
	          	}
	        },
	        error: function(xhr, ajaxOptions, thrownError) {
	            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	        }
	    });
  	});
  	$(document).delegate('#btn-cancel_select-product', 'click', function() {
	    var options_category_id = $(this).data('id');
	    var category_id = $(this).data('catid');
	    var category_name = $(this).data('catname');
	    var show_limit = $(this).data('show_limit');
	    var sort_order = $(this).data('sort_order');
	    var sort_order_name = $(this).data('sort_order_name');
	    //alert(options_category_id);
	    $.ajax({
	        url:'ajax_homecategory.php?action=delete_product_id',
	        type: 'post',
	        data: 'catecory_id=' + category_id+'&options_category_id='+options_category_id,
	        dataType: 'json',
	        success: function(json) {
	          if (json['success']) {
	             location.replace(json['success']);
	          }
	        },
	        error: function(xhr, ajaxOptions, thrownError) {
	            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	        }
	      }); 
  	});
  	 $(document).delegate('#btn-select-product', 'click', function() {
    var options_category_id = $(this).data('id');
    var category_id = $(this).data('catid');
    var category_name = $(this).data('catname');
    var show_limit = $(this).data('show_limit');
    var sort_order = $(this).data('sort_order');
    var sort_order_name = $(this).data('sort_order_name');
    $('#homecatid_select').val(options_category_id);
    $('#category-update-select').empty();
 
    $('#category-update-select').prepend('<option value="'+category_id+'">'+category_name+'</option>');
	    $.ajax({
	        url:'ajax_homecategory.php?action=getcategory',
	        type: 'post',
	        dataType: 'json',
	        success: function(json) {
	          var options_category_update = "";
	            for (var i = 0; i < json['getcategories'].length; i++) {
	              if(category_id!=json['getcategories'][i].category_id){
	                options_category_update =  options_category_update + '<option value="'+json['getcategories'][i].category_id+'">'+json['getcategories'][i].name+'</option>';
	              }

	            }
	          $('#category-update-select').append(options_category_update);
	        },
	        error: function(xhr, ajaxOptions, thrownError) {
	            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	        }
    	});
	    $('#particular-table_select tbody').empty();
	    $.ajax({
	        url:'ajax_homecategory.php?action=getcategory_product',
	        type: 'post',
	        data: 'catecory_id=' + category_id,
	        dataType: 'json',
	        success: function(json) {
	          var product_per_category = "";
	            for (var i = 0; i < json['get_product_under_category_id'].length; i++) {

	               product_per_category =  product_per_category + '<tr><td><input type="checkbox" value="'+json['get_product_under_category_id'][i].product_id+'" name="sport">'+json['get_product_under_category_id'][i].name+'</td></tr>';
	              
	            }
	          $('#particular-table_select tbody').append(product_per_category);
	        },
	        error: function(xhr, ajaxOptions, thrownError) {
	            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	        }
	    });
	    $('#open_category_module_selectproduct').modal('show'); 
  	});
  	$(document).delegate('#category-update-select', 'change', function() {
	  	var category_id = $("#category-update-select").val();
	    $('#particular-table_select tbody').empty();
	    $.ajax({
	        url:'ajax_homecategory.php?action=getcategory_product',
	        type: 'post',
	        data: 'catecory_id=' + category_id,
	        dataType: 'json',
	        success: function(json) {
	          var product_per_category = "";
	            for (var i = 0; i < json['get_product_under_category_id'].length; i++) {

	               product_per_category =  product_per_category + '<tr><td><input type="checkbox" value="'+json['get_product_under_category_id'][i].product_id+'" name="sport">'+json['get_product_under_category_id'][i].name+'</td></tr>';
	              
	            }
	          $('#particular-table_select tbody').append(product_per_category);
	        },
	        error: function(xhr, ajaxOptions, thrownError) {
	            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	        }
	    });
	});
	$(document).delegate('.btn-category-select-products', 'click', function() {
	    var catecory_id=$('#category-update-select').val();
	    var hid= $('#homecatid_select').val();
	    var favorite = [];
	    $.each($("input[name='sport']:checked"), function(){
	      favorite.push($(this).val());
	    });

	    if(favorite.length==0){
	      alert("Please select Product First");
	      return false;
	    }else{
	      
	      for (var i = 0; i < favorite.length; i++) {
	          var product_id=favorite[i];
	         $.ajax({
	            url:'ajax_homecategory.php?action=add_product_id',
	            type: 'post',
	            data: 'catecory_id=' + catecory_id + '&product_id=' + product_id+'&h_id='+hid,
	            dataType: 'json',
	            success: function(json) {
	              if (json['success']) {
	              }
	            },
	            error: function(xhr, ajaxOptions, thrownError) {
	                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	            }
	          }); 
	      }
	      location.replace("homecategory.php");       
	    }      
  	});

   function getpermission() {

    var id ='<?php echo $_SESSION['user_id'] ?>';
  
              $.ajax({
              url: 'ajax_get_permission.php',
              type: 'GET',
              data: 'user_id='+ id+'&user_page=2'  ,
              dataType: 'json',
              success: function(json) {
                  
                    if(json.length<1){
                    
                      location.replace("landing.php");
                    }
              
                   
                 
                               },
                  error: function(xhr, ajaxOptions, thrownError) {
                      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                  }
              });


          
        }
</script>