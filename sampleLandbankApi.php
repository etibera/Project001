<?php
require_once "include/landBankApi.php";
$LandBank=new LandBank;
$credentials =$LandBank->getLandBankUAT();
$LanBankDomain =$LandBank->LanBankDomain();
$LandBankUrl = $LanBankDomain['sandbox'];

//get token
$token="";
$tokenRequestData = array(
	'clientId' => $credentials['ClientID'],
	'secret' => $credentials['Secret'],
);
$lburltoken = curl_init($LandBankUrl.'/api/Ecommerce/Token');
curl_setopt($lburltoken, CURLOPT_RETURNTRANSFER, true);
curl_setopt($lburltoken, CURLOPT_HTTPHEADER, array(
	'Content-Type: application/json',
	'Identity: 6d5a74c940694d668aaaae6b402b4ee71cca906bc6ce48c39002fae9d72ae384-IDTK45E291-112701'
));
curl_setopt($lburltoken, CURLOPT_POST, 1);
curl_setopt($lburltoken, CURLOPT_POSTFIELDS,json_encode($tokenRequestData));
curl_setopt($lburltoken, CURLOPT_FOLLOWLOCATION, 1);
$datalburltoken = curl_exec($lburltoken);
curl_close($lburltoken);
$Responsedatatoken = json_decode($datalburltoken);


$token=$Responsedatatoken->body->token;
echo "<pre>";
print_r($Responsedatatoken);
//Create Order	
$MobileNumber='9176523353';
$amount=floatval(1500);
$orderRequestData = array(
	'clientId' => $credentials['ClientID'],
	'secret' => $credentials['Secret'],
	'customerMobileNumber' => $MobileNumber,
	'amount' => $amount,
	'requestOrderNumber1' => "orderTest002",
 	'requestOrderNumber2' =>"",
 	'requestOrderNumber3'=> ""
);
print_r(json_encode($orderRequestData));
$lburl = curl_init($LandBankUrl.'/api/PaymentGateway/Order');
curl_setopt($lburl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($lburl, CURLOPT_HTTPHEADER, array(
	'Content-Type: application/json',
	'Authorization: Bearer ' . $token
));
curl_setopt($lburl, CURLOPT_POST, 1);
curl_setopt($lburl, CURLOPT_POSTFIELDS,json_encode($orderRequestData));
curl_setopt($lburl, CURLOPT_FOLLOWLOCATION, 1);
$datalburl = curl_exec($lburl);
//statuscode
$httpcode = curl_getinfo($lburl);
curl_close($lburl);
$Responsedata = json_decode($datalburl);
print_r($Responsedata);
echo "<br>adxfcgnvhj,kjl<br>";
print_r($httpcode);
if($Responsedata->code=="200"){
	$orderId=$Responsedata->body->orderId;
	$url=$Responsedata->body->url;
	$code=$Responsedata->body->code;
	$remarks=$Responsedata->body->remarks;
}else{
	$orderId="";
	echo "<br><br>".$Responsedata->description;
}
echo "<br><br>Order Id: ".$orderId.'<br>';
echo "Url: ".$url.'<br>';
echo "code : ".$code.'<br>';
echo "remarks : ".$remarks.'<br>';


//get moblie stats
$MobileNumber2='9171123183';
$MobileRequestData = array(
	'clientId' => $credentials['ClientID'],
	'secret' => $credentials['Secret'],
	'mobileNumber' => $MobileNumber2,	
);
$lburl2 = curl_init($LandBankUrl.'/api/Customer/Get');
curl_setopt($lburl2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($lburl2, CURLOPT_HTTPHEADER, array(
	'Content-Type: application/json',
	'Authorization: Bearer ' . $token
));
curl_setopt($lburl2, CURLOPT_POST, 1);
curl_setopt($lburl2, CURLOPT_POSTFIELDS,json_encode($MobileRequestData));
curl_setopt($lburl2, CURLOPT_FOLLOWLOCATION, 1);
$datalburl2 = curl_exec($lburl2);
curl_close($lburl2);
$Responsedata2 = json_decode($datalburl2);
print_r($Responsedata2);

//Get Order Status$quadxdata->tracking_number
$OrderStatusRequestData = array(
	'clientId' => $credentials['ClientID'],
	'secret' => $credentials['Secret'],
	'orderId' => $orderId,	
);
$lburl3 = curl_init($LandBankUrl.'/api/PaymentGateway/OrderStatus');
curl_setopt($lburl3, CURLOPT_RETURNTRANSFER, true);
curl_setopt($lburl3, CURLOPT_HTTPHEADER, array(
	'Content-Type: application/json',
	'Authorization: Bearer ' . $token
));
curl_setopt($lburl3, CURLOPT_POST, 1);
curl_setopt($lburl3, CURLOPT_POSTFIELDS,json_encode($OrderStatusRequestData));
curl_setopt($lburl3, CURLOPT_FOLLOWLOCATION, 1);
$datalburl3 = curl_exec($lburl3);
curl_close($lburl3);
$Responsedata3 = json_decode($datalburl3);
print_r($Responsedata3);
?>


