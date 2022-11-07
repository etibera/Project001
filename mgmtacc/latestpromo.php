<?php
include 'template/header.php'; 
require_once "model/homecategory.php";
$edit_id=0;
$model=new homecategory();	
$latestPromoList=$model->getLatesPromolist();
// $perm = $_SESSION['permission'];
// if (!strpos($perm, "'3';") !== false){
//     header("Location: landing.php");
   
// } 
if(isset($_GET['lp_delete'])){
	$delete=$model->lp_delete($_GET['lp_delete']);
	if($delete=="200"){
	 $sMsg="Successfully Deleted";
	 $latestPromoList=$model->getLatesPromolist(); 
	}
	
}

if(isset($_GET['lp_disable'])){
	$lp_disable=$model->lp_disable($_GET['lp_disable']);
	if($lp_disable=="200"){
	 $sMsg="Successfully Disabled";
	 $latestPromoList=$model->getLatesPromolist(); 
	}
}
if(isset($_GET['lp_enable'])){
	$lp_enable=$model->lp_enable($_GET['lp_enable']);
	if($lp_enable=="200"){
	 $sMsg="Successfully Enabled";
	 $latestPromoList=$model->getLatesPromolist(); 
	}

}
if(isset($_REQUEST['lp_save'])) {
	if($_POST['imageedit']=="0"){
		$target_dir = isset($_SERVER['HTTPS']) ? '/home/pesoappdadmin/public_html/img/latest_promo/' : "c://xampp/htdocs/peso-web-new/img/latest_promo/";
		if (isset( $_FILES["fileToUpload"] ) && !empty( $_FILES["fileToUpload"]["name"])) {
			$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	    	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	    	$img="/latest_promo/".basename($_FILES["fileToUpload"]["name"]);
			$imagefile=basename($_FILES["fileToUpload"]["name"]);
			if(empty($imagefile)){
		        $errorMsg[]=array('name' => "Please Select Image");        
		    }else{
		        if (file_exists($target_file)) {
		          $errorMsg[]=array('name' => "Sorry, image already exists.");      
		        } 
		        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		        if($check !== false) {} else {
		            $errorMsg[]=array('name' => "File is not an image."); 
		        } 
		        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		            && $imageFileType != "gif" ) {
		            $errorMsg[]=array('name' => "Sorry, only JPG, JPEG, PNG & GIF files are allowed."); 
		        } 
		    }
		    if(!isset($errorMsg) ){
		    	move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
		    }
		}else{
			$errorMsg[]=array('name' => "Please Select Image");
		}
		if (isset( $_FILES["fileToUpload_promo_title_image"] ) && !empty( $_FILES["fileToUpload_promo_title_image"]["name"])){
			$target_file_PtitleImg = $target_dir . basename($_FILES["fileToUpload_promo_title_image"]["name"]);
	    	$imageFileType_PtitleImg = strtolower(pathinfo($target_file_PtitleImg,PATHINFO_EXTENSION));
	    	$promo_title_image="/latest_promo/".basename($_FILES["fileToUpload_promo_title_image"]["name"]);
			$imagefile_PtitleImg=basename($_FILES["fileToUpload_promo_title_image"]["name"]);
			if(empty($target_file_PtitleImg)){
	    		$promo_title_image=$_POST['imageedit_promotitle']; 
		       //$errorMsg[]=array('name' => "Please Select Thumbnail Image ");        
		    }else{
		        if (file_exists($imageFileType_PtitleImg)) {
		          $errorMsg[]=array('name' => "Sorry, Thumbnail image already exists.");      
		        } 
		        $check_PtitleImg = getimagesize($_FILES["fileToUpload_promo_title_image"]["tmp_name"]);
		        if($check_PtitleImg !== false) {} else {
		            $errorMsg[]=array('name' => "File is not an image."); 
		        } 
		        if($imageFileType_PtitleImg != "jpg" && $imageFileType_PtitleImg != "png" && $imageFileType_PtitleImg != "jpeg"
		            && $imageFileType_PtitleImg != "gif" ) {
		            $errorMsg[]=array('name' => "Sorry, only JPG, JPEG, PNG & GIF files are allowed."); 
		        } 
		    }
			if(!isset($errorMsg) ){
		    	move_uploaded_file($_FILES["fileToUpload_promo_title_image"]["tmp_name"], $target_file_PtitleImg);
		    }	
		}else{
			$promo_title_image=""; 
		}
		
		if (isset( $_FILES["fileToUpload_thumbnail"] ) && !empty( $_FILES["fileToUpload_thumbnail"]["name"])) {
			$target_file_tumb = $target_dir . basename($_FILES["fileToUpload_thumbnail"]["name"]);
	    	$imageFileType_tumb = strtolower(pathinfo($target_file_tumb,PATHINFO_EXTENSION));
	    	$img_tumb="/latest_promo/".basename($_FILES["fileToUpload_thumbnail"]["name"]);
			$imagefile_tumb=basename($_FILES["fileToUpload_thumbnail"]["name"]);
			if(empty($target_file_tumb)){
	    		$img_tumb=$_POST['imageedit_thumbnail']; 
		       //$errorMsg[]=array('name' => "Please Select Thumbnail Image ");        
		    }else{
		        if (file_exists($imageFileType_tumb)) {
		          $errorMsg[]=array('name' => "Sorry, Thumbnail image already exists.");      
		        } 
		        $check_tumb = getimagesize($_FILES["fileToUpload_thumbnail"]["tmp_name"]);
		        if($check_tumb !== false) {} else {
		            $errorMsg[]=array('name' => "File is not an image."); 
		        } 
		        if($imageFileType_tumb != "jpg" && $imageFileType_tumb != "png" && $imageFileType_tumb != "jpeg"
		            && $imageFileType_tumb != "gif" ) {
		            $errorMsg[]=array('name' => "Sorry, only JPG, JPEG, PNG & GIF files are allowed."); 
		        } 
		    }
			if(!isset($errorMsg) ){
		    	move_uploaded_file($_FILES["fileToUpload_thumbnail"]["tmp_name"], $target_file_tumb);
		    }			
		}else{
			$img_tumb=""; 
		}
	    
	    if(!isset($errorMsg) ){
	    	$data_insert[]=array(
                'title' =>  $_POST['title'],
                'sort_order' =>  $_POST['sort_order'],
                'status' =>  $_POST['status'],
                'featured_promo' =>  $_POST['featured_promo'],
                'exclusive_for' =>  $_POST['exclusive_for'],
                'thumbnail_image' =>  $img_tumb,
                'promo_title_image' =>  $promo_title_image,
                'image' => $img);   
	    	 	$res=$model->add_latest_promo($data_insert);
	            if($res=="200"){
	            	$sMsg="Successfully Save";
	 				$latestPromoList=$model->getLatesPromolist(); 
	        	}else{
	        		 $errorMsg[]=array('name' => $res); 
	        	}
	    }
	}else{
		//for update
		$target_dir_update = isset($_SERVER['HTTPS']) ? '/home/pesoappdadmin/public_html/img/latest_promo/' : "c://xampp/htdocs/peso-web-new/img/latest_promo/";
		if (isset( $_FILES["fileToUpload"] ) && !empty( $_FILES["fileToUpload"]["name"])) {
			$target_file_update = $target_dir_update . basename($_FILES["fileToUpload"]["name"]);
			$imageFileType_update = strtolower(pathinfo($target_file_update,PATHINFO_EXTENSION));
			$img_update="/latest_promo/".basename($_FILES["fileToUpload"]["name"]);
			$imagefile=basename($_FILES["fileToUpload"]["name"]);
			if(empty($imagefile)){
		       $img_update=$_POST['imageedit'];     
		    }else{
		        if (file_exists($target_file_update)) {
		          $errorMsg[]=array('name' => "Sorry, image already exists.");      
		        } 
		        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		        if($check !== false) {} else {
		            $errorMsg[]=array('name' => "File is not an image."); 
		        } 
		        if($imageFileType_update != "jpg" && $imageFileType_update != "png" && $imageFileType_update != "jpeg"
		            && $imageFileType_update != "gif" ) {
		            $errorMsg[]=array('name' => "Sorry, only JPG, JPEG, PNG & GIF files are allowed."); 
		        } 
		    }
		    if(!isset($errorMsg) ){
		    	move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file_update);
		    }		
		}else{
			$img_update=$_POST['imageedit'];  
		}
		if (isset( $_FILES["fileToUpload_promo_title_image"] ) && !empty( $_FILES["fileToUpload_promo_title_image"]["name"])){
			$target_file_PtitleImg = $target_dir_update . basename($_FILES["fileToUpload_promo_title_image"]["name"]);
	    	$imageFileType_PtitleImg = strtolower(pathinfo($target_file_PtitleImg,PATHINFO_EXTENSION));
	    	$promo_title_image="/latest_promo/".basename($_FILES["fileToUpload_promo_title_image"]["name"]);
			$imagefile_PtitleImg=basename($_FILES["fileToUpload_promo_title_image"]["name"]);
			if(empty($target_file_PtitleImg)){
	    		$promo_title_image=$_POST['imageedit_promotitle']; 
		       //$errorMsg[]=array('name' => "Please Select Thumbnail Image ");        
		    }else{
		        if (file_exists($imageFileType_PtitleImg)) {
		          $errorMsg[]=array('name' => "Sorry, Thumbnail image already exists.");      
		        } 
		        $check_PtitleImg = getimagesize($_FILES["fileToUpload_promo_title_image"]["tmp_name"]);
		        if($check_PtitleImg !== false) {} else {
		            $errorMsg[]=array('name' => "File is not an image."); 
		        } 
		        if($imageFileType_PtitleImg != "jpg" && $imageFileType_PtitleImg != "png" && $imageFileType_PtitleImg != "jpeg"
		            && $imageFileType_PtitleImg != "gif" ) {
		            $errorMsg[]=array('name' => "Sorry, only JPG, JPEG, PNG & GIF files are allowed."); 
		        } 
		    }
			if(!isset($errorMsg) ){
		    	move_uploaded_file($_FILES["fileToUpload_promo_title_image"]["tmp_name"], $target_file_PtitleImg);
		    }	
		}else{
			$promo_title_image=""; 
		}
		if (isset( $_FILES["fileToUpload_thumbnail"] ) && !empty( $_FILES["fileToUpload_thumbnail"]["name"])) {
			$target_file_tumb = $target_dir_update . basename($_FILES["fileToUpload_thumbnail"]["name"]);
		    $imageFileType_tumb = strtolower(pathinfo($target_file_tumb,PATHINFO_EXTENSION));
		    $img_tumb_update="/latest_promo/".basename($_FILES["fileToUpload_thumbnail"]["name"]);
			$imagefile_tumb=basename($_FILES["fileToUpload_thumbnail"]["name"]);

		    if(empty($target_file_tumb)){
			        $img_tumb_update=$_POST['imageedit_thumbnail'];          
		    }else{
		        if (file_exists($imageFileType_tumb)) {
		          $errorMsg[]=array('name' => "Sorry, Thumbnail image already exists.");      
		        } 
		        $check_tumb = getimagesize($_FILES["fileToUpload_thumbnail"]["tmp_name"]);
		        if($check_tumb !== false) {} else {
		            $errorMsg[]=array('name' => "File is not an image."); 
		        } 
		        if($imageFileType_tumb != "jpg" && $imageFileType_tumb != "png" && $imageFileType_tumb != "jpeg"
		            && $imageFileType_tumb != "gif" ) {
		            $errorMsg[]=array('name' => "Sorry, only JPG, JPEG, PNG & GIF files are allowed."); 
		        } 
		    }
		    if(!isset($errorMsg) ){
		    	move_uploaded_file($_FILES["fileToUpload_thumbnail"]["tmp_name"], $target_file_tumb);
		    }
		}else{
			$img_tumb_update=""; 
		}
	    if(!isset($errorMsg) ){
	    	$data_update[]=array(
                'title' =>  $_POST['title'],
                'sort_order' =>  $_POST['sort_order'],
                'status' =>  $_POST['status'],
                'edit_id' =>  $_POST['edit_id'],
                'featured_promo' =>  $_POST['featured_promo'],
                'exclusive_for' =>  $_POST['exclusive_for'],
                'thumbnail_image' =>  $img_tumb_update,
                'promo_title_image' =>  $promo_title_image,
                'image' => $img_update);
	    	//var_dump($data_update);
	    	$res_update=$model->Update_latest_promo($data_update);
	            if($res_update=="200"){
	            	$sMsg="Successfully Updated";
	 				$latestPromoList=$model->getLatesPromolist(); 
	        	}else{
	        		 $errorMsg[]=array('name' => $res_update); 
	        	}
	    }
	}
	
}
if(isset($_GET['lp_edit_id'])){
	$edit_id=$_GET['lp_edit_id'];
	if($edit_id!=0){
		$lpdetails=$model->getlp_details($edit_id); 
		//var_dump($lpdetails);
	}
}else{
	$edit_id="not_set";
}
?>
<div id="content">
	<div class="page-header">
      <h2 class="text-center">Latest Promo</h2>
    </div>
    <div class="container-fluid">
    	<div class="panel panel-default">
    		<div class="panel-heading" style="padding:20px;">
    		 	<div class="row">
          			<div class="col-lg-6">
          				 <p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i>Latest Promo List</p>
          			</div>
          			<div class="col-lg-6">
          				<a class="btn btn-primary pull-right" href="latestpromo.php?lp_edit_id=0" class="btn btn-success btn-edit"  title="Add"><i data-feather="plus-square"></i></a> <!-- 
          				 <a class="btn btn-primary pull-right" id="add-lp" class="btn btn-primary"><i class="fa fa-plus"></i></button>               -->
          			</div>
          		</div>
    		</div>
    		<div class="panel-body">
    			<?php if(isset($sMsg)){ ?>
				    <div class="alert alert-success">
				        <strong><?php echo $sMsg;?></strong></br>
				    </div>
				<?php } ?>
				<?php if(isset($sMsg_failed)){ ?>
				    <div class="alert alert-danger">
				        <strong><?php echo $sMsg_failed;?></strong></br>
				    </div>
				<?php } ?>
				<?php if(isset($errorMsg)){ ?>
				    <div class="alert alert-danger">
				        <?php foreach ($errorMsg as $error) : ?>  
				                <strong><?php echo $error['name']?></strong></br>
				        <?php  endforeach;?>
				    </div>
				<?php } ?>
		        <div class="table-responsive">
		          	<table class="table table-bordered table-hover" id="deliverytb">
		          		<thead>
		                    <tr>
		                      <th class="text-center">Image</th>
		                       <th class="text-center">Title</th>
		                      <th class="text-center">Sort Order</th>
		                      <th class="text-center">Status</th>
		                      <th class="text-center">Promo Type </th>
		                      <th class="text-center">Exclusive For</th>
		                      <th class="text-center">Action</th>
		                    </tr>
		                </thead>
		            	<tbody>
		            		 <?php foreach ($latestPromoList as $result) :  ?>
		            		 	<tr>
		            		 		
		            		 		<td>
		            		 			 <?php $img2 ="../img/".$result['image']; ?>
		            		 			<div class="image-container" style=" height: 100px; width:  100px;overflow:hidden;border: groove 1px;">
				              				<img src="<?php echo $img2; ?>"  class="img-responsive" />
				              			</div>
				              		</td>
				              		<td><?php echo $result['title']; ?></td>
		            		 		<td><?php echo $result['sort']; ?></td>
		            		 		<td class="text-center"><?php if($result['status'] == '1') { 
								 			echo '<span style="color:green;">Enabled</span>'; 
								 		} else {
								 			echo '<span style="color:red;">Disabled</span>';
								 		}?>
								 	</td>
								 	<td class="text-center">
								 		<?php if($result['featured_promo'] == '1') { 
								 			echo '<span style="color:green;">Featured Promo</span>'; 
								 		}elseif($result['featured_promo'] == '2') { 
								 			echo '<span style="color:green;">Featured Store</span>'; 
								 		} elseif($result['featured_promo'] == '3') { 
								 			echo '<span style="color:green;">Featured Brand</span>'; 
								 		} elseif($result['featured_promo'] == '4') { 
								 			echo '<span style="color:green;">Watch Out For</span>'; 
								 		}else {
								 			echo '<span style="color:green;">Regular Promo</span>';
								 		}?>
								 	</td>
								 	<td class="text-center">
								 		<?php if($result['exclusive_for'] == '0') { 
								 			echo '<span style="color:green;">Regular Customers</span>'; 
								 		}elseif($result['featured_promo'] == '1') { 
								 			echo '<span style="color:green;">LandBank Customers</span>'; 
								 		} elseif($result['featured_promo'] == '3') { 
								 			echo '<span style="color:green;">4Gives Customers</span>'; 
								 		} ?>
								 	</td>
		            		 		<td class="text-center">
								 		<?php if($result['status'] == '1') { ?>
								 			<a href="latestpromo.php?lp_disable=<?php echo $result['id'];?>" class="btn btn-warning btn-Disable"  title="Disable"><i data-feather="x-circle"></i></a> 
								 		<?php } else { ?>
								 			<a href="latestpromo.php?lp_enable=<?php echo $result['id'];?>" class="btn btn-primary btn-Enable" title="Enable"><i data-feather="check"></i></a> 
								 		<?php }?>
								 		<a href="latestpromo.php?lp_delete=<?php echo $result['id'];?>" class="btn btn-danger btn-edit"  title="Delete"><i data-feather="delete"></i></a>
								 		<a href="latestpromo.php?lp_edit_id=<?php echo $result['id'];?>" class="btn btn-success btn-edit"  title="Update"><i data-feather="edit"></i></a>
								 		<a href="latestpromo_products.php?lp_id=<?php echo $result['id'];?>" class="btn btn-primary btn-edit"  title="Add Products" target="_blank"><i data-feather="plus-square"></i></a>
								 		<a href="latestpromo_category.php?lp_id=<?php echo $result['id'];?>" class="btn btn-primary btn-edit"  title="Add Category" target="_blank"><i data-feather="align-justify"></i></a>
								 		<a href="latestpromo_seller_list.php?lp_id=<?php echo $result['id'];?>" class="btn btn-success btn-edit"  title="Add seller" target="_blank"><i data-feather="plus-square"></i></a>

	                                </td>
		            		 	</tr>
		            		 <?php endforeach;?> 
		            	</tbody>
		            </table>
		        </div>
		    </div>

    	</div>
    </div>
</div>
<?php include 'template/footer.php'; ?>
<div  class="modal" id="lp_opnen_mdl" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-error-dialog">
        <!-- <form role="form"> -->
        <div class="modal-header " style="border: none;">
        </div>
        <div class="modal-content" style="margin: auto;">
          <div class="panel-heading">
              <button class="btn" id="homecategoryclosed"  data-dismiss="modal" style="float: right;
            font-size: 18px;
            font-weight: 700;
            line-height: 1;
            color: #000;
            text-shadow: 0 1px 0 #fff;
          ">x</button>
            <?php $header_mod = $edit_id !== '0' ? $header_mod = 'Edit Latest Promo' : $header_mod = 'Add Latest Promo'; ?>
          <h3><?php echo $header_mod;?></h3>
            </div><!-- /.panel-heading -->
            <div class="panel-body">
            	<form action="latestpromo.php"method="post" enctype="multipart/form-data" id="form-category">            		
	             	<div class="form-group">
	             		<?php if(isset($lpdetails['image'])){ ?>
	             			 <?php $img ="../img/".$lpdetails['image']; ?>
	             			 <div class="image-container" style=" height: 100px; width:  100px;overflow:hidden;border: groove 1px;">
				              	<img src="<?php echo $img; ?>"  class="img-responsive img_banner_lp" />
				              </div>
				               <input type="hidden" class="form-control" name="imageedit" value="<?php echo $lpdetails['image'];?>" >
	             		<?php }else{ ?>
				          	<span>
				             	<div class="image-container" style=" height: 100px; width:  100px;overflow:hidden;border: groove 1px;">
				             		<img src="../fonts/feathericons/icons/image.svg" style=" height: 100px; width:  100px;" class="img-responsive img_banner_lp"/>
				             	</div>
	             			</span>
	             			<input type="hidden" class="form-control" name="imageedit" value="0" >
             			<?php }?>
				        <input type="file" name="fileToUpload" id="fileToUpload" class="form-control" onchange="readURL(this);"/>

				    </div>
				    <div class="form-group required">
				     	<label for="">Title</label>
				     	<?php $title_val = isset($lpdetails['title']) ? $lpdetails['title']: " "; ?>
				     	<input type="text" class="form-control" name="title" value="<?php echo $title_val?>" placeholder="Title" required>
				     	<input type="hidden" class="form-control" name="edit_id" value="<?php echo $edit_id;?>"  required>
				    </div>
				    <div class="form-group">
				     	<label for="">Sort Order</label>
				     	<?php $sort_val = isset($lpdetails['sort']) ? $lpdetails['sort']: " "; ?>
				     	<input type="number" class="form-control" name="sort_order" value="<?php echo $sort_val?>" placeholder="Sort Order" required>
				    </div>
				    <div class="form-group">
				     	<label for="">Status</label>
				     	 <select class="form-control" id ="status" name="status">
				     	 	<?php $status_val = isset($lpdetails['status']) ? $lpdetails['status']: " "; ?>
				     	 	<?php if($status_val=="0"){ ?>
				     	 		<option value="0">Disable</option>
				     	 		<option value="1">Enable</option>
				     	 	<?php }else{ ?>
				     	 		<option value="1">Enable</option>
				     	 		<option value="0">Disable</option>
				     	 	<?php } ?>
				     	 	
                         </select>
				    </div>
				    <div class="form-group">
				     	<label for="">Promo Type</label>				     	
				     	 <select class="form-control" id ="featured_promo" name="featured_promo">
				     	 	<?php $Promotypeval = isset($lpdetails['featured_promo']) ? $lpdetails['featured_promo']: " "; ?>
				     	 	<?php if($Promotypeval=="0"){ ?>
				     	 		<option value="0">Regular Promo</option>
				     	 		<option value="1">Featured Promo</option>
				     	 		<option value="2">Featured Store</option>
				     	 		<option value="3">Featured Brand</option>
				     	 		<option value="4">Watch Oot For</option>
				     	 	<?php }elseif($Promotypeval=="1"){ ?>
				     	 		<option value="1">Featured Promo</option>
				     	 		<option value="2">Featured Store</option>				     	 		
				     	 		<option value="0">Regular Promo</option>
				     	 		<option value="3">Featured Brand</option>
				     	 		<option value="4">Watch Oot For</option>
				     	 	<?php }elseif($Promotypeval=="2"){ ?>
				     	 		<option value="2">Featured Store</option>	
				     	 		<option value="1">Featured Promo</option>				     	 		
				     	 		<option value="0">Regular Promo</option>
				     	 		<option value="3">Featured Brand</option>
				     	 		<option value="4">Watch Oot For</option>
				     	 	<?php }elseif($Promotypeval=="3"){?>				     	 		
				     	 		<option value="3">Featured Brand</option>
				     	 		<option value="4">Watch Oot For</option>
				     	 		<option value="2">Featured Store</option>				     	 		
				     	 		<option value="0">Regular Promo</option>				     	 		
				     	 		<option value="1">Featured Promo</option>
				     	 	<?php }else{ ?>				     	 		
				     	 		<option value="4">Watch Oot For</option>
				     	 		<option value="3">Featured Brand</option>
				     	 		<option value="2">Featured Store</option>				     	 		
				     	 		<option value="0">Regular Promo</option>				     	 		
				     	 		<option value="1">Featured Promo</option>
				     	 	<?php } ?>
				     	 	
                         </select>
				    </div>
				    <div class="form-group">
				     	<label for="">Exclusive For</label>				     	
				     	 <select class="form-control" id ="exclusive_for" name="exclusive_for">
				     	 	<?php $exclusive_for = isset($lpdetails['exclusive_for']) ? $lpdetails['exclusive_for']: " "; ?>
				     	 	<?php if($exclusive_for=="0"){ ?>
				     	 		<option value="0">Regular Customers</option>
				     	 		<option value="1">LandBank Customers</option>
				     	 		<option value="2">4Gives Customers</option>
				     	 	<?php }elseif($exclusive_for=="1"){ ?>
				     	 		<option value="1">LandBank Customers</option>
				     	 		<option value="2">4Gives Customers</option>				     	 		
				     	 		<option value="0">Regular Customers</option>
				     	 	<?php }elseif($exclusive_for=="2"){ ?>
				     	 		<option value="2">4Gives Customers</option>	
				     	 		<option value="1">LandBank Customers</option>				     	 		
				     	 		<option value="0">Regular Customers</option>
				     	 	<?php }else{ ?>
				     	 		<option value="0">Regular Customers</option>
				     	 		<option value="2">4Gives Customers</option>	
				     	 		<option value="1">LandBank Customers</option>		
				     	 	<?php }?>
                         </select>
				    </div>
				    <div class="form-group required">
				     	<label for="">Thumbnail Image</label>
				     	<?php if(isset($lpdetails['thumbnail_image'])){ ?>
				     		<?php if($lpdetails['thumbnail_image']!=""){ ?>
				     			<?php $ThumbnailImage ="../img/".$lpdetails['thumbnail_image']; ?>
		             			<div class="image-container" style=" height: 100px; width:  100px;overflow:hidden;border: groove 1px;">
					              	<img src="<?php echo $ThumbnailImage; ?>"  class="img-responsive Thumbnail_lp" />
					             </div>
					             <input type="hidden" class="form-control" name="imageedit_thumbnail" value="<?php echo $lpdetails['thumbnail_image'];?>" >
				     		<?php }else{ ?>
					          	<span>
					             	<div class="image-container" style=" height: 100px; width:  100px;overflow:hidden;border: groove 1px;">
					             		<img src="../fonts/feathericons/icons/image.svg" style=" height: 100px; width:  100px;" class="img-responsive Thumbnail_lp"/>
					             	</div>
		             			</span>
		             			<input type="hidden" class="form-control" name="imageedit_thumbnail" value="" >
             				<?php }?>	             			 
	             		<?php }else{ ?>
				          	<span>
				             	<div class="image-container" style=" height: 100px; width:  100px;overflow:hidden;border: groove 1px;">
				             		<img src="../fonts/feathericons/icons/image.svg" style=" height: 100px; width:  100px;" class="img-responsive Thumbnail_lp"/>
				             	</div>
	             			</span>
	             			<input type="hidden" class="form-control" name="imageedit_thumbnail" value="" >
             			<?php }?>
				        <input type="file" name="fileToUpload_thumbnail" id="fileToUpload_thumbnail" class="form-control" onchange="readURL_thumbnail(this);"/>
				    </div>
				    <div class="form-group required">
				     	<label for="">Promo Title Image</label>
				     	<?php if(isset($lpdetails['promo_title_image'])){ ?>
				     		<?php if($lpdetails['promo_title_image']!=""){ ?>
				     			<?php $promo_title_image ="../img/".$lpdetails['promo_title_image']; ?>
		             			<div class="image-container" style=" height: 100px; width:  100px;overflow:hidden;border: groove 1px;">
					              	<img src="<?php echo $promo_title_image; ?>"  class="img-responsive Thumbnail_lp" />
					             </div>
					             <input type="hidden" class="form-control" name="imageedit_promotitle" value="<?php echo $lpdetails['promo_title_image'];?>" >
				     		<?php }else{ ?>
					          	<span>
					             	<div class="image-container" style=" height: 100px; width:  100px;overflow:hidden;border: groove 1px;">
					             		<img src="../fonts/feathericons/icons/image.svg" style=" height: 100px; width:  100px;" class="img-responsive promotitle_lp"/>
					             	</div>
		             			</span>
		             			<input type="hidden" class="form-control" name="imageedit_promotitle" value="" >
             				<?php }?>	             			 
	             		<?php }else{ ?>
				          	<span>
				             	<div class="image-container" style=" height: 100px; width:  100px;overflow:hidden;border: groove 1px;">
				             		<img src="../fonts/feathericons/icons/image.svg" style=" height: 100px; width:  100px;" class="img-responsive promotitle_lp"/>
				             	</div>
	             			</span>
	             			<input type="hidden" class="form-control" name="imageedit_promotitle" value="" >
             			<?php }?>
				        <input type="file" name="fileToUpload_promo_title_image" id="fileToUpload_promo_title_image" class="form-control" onchange="readURL_promotitle(this);"/>
				    </div>
				    <div class="form-group navbar-right" style="margin-right: 10px;">
		                <button type="submit" class="btn btn-primary btn-category-SAVE" name="lp_save"><i class="fa fa-save"></i> Save</button>
		             </div>
            	</form>
            </div><!-- /.panel-body -->
        </div><!--modal-content-->
    </div><!-- modal-dialog modal-error-dialog-->
</div><!-- open_category_module_modal-->
<script type="text/javascript">
	$(document).ready(function() {
		var edit_id='<?php echo $edit_id;?>';
		if(edit_id!="not_set"){ $('#lp_opnen_mdl').modal('show');}
	});
	// $(document).delegate('#add-lp', 'click', function() {
	// 	$('#lp_opnen_mdl').modal('show');
	// });
	function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.img_banner_lp')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    function readURL_thumbnail(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.Thumbnail_lp')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    function readURL_promotitle(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.promotitle_lp')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>