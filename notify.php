<?php
include "model/credit_card.php";
$model_CR= new credit_card();
if(isset($_POST['paymentresponse'])){
	$body = $_POST['paymentresponse'];
	$base64 = str_replace(" ", "+", $body);
	$body = base64_decode($base64); // this will be the actual xml
	$data = simplexml_load_string($body);
	var_dump($data);
	$forSign = $data->application->merchantid . $data->application->request_id . $data->application->response_id . $data->responseStatus->response_code . $data->responseStatus->response_message . $data->responseStatus->response_advise . $data->application->timestamp . $data->application->rebill_id;
	$cert = "FD96B5543476FB7D904B8A21C3993531"; //<-- your merchant key live
	//$cert = "D6ED2D5E1EA172BF0272DF675301FCAA"; //<-- your merchant key
	$_sign = hash("sha512", $forSign . $cert);
	if ($data->application->signature == $_sign) {		
		$merchantid = $data->application->merchantid;
		$request_id = $data->application->request_id;
		$response_id = $data->application->response_id;
		$response_code = $data->responseStatus->response_code;
		$response_message = $data->responseStatus->response_message;
		$response_advise = $data->responseStatus->response_advise;
		$timestamp = $data->application->timestamp;
		$rebill_id = $data->application->rebill_id;
		$signature = $data->application->signature;

		$model_CR->savepaymentresponse($merchantid,$request_id,$response_id,$response_code,$response_message,$response_advise,$timestamp,$rebill_id,$signature);

		if($response_code == 'GR001' || $response_code == 'GR002'){
			$model_CR->addOrderHistory($request_id); 
			// $order_info = $model_CR->order_details($request_id);
   // 	 		$opch=0;
   // 	 		$subtotal=0;
   // 	 		$total=0;
   // 	 		foreach ($order_info['total'] as $totals):
   // 	 			if($totals['title']=="Sub-Total"){
   // 	 				$subtotal+=$totals['value'];
   // 	 			}		       	 			
   // 	 			if($totals['title']=="Total"){
   // 	 				$total+=$totals['value'];
   // 	 			}					       
		 //    endforeach;
		 //    $opch=$subtotal*0.028;
		 //    $total+=$opch;	
		 //    $model_CR->updatetotals($request_id,$opch,$total);   
          	
		}

	}
}

?>