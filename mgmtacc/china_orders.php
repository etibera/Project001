<?php 
	include 'template/header.php'; 
	include "model/Specification.php";
	include "../include/china_token.php";
	$model = new Specification(); 
	$perm = $_SESSION['permission'];
	if (!strpos($perm, "'26';") !== false){
	    header("Location: landing.php");
	}  
	if(isset($_GET['cancel'])){
		$order_cancel=$_GET['cancel'];
		$post_data_cn = array(
		    'token' => $token_china,
		    'order' => json_encode($order_cancel),
		    'signature' => md5($client_secret. json_encode($order_cancel)),
		);
		$curl_cn = curl_init('https://gloapi.chinabrands.com/v2/order/cancel');
		curl_setopt($curl_cn, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl_cn, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_cn, CURLOPT_POST, 1);
		curl_setopt($curl_cn, CURLOPT_POSTFIELDS, $post_data_cn);
		$result_cn = curl_exec($curl_cn); //返回结果
		curl_close($curl_cn);
		$res_cancel= json_decode($result_cn);
		
		$stats_c=$res_cancel->msg[0]->status;
		if($stats_c){
			$sMsg_success="Order ID:".$res_cancel->msg[0]->order_sn. " already Cancelled.";
		}else{
			$sMsg_failed="Cancellation Failed (".$res_cancel->msg[0]->msg. ")";
		}
	}
	if(isset($_GET['pay'])){
		$order_id =  $_GET['pay']; //字符串
		$post_payment = array(
		    'token' => $token_china,
		    'order' => json_encode($order_id),
		    'signature' => md5($client_secret . json_encode($order_id)),
		);
		$curl_payment = curl_init('https://gloapi.chinabrands.com/v2/order/pay');
		curl_setopt($curl_payment, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl_payment, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_payment, CURLOPT_POST, 1);
		curl_setopt($curl_payment, CURLOPT_POSTFIELDS, $post_payment);
		$result_payment = curl_exec($curl_payment); //返回结果
		curl_close($curl_payment);
		$res_payment= json_decode($result_payment);
		$stats_p=$res_payment->msg[0]->status;
		if($stats_p){
			$sMsg_success="Payment ".$res_payment->msg[0]->msg." Paid amount: $".$res_payment->msg[0]->paid_amount;
		}else{
			$sMsg_failed="Payment Failed (".$res_payment->msg[0]->msg. ")";
		}
		

	}
?>
<div id="content">
	<div class="page-header">
		 <h2 class="text-center">China Orders</h2>
	</div>
	
	<div class="container-fluid">
		<div class="panel panel-success">
		 	<div class="panel-heading" style="padding:20px;">
		 		<div class="row">
		 			<div class="col-lg-6">
          				<p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i> Order List</p>
          			</div>
          			<div class="col-lg-6">
			            <div class="pull-right">
			            	<?php 
		            			$post_current_bal = array(
								'token' => $token_china,
								);
								$curl_current_bal= curl_init('https://gloapi.chinabrands.com/v2/user/get-user-balance');
								curl_setopt($curl_current_bal, CURLOPT_SSL_VERIFYPEER, false);
								curl_setopt($curl_current_bal, CURLOPT_RETURNTRANSFER, 1);
								curl_setopt($curl_current_bal, CURLOPT_POST, 1);
								curl_setopt($curl_current_bal, CURLOPT_POSTFIELDS, $post_current_bal);
								$result_curr_bal = curl_exec($curl_current_bal); //返回结果
								curl_close($curl_current_bal);
								$res_curr_bal= json_decode($result_curr_bal);
								// echo "<pre>";
								// print_r($res_curr_bal->msg->balance);
							?>
			            	<h4>CB Wallet : <?php echo '$'.$res_curr_bal->msg->balance;?></h4>
			            </div>
			        </div>
		 		</div>
		 	</div>
		 	<div class="panel-body">
		 		<?php if(isset($sMsg_success)){ ?>
				    <div class="alert alert-success">
				        <strong><?php echo $sMsg_success;?></strong></br>
				    </div>
				<?php } ?>
				<?php if(isset($sMsg_failed)){ ?>
				    <div class="alert alert-danger">
				        <strong><?php echo $sMsg_failed;?></strong></br>
				    </div>
				<?php } ?>
    	 		<div class="table-responsive">
    	 			<table class="table table-bordered table-hover">
    	 				<thead >
							<th class="text-center">China Order No</th>
							<th class="text-center">Batch Order ID</th>
							<th class="text-center">Product Amount</th>
							<th class="text-center">Pay Amount</th>
							<th class="text-center">Shipping Name</th>
							<th class="text-center">Tracking Number</th>
							<th class="text-center">Order Status</th>
							<th class="text-center">Date Added</th>
							<th class="text-center">Action</th>
						</thead>
						<tbody>
						 	<?php foreach($model->get_china_orders() as $order): 
						 		//Get Order Tracking Number
								$post_data_otn = array(
								    'token' => $token_china,
								    'order_sn' => json_encode($order['order_sn'])
								);
								$curl_otn = curl_init('https://gloapi.chinabrands.com/v2/shipping/track');
								curl_setopt($curl_otn, CURLOPT_SSL_VERIFYPEER, false);
								curl_setopt($curl_otn, CURLOPT_RETURNTRANSFER, 1);
								curl_setopt($curl_otn, CURLOPT_POST, 1);
								curl_setopt($curl_otn, CURLOPT_POSTFIELDS, $post_data_otn);
								$result_otn = curl_exec($curl_otn);
								curl_close($curl_otn);
								$res_otn= json_decode($result_otn);
								$msg_otn=$res_otn->msg;
								
								//Get Order Detail
								$post_data_od = array(
								    'token' => $token_china,
								    'order_sn' => $order['order_sn'],
								);
								$curl_od = curl_init('https://gloapi.chinabrands.com/v2/order/index');
								curl_setopt($curl_od, CURLOPT_SSL_VERIFYPEER, false);
								curl_setopt($curl_od, CURLOPT_RETURNTRANSFER, 1);
								curl_setopt($curl_od, CURLOPT_POST, 1);
								curl_setopt($curl_od, CURLOPT_POSTFIELDS, $post_data_od);
								$result_od = curl_exec($curl_od);
								curl_close($curl_od);
								$res_od= json_decode($result_od);
								$msg_od=$res_od->msg;
								// echo "<pre>";
								// print_r();
						 	?>
							 	<tr>
							 		<td class="text-left" ><?php echo $order['order_sn'];?></td>
							 		<td class="text-left" >
							 			<a  title="View"
	                    				 href="batch_china_order.php?batch_id=<?php echo  $order['order_id'];?>" >
	                    				<?php echo $order['order_id'];?>
	                 					</a>
	                 				</td>
	                 				<td class="text-left" ><?php echo '$'.$order['goods_amount'];?></td>
	                 				<td class="text-left" ><?php echo '$'.$order['grand_total'];?></td>
	                 				<?php foreach ($msg_otn as $otn) { ?>
	                 					<td class="text-left" ><?php echo $otn->shipping_info[0]->shipping_name;?></td>
	                 					<td class="text-left" ><?php echo $otn->shipping_info[0]->shipping_no;?></td>
	                 				<?php }?>
	                 				<td class="text-left" ><?php echo $msg_od->page_result[0]->order_status;?></td>
							 		<td class="text-left" ><?php echo $order['date'];?></td>
	                 				<td class="text-center">
	                 					<?php if($msg_od->page_result[0]->order_status=="Waiting for payment"){?>
	                 						<a href="china_orders.php?pay=<?php echo $order['order_sn'];?>"class="btn btn-primary"  data-toggle="tooltip" title="Pay order"><i data-feather="credit-card"></i></a>
	                 					<?php } ?>
	                 					<?php if($msg_od->page_result[0]->order_status!="Cancel"){?>
	                 						<a href="china_orders.php?cancel=<?php echo $order['order_sn'];?>"class="btn btn-danger"  data-toggle="tooltip" title="Cancel Order"><i data-feather="delete"></i></a>
	                 					<?php } ?>
	                 					
	                 				</td>
							 	</tr>
						 	<?php endforeach;?>
						</tbody>
    	 			</table>
    	 		</div>
    	 	</div>
		 </div>
	</div>
</div>

<?php include 'template/footer.php'; ?>