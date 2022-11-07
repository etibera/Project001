<?php 
	if(isset($_GET['action'])){
	    $source = $_GET['action'];
	    session_start();
		$id = $_SESSION['user_login'];
	}else{
	    $source = "";
	}
	switch($source){
        case 'addsendFBacct':        		
			include "model/generate.php";
       		$model=new generate();
       		$nolingtobegen=0;
			$noOflink=0;
       		$noOflink=$model->countLinks($id) ;
       		$nolingtobegen=100-$noOflink;
       		//var_dump($nolingtobegen);
       		if($nolingtobegen<=0){
			}else{
				for ($i = 0; $i < $nolingtobegen; $i++) {
				$model->generateMore($id);
			   }
			}
			$json['success'] =$id;
      		echo json_encode($json);            
        break;
    	default:
        break;
	}
?>