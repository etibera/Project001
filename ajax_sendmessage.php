
<?php
include "model/invitees.php";
 
if(isset($_POST['userid']) && isset($_POST['msg'])&& isset($_POST['cid'])){
  $userid =$_POST['userid'];
  $msg=$_POST['msg'];
  $cid=$_POST['cid'];

  $send = new Invitees();
  $list = $send->sendmessage($userid, $msg, $cid);
  $json = array();
	if($list=="200"){
		$json['success'] ="Successfully Send.";
	}else{
		$json['success'] ="Error Occured.";
	}
    echo json_encode($json)  ;             
	
	
}else{
	$json = array();
	$json['success'] ="Error Occured.";
	echo json_encode($json)  ; 
}	
?>