<?php
	require_once("includes/init.php");
	include 'template/header.php';
	require_once "../mgmtseller/model/sellerAddProduct.php";	
	$mod_SellerAddProduct=new SellerAddProduct;
	$dataPendingPRD=$mod_SellerAddProduct->GetAdminDataPendingPRD();
  $perm = $_SESSION['permission'];
  if (!strpos($perm, "'2526';") !== false){
      header("Location: landing.php");
  }
?>
<div id="content">
  	<div class="container-fluid">
    	<div class="panel panel-default">
    		<div class="panel-heading" style="padding:20px;">
		        <div class="row">
			          <div class="col-lg-12">
			             <p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i> Pending Product List</p>
			          </div>
		      	</div>
		    </div>
		    <div class="panel-body">
          <div class="well">
              <div class="row">
                <input class="form-control pull-right" type="text" id="InputsearchOrders" onkeyup="searchOrders()" placeholder="Search for Store nAme , Product id or Product name"> 
              </div>
          </div>
			    <div class="table-responsive">
        			<table class="table table-bordered table-hover" id="pendingPrd">
        				<thead>        					
                  <th class="text-center">Image</th>
                  <th class="text-center">Store Name</th>
	                <th class="text-center">Product Id </th>
                	<th class="text-center">Product Name </th>
                	<th class="text-center">Model</th>
                	<th class="text-center">Price</th>
                	<th class="text-center">Action</th>
        				</thead>
        				<tbody id="tblTbody">
        					<?php foreach ($dataPendingPRD as $dpr) { ?>
        						 <?php  $getimg =$dpr['thumb']; ?>
        						<tr>
                      
        							<td> 
        								<?php if($getimg!=""): ?>
			                              <img src="<?php echo $getimg; ?>" alt="<?php echo $dpr['name']; ?>" class="img-responsive" />
			                            <?php else: ?>
			                              <i class="fa fa-shopping-bag" style="font-size: 50px;color: #333;"></i>
			                            <?php endif; ?>  </td>
                      <td><?php echo  $dpr['shop_name']?></td>            
        							<td><?php echo  $dpr['product_id']?></td>
        							<td><?php echo  $dpr['name']?></td>
        							<td><?php echo  $dpr['model']?></td>
        							<td><?php echo  $dpr['price']?></td>
        							<td><a href="editproduct.php?prod_id=<?php echo $dpr['product_id'];?>" class="btn btn-primary btn-edit" data-product_id="<?php echo $dpr['product_id'];?>" title="Edit"><i data-feather="edit"></i></a>
                        <a class="btn btn-success approve_prd" id="approve_prd" data-product_id="<?php echo $dpr['product_id'];?>" title="Approve"><i data-feather="check-square"></i></a>
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
<?php include 'template/footer.php'; ?>
<script type="text/javascript">
   function searchOrders() {    
      var input, filter, table, tr, td,td2,td3, i, txtValue,txtValue2,txtValue3;
      input = document.getElementById("InputsearchOrders");
      filter = input.value.toUpperCase().trim();
      table = document.getElementById("pendingPrd");
      var tBody = table.tBodies.namedItem("tblTbody");
      var tableRow = tBody.getElementsByTagName('tr');
      for (var t = 0; t < tableRow.length; t++){
          td = tableRow[t].getElementsByTagName("td")[1];
          td2 = tableRow[t].getElementsByTagName("td")[2];
          td3 = tableRow[t].getElementsByTagName("td")[3];
         // console.log(td);

          if (td) {
            txtValue = td.textContent || td.innerText;
            txtValue2 = td2.textContent || td2.innerText;
            txtValue3 = td3.textContent || td3.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
              tableRow[t].style.display = "";
            } else if (txtValue2.toUpperCase().indexOf(filter) > -1){
                tableRow[t].style.display = "";
            }else if(txtValue3.toUpperCase().indexOf(filter) > -1){
               tableRow[t].style.display = "";
            }else{
               tableRow[t].style.display = "none";
            }
          }       
          
      }
      $('.order-table').paging({   
      limit: 20,
      rowDisplayStyle: 'block',
      activePage: 0,
      rows: []
    });
  }
  $(document).ready(function() {
   $(".approve_prd").click(function() {
      var product_id=$(this).data('product_id');
      bootbox.confirm({
        message: "Are you sure you want to Approve this product?",
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
              url: 'ajax_pendingPRD.php?action=approvePRD',
              type: 'post',
              data: 'product_id=' + product_id,
              dataType: 'json',
              beforeSend: function() {
                    bootbox.dialog({
                          title: "Approving Product",
                          message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
                  });
               },
              success: function(json) {
                bootbox.alert(json['success'], function(){ 
                  location.reload();
                });
              }
            });
          } 
        }
      }); 
    });
  });
</script>