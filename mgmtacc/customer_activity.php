<?php
include 'template/header.php';
include "model/customer_activity.php";
if(!$session->is_signed_in()){redirect("index");}

$perm = $_SESSION['permission'];
if (!strpos($perm, "'20';") !== false){
    header("Location: landing.php");
   
}

$product = new customeractivity();	
if(isset($_REQUEST['btn_search'])) {
	$datefrom=$_REQUEST["datefrom"];
	$dateto=$_REQUEST["dateto"];
	$customer=$_REQUEST["customer"];
	$ip=$_REQUEST["ip"];
					
	$list = $product->activity_list($datefrom,$dateto,$customer,$ip);
 }
 else{
 	$list = $product->activity_list('','','','');
 }
?>
<div class="wrapper">
	<div class="container">
		<div class="row">
			<center><h2>Customer Activity Report</h2></center><br>
            <div class="col-xs-12 well">
              	<form method="post" class="form-horizontal" action="customer_activity.php">
					<div class="form-group">
						<div class="col-sm-6">
							<label>Date From</label>
							<input type="date" name="datefrom"  class="form-control" value="<?php if(isset($_REQUEST['btn_search'])){echo $_REQUEST["datefrom"]; }else { echo ''; }?>"/ >
						</div>
						<div class="col-sm-6">
							<label>Date To</label>
							<input type="date" name="dateto"  class="form-control" value="<?php if(isset($_REQUEST['btn_search'])){echo $_REQUEST["dateto"]; }else { echo ''; }?>"/ >
						</div>				
					</div>
					<div class="form-group">
						<div class="col-sm-6">
							<input type="text" name="customer"  class="form-control" placeholder="Customer Name" value="<?php if(isset($_REQUEST['btn_search'])){echo $_REQUEST["customer"]; }else { echo ''; }?>"/ >
						</div>
						<div class="col-sm-5">
							<input type="text" name="ip"  class="form-control" placeholder="IP Address" value="<?php if(isset($_REQUEST['btn_search'])){echo $_REQUEST["ip"]; }else { echo ''; }?>"/ >
						</div>
						<div class="col-sm-1">
							<input type="submit" name="btn_search" class="btn btn-success" value="Search">
						</div>
					</div>
				</form>
            </div>
         </div>
		<div class="row">
		<div class="col-xs-12">
			 <div class="table-responsive" style="overflow-x: auto">
			 	<table class="table table-striped table-bordered table-hover" id="table-product">
			 	  	<thead>
	                    <tr>
	                     <th>Comment</th>
	                     <th>IP</th>
	                     <th>Date Added</th>
	                   
	                    </tr>
                    </thead> 
                    <tbody>
                   	 <?php					
						if(count($list) == 0){
							?>
							 <tr>
							 	<td colspan="6" align="center">No data found.</td>
							 	
							 </tr>
							<?php
						}else{
							foreach($list as $product){
								if($product['key'] == 'register' && $product['type']=="guest"){
								}else if ($product['key'] == 'Google Account registed') {
								}else{ ?>
								 	<tr>
									 	<td><?php if($product['key'] == 'login') {
									 		echo  '<a href="customer_update.php?cid='.$product['customer_id'].'">'.$product['firstname'].' '.$product['lastname'].'</a> logged in.'; 
									 	}else if ($product['key'] == 'register'){							 		
								 			if($product['firstname']==''){
									 			echo  '<a href="customer_update.php?cid='.$product['customer_id'].'">'.$product['email'].' </a> registered for an account.'; 

									 		}else{
									 			echo  '<a href="customer_update.php?cid='.$product['customer_id'].'">'.$product['firstname'].' '.$product['lastname'].'</a> registered for an account.'; 
									 		}					 		
									 	}else if ($product['key'] == 'order_account'){ 
									 		echo  '<a href="customer_update.php?cid='.$product['customer_id'].'">'.$product['firstname'].' '.$product['lastname'].'</a> created a new order. '; 
									 	}else if ($product['key'] == 'login Google Account'){ 
									 		echo  '<a href="customer_update.php?cid='.$product['customer_id'].'">'.$product['firstname'].' '.$product['lastname'].'</a> logged in Using Google Account.'; 
									 	}else if ($product['key'] == 'logOut'){ 
									 		echo  '<a href="customer_update.php?cid='.$product['customer_id'].'">'.$product['firstname'].' '.$product['lastname'].'</a> Log out'; 
									 	}else {   echo $product['key']; } ?></td>
									 	<td><?php echo  $product['ip'];?></td>
									 	<td><?php echo  $product['date_added'];?></td>
								 	</tr>
								 <?php } ?>
							<?php } ?>
						<?php } ?>		
                  	</tbody>
			 	</table>
			</div>
			
		</div>
		</div>
	</div>	
</div>

<?php include 'template/footer.php';?>									
 	
 <script>
    $(document).ready(function() {

    	if($("#status") != '')
    	{
    		$("#d_status").val($("#status").val());
    	}

    	$("#d_status").on('change', function(){
            $('#status').val($(this).val());

        });
    });
 </script>

