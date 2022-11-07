<?php
 include 'template/header.php';  
 include "model/Returns.php";

$perm = $_SESSION['permission'];
if (!strpos($perm, "'16';") !== false){
    header("Location: landing.php");
   
}

 $model=new Returns();
 $data=$model->return_historyA();
 $info=$model->return_details($_GET['retid']);
 $reason = $model->return_reason();
 $action = $model->return_action();
 $status = $model->return_status();
 $return_history=$model->return_history_details($_GET['retid']);
?>
<script type="text/javascript">
  var islog='<?php echo $is_log;?>'; 
      if(islog=="0"){
      location.replace("home.php");
    }
</script>
<style> 
	.text-right{
		text-align: left;
	}
	.mt-5{
		margin-top: 5px;
	}

</style>
<div class="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h2>Edit Product Return</h2> 
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<form action="submit_return.php" method="POST">
			 	<div class="form-group col-sm-12">						
					<div class="pull-right">
						<a href="./return_list.php?t=<?php echo  uniqid();?>" title="Back" class="btn btn-danger"><i data-feather="arrow-left"></i></a>
						<button class="btn btn-primary" type="submit" name="update_returns" title="Update Return"><i data-feather="save"></i></button>
					</div>
				</div>
				<ul class="nav nav-tabs justify-content-center">
	                <li class="nav-item active">
	                    <a class="nav-link" href="#General" data-toggle="tab" aria-expanded="true">General</a>
	                </li>
	                <li class="nav-item">
	                    <a class="nav-link" href="#History" data-toggle="tab" aria-expanded="true">History</a>
	                </li>
	            </ul>
	             <div class="tab-content">
	                <div class="tab-pane active" id="General">
	                	<br>
	                    <fieldset>
						<legend>Order Information</legend>
						<input type="hidden" name="return_id" value="<?php echo $_GET['retid'];?>">
						<input type="hidden" name="customer_id" value="<?php echo $info['customer_id'];?>">
	                    <div class="form-group text-right">	                       
	                        <label class="col-sm-2 control-label"><b style="color: red">*</b> Order ID</label>
	                       	<div  class="col-sm-10">
	                        	<input type="text" class="form-control" id="order_id" name="order_id" placeholder="Order ID"
	                        	value="<?php echo $info['order_id'];?>" required>
	                    	</div>
	                    </div><br>
	                  	<div class="form-group text-right">
	                        <label class="col-sm-2 control-label">Order Date</label>
	                       	<div  class="col-sm-10">
	                        	<input type="date" class="form-control" id="date_ordered" name="date_ordered" value="<?php echo $info['date_ordered'];?>" required>
	                    	</div>
	                    </div><br>
	                     <div class="form-group  text-right">
	                        <label class="col-sm-2 control-label">Customer</label>
	                       	<div  class="col-sm-10">
	                        	<input type="text" class="form-control" id="customer" name="customer" placeholder="Customer" 
	                        	value="<?php echo $info['customer']?>" required>
	                    	</div>
	                    </div><br>
	                  	<div class="form-group  text-right">
	                        <label class="col-sm-2 control-label"><b style="color: red">*</b> First Name</label>
	                       	<div  class="col-sm-10">
	                        	<input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" 
	                        	value="<?php echo $info['firstname'];?>" required>
	                    	</div>
	                    </div><br>
	                  	<div class="form-group  text-right">
	                        <label class="col-sm-2 control-label"><b style="color: red">*</b> Last Name</label>
	                       	<div  class="col-sm-10">
	                        	<input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" value="<?php echo $info['lastname'];?>"required>
	                    	</div>
	                    </div><br>
						<div class="form-group  text-right">
	                        <label class="col-sm-2 control-label"><b style="color: red">*</b> Email</label>
	                       	<div  class="col-sm-10">
	                        	<input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo $info['email'];?>" required>
	                    	</div>
	                    </div><br>
	                    <div class="form-group  text-right">
	                        <label class="col-sm-2 control-label"><b style="color: red">*</b> Mobile Number</label>
	                       	<div  class="col-sm-10">
	                        	<input type="text" class="form-control" id="telephone" name="telephone" placeholder="Mobile Number" value="<?php echo $info['telephone'];?>" required>
	                    	</div>
	                    </div><br><hr>
	                    </fieldset>
	                    <fieldset>
	                    <legend>Product Information & Reason for Return</legend>
	                 	<div class="form-group  text-right">
	                        <label class="col-sm-2 control-label"><b style="color: red">*</b> Product</label>
	                       	<div  class="col-sm-10">
	                        	<input type="text" class="form-control" id="product" name="product" placeholder="Product" value="<?php echo $info['product'];?>" readonly>
	                		</div>
	                    </div><br>
	                  	<div class="form-group  text-right">
	                        <label class="col-sm-2 control-label">Model</label>
	                       	<div  class="col-sm-10">
	                        	<input type="text" class="form-control" id="model" name="model" placeholder="Model" value="<?php echo $info['model'];?>" readonly>
	                		</div>
	            		</div><br>
	            		<div class="form-group text-right">
	                        <label class="col-sm-2 control-label">Quantity</label>
	                       	<div  class="col-sm-10">
	                        	<input type="number" class="form-control" id="quantity" name="quantity" placeholder="Quantity" value="<?php echo $info['quantity'];?>" required>
	                		</div>
	            		</div><br>
	            		<div class="form-group text-right">
						 	<label class="col-sm-2">Return Reason</label>
						 	<div class="col-sm-10">
			          		  <select class="form-control" name="return_reason_id" id="return_reason_id">
							 	<?php foreach ($reason as $r): ?>
				          		<option value="<?php echo $r['return_reason_id']; ?>"><?php echo $r['name']; ?></option>
				          		<?php endforeach;
				          		echo "<script>$('#return_reason_id').val('".$info['return_reason']."')".";</script>'";?>
						      </select>	
					        </div>		 	
					 	</div><br>
					 	<div class="form-group text-right">
						 	<label class="col-sm-2">Opened</label>
						 	<div class="col-sm-10">
			          		  <select class="form-control" name="opened" id="opened">
				          		<option value="1">Opened</option>
				          		<option value="0">Unopened</option>
				          		<?php echo "<script>$('#opened').val('".$info['opened']."')".";</script>'";?>
						      </select>	
					        </div>		 	
					 	</div><br>
					 	<div class="form-group text-right">
						 	<label class="col-sm-2">Comment</label>
						 	<div class="col-sm-10">
			          		  <textarea rows="5" class="form-control" name="comment" id="comment"><?php echo $info['comment']; ?>
			          		  </textarea> 
					        </div>		 	
					 	</div><br>
					 	<div class="form-group text-right">
						 	<label class="col-sm-2">Return Action</label>
						 	<div class="col-sm-10">

			          		  <select class="form-control" name="return_action_id" id="return_action_id">
							 	<?php foreach ($action as $r): ?>
				          		<option value="<?php echo $r['return_action_id']; ?>"><?php echo $r['name']; ?></option>
				          		<?php endforeach;
				          		echo "<script>$('#return_action_id').val('".$info['return_action']."')".";</script>'";?>
						      </select>	
					        </div>		 	
					 	</div><br>
	                   </fieldset>
	                </div>
	                <div class="tab-pane" id="History"><br>        
	                    <div class="row">
							<div class="col-sm-12">
								<div class="table-responsive">					
							 		<table class="table table-striped table-bordered table-hover">
							 			<thead>
											<th>Date Added</th>
											<th>Comment</th>
											<th>Status</th>											
											<th>Customer Notified</th>											
										</thead>
										<tbody>
											<?php
												if(count($return_history) > 0){ 
												foreach($return_history as $o):
											?>
											<tr>
												<td><?php echo $o['added_date'];?></td>
												<td><?php echo $o['comment'];?></td>
												<td><?php echo $o['return_status'];?></td>
												<td><?php $n = $o['notify'] == 1 ? 'Yes': 'No'; echo $n;?></td>

											</tr>
										<?php endforeach; }
											else { ?>
													<tr><td colspan="6" align="center">No data found.</td></tr>
												<?php } ?>
										</tbody>
							 		</table>
								</div>
							</div>
						</div> 
					  	<fieldset>
	                    	<legend>Add Return History</legend>    
	                    	<div class="form-group text-right">
	                        <label class="col-sm-2 control-label">Return Status</label>
	                       	<div  class="col-sm-10">
	                        	<select class="form-control" name="return_status_id" id="return_status_id">
							 	<?php foreach ($status as $r): ?>
				          		<option value="<?php echo $r['return_status_id']; ?>"><?php echo $r['name']; ?></option>
				          		<?php endforeach;?>
						      </select>	
	                		</div>
	                    	</div><br>
	                    	<div class="form-group text-right">
	                        <label class="col-sm-2 control-label">Notify Customer</label>
	                       	<div  class="col-sm-10" style="margin:auto;">
	                        	<input type="checkbox" style="width:18px;height:16px;" value="1" id="input-notify">
	                        	<span id="lbl-notify" style="font-size:18px;">No</span>
	                        	<input type="hidden" name="notify" id="txt-notify" value="0">
	                		</div>
	                    	</div><br>
						 	<div class="form-group text-right">
						 	<label class="col-sm-2">Comment</label>
						 	<div class="col-sm-10">
			          		  <textarea rows="5" class="form-control" name="comment_a" id="comment_a" required></textarea> 
					        </div>		 	
					 	</div><br>
	                    </fieldset>      
	                </div>
                </div>
            	</form>
                <br>               
			</div>
		</div>			
	</div>
</div>
<?php include 'template/footer.php';?>
<script>
	$(document).ready(function() {
		$('.return-table').paging({	 
			limit: 30,
			rowDisplayStyle: 'block',
			activePage: 0,
			rows: []
		});
		$('#input-notify').on('change',function(){
			if($(this).prop('checked') == true){
				$('#lbl-notify').html('Yes');
				$('#txt-notify').val('1');
			}else{
				$('#lbl-notify').html('No');
				$('#txt-notify').val('0');
			}
		});
	});
</script>

