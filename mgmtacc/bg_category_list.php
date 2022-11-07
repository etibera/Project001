<?php
 	// include "../include/banggoodAPI.php"; 
 	include "model/bg_save_model.php";
 	include 'template/header.php'; 
 	$model = new BG_Model_db();   
 	
	if(isset($_GET['prod_disable'])){
		$delete=$model->bg_cat_disable($_GET['prod_disable']);
		if($delete=="200"){
		 $sMsg="Successfully Disabled"; 
		}
	}
	if(isset($_GET['prod_enable'])){
		$delete=$model->bg_cat_enable($_GET['prod_enable']);
		if($delete=="200"){
		 $sMsg="Successfully Enabled"; 
		}
	}
	if(isset($_GET['parent_id_cat'])){
		$catecory_list=$model->get_bg_category($_GET['parent_id_cat']);
		$category_bg_name=$_GET['cat_name'];
		
	}else{
		$catecory_list=$model->get_bg_category(0);
	}


if(isset($_REQUEST['lp_save'])) {
	if($_POST['imageedit']=="0"){
		$target_dir = isset($_SERVER['HTTPS']) ? '/home/pesoappdadmin/public_html/img/bg_cat/' : "c://xampp/htdocs/peso-web-new/img/bg_cat/";
	    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	    $img="/bg_cat/".basename($_FILES["fileToUpload"]["name"]);
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
	    	$data_insert[]=array(
                'edit_id' =>  $_POST['category_id'],
                'image' => $img);
	    	//var_dump($data_insert);
	    	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	    	 	$res=$model->UpdateImage($data_insert); 

	            if($res=="200"){
	            	$sMsg="Successfully Save";
					if(isset($_GET['parent_id_cat'])){
						$catecory_list=$model->get_bg_category($_GET['parent_id_cat']);
						$category_bg_name=$_GET['cat_name'];
						
					}else{
						$catecory_list=$model->get_bg_category(0);
					}
	        	}else{
	        		 $errorMsg[]=array('name' => $res); 
	        	}
	    	}
	    }
	}else{
		//for update
		$target_dir_update = isset($_SERVER['HTTPS']) ? '/home/pesoappdadmin/public_html/img/bg_cat/' : "c://xampp/htdocs/peso-web-new/img/bg_cat/";
	    $target_file_update = $target_dir_update . basename($_FILES["fileToUpload"]["name"]);
	    $imageFileType_update = strtolower(pathinfo($target_file_update,PATHINFO_EXTENSION));
	    $img_update="/bg_cat/".basename($_FILES["fileToUpload"]["name"]);
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
	    	$data_update[]=array(
                'edit_id' =>  $_POST['category_id'],
                'image' => $img_update);
	    	//var_dump($data_update);
	    	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file_update)) {
	    	 	$res_update=$model->UpdateImage($data_update);
	            if($res_update=="200"){
	            	$sMsg="Successfully Updated";
	 				if(isset($_GET['parent_id_cat'])){
						$catecory_list=$model->get_bg_category($_GET['parent_id_cat']);
						$category_bg_name=$_GET['cat_name'];
						
					}else{
						$catecory_list=$model->get_bg_category(0);
					}
	        	}else{
	        		 $errorMsg[]=array('name' => $res_update); 
	        	}
	    	}
	    }
	}
	
}
 	
	if(isset($_GET['lp_edit_id'])){
		$edit_id=$_GET['lp_edit_id'];
		if($edit_id==0){
			$edit_id=$_GET['cat_id'];
		}
		$bg_cat_image=$model->getbg_cat_image($edit_id); 
		//var_dump($bg_cat_image);
	}else{
		$edit_id="not_set";
	}



?>
<div id="content">
	<div class="page-header">
		 <h2 class="text-center">Banggoods Category list</h2>
	</div>
	<div class="container-fluid">
		<div class="panel panel-success">
		 	<div class="panel-heading" style="padding:20px;">
		 		<div class="row">
		 			<div class="col-lg-6">
          				<p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i> Category list 
          					<?php if(isset($category_bg_name)){ echo " ($category_bg_name)"; }?>
          				</p>
          			</div>
          		</div>
          	</div>        
	        <div class="panel-body">
				<?php if(isset($errorMsg)){ ?>
				    <div class="alert alert-danger">
				        <?php foreach ($errorMsg as $error) : ?>  
				                <strong><?php echo $error['name']?></strong></br>
				        <?php  endforeach;?>
				    </div>
				<?php } ?>
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
				<div class="table-responsive">
		 			<table class="table table-bordered table-hover">
		 				<thead >
							<th class="text-center">Category Id</th>
							<th class="text-center">Category Name</th>
							<th class="text-center">Status</th>
							<th class="text-center">Image</th>
							<th class="text-center">Action</th>
						</thead>
						<tbody>
							<?php foreach($catecory_list as $cat): ?>
								<tr>
									<td class="text-left" ><?php echo $cat['cat_id'];?></td>
									<?php $count_pid=$model->get_bg_category_count($cat['cat_id']); ?>
									<?php if($cat['status']=='0'){ ?> 
										<td class="text-left" ><?php echo $cat['cat_name'];?></td>
									<?php }else{ ?> 
										<?php if($count_pid==0){ ?>
											<td class="text-left" ><?php echo $cat['cat_name'];?></td>
										<?php }else{ ?> 
											<td class="text-left" >
								 				<a  title="View"
			                    				 href="bg_category_list.php?parent_id_cat=<?php echo $cat['cat_id'];?>&cat_name=<?php echo $cat['cat_name'];?>" >
			                    				<?php echo $cat['cat_name'];?>
			                 					</a>
			                 				</td>

										<?php } ?>
									<?php } ?>
		                 			<td class="text-center"><?php if($cat['status'] == '1') { 
								 			echo '<span style="color:green;">Enabled</span>'; 
								 		} else {
								 			echo '<span style="color:red;">Disabled</span>';
								 		}?>
								 	</td>
								 	<td class="text-left" >
								 		
								 		<?php if($cat['status'] == '1'){ ?>
								 			<?php if(isset($_GET['parent_id_cat'])){
												$catecory_list=$model->get_bg_category($_GET['parent_id_cat']);
												$category_bg_name=$_GET['cat_name'];?>
												<?php if($cat['image']!=""){ ?>
										 			<img src="<?php echo "../img/".$cat['image']; ?>" class="img-responsive" style="width: 80px; width: 80px; display:inline-block;" />
										 			<a href="bg_category_list.php?lp_edit_id=<?php echo $cat['id'];?>&cat_name=<?php echo $_GET['cat_name'];?>&parent_id_cat=<?php echo $_GET['parent_id_cat'];?>" class="btn btn-success btn-edit"  title="Update Image" style="display: inline-block;"><i data-feather="edit"></i></a>
										 		<?php }else{ ?> 
										 			<a class="btn btn-primary pull-right" href="bg_category_list.php?lp_edit_id=0&cat_id=<?php echo $cat['id'];?>&cat_name=<?php echo $_GET['cat_name'];?>&parent_id_cat=<?php echo $_GET['parent_id_cat'];?>" class="btn btn-success btn-edit"  title="Add"><i data-feather="plus-square"></i></a>
										 		<?php } ?>
											<?php }else{ ?>
												<?php if($cat['image']!=""){ ?>
										 			<img src="<?php echo "../img/".$cat['image']; ?>" class="img-responsive" style="width: 80px; width: 80px; display:inline-block;" />
										 			<a href="bg_category_list.php?lp_edit_id=<?php echo $cat['id'];?>" class="btn btn-success btn-edit"  title="Update Image" style="display: inline-block;"><i data-feather="edit"></i></a>
										 		<?php }else{ ?> 
										 			<a class="btn btn-primary pull-right" href="bg_category_list.php?lp_edit_id=0&cat_id=<?php echo $cat['id'];?>" class="btn btn-success btn-edit"  title="Add"><i data-feather="plus-square"></i></a>
										 		<?php } ?>
											<?php } ?>
									 		
								 		<?php } ?>
								 		
								 	</td>
								 	<td class="text-center">
								 		<?php if($cat['status'] == '1') { ?>
								 			<a href="bg_category_list.php?prod_disable=<?php echo $cat['id'];?>" class="btn btn-warning btn-edit"  title="Disable"><i data-feather="x-circle"></i></a> 
								 			<a href="bg_download_product.php?parent_id_cat=<?php echo $cat['cat_id'];?>&cat_name=<?php echo $cat['cat_name'];?>&page_no=1" class="btn btn-success btn-edit"  title="Download Products"  target="_blank"><i data-feather="download"></i></a> 
								 		
								 		<?php } else { ?>
								 			<a href="bg_category_list.php?prod_enable=<?php echo $cat['id'];?>" class="btn btn-primary btn-edit" title="Enable"><i data-feather="check"></i></a> 
								 		<?php }?>
								 		
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
            <?php $header_mod = $_GET['lp_edit_id'] !== '0' ? $header_mod = 'Edit Image' : $header_mod = 'Add Image'; ?>
          <h3><?php echo $header_mod;?></h3>
            </div><!-- /.panel-heading -->
            <div class="panel-body">
            	<?php if(isset($_GET['parent_id_cat'])){?>
            	<form action="bg_category_list.php?&cat_name=<?php echo $_GET['cat_name'];?>&parent_id_cat=<?php echo $_GET['parent_id_cat'];?>"method="post" enctype="multipart/form-data" id="form-category">  
            	<?php }else{ ?> 
            		<form action="bg_category_list.php"method="post" enctype="multipart/form-data" id="form-category"> 
            	<?php }?>         		
	             	<div class="form-group">
	             		<?php if(isset($bg_cat_image['image'])){ ?>
	             			<?php if($bg_cat_image['image']==""){  ?>
	             				<span>
					             	<div class="image-container" style=" height: 100px; width:  100px;overflow:hidden;border: groove 1px;">
					             		<img src="../fonts/feathericons/icons/image.svg" style=" height: 100px; width:  100px;" class="img-responsive img_banner_lp"/>
					             	</div>
		             			</span>
	             			<input type="hidden" class="form-control" name="imageedit" value="0" >
	             		    <?php }else{ ?>
	             		    	<?php $img ="../img/".$bg_cat_image['image']; ?>
	             		    	 <div class="image-container" style=" height: 100px; width:  100px;overflow:hidden;border: groove 1px;">
					              	<img src="<?php echo $img; ?>"  class="img-responsive img_banner_lp" />
					              </div>
					               <input type="hidden" class="form-control" name="imageedit" value="<?php echo $bg_cat_image['image'];?>" >
	             		    <?php }?>
	             			 
	             			
	             		<?php }else{ ?>
				          	<span>
				             	<div class="image-container" style=" height: 100px; width:  100px;overflow:hidden;border: groove 1px;">
				             		<img src="../fonts/feathericons/icons/image.svg" style=" height: 100px; width:  100px;" class="img-responsive img_banner_lp"/>
				             	</div>
	             			</span>
	             			<input type="hidden" class="form-control" name="imageedit" value="0" >
             			<?php }?>
             			<input type="hidden" class="form-control" name="category_id" value="<?php echo $edit_id;?>" >
				        <input type="file" name="fileToUpload" id="fileToUpload" class="form-control" onchange="readURL(this);"/>

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
</script>