<?php
include "model/reviews.php";

if(isset($_GET['trigger'])){
 
  
$review = new Reviews();
  
  $data = array();
  
  foreach ($review->getreviews() as $row) {
	 $data[] = array(
	                    'model' => $row['model'],
                		'product_id' => $row['product_id']

	                );                     
  }
  echo json_encode($data);
	
	
}else{
	$json = array();
	$json['success'] ="Error Occured.";
	echo json_encode($json)  ; 
}	
?>