<?php 

$list = $model->activity_list('','','','');	
?>
<div class="row">
	<div class="container">
		<div >
		<h3 style="display: inline-block;">Recent Customer Activity  </h3> <a href="customer_activity.php"><small>See More...</small></a>
		</div>
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