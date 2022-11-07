 <?php

    require_once "include/M360Api.php";
    $M360Api=new M360Api;
    $credentials =$M360Api->M360Credetial();
    $M360Domain =$M360Api->M360Domain();
    $M360Url = $M360Domain['production'];
    $mobileNumber='09562841199';
    //$mobileNumber='09773759742';
    $content='Babu sorry ah awan load kun pa load nak man 09959200828 dadail may net mi awan pay ajay agsimpa tas inngana ita awan pay ni sir orly su awan net mi babu';
  /*  $confirmcode = rand(100000, 999999);
    $messageval = 'Your PESO Verification Code Is ' . $confirmcode;*/

    $M360RequestData = array(
        'username' => $credentials['username'],
        'password' => $credentials['password'],
        'msisdn' =>  $mobileNumber,
        'content' =>  $content,
        'shortcode_mask' => $credentials['shortcode_mask'],
    );
    $M360sms = curl_init($M360Url);
    curl_setopt($M360sms, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($M360sms, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json'     
    ));
    curl_setopt($M360sms, CURLOPT_POST, 1);
    curl_setopt($M360sms, CURLOPT_POSTFIELDS,json_encode($M360RequestData));
    curl_setopt($M360sms, CURLOPT_FOLLOWLOCATION, 1);
    $dataM360sms = curl_exec($M360sms);
    curl_close($M360sms);
    $ResponsedataM360 = json_decode($dataM360sms);
    if($ResponsedataM360->code=="400"){
        //sms fail

    }
     echo "<pre>";
        print_r($ResponsedataM360);
   /* if($ResponsedataM360->code=="201"){
        echo "<pre>";
        print_r($ResponsedataM360);
    }else{
        echo "<pre>";
        print_r($ResponsedataM360);

    }*/
   
?>