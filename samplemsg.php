<?php

   $cURLConnection = curl_init();
   curl_setopt($cURLConnection, CURLOPT_URL, 'http://192.168.230.187:14344/api/token/gentoken/reizon');
   curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
    $tokendata = curl_exec($cURLConnection);
   curl_close($cURLConnection);

    $jsonArrayResponse =json_decode($tokendata);
   // print_r($jsonArrayResponse);

    $post_data_gpd = array(
	'MobileNumber' => $_GET['mobile'],
	'Message' => $_GET['message']
	);

	
    $token = $jsonArrayResponse;
	//setup the request, you can also use CURLOPT_URL
	$ch = curl_init('http://192.168.230.187:14344/api/Messages/SendMessage');
	// Returns the data/output as a string instead of raw data
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//Set your auth headers
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	   'Content-Type: application/json',
	   'Authorization: Bearer ' . $token
	   ));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($post_data_gpd));
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	// get stringified data/output. See CURLOPT_RETURNTRANSFER
	$data = curl_exec($ch);
	// close curl resource to free up system resources
	curl_close($ch);
	echo "<pre>";
    print_r(json_decode($data));
?>
<script type="text/javascript">

</script>