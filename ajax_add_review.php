
<?php
include "model/user_review.php";
 
if(isset($_POST['author']) && isset($_POST['product'])&& isset($_POST['status'])&&  isset($_POST['ratings'])&&  isset($_POST['customerid'])&&  isset($_POST['desc'])){
  $author =$_POST['author'];
  $product=$_POST['product'];
  $status=$_POST['status'];
  $ratings=$_POST['ratings'];
  $customerid=$_POST['customerid'];
  $desc=$_POST['desc'];


  $save = new UserReviews();
  $list = $save->savereview($author, $product, $status,$ratings,$customerid,$desc);
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