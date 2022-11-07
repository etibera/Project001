
<?php
include "model/productbrand.php";
 
if(isset($_POST['name']) && isset($_POST['description'])){
  $name =$_POST['name'];
  $description=$_POST['description'];
  $sort_order=$_POST['sort_order'];


  $save = new productbrand();
  $list = $save->savebrand($name, $description,$sort_order);
  $json = array();
	if($list=="200"){
		$json['success'] ="Successfully Saved.";
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