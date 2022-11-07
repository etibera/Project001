<?php
	include 'template/header.php';	
	require_once "model/manageWallet.php";
	$model=new ManageWallet;
	$perm = $_SESSION['permission'];	
	if (!strpos($perm, "'252526';") !== false){
	    header("Location: landing.php");
	}
	$customer_list=$model->GetCustomer_list();
?>
<div id="content">
	<div class="container-fluid">
		<div class="panel panel-default">
	  		<div class="panel-heading" style="padding:20px;">
	  	 		<div class="row">
		          	<div class="col-lg-4">
		             	<p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i> Add Customer Wallet</p>
		          	</div>
		          	<div class="col-lg-8">	</div>
		        </div>
	  		</div>
	  		<div class="panel-body">	  				        
	  			 <div class="row">
	  			 	<div class="table-responsive">
	  			 		 <table class="table table-bordered table-hover" id="tblWalletAdd">
	  			 		 	<thead>
	  			 		 		<tr>
			                     	<th class="text-center" colspan="6"style="vertical-align: middle;">
				                     	<input class="form-control pull-right" type="text" id="InputsearchWallet" onkeyup="searchOrders()" placeholder="Search for Customer Name"> 
				                	</th>                     
			                    </tr>
	  			 		 		<tr>
			                      <th >Customer Name</th>
			                      <th >Email Address</th>
			                      <th >Action</th>
			                    </tr>
	  			 		 	</thead>
	  			 		 	<tbody id="tblTbody">
	  			 		 		<?php if($customer_list){ ?>
	  			 		 		  	<?php foreach ($customer_list as  $cl) { ?>
	  			 		 		  		<tr>			 		 		  			
	  			 		 		  			<td><?php echo $cl['fullname'];?></td>	  			 		 		  		
	  			 		 		  			<td><?php echo $cl['email'];?></td>	  			 		 		  		
	  			 		 		  			<td> <a class="btn btn-primary pull-right addCusTWallet"   title="Add Customer Wallet"  style="margin-left:5px; " data-customer_id="<?php echo $cl['customer_id'];?>" data-fullname="<?php echo $cl['fullname'];?>" ><i data-feather="plus-circle"> </i> Add Customer Wallet</a> </td> 
	  			 		 		  		</tr>
	  			 		 		  	<?php }
	  			 		 		}else{ ?>
	  			 		 			<tr >
	  			 		 				<td colspan="7" class="text-center"> No data Found </td>
	  			 		 			</tr>
	  			 		 		<?php } ?>	  			 		 		  
	  			 		 	</tbody>
	  			 		 </table>
	  			 	</div>
	  			 </div>
	  		</div>
		</div>
	</div>
</div>
<?php include 'template/footer.php';?>  


<div  class="modal fade" id="MOD-addWallet"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-error-dialog">
       	<div class="modal-content">
          	<div class="modal-header">
            	<label ><h2>Add Customer Wallet</h2></label>
             	<button type="button" class="close" id="closemod" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title" id="myModalLabel"></h4>
          	</div>
          	<div class="modal-body" >  
          		<div class="form-group">
					<label>Customer Name:</label>
					<label id="lblfname"></label>	
				</div>	        		
				<div class="form-group">
					<label>Wallet Type:</label>
						<select name="typeAdd" id="typeAdd" class="form-control" required>
							<option value="">--Select Wallet Type--</option>	
							<option value="shipWallet">Shipping Wallet</option>	
		                    <option value="cashWallet">Cash Wallet</option>
		                    <option value="disWallett">Discount Wallet</option>	 
						</select>					 
				</div>			
                <div class="form-group">
					<label>Particulars:</label>
						<input type ="text" name="particulars_ADD" class="form-control" id ="particulars_ADD" placeholder="Input Particulars">			 
								 
				</div>	
				<div class="form-group">
					<label>Amount:</label>
						<input type ="Number" name="amount_add" class="form-control" id ="amount_add" placeholder="Amount">			 
						<input type ="hidden" name="customerIdAdd" class="form-control" id ="customerIdAdd" >			 
				</div>	
	            <div class="row">
	              <div class="col-sm-12">
	                <div class="form-group">
	                  <input type="button" value="SAve" class="btn btn-primary" id="SaveWallet"/> 
	                  <a type="button" class="btn btn-default" data-dismiss="modal" >Cancel</a>
	                </div>
	              </div>
	            </div>
          	</div>
       	</div>
    </div>            
 </div>
<script type="text/javascript">
	function searchOrders() {	   
	    var input, filter, table, tr, td,td2,td3, i, txtValue,txtValue2,txtValue3;
	    input = document.getElementById("InputsearchWallet");
	    filter = input.value.toUpperCase().trim();
	    table = document.getElementById("tblWalletAdd");
	    var tBody = table.tBodies.namedItem("tblTbody");
	    var tableRow = tBody.getElementsByTagName('tr');
	    for (var t = 0; t < tableRow.length; t++){
	        td = tableRow[t].getElementsByTagName("td")[0];
	       
	       // console.log(td);

	        if (td) {
	          txtValue = td.textContent || td.innerText;
	          if (txtValue.toUpperCase().indexOf(filter) > -1) {
	            tableRow[t].style.display = "";
	          } else{
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
	$(document).delegate('.addCusTWallet', 'click', function() { 
       	var customer_id = $(this).data('customer_id');
       	var fullname = $(this).data('fullname');
       	$('#customerIdAdd').val(customer_id);
       	$('#lblfname').text(fullname);
       	$('#MOD-addWallet').modal('show'); 
    })
    $(document).delegate('#SaveWallet', 'click', function() {       
       	var customer_id = $('#customerIdAdd').val();
       	var type = $('#typeAdd').val();
       	var amount = $('#amount_add').val();
       	var particulars = $('#particulars_ADD').val();
       	if(type==""){
      		bootbox.alert("Wallet Type is required");
      		return false;
      	}
      	if(particulars==""){
      		bootbox.alert("Particulars is required");
      		return false;
      	}
      	if(amount==""){
      		bootbox.alert("Amount is required");
      		return false;
      	}
      	$.ajax({
	        url: 'ajax_spp.php?action=addwallet',
	        type: 'POST',
	        data: 'customer_id=' + customer_id + '&type='+type+'&amount='+amount+'&particulars='+particulars,
	        dataType: 'json',
	        success: function(json) {                     
	          if (json['success']) {
	          	bootbox.alert(json['success'], function(){ 
	               location.replace("manageWallet.php");
	            });           
	          }
	        },
	        error: function(xhr, ajaxOptions, thrownError) {
	            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	        }
	    });
       	console.log(customer_id);
       	console.log(type);
       	console.log(amount);
    })
</script>

	