<?php 	
	require_once 'template/header.php';
	require_once "model/Order.php";
	require_once "model/viewStoreOrderDetails.php";
	include "../include/banggoodAPI.php"; 
	require_once "../mgmtseller/model/seller_branch.php";
	$model_branch=new SellerBranch;
	$model=new Order;
	$modelStore=new StoreOrder;	
	if(!$session->is_signed_in()){redirect("index");}
	
	if(isset($_GET['order_id'])){
		$order = $model->order_details($_GET['order_id']);	
	}else{
		redirect('home');
	}
	if(isset($_POST['add_order_history_admin'])){
		$order_status_id = trim($_POST['order_status_id_admin']);
		$comment = trim($_POST['comment_admin']);
		$order_id = trim($_GET['order_id']);
		if($order_status_id==49){
			if($comment==''){
				$message = '<div class="alert alert-danger">Please Input Seller Receipt No.</div>';
			}
		}
		if(!isset($message)){
			$result = $model->insert_order_history($order_status_id, $comment, $order_id);
			if($result){
				$order = $model->order_details($_GET['order_id']);
				$message = '<div class="alert alert-success">Successfully Added</div>';
			}else{
				$message = '<div class="alert alert-danger">There something wrong to your server</div>';
			}
		}
		
	}else{
		$message = '';
	}
	$counterdisabled=0;
	if($order['payment_code']=="cod"){		
		if($order['ops_verification']!=""){
			$counterdisabled++;
		}		
	}else{
		if($order['fund_status']!=""){
			$counterdisabled++;
		}
	}

?>
<div class="row">
	<div class="container">
		<?php if(isset($message)){echo $message;} ?>
		<div class="row">
              <div class="col-lg-6">
                 <h1>Order (#<?php echo $order['order_id'];?>) </h1>
                 <?php if($order['order_status_id']==17 && $order['payment_code']!="cod"){?>
					<h3>Payment Confirmation (<?php if($order['fund_status']!=""){echo $order['fund_status'];}else{echo "Unpaid";}?>) </h3>
				<?php }?>
              </div>             
               <div class="col-lg-6">
                  <a class="btn btn-primary pull-right" id="VerifyCustomer" title="Verify Customer Order" style="margin-left:5px;"><i class="fas fa-check-circle"></i> Verify Customer Order</a>
              </div>
          </div>  
		<br>
		<div class="col-md-4">
			<div class="panel panel-default">
			  <div class="panel-heading"><b>Order Details</b></div>
			  <div class="panel-body">
			  	<ul>
			  		<li><?php echo $order['store_name'];?></li>
			  		<li><?php echo $order['date_added'];?></li>
			  		<li><?php echo $order['payment_method'];?></li>
			  		<li><?php echo $order['shipping_method'];?></li>
			  	</ul>
			  </div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="panel panel-default">
			  <div class="panel-heading"><b>Customer Details</b></div>
			  <div class="panel-body">
			  	<ul>
			  		<li><?php echo $order['customer'];?></li>
			  		<li><?php echo $order['email'];?></li>
			  		<li><?php echo $order['telephone'];?></li>
			  	</ul>
			  </div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="container">
		<div class="col-md-6">
			<div class="panel panel-default">
			  <div class="panel-heading"><b>Payment Address</b></div>
			  <div class="panel-body">
			  	<ul>
			  		<li><?php echo $order['payment_firstname'] . ' '. $order['payment_lastname'];?></li>
			  		<li><?php echo $order['payment_company'];?></li>
			  		<li><?php echo $order['payment_address_1'].' '.$order['payment_address_2'];?></li>		  		
			  		<li><?php echo $order['payment_district'];?></li>
			  		<li><?php echo $order['payment_city'] . ' ' . $order['payment_postcode'];?></li>
			  		<li><?php echo $order['payment_region'];?></li>
			  		<li><?php echo $order['payment_country'];?></li>
			  	</ul>
			  </div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-default">
			  <div class="panel-heading"><b>Shipping Adress</b></div>
			  <div class="panel-body">
			  	<ul>
			  		<li><?php echo $order['shipping_firstname'] . ' '. $order['shipping_lastname'];?></li>
			  		<li><?php echo $order['shipping_company'];?></li>
			  		<li><?php echo $order['shipping_address_1'].' '.$order['shipping_address_2'];?></li>
			  		<li><?php echo $order['shipping_district'];?></li>
			  		<li><?php echo $order['shipping_city'] . ' ' . $order['shipping_postcode'];?></li>
			  		<li><?php echo $order['shipping_region'];?></li>
			  		<li><?php echo $order['shipping_country'];?></li>
			  	</ul>
			  </div>
			</div>
		</div>
	</div>
</div>
<?php 
	$order_PrdDetails = $modelStore->StoreOrderPrdDetails($_GET['order_id']);	
	$order_PrdDetailsGlobal = $model->OrderPrdDetailsGlobal($_GET['order_id']);	
	/*echo "<pre>";
	print_r($order_PrdDetails);*/
?>
<div class="row">
	<div class="container">
		<div class="col-md-12">
			<div class="panel panel-default">
				<?php if(count($order_PrdDetails)){ ?>
					<div class="panel-heading"><b>Local Products</b></div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover" >		
								<tbody>
									<?php foreach($order_PrdDetails as $lprd): ?>
										<tr>
											<td colspan="2" style="vertical-align: middle;text-align: center;">
												<div id="div-hs">
											        <div data-toggle="tooltip" title="<?php echo $lprd['shop_name'];?>" class="image-hps">											          
											            <img src="<?php echo $lprd['thumb'];?>" style="width: 70px;height: 70px;margin:auto"/>
											        </div>
											        <div style="text-align: center;">
											           <small> <?php echo $lprd['shop_name'];?></small>
											        </div>
											      </div>
											</td>
											<th colspan="2" style="vertical-align: middle;text-align: center;"	>
												Status : <?php echo $lprd['order_status'][0]['status'];?>
											</th>
											<th style="vertical-align: middle;text-align: center;">
												<div class="col-md-12">
													<div class="panel panel-default">
													  <div class="panel-heading"><b>Store Address</b></div>
													  <div class="panel-body">
													  	<?php 
													  		$SellerBranchList=$model_branch->SellerBranchlist($lprd['seller_id']);
													  		if($lprd['branch_id']!=0){
													  			$GetSellerPreBranch = $model_branch->GetSellerPreBranch($lprd['branch_id'],$lprd['seller_id']);
													  		}
															
														?>
														<?php 
															if(count($SellerBranchList)!=0){
																if($lprd['branch_id']!=0){
																	if(count($GetSellerPreBranch)!=0){
																		echo $GetSellerPreBranch[0]['b_name'].' '.$GetSellerPreBranch[0]['address_1'].' '.$GetSellerPreBranch[0]['address_2'].' '.$GetSellerPreBranch[0]['district'].' ,'.$GetSellerPreBranch[0]['city'].' ,'.$GetSellerPreBranch[0]['region'].', '.$GetSellerPreBranch[0]['country'];
																	}
																}else{
																	echo "Store address not yet selected";
																}
															}else{
															 	$GetSellerAddressdeff = $model_branch->GetSellerAddressdeff($lprd['seller_id']);
															 	if(count($GetSellerAddressdeff)!=0){
															 		echo $GetSellerAddressdeff[0]['company'].' '.$GetSellerAddressdeff[0]['address_1'].' '.$GetSellerAddressdeff[0]['address_2'].' '.$GetSellerAddressdeff[0]['district'].' ,'.$GetSellerAddressdeff[0]['city'].' ,'.$GetSellerAddressdeff[0]['region'].', '.$GetSellerAddressdeff[0]['country'];
															 	}
															}
														?>
													  </div>
													</div>
												</div>
												
											</th>
											<th colspan="4" style="vertical-align: middle;text-align: center;">
												<?php if($lprd['order_status'][0]['order_status_id']!="49" && $lprd['order_status'][0]['order_status_id']!="48" && $lprd['order_status'][0]['order_status_id']!="31" && $lprd['order_status'][0]['order_status_id']!="27"){?>
													<button id="AddOrderHistotry" class="btn btn-primary AddOrderHistotry" 
														data-seller_id="<?php echo $lprd['seller_id']; ?>"
														data-current_status="<?php echo $lprd['order_status'][0]['order_status_id']; ?>"
														data-order_number="<?php echo $lprd['order_number']; ?>"
										                data-order_id="<?php echo $_GET['order_id'];?>">
													<i class="fa fa-plus-circle"></i> Add Order History
										        	</button>
										    	<?php }?>
											</th>
										</tr>
										<tr>
											<td class="text-center" colspan="2">Serial</td>
											<th>Product</th>
											<th>Model</th>
											<th>Promo/Discount</th>
											<th>Freebies</th>
											<th>Quantity</th>				
											<th>Unit Price</th>				
											<th>Total</th>
										</tr>
										<?php foreach($lprd['details'] as $product):?>
											<?php $bg_order_id=0; $bg_order_status=""; $bg_Track_Info=""; $track_number_bg="";?>
											<tr>
											<td colspan="2"><?php echo $product['serial'];?></td>
											<td><?php echo $product['name'];?></td>
											<td><?php echo $product['model'];?></td>
											<td><?php echo $product['discount_details'];?></td>
											<td><?php echo $product['freebies'];?></td>					
											<?php if($product['NOA']==$product['quantity']){?>
								                <td class="text-right">1</td>			               
								                <td class="text-right"><?php echo $product['price']; ?></td>
								                <td class="text-right"><?php echo $product['price']; ?></td>
								             <?php }else{ ?>
								                 <td class="text-right"><?php echo $product['quantity']; ?></td>
								                 <td class="text-right"><?php echo $product['price']; ?></td>		                 
								                 <td class="text-right"><?php echo $product['total']; ?></td>
								             <?php }?>
										</tr>
										<?php endforeach;?>
										<?php foreach ($lprd['totals'] as $totals) { ?>
							            <tr>
							              <td colspan="8" class="text-right"><?php echo $totals['title']." : "; ?></td>
							              <td class="text-right"><?php echo number_format($totals['value'],2); ?></td>
							            </tr>
							            <?php } ?>
									<?php endforeach;?>
								</tbody>
							</table>
						</div>
					</div>
				<?php }?>
				<?php if(count($order_PrdDetailsGlobal)){?>
					<div class="panel-heading"><b>Global Products</b></div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover" >
								<thead>
									<td class="text-center" colspan="2">Add Serial</td>
									<th>Product</th>
									<th>Model</th>
									<th>Options</th>
									<th>Order Status</th>
									<th>Tracking Number</th>
									<th>Track Info</th>
									<th>Quantity</th>
									<th>Shipping Fee</th>
									<th>Unit Price</th>				
									<th>Total</th>
								</thead>
								<tbody>
									<?php foreach($order_PrdDetailsGlobal as $Globalproduct): ?>
										<?php $bg_order_id=0; $bg_order_status=""; $bg_Track_Info=""; $track_number_bg="";?>
										<tr>
											<?php if($Globalproduct['p_type']=="2"){
												$params_goi = array('sale_record_id' => $_GET['order_id'],'lang' => 'en');
												$banggoodAPI->setParams($params_goi);
												$result_goi = $banggoodAPI->getOrderInfo();
												$status_goi=$result_goi['code'];
												if($status_goi==0){
													$bg_order_id=$result_goi['sale_record_id_list'][0]['order_list'][0]['order_id'];
													$bg_order_status=$result_goi['sale_record_id_list'][0]['order_list'][0]['status'];
													//get GetTrackInfo API
													$params_gti = array('order_id' =>$bg_order_id,'lang' => 'en');
													$banggoodAPI->setParams($params_gti);
													$result_gti = $banggoodAPI->getTrackInfo();
													$bg_Track_Info=$result_gti['track_info'][0]['event']. "(".$result_gti['track_info'][0]['time'].")";
													
													//GetOrderHistory API
													$params_goh = array('sale_record_id' => $_GET['order_id'],'order_id' =>$bg_order_id,'lang' => 'en');
													$banggoodAPI->setParams($params_goh);
													$result_goh = $banggoodAPI->getOrderHistory();
													$track_number_bg=$result_goh['track_number'];
													/*echo'<pre>';
													print_r($result_goh);*/

												}else{
													$bg_order_id=0;
													$bg_order_status=$result_goi['msg'];
													$bg_Track_Info="";
												}
												
											}?>
											<?php if($Globalproduct['NOA']==$Globalproduct['quantity']){?>
												<td class="text-right">
									                <button id="button-clear_serialgbl" 
									                          class="btn btn-danger"
									                          data-op_id="<?php echo $Globalproduct['order_product_id']; ?>"
									                          data-order_id_s="<?php echo $_GET['order_id']; ?>"
									                          data-model="<?php echo $Globalproduct['model']; ?>">
									                    <i class="fa fa-trash"></i> 
									                    Clear
									                </button> 
									             </td>
									            <td class="text-center"> You have Already added Serial </td>
									        <?php }else{ $remain=$Globalproduct['quantity']-$product['NOA']; ?>
          										<?php if( $remain==$Globalproduct['quantity']){ ?>
          											<td class="text-right"> 
										                <button id="button-serialgbl" 
										                          class="btn btn-primary"
										                          data-op_id="<?php echo $Globalproduct['order_product_id']; ?>"
										                          data-op_id2="<?php echo $Globalproduct['NOA']; ?>"
										                          data-c_p_id="<?php echo $Globalproduct['product_id']; ?>"
										                          data-order_id_s="<?php echo $_GET['order_id']; ?>"
										                          data-op_qty="<?php echo $Globalproduct['quantity'];?>">
										                    <i class="fa fa-plus-circle"></i> 
										                    Add Serial
										                </button>
										            </td >
										         <?php }else{ ?>
										         	<td class="text-right"> 
										                <button id="button-serialgbl" 
										                          class="btn btn-primary"
										                          data-op_id="<?php echo $Globalproduct['order_product_id']; ?>"
										                          data-op_id2="<?php echo $Globalproduct['NOA']; ?>"
										                          data-c_p_id="<?php echo $Globalproduct['product_id']; ?>"
										                          data-order_id_s="<?php echo $Globalproduct['order_id']; ?>"
										                          data-op_qty="<?php echo $Globalproduct['quantity'];?>">
										                    <i class="fa fa-plus-circle"></i> 
										                    Add Serial
										                </button>
										                <button id="button-clear_serialgbl" 
										                          class="btn btn-danger"
										                          data-op_id="<?php echo $Globalproduct['order_product_id']; ?>"
										                          data-order_id_s="<?php echo $_GET['order_id']; ?>"
										                          data-model="<?php echo $Globalproduct['model']; ?>">
										                    <i class="fa fa-trash"></i> >
										                    Clear
										                </button> 
										              </td >
          										<?php }?>
          										<td class="text-left"> Remain Items (<?php echo $remain;?>)</td>
          									<?php }?>
          									<td><?php echo $Globalproduct['name'];?></td>
											<td><?php echo $Globalproduct['model'];?></td>
											<td><?php echo $Globalproduct['poa_name'];?></td>
											<td><?php echo $bg_order_status;?></td>
								            <td><?php echo $track_number_bg;?></td>
								            <td><?php echo $bg_Track_Info;?></td>											
											<?php if($Globalproduct['NOA']==$Globalproduct['quantity']){?>
								                <td class="text-right">1</td>
								                <td class="text-right"><?php echo $Globalproduct['price']; ?></td>
								                <td class="text-right"><?php echo $Globalproduct['price']; ?></td>
								             <?php }else{ ?>
								                 <td class="text-right"><?php echo $Globalproduct['quantity']; ?></td>
								                 <td class="text-right"><?php echo $Globalproduct['shipping_fee']; ?></td>
								                 <td class="text-right"><?php echo $Globalproduct['price']; ?></td>		                 
								                 <td class="text-right"><?php echo $Globalproduct['total']; ?></td>
								             <?php }?>
										</tr>
										</tr>
									<?php endforeach;?>
								</tbody>
							</table>
						</div>
					</div>
				<?php }?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="container">
		<div class="col-md-8"></div>
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Grand Totals</b></div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover" >
								<?php foreach ($order['total'] as $totals) { ?>
						            <tr>
						              <td colspan="8" class="text-right"><?php echo $totals['title']." : "; ?></td>
						              <td class="text-right"><?php echo number_format($totals['value'],2); ?></td>
						            </tr>
						        <?php } ?>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading"><b>Order History</b></div>
			<div class="panel-body">
				<ul class="nav nav-tabs" role="tablist">
				    <li role="presentation" class="active"><a href="#history" role="tab" data-toggle="tab">History</a></li>
				    <li role="presentation"><a href="#additional"  role="tab" data-toggle="tab">Additional</a></li>
				</ul>
				<div class="tab-content">
				    <div role="tabpanel" class="tab-pane active" id="history">
						<div class="table-responsive">
							<table class="table table-bordered">
				    			<thead>
				    				<tr>
				    				<th>Date Added</th>
				    				<th>Comment</th>
				    				<th>Status</th>
				    				<th>Customer Notified</th>
				    				</tr>
				    			</thead>
				    			<tbody>
				    				<?php foreach($order['historyNew'] as $history):?>
									<tr>
										<td><?php echo $history['date_added'];?></td>
										<td><?php echo $history['shop_name']." ".nl2br($history['comment']);?></td>
										<td><?php echo $history['status'];?></td>
										<td><?php echo $history['notify'];?></td>
									</tr>
				    				<?php endforeach;?>
				    			</tbody>
				    		</table>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane" id="additional">
				    	<div class="container">
				    		<ul>
				    			<li><?php echo $order['ip'];?></li>
				    			<li><?php echo $order['user_agent'];?></li>
				    			<li><?php echo $order['accept_language'];?></li>
				    		</ul>
				    	</div>
				    </div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- <div class="row">
	<div class="container">
		<div class="col-md-6">
			<h4>Add Order History</h4>
		<form method="post">
			<div class="form-group">
				<select class="form-control" name="order_status_id_admin" id="order_status_id_admin">
					<?php
					foreach($model->order_status() as $status) {
						echo '<option value='.$status['order_status_id'].'>'.$status['name'].'</option>';
					}
					?>
				</select>
			</div>
			<label  id="lbltxt"></label> <i id="itxt"></i>
			<div class="form-group">
				<textarea name="comment_admin" cols="30" rows="10" class="form-control"></textarea>
			</div>
			<button type="submit" name="add_order_history_admin" class="btn btn-primary pull-right">Add History</button>
		</form>
		</div>

	</div>
</div> -->
<div  class="modal fade" id="MOD-add-OrderHistory"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-error-dialog">
       	<div class="modal-content">
          	<div class="modal-header">
            	<label ><h2>Add Order History</h2></label>
             	<button type="button" class="close" id="closemod" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title" id="myModalLabel"></h4>
          	</div>
          	<div class="modal-body" >
             	<div class="form-group">
					<select class="form-control" name="order_status_id" id="order_status_id" >
						<?php
							foreach($model->order_status() as $status) {
								echo '<option value='.$status['order_status_id'].'>'.$status['name'].'</option>';
							}
						?>
					</select>
				</div>
				<div class="form-group" id="div-shipment" style="display: none;">
					<select class="form-control" name="order_package_type" id="order_package_type">
						<option value="">Package type</option>
						<option value="small-pouch">Small (12X18 up to 5kls.)</option>
						<option value="medium-pouch">Medium (12X18 up to 5kls.)</option>
						<option value="big-pouch">Large (15.5 X 20 X 4 up to 10kls.)</option>
						<option value="box">Box  (18 x12 x 9 up to 6kls.)</option>
					</select>
				</div>
				<label  id="lbltxt"></label> <i id="itxt"></i>
				<div class="form-group">
					<textarea name="comment" id="comment"cols="30" rows="10" class="form-control"></textarea>
				</div>
                 <input type ="hidden" name="seller_idhidden" class="form-control" id ="seller_idhidden">
                 <input type ="hidden"  name ="order_idhidden" class="form-control" id ="order_idhidden">
                 <input type ="hidden"  name ="order_numberhidden" class="form-control" id ="order_numberhidden">
	            <div class="row">
	              <div class="col-sm-4">
	                <div class="form-group">
	                  <input type="button" value="Save" class="btn btn-primary" id="SaveOrderHistory"/> 
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
	$("#order_status_id").change(function() {
		var order_status_id=$("#order_status_id" ).val();		
		if(order_status_id==47){
			$("#div-shipment").css("display","block");
			$("#lbltxt").text("Input Comment: ");
			$("#itxt").text("");
		}else if(order_status_id==49){
			$("#lbltxt").text("Seller Receipt No. ");
			$("#itxt").text("(Only the reciept details.)");
			$("#div-shipment").css("display","none");
		}else{
			$("#lbltxt").text("Input Comment: ");
			$("#itxt").text("");
			$("#div-shipment").css("display","none");
		}
		
	});
	$(document).delegate('.AddOrderHistotry', 'click', function() {  
	    $('#SaveOrderHistory').prop('disabled', false);     
       	$('#MOD-add-OrderHistory').modal('show');       
      	var seller_id=$(this).data('seller_id');
     	var order_id=$(this).data('order_id');
     	var order_number=$(this).data('order_number');
     	var currentStatus=$(this).data('current_status');
     	if(currentStatus!=46){
     		$("#order_status_id").find("option").each(function () {
			    if ($(this).val() == "49") {
			        $(this).prop("disabled", true);
			    }
			});
			if(currentStatus==17){ 
     			
     			var counterdisabled ='<?php echo $counterdisabled;?>'; 	
     			if(counterdisabled!=0){
     				$("#order_status_id").find("option").each(function () {
					    if ($(this).val() == "47") {
					        $(this).prop("disabled", false);
					    }
					});
     			}else{     				
     				$("#order_status_id").find("option").each(function () {
					    if ($(this).val() == "47") {
					        $(this).prop("disabled", true);
					    }
					});
     			}
     		}
     	}else{     		
     		$("#order_status_id").find("option").each(function () {
			    if ($(this).val() == "49") {
			        $(this).prop("disabled", false);
			    }
			});
     	}
      	$("#seller_idhidden").val(seller_id);
      	$("#order_idhidden").val(order_id);
      	$("#order_numberhidden").val(order_number);
      	$("#lbltxt").text("Input Comment: ");
		$("#itxt").text("");
    })
    $(document).delegate('#SaveOrderHistory', 'click', function() {  
    	$('#SaveOrderHistory').prop('disabled', true);
      	var seller_id=$("#seller_idhidden").val();
      	var order_id=$("#order_idhidden").val();
      	var order_status_id=$("#order_status_id").val();
      	var order_package_type=$("#order_package_type").val();      	
      	var order_number=$("#order_numberhidden").val();      	
      	var comment=$("#comment").val();
      	if(order_status_id==47){
      		if(order_package_type==""){
      			bootbox.alert("Package type Is required"); 
      			$('#SaveOrderHistory').prop('disabled', false);
      			return false;          
      		}
      		$.ajax({
	          url: 'ajax_order_history.php?action=StoreHistoryPackage',
	          type: 'post',
	          data: 'seller_id=' + seller_id+'&order_id='+order_id+'&order_status_id='+order_status_id+'&comment='+comment+'&package_type='+order_package_type + '&order_number='+order_number,
	          dataType: 'json',
	           beforeSend: function() {
		        	bootbox.dialog({
				            title: "Adding Order History",
				            message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
				    });
			   },
	          success: function(json) {
	            bootbox.alert(json['success'], function(){ 
	              location.reload();
	            });
	          }
	        });  

      	}else if(order_status_id==49){
      		if(comment==""){
      			bootbox.alert("Please Input Seller Receipt No."); 
      			$('#SaveOrderHistory').prop('disabled', false);
      			return false; 				
			}
      		$.ajax({
		        url: '../ajax_add_to_cart_latest.php?action=ReciveStoreOrder',
		        type: 'POST',
		        data: 'seller_id=' + seller_id + '&order_id='+order_id + '&comment='+comment+ '&order_number='+order_number,
		        dataType: 'json',
		         beforeSend: function() {
		        	bootbox.dialog({
				            title: "Adding Order History",
				            message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
				    });
			   },
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
      	}else{
      		$.ajax({
	          url: 'ajax_order_history.php?action=StoreOrderHistory',
	          type: 'post',
	          data: 'seller_id=' + seller_id+'&order_id='+order_id+'&order_status_id='+order_status_id+'&comment='+comment+ '&order_number='+order_number,
	          dataType: 'json',
	          beforeSend: function() {
		        	bootbox.dialog({
				            title: "Adding Order History",
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
      	
    })
    $(document).delegate('#VerifyCustomer', 'click', function() {
      $('#VerifyCustomer').prop('disabled', true);
     	var order_id='<?php echo $_GET['order_id'];?>'
        $.ajax({
            url: 'ajax_sms_admin.php?action=VerifyCustomer&t=' + new Date().getTime(),
            type: 'post',
            data: {
                order_id: order_id,
            },
            dataType: 'json',
            beforeSend: function() {
                bootbox.dialog({
                      title: "Verifying Customer",
                      message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
              });
           },
            success: function(json) {
                bootbox.alert(json['success'], function(){ 
                    window.location.reload();
                }); 
            }
        });
    });
</script>
