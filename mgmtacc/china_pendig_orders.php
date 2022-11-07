<?php 
	include 'template/header.php'; 
	include "model/china_PO.php";
	$model = new China_PO(); 
	include "../include/china_token.php";
	$perm = $_SESSION['permission'];
	if (!strpos($perm, "'27';") !== false){
	    header("Location: landing.php");
	} 

	if(isset($_REQUEST['send_order'])){
		echo $_POST['warehouse_data_p'];
		$order_id_array = isset($_POST['chkorderid'])?$_POST['chkorderid']:"";

		if(empty($order_id_array)) {
	    	$sMsg_failed="Please Select Order Id First";  
	  	}
		if(!isset($sMsg_failed) ){
			$batch_id=$model->add_batch_order($order_id_array);
			$get_product_warehouse="";
			$goods_info_arr_temp=array();
			$goods_info_arr=array();
			$ch_total_price_ccp=0;
			foreach ($order_id_array as $order_id) {
				foreach($model->get_order_products($order_id) as $order_det){
					//$ch_total_price+=$order_det['total'];
					 $goods_info_arr_temp[]=array(
                                    'goods_sn' => $order_det['product_id'],
                                    'goods_number' => $order_det['quantity']
                    );
					$goods_sn = $order_det['product_id'];
                    $post_data_gpd = array(
                    'token' => $token_china,
                    'goods_sn' => json_encode($goods_sn)
                    );
                    $curl_gpd = curl_init('https://gloapi.chinabrands.com/v2/product/index');
                    curl_setopt($curl_gpd, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($curl_gpd, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($curl_gpd, CURLOPT_POST, 1);
                    curl_setopt($curl_gpd, CURLOPT_POSTFIELDS, $post_data_gpd);
                    $resul_gpd = curl_exec($curl_gpd); 
                    curl_close($curl_gpd);
                    $res_gpd= json_decode($resul_gpd);
                    $get_products= $res_gpd->msg; 
                    $get_products_status= $res_gpd->status; 
                    $STATUS=0;
                    $china_price_usd_ccp=0;
                    if($get_products_status){
                        $get_product_warehouse="";
                        foreach ($get_products as $product_desc) {
                            $STATUS=$product_desc->status; 
                            if($STATUS==1){ 
                            	$warehouse_code=$order_det['warehouse_code'];
                            	$china_price_usd_ccp=$product_desc->warehouse_list->$warehouse_code->price;
                                $get_product_warehouse=$warehouse_code; 
                            } 
                        }
                    }
                    $china_subtotal=$china_price_usd_ccp*$order_det['quantity'];
                    $ch_total_price_ccp+=$china_subtotal;
				}
			}
			$order = array(
                '0' => array(
                    'user_order_sn' => $batch_id,
                    'country' => 'CN',
                    'warehouse' => $get_product_warehouse,
                    'firstname' => '联系人：伍基标 ',
                    'lastname' => 'Patrick-TWR',
                    'addressline1' => '联系电话13924091288',
                    'addressline2' => '联系人：伍基标',
                    'shipping_method' => $_POST['warehouse_data_p'],
                    'tel' => '020-81997500',
                    'state' => 'china',
                    'city' => 'y广州通达物流地址：广州市白云区大朗北路72号十三社工业区A2通达仓库',
                    'zip' => '510000',
                    'order_remark' => $batch_id,
                    'original_order_id' => $batch_id,
                    'original_order_amount' => $ch_total_price_ccp,
                    'goods_info' => $goods_info_arr_temp,
                ),
            );
            $post_import_order = array(
                'token' => $token_china,
                'signature' => md5($client_secret.json_encode($order)),
                'order' => json_encode($order)
            );
            $curl_i0 = curl_init('https://gloapi.chinabrands.com/v2/order/create');
            curl_setopt($curl_i0, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl_i0, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_i0, CURLOPT_POST, 1);
            curl_setopt($curl_i0, CURLOPT_POSTFIELDS, $post_import_order);
            $result_io = curl_exec($curl_i0); 
            $res_io= json_decode($result_io);
            curl_close($curl_i0);
            $get_msg= $res_io->msg; 
          
            $final_status=$model->save_order_res_china($get_msg,$batch_id,$order_id_array);
            if($final_status==1){
            	$sMsg_success="Batch Order:".$batch_id ." Already Forwarded.";
            }else{
            	$sMsg_failed=$final_status;
            }

		}
	}
?>
<div id="content">
	<div class="page-header">
		 <h2 class="text-center">China Pending Orders</h2>
	</div>
	<form  method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
	<div class="container-fluid">
		<div class="panel panel-success">
			<div class="panel-heading" >
				<div class="row">
		 			<div class="col-lg-6">
          				<p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i> Pending Order List</p>
          			</div>
          			<div class="col-lg-6">
			            <div class="pull-right">
			                <button type="submit" class="btn btn-primary pull-right" name="send_order"  data-toggle="tooltip" title="Send Order">
			                	<i data-feather="save"></i>
			                </button>
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
				<div class="row">
					<div class="col-lg-6">
						<div class="">
							<?php $post_data_gspc = array(
							'token' => $token_china
							);
							$curl_gspc = curl_init('https://cnapi.chinabrands.com/v2/shipping/index');
							curl_setopt($curl_gspc, CURLOPT_SSL_VERIFYPEER, false);
							curl_setopt($curl_gspc, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($curl_gspc, CURLOPT_POST, 1);
							curl_setopt($curl_gspc, CURLOPT_POSTFIELDS, $post_data_gspc);
							$resul_gspc = curl_exec($curl_gspc); 
							curl_close($curl_gspc);
							$res_gspc= json_decode($resul_gspc);
							$available_warehouse_data = array();
							if($res_gspc->status){
								foreach ($res_gspc->msg as $gspc) {
									$hipname=$gspc->en_name;
								 	$ship_code=$gspc->ship_code;
								 	
									$available_warehouse=explode(",",$gspc->available_warehouse);
									if (in_array('FXXN', $available_warehouse)){
										$available_warehouse_data[] = array(
								          'ship_name'       =>  $hipname,      
								          'ship_code'       =>  $ship_code,
								          'available_warehouse'       =>  $gspc->available_warehouse     
								        );
									}
								}
							} ?>
		 					<label>Shipping Method</label>
		 					 <select name="warehouse_data_p" class="form-control" id="warehouse_data">
		 					 	 <?php foreach($available_warehouse_data as $w_data) { 
		 					 	 	 echo '<option value='.$w_data['ship_code'].' >'.$w_data['ship_name'].'</option>';
		 					 	  } ?>
		 					 </select>
						</div>
					</div>	
				</div>	</br>
				<div class="table-responsive">
    	 			<table class="table table-bordered table-hover">
    	 				<thead >	
							<th class="text-left" colspan="2">Product ID</th>
							<th class="text-left">Name</th>						
							<th class="text-left">Model</th>						
							<th class="text-left">Quantity</th>						
						</thead>
						<tbody>
						 	<?php if(!$model->get_china_PO()){?>
							 	<tr>
							 		<td class="text-center" colspan="5">**No Da Found**</td>
							 	</tr>
						 	<?php }?>

						 	<?php foreach($model->get_china_PO() as $PO): ?>
						 		<tr>
						 			<td class="text-left" colspan="5"><input type="checkbox" name="chkorderid[]" value="<?php echo $PO['order_id'];?>" checked/> <label> Order Id: <?php echo $PO['order_id'];?></label></td>
						 		</tr>
						 		<?php foreach($model->get_order_products($PO['order_id']) as $PO_details){ ?>
						 			<tr>
						 				<td class="text-left" colspan="2"><?php echo $PO_details['product_id'];?></td>
						 				<td class="text-left" ><?php echo $PO_details['name'];?></td>
						 				<td class="text-left" ><?php echo $PO_details['model'];?></td>
						 				<td class="text-left" ><?php echo $PO_details['quantity'];?></td>
						 			</tr>
						 		<?php }?>
						 	
						 	<?php endforeach;?>
						</tbody>
    	 			</table>
    	 		</div>
			</div>
		</div>
	</div>
	</form>
</div>
<?php include 'template/footer.php'; ?>