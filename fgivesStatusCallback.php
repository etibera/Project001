<?php 
$rawPostData = file_get_contents('php://input');
$jsonData = json_decode($rawPostData);
$merc_token=$jsonData->merc_token;
if(isset($merc_token)){
    require_once "model/credit_card.php";
    require_once "model/checkoutLatest.php";  
    $checkout = new CheckoutLatest();
    $model_CR= new credit_card();
    require_once "include/p2mApi.php"; 
    $p2mApi2=new p2mApi;
    $order_id=0;
    $p2mData=$checkout->getp2mData($merc_token);
    if(count($p2mData)){
        $order_id=$p2mData['order_id'];
        $customerDet=$checkout->getCustomerDet($order_id);
    } 
    $credentials2 =$p2mApi2->getp2mUAT();
    $p2mToken2 =$p2mApi2->p2mToken();
    $p2mDomain2 =$p2mApi2->p2mDomain();

    $p2mUrlCFP=$p2mDomain2['production'];
    $xmlPOSTFIELDS = "<Envelope xmlns='http://schemas.xmlsoap.org/soap/envelope/'>\r\n    <Body>\r\n        <wb_Get_Info xmlns='http://tempuri.org/'>\r\n            <XMLRequest>\r\n                &lt;Account.Info \r\n                        id='".$credentials2['ClientID'] ."' tdt='".$p2mToken2['date'] ."'  token='".$p2mToken2['token'] ."' \r\n                \t\tcmd='".$credentials2['cmdPayment']."'\r\n                        merc_token='".$merc_token."'\r\n                /&gt;\r\n            </XMLRequest>\r\n        </wb_Get_Info>\r\n    </Body>\r\n</Envelope>"; 
    $p2mCFP = curl_init($p2mUrlCFP);
    curl_setopt($p2mCFP, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($p2mCFP, CURLOPT_HTTPHEADER, array(
        'Content-Type: text/xml' ,    
        'SoapAction: http://tempuri.org/iWebInterface/wb_Get_Info' ,    
    ));
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
   
    
    $remarks="";
    $jcode="";
    if($attrsCFP["ReturnCode"]=="0"){
        //lanbank pay Successful
        $model_CR->addOrderHistory($order_id); 
        $remarks=$attrsCFP["ErrorMsg"];
         $jcode="200";
        
    }else{
         // lanbank pay Unsuccessful      
        $remarks=$attrsCFP["ErrorMsg"]; 
        $jcode="300";
    }
    $json['remarks'] =$remarks;
    $json['Status_code'] =$jcode;
    $json['order_id'] =$order_id;
    $json['customer_name'] =$customerDet['firstname'].' '.$customerDet['lastname'];
    echo json_encode($json)  ; 

  }



 

?>