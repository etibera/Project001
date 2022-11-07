
<?php
include "model/cash_wallet.php";
 
if(isset($_POST['cashtype']) && isset($_POST['accname']) && isset($_POST['accnumber']) && isset($_POST['amount'])) {
  $amount =$_POST['amount'];
  $accname=$_POST['accname'];
  $accnumber=$_POST['accnumber'];
  $cashtype=$_POST['cashtype'];
  $id=$_POST['id'];
  
  $cashout = new cash_wallet();

  $walletamount=  $cashout->gettotalwallet($id);

  if ($amount > $walletamount){
  		$json['success'] ="Invalid Amount.";
  }else{

  $list = $cashout->cashout($amount, $accname, $accnumber, $cashtype,$id);
  $json = array();
	if($list=="200"){
		$json['success'] ="Successfully Updated.";
	}else{
		$json['success'] ="Error Occured.";
	}
	}

    echo json_encode($json)  ;       
	
	
}else{
	$json = array();
	$json['success'] ="Error Occured.";
	echo json_encode($json)  ; 
}	
?>