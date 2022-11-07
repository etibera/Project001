<?php
	include 'template/header.php';	
	require_once "model/SellerPendingPayables.php";
	$model=new SPayables;
	$perm = $_SESSION['permission'];	
	if (!strpos($perm, "'25';") !== false){
	    header("Location: landing.php");
	}
	
	
	if(isset($_GET['date_to'])){
	 $date_to=$_GET['date_to'];
	}else{
		$date_to="notset";
	}
	if(isset($_GET['date_from'])){
		$date_from=$_GET['date_from'];
	}else{
		$date_from="notset";
	}
	$status=0;
	if(isset($_GET['stats'])){
	 	$status=$_GET['stats'];
	 	if($status=="All"){
			$getSPP_list=$model->getSPP_listAll($date_from,$date_to);
	 	}else{
			$getSPP_list=$model->getSPP_list($status,$date_from,$date_to);
	 	}
	}else{
		$getSPP_list=$model->getSPP_list(0,$date_from,$date_to);
	}
	
?>
<div id="content">
	<div class="container-fluid">
		<div class="panel panel-default">
	  		<div class="panel-heading" style="padding:20px;">
	  	 		<div class="row">
		          	<div class="col-lg-4">
		             	<p style="font-weight: 700;" class="panel-title"><a class="btn btn-success" href="SellerPendingPayablesPrint.php?stats=<?php echo $status;?>"  data-toggle="tooltip" title="Print" class="btn btn-primary"><i class="fa fa-print"></i></a> Pending Payable List</p>
		          	</div>
		          	<div class="col-lg-4">	
		          		<div class="form-group">
		          			
		          		</div>
		         	</div>
		         	<div class="col-lg-4">	
		         	</div>
		        </div>
	  		</div>
	  		<div class="panel-body">
	  			<div class="row">
            <div class="col-sm-12">
            <div class="well">
              <div class="row">
                 <div class="col-sm-4">
                  <div class="form-group">
                    <label class="control-label" for="input-order-id">Date From:</label>
                    <input type="date" id="date_from" class="form-control" value="<?php echo isset($_GET['date_from'])? $_GET['date_from'] :''; ?>" required/>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label class="control-label" for="input-order-id">Date To:</label>
                    <input type="date" id="date_to" class="form-control" value="<?php echo isset($_GET['date_to'])? $_GET['date_to'] :''; ?>" required/>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label class="control-label" for="input-order-id">Status:</label>
          			<select name="payable_status" id="payable_status" class="form-control" required>  
              			<option value="">--Select Status--</option>
              			<?php if($status=="All"){ ?> 
              				<option value="All" selected>All</option> 
              			<?php }else{ ?> 
              				<option value="All">All</option> 
              			<?php } ?> 
              			<?php if($status=="0"){ ?> 
              				<option value="0" selected >Pending</option> 
              			<?php }else{ ?> 
              				<option value="0">Pending</option> 
              			<?php } ?> 
              			<?php if($status=="1"){ ?> 
              				<option value="1" selected>Paid</option> 
              			<?php }else{ ?>
              				<option value="1">Paid</option> 
              			<?php } ?> 
              		</select>
                  </div>
                </div>
              </div>
            </div>
            </div> 
          </div><br>
	  			 <div class="row">
	  			 	<div class="table-responsive">
	  			 		 <table class=" table table-bordered table-hover">
	  			 		 	<thead>                 
				                <th class="text-center" colspan="10"style="vertical-align: middle;">
				                      <input type="text" id="customer-name"  class="form-control customer-name" placeholder="Quick search">
				                  </th>				                  
				             </thead>    
	  			 		 	<thead>
	  			 		 		<tr>
	  			 		 		  <th >Order Id</th>			                      
			                      <th >Store name</th>	
			                      <th>Bank Name </th>
			                      <th>Bank Account Name </th>
			                      <th>Bank Account No</th>	
			                      <th>Amount</th>
			                      <th>Reference Number</th>
			                      <th>Status</th>
			                      <th >Date Added</th>
			                      <th>Actoin</th>
			                    </tr>
	  			 		 	</thead>
	  			 		 	<tbody id="customer-table">
	  			 		 		<?php if($getSPP_list){ ?>
	  			 		 		  	<?php foreach ($getSPP_list as  $spp) { ?>
	  			 		 		  		<tr>
	  			 		 		  			<td><a href="view_order_new.php?order_id=<?php echo $spp['order_id'];?>" target="_blank"> <?php echo $spp['order_id'];?></a></td>	 
	  			 		 		  			<td><?php echo $spp['shop_name'];?></td>
	  			 		 		  			
	  			 		 		  			<td><?php echo $spp['bank_name'];?></td>
	  			 		 		  			<td><?php echo $spp['bank_account_name'];?></td>
	  			 		 		  			<td><?php echo $spp['bank_account_no'];?></td>	  
	  			 		 		  			<td><?php echo $spp['amount'];?></td>
	  			 		 		  			<td><?php echo $spp['reference_number'];?></td>
	  			 		 		  			<td><?php if( $spp['status']==0){ echo "Pending";}else{ echo "Paid"; }?></td>
	  			 		 		  			<td><?php echo $spp['date'];?></td>
	  			 		 		  			<td>
	  			 		 		  				<?php if( $spp['status']==0){?>
	  			 		 		  				<button id="paywallet" class="btn btn-primary" 
														data-id="<?php echo $spp['id']; ?>"
										                data-order_id="<?php echo $spp['order_id'];?>"
										                data-bank_account_no="<?php echo $spp['bank_account_no'];?>"
										                data-bank_account_name="<?php echo $spp['bank_account_name'];?>"
										                data-seller_id="<?php echo $spp['seller_id'];?>"
										                data-amount="<?php echo $spp['amount'];?>"
										                data-bank_name="<?php echo $spp['bank_name'];?>">
													<i class="fa fa-random"></i> Pay/Transfer
										        </button>
										    	<?php } ?>
	  			 		 		  			</td>

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

<div  class="modal fade" id="MOD-Pay_transfer"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-error-dialog">
       	<div class="modal-content">
          	<div class="modal-header">
            	<label ><h2>Pay / Transfer</h2></label>
             	<button type="button" class="close" id="closemod" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title" id="myModalLabel"></h4>
          	</div>
          	<div class="modal-body" >
          		<div class="form-group">
					<label id="divOrderId" ></label>
				</div>
          		<div class="form-group">
          			<label id="divBankName" ></label>
				</div>
				<div class="form-group">
          			<label id="divBankAccName" ></label>
				</div>
				<div class="form-group">
					<label id="divBankAccNo" ></label>
				</div>
				<div class="form-group">
					<label id="divAmount" ></label>
				</div>
				<div class="form-group">
					<label>Reference Number:</label>
					 <input type ="text" name="reference_no" class="form-control" id ="reference_no" placeholder="Reference Number">
				</div>
				
                 <input type ="hidden" name="payableId" class="form-control" id ="payableId">
                 <input type ="hidden" name="inputOrderId" class="form-control" id ="inputOrderId">
                 <input type ="hidden"  name ="inputBankName" class="form-control" id ="inputBankName">
                 <input type ="hidden"  name ="inputBankAccNo" class="form-control" id ="inputBankAccNo">
                 <input type ="hidden"  name ="inputBankAccName" class="form-control" id ="inputBankAccName">
                 <input type ="hidden"  name ="inputsellerId" class="form-control" id ="inputsellerId">
                 <input type ="hidden"  name ="inputamount" class="form-control" id ="inputamount">
	            <div class="row">
	              <div class="col-sm-12">
	                <div class="form-group">
	                  <input type="button" value="Pay / Transfer" class="btn btn-primary" id="SavePay_transfer"/> 
	                  <a type="button" class="btn btn-default" data-dismiss="modal" >Cancel</a>
	                </div>
	              </div>
	            </div>
          	</div>
       	</div>
    </div>            
 </div>
<?php include 'template/footer.php';?>  
<script type="text/javascript">
	function SearchFilter() {
      var input, filter, table, tr, td, i;
      input = document.getElementById("customer-name");
      filter = input.value.toUpperCase();
      table = document.getElementById("customer-table");
      tr = table.getElementsByTagName("tr");
      for (i = 0; i < tr.length; i++) {
        tr[i].style.display = "none";
        td = tr[i].getElementsByTagName("td");
        for (var j = 0; j < td.length; j++) {
          cell = tr[i].getElementsByTagName("td")[j];
          if (cell) {
            if (cell.innerHTML.toUpperCase().indexOf(filter) > -1) {
              tr[i].style.display = "";
              break;
            } 
          }
        }      
      }
    }
	$(document).delegate('#payable_status', 'change', function() { 
	  var payable_status=$(this).val();	  
	  if(payable_status==""){
	  	bootbox.alert("Please Select Status");
	  	}else{
	  		var date_from=$('#date_from').val();
		  	var date_to=$('#date_to').val();
		  	if(date_from==""){
		    	date_from="notset";
		  	}
		  	if(date_to==""){
		    	date_to="notset";
		  	}
	  	location.replace("SellerPendingPayables.php?stats="+payable_status+'&date_to='+date_to+'&date_from='+date_from);
	  }	  
	}); ;
	$(document).delegate('#customer-name', 'keyup', function() { 
	  SearchFilter();
	});
	$(document).delegate('#paywallet', 'click', function() {       
       	$('#MOD-Pay_transfer').modal('show');       
      	var seller_id=$(this).data('seller_id');
     	var order_id=$(this).data('order_id');
     	var bank_account_no=$(this).data('bank_account_no');
     	var bank_account_name=$(this).data('bank_account_name');
     	var bank_name=$(this).data('bank_name');
     	var payableId=$(this).data('id');
     	var amount=$(this).data('amount');

      	$("#divBankName").html("Bank Name: "+bank_name);
      	$("#divBankAccNo").html("Bank Account Number: "+bank_account_no);
      	$("#divBankAccName").html("Bank Account Name: "+bank_account_name);
      	$("#divOrderId").html("Order Id: "+order_id);
      	$("#divAmount").html("Amount: "+amount);
      	$("#payableId").val(payableId);
      	$("#inputOrderId").val(order_id);
      	$("#inputBankName").val(bank_name);
      	$("#inputBankAccNo").val(bank_account_no);
      	$("#inputBankAccName").val(bank_account_name);
      	$("#inputsellerId").val(seller_id);
      	$("#inputamount").val(amount);
    })
    $(document).delegate('#SavePay_transfer', 'click', function() {    
    	$("#SavePay_transfer").attr("disabled", true);    
      	var payableId = $("#payableId").val();
      	var order_id = $("#inputOrderId").val();
      	var bank_name = $("#inputBankName").val();
      	var bank_account_no = $("#inputBankAccNo").val();
      	var bank_account_name = $("#inputBankName").val();
      	var seller_id = $("#inputsellerId").val();
      	var amount = $("#inputamount").val();
      	var reference_no = $("#reference_no").val();
      	if(reference_no==""){
      		bootbox.alert("Reference number is required");
      		return false;
      	}
  		$.ajax({
	        url: 'ajax_spp.php?action=PayTransfer',
	        type: 'POST',
	        data: 'seller_id=' + seller_id + '&order_id='+order_id+'&payableId='+payableId+'&bank_name='+bank_name+'&bank_account_no='+bank_account_no+'&amount='+amount+'&reference_no='+reference_no,
	        dataType: 'json',
	        success: function(json) {                     
	          if (json['success']) {
	          	bootbox.alert(json['success'], function(){ 
	                location.reload();
	            });           
	          }
	        },
	        error: function(xhr, ajaxOptions, thrownError) {
	            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	        }
	    });
      	
    })
</script>