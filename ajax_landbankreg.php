
<?php 
	if(isset($_GET['action'])){
	    $source = $_GET['action'];
	    session_start();
		
	}else{
	    $source = "";
	}
	switch($source){
        case 'lbpRegister':        		
			require_once 'model/forgot_password.php';
			$FPMod=new ForgotPasswowrd();
			$json=array();
			$result=$FPMod->landbankReg($_POST['lbpMobileNo'],$_POST['lbpPassword']);
			$json['success']=$result;
      		echo json_encode($json);            
        break;
        case 'fgivesRegister':        		
			require_once 'model/forgot_password.php';
			$FPMod=new ForgotPasswowrd();
			$json=array();
			$result=$FPMod->fgivesRegister($_POST['fgivesMobileNo'],$_POST['fgivesPassword']);
			$json['success']=$result;
			if($result['status']==200){
				$customer_id=$result['data'];
				$confirmcode=$result['confirmcode'];
				$fgivesMobileNo=$result['fgivesMobileNo'];

				require_once "include/M360Api.php";
			    $M360Api=new M360Api;
			    $credentials =$M360Api->M360Credetial();
			    $M360Domain =$M360Api->M360Domain();
			    $M360Url = $M360Domain['production'];
			    $mobileNumber='0'.$fgivesMobileNo;		  
			    $messageval = 'Your PESO Verification Code Is ' . $confirmcode;

			    $M360RequestData = array(
			        'username' => $credentials['username'],
			        'password' => $credentials['password'],
			        'msisdn' =>  $mobileNumber,
			        'content' =>  $messageval,
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
			    	$FPMod->sendVevification('0' . $fgivesMobileNo, $messageval);
			    }

			}
      		echo json_encode($json);            
        break;
        case 'manualLogin':        		
			require_once "model/user.php";	 
			$user = new User();
			$username =$_POST['username'];
		  	$email =$_POST['username'];
		  	$password=$_POST['password'];
		  	$user_found= $user->login($username,$email,$password);
		  	$result="";
		  	$status=0;		  	
		  	$customer_idval=0;		  	
		  	if($user_found){
				$result = 'Account Successfully Login'; 
				$status=200;
				$customer_idval=$user_found['customer_id'];
				
			}else{
				$result = "Your password or username are incorrect";
				$status=300;
				$customer_idval=0;
			}

			$json=array();
			$json['success']=$result;
			$json['status']=$status;
			$json['customer_id']=$customer_idval;
      		echo json_encode($json);            
        break;        
    	default:
        break;
	}

	
?>