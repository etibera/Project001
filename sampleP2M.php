<?php
    require_once "include/p2mApi.php";   

 //   ///1.	Request QR “PESO-QR-REQ”
 //    echo '---------------------------1. Request QR “PESO-QR-REQ”---------------------------------------<br>';
 //    $p2mApi=new p2mApi;
 //    $credentials =$p2mApi->getp2mUAT();
 //    $p2mToken =$p2mApi->p2mToken();
 //    $p2mDomain =$p2mApi->p2mDomain();
 //    echo"<pre>";
 //    print_r($credentials);
 //    print_r($p2mToken);
 //    print_r($p2mDomain);

 //    $p2mUrl = $p2mDomain['production'];
 //    $amount = '1000';
 //    $order_id = '1234';
  
 //    $input_xml = "<Envelope xmlns='http://schemas.xmlsoap.org/soap/envelope/'>\r\n    <Body>\r\n        <wb_Get_Info xmlns='http://tempuri.org/'>\r\n            <XMLRequest>\r\n                &lt;Account.Info \r\n                        id='".$credentials['ClientID'] ."' tdt='".$p2mToken['date'] ."'  token='".$p2mToken['token'] ."' \r\n                \t\tcmd='".$credentials['cmdQR'] ."'\r\n                        rf='". $order_id."'\r\n                        amt='". $amount."'\r\n                        merc_tid=\"0\"\r\n                /&gt;\r\n            </XMLRequest>\r\n        </wb_Get_Info>\r\n    </Body>\r\n</Envelope>\r\n\r\n\r\n\r\n\r\n";					
      
 //    $p2mCurl = curl_init($p2mUrl);
 //    curl_setopt($p2mCurl, CURLOPT_RETURNTRANSFER, true);
 //    curl_setopt($p2mCurl, CURLOPT_HTTPHEADER, array(
 //        'Content-Type: text/xml' ,    
 //        'SoapAction: http://tempuri.org/iWebInterface/wb_Get_Info' ,    
 //    ));
 //    curl_setopt($p2mCurl, CURLOPT_POST, 1);
 //    curl_setopt($p2mCurl, CURLOPT_POSTFIELDS, $input_xml);
 //    curl_setopt($p2mCurl, CURLOPT_FOLLOWLOCATION, 1);
 //    $datap2mCurl = curl_exec($p2mCurl);
 //    curl_close($p2mCurl);

	// $data1=str_replace("&lt;","<",$datap2mCurl);
	// $data2=str_replace("&gt;",">",$data1);
	// $sample="Account.Info";

	// $xml = simplexml_load_string(utf8_encode($data2));
	// $data = $xml->xpath("//s:Body/*")[0];
	// $details = $data->children("http://tempuri.org/");
	// $attrs = $details->wb_Get_InfoResult->$sample->attributes();
	// echo 'merc_token:'.$attrs["merc_token"].'<br><br>';
	// echo 'qrph:'.$attrs["qrph"].'<br>';
	
   ///2.	Check for Payment “PESO-PAY-CHK”
	echo '---------------------------2.	Check for Payment “PESO-PAY-CHK”---------------------------------------<br>';
    $p2mApi2=new p2mApi;
    $credentials2 =$p2mApi2->getp2mUAT();
    $p2mToken2 =$p2mApi2->p2mToken();
    $p2mDomain2 =$p2mApi2->p2mDomain();

    echo"<pre>";
    print_r($credentials2);
    print_r($p2mToken2);
    print_r($p2mDomain2);

	$merc_token='3294813723946166';//$attrs["merc_token"];
	$p2mUrlCFP=$p2mDomain2['production'];
	$xmlPOSTFIELDS = "<Envelope xmlns='http://schemas.xmlsoap.org/soap/envelope/'>\r\n    <Body>\r\n        <wb_Get_Info xmlns='http://tempuri.org/'>\r\n            <XMLRequest>\r\n                &lt;Account.Info \r\n                        id='".$credentials2['ClientID'] ."' tdt='".$p2mToken2['date'] ."'  token='".$p2mToken2['token'] ."' \r\n                \t\tcmd='".$credentials2['cmdPayment']."'\r\n                        merc_token='".$merc_token."'\r\n                /&gt;\r\n            </XMLRequest>\r\n        </wb_Get_Info>\r\n    </Body>\r\n</Envelope>";	
	$p2mCFP = curl_init($p2mUrlCFP);
    curl_setopt($p2mCFP, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($p2mCFP, CURLOPT_HTTPHEADER, array(
        'Content-Type: text/xml' ,    
        'SoapAction: http://tempuri.org/iWebInterface/wb_Get_Info' ,    
    ));
    print_r($xmlPOSTFIELDS);
    curl_setopt($p2mCFP, CURLOPT_POST, 1);
    curl_setopt($p2mCFP, CURLOPT_POSTFIELDS, $xmlPOSTFIELDS);
    curl_setopt($p2mCFP, CURLOPT_FOLLOWLOCATION, 1);
    $Datap2mCFP = curl_exec($p2mCFP);
    curl_close($p2mCFP);

	$dataCFP=str_replace("&lt;","<",$Datap2mCFP);
	$dataCFP2=str_replace("&gt;",">",$dataCFP);
	$AiCFP="Account.Info";

	$xmlCFP = simplexml_load_string(utf8_encode($dataCFP2));
	$xmldatCFP = $xmlCFP->xpath("//s:Body/*")[0];
	$detailsCFP = $xmldatCFP->children("http://tempuri.org/");
	$attrsCFP = $detailsCFP->wb_Get_InfoResult->$AiCFP->attributes();
	echo 'merc_token:'.$attrsCFP["merc_token"].'<br><br>';
	echo 'ReturnCode:'.$attrsCFP["ReturnCode"].'<br><br>';
	echo 'ErrorMsg:'.$attrsCFP["ErrorMsg"].'<br><br>';

	/*var_dump($detailsCFP);
	var_dump($attrsCFP);*/

 ?>