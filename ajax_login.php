
<?php
if(isset($_GET['action'])){
    $source = $_GET['action'];
}else{
    $source = "";
}
switch($source){
    case 'login':        		
		require_once("include/init.php");
		require_once "model/user.php";		
		$user = new User();
		if(isset($_POST['username']) && isset($_POST['password'])) {
			$username =$_POST['username'];
		  	$email =$_POST['username'];
		  	$password=$_POST['password'];
		  	$user_found= $user->login($username,$email,$password);
		  	if($user_found){
				$session->login($user_found);
				$user->insertactivity($user_found['customer_id'],'login');
				$json['success']= "Successfully Login...";
			}else{
				$json['success']= "Your password or username are incorrect";
			}	
			echo json_encode($json)  ;      
			
		}else{
			$json = array();
			$json['success'] ="Error Occured.";
			echo json_encode($json)  ; 
		}	      
    break;     
	default:
    break;
}
?>