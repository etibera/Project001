<?php
	include 'template/header.php';	
	require_once "model/manageWallet.php";
	$model=new ManageWallet;
	$perm = $_SESSION['permission'];	
	if (!strpos($perm, "'252526';") !== false){
	    header("Location: landing.php");
	}
	$headerval="Mange Shipping Wallet";
	$wallet_list=$model->getWalletList('shipWallet');
	$totalWallet=$model->getTotalWallet('shipWallet');
	if(isset($_POST['btn_search'])){
		$type=$_POST['type'];
		if($type=="shipWallet"){
			$headerval="Mange Shipping Wallet";
		}else if($type=="cashWallet"){
			$headerval="Mange Cash Wallet";
		}else{
			$headerval="Mange Discount Wallet";
		}
    	$wallet_list=$model->getWalletList($type);
    	$totalWallet=$model->getTotalWallet($type);
  	}
/*echo "<pre>";
print_r($wallet_list);*/
?>
<div id="content">
	<div class="container-fluid">
		<div class="panel panel-default">
	  		<div class="panel-heading" style="padding:20px;">
	  	 		<div class="row">
		          	<div class="col-lg-4">
		             	<p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i> <?php echo $headerval;?> </p>
		          	</div>
		          	<div class="col-lg-8">		          		
		          		 <a href="addwallet.php" class="btn btn-primary pull-right"  title="Add Customer Wallet" style="margin-left:5px; "><i data-feather="plus-circle"> </i> Add Customer Wallet</a> 
		          	</div>
		        </div>
	  		</div>
	  		<div class="panel-body">
	  			<div class="row">
		          	<div class="col-xs-12 well">
		            	<form method="post" class="form-horizontal" action="manageWallet.php">
		             		<div class="form-group">               
		               			<div class="col-sm-3">
				                  	<select name="type" id="d_status" class="form-control" required>
				                  		<?php if(isset($_POST['btn_search'])){ ?>
				                  			<?php if($_POST['type']=="shipWallet"){ ?>
				                  				<option value="shipWallet" selected>Shipping Wallet</option>
				                  				<option value="cashWallet">Cash Wallet</option>
						                    	<option value="disWallett">Discount Wallet</option>	
				                  			<?php }else if($_POST['type']=="cashWallet"){ ?>
				                  				<option value="cashWallet" selected>Cash Wallet</option>
				                  				<option value="shipWallet">Shipping Wallet</option>	
						                    	<option value="disWallett">Discount Wallet</option>	  
				                  			<?php }else{ ?>	
				                  				<option value="disWallett" selected>Discount Wallet</option>
				                  				<option value="shipWallet">Shipping Wallet</option>	
						                    <option value="cashWallet">Cash Wallet</option>  
				                  			<?php }?>
				                  		<?php }else{ ?>
				                  			<option value="shipWallet">Shipping Wallet</option>	
						                    <option value="cashWallet">Cash Wallet</option>
						                    <option value="disWallett">Discount Wallet</option>	  
				                  		<?php }?>
					                                      
				                  	</select>
		                		</div>
				                <div class="col-sm-3">
				                  	<input type="submit" name="btn_search" class="btn btn-success" value="Search">
				                </div>
				                <div class="col-sm-3">
				                  	
				                </div>
				                <div class="col-sm-3" style="vertical-align: middle;">
				                  	<label > Total Amount: <?php echo number_format($totalWallet['total'],2);?></label>
				                </div>
		              		</div>
		            	</form>
		        	</div><!--end col-xs-12 well  -->          
		        </div><!--end row  -->		        
	  			 <div class="row">
	  			 	<div class="table-responsive">
	  			 		 <table class="table table-bordered table-hover" id="tblWallet">
	  			 		 	<thead>
	  			 		 		<tr>
			                     	<th class="text-center" colspan="6"style="vertical-align: middle;">
				                     	<input class="form-control pull-right" type="text" id="InputsearchWallet" onkeyup="searchOrders()" placeholder="Search for Customer Name or Particulars"> 
				                	</th>                     
			                    </tr>
	  			 		 		<tr>
			                      <th >Customer Name</th>
			                      <th >Email</th>			                      
			                      <th >Particulars</th>			                      
			                      <th >Date Added</th>	
			                      <th>Amount</th>
			                    </tr>
	  			 		 	</thead>
	  			 		 	<tbody id="tblTbody">
	  			 		 		<?php if($wallet_list){ ?>
	  			 		 		  	<?php foreach ($wallet_list as  $wl) { ?>
	  			 		 		  		<tr>			 		 		  			
	  			 		 		  			<td><?php echo $wl['fullname'];?></td>
	  			 		 		  			<td><?php echo $wl['email'];?></td>	  			 		 		  		
	  			 		 		  			<td><?php echo $wl['particulars'];?></td>	  			 		 		  		
	  			 		 		  			<td><?php echo $wl['date_added'];?></td>
	  			 		 		  			<td><?php echo  number_format($wl['amount'],2);?></td>	 
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
<script type="text/javascript">
	function searchOrders() {	   
	    var input, filter, table, tr, td,td2,td3, i, txtValue,txtValue2,txtValue3;
	    input = document.getElementById("InputsearchWallet");
	    filter = input.value.toUpperCase().trim();
	    table = document.getElementById("tblWallet");
	    var tBody = table.tBodies.namedItem("tblTbody");
	    var tableRow = tBody.getElementsByTagName('tr');
	    for (var t = 0; t < tableRow.length; t++){
	        td = tableRow[t].getElementsByTagName("td")[0];
	        td2 = tableRow[t].getElementsByTagName("td")[1];
	        td3 = tableRow[t].getElementsByTagName("td")[2];
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
</script>