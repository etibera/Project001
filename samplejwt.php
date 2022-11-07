<?php
	$api_key="63140160d4618307dfa";
	$uuid="d62f212f-07dd-4c16-8127-4a61aa0920a8";
	$secret_key="205694efe6813862dcfcbdd4b821a911";
	$guid=uniqid();

	$header = json_encode(['alg' => 'HS256','typ' => 'JWT']);
	$payload = json_encode(['sub' => $api_key,'iat' => 1624874006,'jti' => 1624874006,]);
	$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
	$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));


	$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret_key);
	$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
	$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

		//$token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiI2MzE0MDE2MGQ0NjE4MzA3ZGZhIiwidXVpZCI6ImQ2MmYyMTJmLTA3ZGQtNGMxNi04MTI3LTRhNjFhYTA5MjBhOCJ9.kJ-qzJCE9etmAlZMLBJ4nbGJ4WvHmCKD3tXV_GEgwtE';					      
      
	print_r($jwt);
?>