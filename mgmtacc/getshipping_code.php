<?php 
include "../include/china_token.php";
 $post_data_gspc = array(
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
	/*echo '<pre>';
	print_r($res_gspc);*/
	$available_warehouse_data = array();
	if($res_gspc->status){
		foreach ($res_gspc->msg as $gspc) {
			$hipname=$gspc->en_name;
		 	$ship_code=$gspc->ship_code;
		 	$status=$gspc->status;
		 	// if($status==1){
		 	// 	$available_warehouse_data[] = array(
		  //         'ship_name'       =>  $hipname,      
		  //         'ship_code'       =>  $ship_code      
		  //       );
		 	// }
		 	/*if (strpos($gspc->available_warehouse, 'SZXIAWAN') !== false) {
				   $available_warehouse_data[] = array(
		          'ship_name'       =>  $hipname,      
		          'ship_code'       =>  $ship_code,      
		          'available_warehouse'       =>  $gspc->available_warehouse 
		        );
			}*/
		 	$available_warehouse=explode(",",$gspc->available_warehouse);
			if (in_array('FXXN', $available_warehouse)){
				$available_warehouse_data[] = array(
		          'ship_name'       =>  $hipname,      
		          'ship_code'       =>  $ship_code,
		          'available_warehouse'       =>  $gspc->available_warehouse     
		        );
			}
		}
	}
	echo '<pre>';
	print_r($available_warehouse_data);
	


?>