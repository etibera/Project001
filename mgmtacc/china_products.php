<?php 
	$BASE="USD";
	$CNTO="PHP";
	$curl_exchangerates = curl_init('https://api.exchangeratesapi.io/latest?base=USD');//curl_init('https://api.exchangeratesapi.io/latest?base='.$BASE.'&symbols='.$CNTO);
	curl_setopt($curl_exchangerates, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl_exchangerates, CURLOPT_RETURNTRANSFER, 1);
	$result_excr = curl_exec($curl_exchangerates); 
	curl_close($curl_exchangerates);
	$res_excr= json_decode($result_excr);
	$RATE=$res_excr->rates->{$CNTO};


	include 'template/header.php'; 
	include "model/Specification.php";
	$model = new Specification(); 
	$perm = $_SESSION['permission'];
	if (!strpos($perm, "'25';") !== false){
	    header("Location: landing.php");
	}   
	if(isset($_GET['prod_del'])){
		$delete=$model->delete_china_products($_GET['prod_del']);
		if($delete=="200"){
		 $sMsg="Successfully Deleted China Products"; 
		}
	}
	if(isset($_GET['prod_disable'])){
		$delete=$model->china_products_disable($_GET['prod_disable']);
		if($delete=="200"){
		 $sMsg="Successfully Disabled"; 
		}
	}
	if(isset($_GET['prod_enable'])){
		$delete=$model->china_products_enable($_GET['prod_enable']);
		if($delete=="200"){
		 $sMsg="Successfully Enabled"; 
		}
	}
	if(isset($_GET['download'])){
		$savesuccess=0;
		include "../include/china_token.php";
		$post_data_gdl = array(
		'token' =>$token_china,
		'type' => 0,
		'per_page' => 50,
		);
		$curl_gdl = curl_init('https://gloapi.chinabrands.com/v2/user/inventory');
		curl_setopt($curl_gdl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl_gdl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_gdl, CURLOPT_POST, 1);
		curl_setopt($curl_gdl, CURLOPT_POSTFIELDS, $post_data_gdl);
		$result_gdl = curl_exec($curl_gdl); 
		curl_close($curl_gdl);
		$res_gdl= json_decode($result_gdl);
		//var_dump($res_gdl);
		// echo "<pre>";
		// print_r($res_gdl);

		$stats_gdl=$res_gdl->status;
		
		$page_result="";
		if($stats_gdl){
			$total_records=$res_gdl->msg->total_records;
			$total_pages=$res_gdl->msg->page_number;
			$per_page=$res_gdl->msg->per_page;
			$return_count=$res_gdl->msg->return_count;
			$page_result=$res_gdl->msg->page_result;
			$goods_sn = "";
			foreach ($page_result as $data) {
				$goods_sn = $data->goods_sn;
				$parent_sn   = $data->parent_sn  ;
				$is_tort   = $data->is_tort;
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
				//var_dump($res_gpd);
				$get_products= $res_gpd->msg; 
				$get_products_status= $res_gpd->status; 
				
				//print_r($res_gpd);
				if($get_products_status){
					foreach ($get_products as $product) {
						$STATUS=$product->status;
						if($STATUS==1){
							$china_price_usd=0;
							$get_product_img= $product->original_img[0]; 
							$get_product_title= $product->title;
							$warehouse_list= $product->warehouse_list;		
							$sku= $product->sku;
							foreach ($warehouse_list as $warehouse_p) { 
								$china_price_usd=$warehouse_p->price; 
							} //var_dump($china_price_usd);
							$save=$model->save_china_products($goods_sn,$parent_sn,$get_product_title,$get_product_img,$sku,$china_price_usd);
							if($save=="200"){
								$savesuccess++;
							 
							}
						}
					}
				}
			}
		}else{
			$errorr_msg=$res_gdl->msg;
		}

		if($savesuccess){
			$sMsg="Successfully Downloaded China Products QTY(".$savesuccess.")"; 
		}
	}
	
?>
<div id="content">
	<div class="page-header">
		 <h2 class="text-center">China Brands</h2>
	</div>
	<?php if(isset($sMsg)){ ?>
	    <div class="alert alert-success">
	        <strong><?php echo $sMsg;?></strong></br>
	    </div>
	<?php } ?>
	<?php if(isset($errorr_msg)){ ?>
	    <div class="alert alert-danger">
	        <strong><?php echo $errorr_msg;?></strong></br>
	    </div>
	<?php } ?>
	<div class="container-fluid">
		 <div class="panel panel-success">
		 	<div class="panel-heading" style="padding:20px;">
		 		<div class="row">
          			<div class="col-lg-6">
          				<p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i>Product List</p>
          			</div>
          			<div class="col-lg-6">
			            <div class="pull-right">
			                <a href="china_products.php?download=1"class="btn btn-primary pull-right"  data-toggle="tooltip" title="Get Download List"><i data-feather="download"></i></a>
			            </div>
			        </div>
          		</div>
		 	</div>
		 	<div class="panel-body">
    	 		<div class="table-responsive">
    	 			<table class="table table-bordered table-hover">
    	 				<thead>
							<th>Image </th>
							<th>Product SN</th>
							<th>Title </th>
							<th>Status </th>
							<th>Action </th>
						</thead>
						 <tbody>
						 	<?php foreach($model->get_china_products() as $product_desc): ?>
						 		<tr>
						 			<td class="text-left" ><img src="<?php echo $product_desc['product_img']; ?>"  class="img-responsive" style="width: 80px; width: 80px;" /></td>
						 			<td class="text-left" ><?php echo $product_desc['goods_sn'];?></td>
						 			<td class="text-left" ><?php echo $product_desc['product_title'];?></td>						 			
						 			<td><?php if($product_desc['status'] == '1') { 
								 			echo '<span style="color:green;">Enabled</span>'; 
								 		} else {
								 			echo '<span style="color:red;">Disabled</span>';
								 		}?>
								 	</td>
								 	<td> 
								 		<a href="china_products.php?prod_del=<?php echo $product_desc['id'];?>" class="btn btn-danger btn-edit" data-product_id="<?php echo $product_desc['prod_id'];?>" title="Delete" ><i data-feather="trash-2"></i></a>
								 		<?php if($product_desc['status'] == '1') { ?>
								 			<a href="china_products.php?prod_disable=<?php echo $product_desc['id'];?>" class="btn btn-warning btn-edit" data-product_id="<?php echo $product_desc['prod_id'];?>" title="Disable"><i data-feather="x-circle"></i></a>
								 			
								 		<?php } else { ?>
								 			<a href="china_products.php?prod_enable=<?php echo $product_desc['id'];?>" class="btn btn-primary btn-edit" data-product_id="<?php echo $product_desc['prod_id'];?>" title="Enable"><i data-feather="check"></i></a>
								 		<?php }?>
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