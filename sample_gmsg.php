<?php
	$smsurl='http://152.32.92.7:8898/api';
	$cURLConnectionToken = curl_init();
	curl_setopt($cURLConnectionToken, CURLOPT_URL, $smsurl.'/token/gentoken/reizon');
	curl_setopt($cURLConnectionToken, CURLOPT_RETURNTRANSFER, true);
	$tokendata = curl_exec($cURLConnectionToken);
	curl_close($cURLConnectionToken);
    $jsonArrayResponse =json_decode($tokendata);
    $mobile = array('09959200828', '09959200828', '09959200828');
	
    $post_data_gpd = array(
	'MobileNumberList' => $mobile,
	'Message' => $_GET['message']
	);
    $tokensms = $jsonArrayResponse;
	$chsms = curl_init($smsurl.'/messages/sendMessage');
	curl_setopt($chsms, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($chsms, CURLOPT_HTTPHEADER, array(
	   'Content-Type: application/json',
	   'Authorization: Bearer ' . $tokensms
	));
	curl_setopt($chsms, CURLOPT_POST, 1);
	curl_setopt($chsms, CURLOPT_POSTFIELDS,json_encode($post_data_gpd));
	curl_setopt($chsms, CURLOPT_FOLLOWLOCATION, 1);
	$datasms = curl_exec($chsms);
	curl_close($chsms);
	echo "<pre>";
    print_r(json_decode($datasms));
?>