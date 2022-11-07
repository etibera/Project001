
<?php
include "model/reviews.php";
 
if(isset($_POST['author']) && isset($_POST['product'])&& isset($_POST['status'])&&  isset($_POST['ratings'])&&  isset($_POST['customerid'])&&  isset($_POST['desc'])&&  isset($_POST['reviewid'])){
  $author =$_POST['author'];
  $product=$_POST['product'];
  $status=$_POST['status'];
  $ratings=$_POST['ratings'];
  $customerid=$_POST['customerid'];
  $desc=$_POST['desc'];
  $reviewid=$_POST['reviewid'];


  $save = new Reviews();
  $list = $save->updatereview($author, $product, $status,$ratings,$customerid,$desc,$reviewid);
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