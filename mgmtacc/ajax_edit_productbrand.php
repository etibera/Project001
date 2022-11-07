
<?php
include "model/productbrand.php";
 
if(isset($_POST['id'])&&isset( $_POST['name']) && isset($_POST['description'])){
  $name =$_POST['name'];
  $description=$_POST['description'];
  $sort_order=$_POST['sort_order'];
  $id=$_POST['id'];

  $save = new productbrand();
  $list = $save->updatebrand($id,$name, $description,$sort_order);
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