<?php
include "model/ProductViewed.php";
  
  $viewed = new ProductViewed();
  $stats = $viewed->product_viewed_reset();
	$json['success'] = $stats;
	echo json_encode($json);       
	
?>