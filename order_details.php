<?php 
include "common/header.php";
include "model/orderhistory.php";
include "include/banggoodAPI.php"; 
$session->check_the_login2();
$model=new OrderHistory;
?>

<?php 
if(isset($_GET['order_id'])){
	$order = $model->order_details($_GET['order_id']);
}else{
	$order = "_null";
}

?>
<script type="text/javascript">
  var islog='<?php echo $is_log;?>';
  var orderid='<?php echo $order;?>';
      if(islog=="0"){
      location.replace("home.php");
    }
     if(orderid=="_null"){
      location.replace("home.php");
    }
</script>
<div class="wrapper">
	<div class="container">
		<?php 
		if(isset($message)){
			echo $message;
		}
		?>
		<div class="row">
			<div class="col-sm-12">
				<center><h1>Order (#<?php echo $order['order_id'];?>)</h1></center>
		 		<div class="row">
				</div>	
			</div>
		</div>	
		<div class="row">
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
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-default">
				  <div class="panel-heading"><b>Payment Address</b></div>
				  <div class="panel-body">
				  	<ul>
				  		<li><?php echo $order['payment_firstname'] . ' '. $order['payment_lastname'];?></li>
				  		<li><?php echo $order['payment_company'];?></li>
				  		<li><?php echo $order['payment_address_1'];?></li>
				  		<li><?php echo $order['payment_address_2'];?></li>
				  		<li><?php echo $order['payment_city'] . ' ' . $order['payment_postcode'];?></li>
				  		<li><?php echo $order['payment_zone'];?></li>
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
				  		<li><?php echo $order['shipping_address_1'];?></li>
				  		<li><?php echo $order['shipping_address_2'];?></li>
				  		<li><?php echo $order['shipping_city'] . ' ' . $order['shipping_postcode'];?></li>
				  		<li><?php echo $order['shipping_zone'];?></li>
				  		<li><?php echo $order['shipping_country'];?></li>
				  	</ul>
				  </div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<th></th>
							<th>Serial</th>
							<th>Product</th>
							<th>Model</th>
							<th>Option</th>
							<th>Order Status</th>
							<th>Tracking Number</th>
							<th>Track Info</th>
							<th>Quantity</th>
							<th>Shipping Fee</th>
							<th>Unit Price</th>							
							<th>Total</th>
							
						</thead>
						<tbody>
							<?php foreach($order['products'] as $product):
								$bg_order_id=0;
								$bg_order_status="";
								$bg_Track_Info="";
								$track_number_bg="";?>
							<tr>
								<?php
									if($product['p_type']=="2"){
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
					             <?php if(trim($product['serial']) !== ''){?>
					                <td class="text-right">
					                	<a href="return_add.php?oid=<?php echo $order['order_id'];?>&pid=<?php 
					                	echo $product['product_id'];?>&srl=<?php 
					                	echo $product['serial'];?>" class="btn btn-danger" title="Return">
					                		<i data-feather="rotate-ccw"></i>
					                	</a>
					                </td>
					             <?php } else { ?>
					             	<td></td>
					             <?php }?>
								<td><?php echo $product['serial'];?></td>
								<td><?php echo $product['name'];?></td>
								<td><?php echo $product['model'];?></td>
								<td><?php echo $product['poa_name'];?></td>
								<td>
				                 	<?php if($bg_order_status=="Payment Pending"){
				                 			echo "Order for verification";
				                 		}else{ echo $bg_order_status;} ?>
					            </td>
					            <td><?php echo $track_number_bg;?></td>
					            <td><?php echo $bg_Track_Info;?></td>
								<?php if($product['NOA']==$product['quantity']){?>
					                <td class="text-right">1</td>
					                <td class="text-right"><?php echo $product['price']; ?></td>
					                <td class="text-right"><?php echo $product['price']; ?></td>
					             <?php }else{ ?>
					                 <td class="text-right"><?php echo $product['quantity']; ?></td>

									 <td>
									 	<?php if($product['shipping_fee']==0){ echo $product['shipping_fee'];}else{echo "Free Shipping";}?>
									 </td>
					                 <td class="text-right"><?php echo $product['price']; ?></td>					                
					                 <td class="text-right"><?php echo $product['total']; ?></td>
					             <?php }?>
							</tr>
							<?php endforeach;?>
						<?php foreach ($order['total'] as $totals) { ?>
			            <tr>
			              <td colspan="11" class="text-right"><?php echo $totals['title']." : "; ?></td>
			              <td class="text-right"><?php echo number_format($totals['value'],2); ?></td>
			            </tr>
			            <?php } ?>
						</tbody>						
					</table>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-default">
					<div class="panel-heading"><b>Order History</b></div>	
					<div class="panel-body">
						<ul class="nav nav-tabs" role="tablist">
						    <li role="presentation" class="active"><a href="#history" role="tab" data-toggle="tab">History</a></li>						    
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
						    				</tr>
						    			</thead>
						    			<tbody>
						    				<?php foreach($order['history'] as $history):?>
											<tr>
												<td><?php echo $history['date_added'];?></td>
												<td><?php echo nl2br($history['comment']);?></td>
												<td><?php echo $history['status'];?></td>
											</tr>
						    				<?php endforeach;?>
						    			</tbody>
						    		</table>
						 		</div>
						 	</div>							 	
						 </div>
					</div>					
				</div>
			</div>
			<br>
		</div>	
		<div class="form-group pull-right">
			<a href="./index.php" title="Continue" class="btn btn-primary">Continue</a>
		</div>
	</div>
</div>
<?php include "common/footer.php";?>

