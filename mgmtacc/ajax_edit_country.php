
<?php
include "model/country.php";
 
if(isset($_POST['id'])&&isset( $_POST['name']) && isset($_POST['iso2']) && isset($_POST['iso3'])){
  $name =$_POST['name'];
  $iso2=$_POST['iso2'];
  $iso3=$_POST['iso3'];
  $id=$_POST['id'];

  $save = new country();
  $list = $save->updatecountry($id,$name,$iso2, $iso3);
  $json = array();
	if($list=="200"){
		$json['success'] ="Successfully Updated.";
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