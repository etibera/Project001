<?php
include 'template/header.php';
include "model/Banner.php";
$model = new Banner();   

$perm = $_SESSION['permission'];
if (!strpos($perm, "'6';") !== false){
    header("Location: landing.php");
   
} 

$img_items = array();    
if(isset($_GET['bid'])) {
        $id=$_GET["bid"];
        $data = $model->banner_details($id);
        $img_list = $model->banner_images($id);
 }
if(isset($_POST['update_banner'])){ 

  
    $target_dir = isset($_SERVER['HTTPS']) ? '/home/pesoappdadmin/public_html/img/catalog_new/banner/' : "c://xampp/htdocs/peso-web-new/img/catalog/banner/";
    $selectimg=0;
    $errorCount = 0;
    if($_POST["table_count"] == 0){
		$errorCount = 5;   
    }
    else{ 

	    foreach ($_POST["title"] as $key => $value) {

		 	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"][$key]);
		    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		    $imagefile=basename($_FILES["fileToUpload"]["name"][$key]);
		    $path = isset($_SERVER['HTTPS']) ? "catalog_new/banner/".basename($_FILES["fileToUpload"]["name"][$key]) : 
		            "catalog/banner/".basename($_FILES["fileToUpload"]["name"][$key]); 
		    
	    	if(empty($_POST['imageedit'][$key]) || $_POST['imageedit'][$key]==""){        
		        if(empty($imagefile)){
		        	$errorCount = 1;      
		        }else{  
		        	$_POST['image_path'][] =  $path;
		            if (file_exists($target_file)) {
		    			$errorCount = 2;    
		            } 
		            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"][$key]);
		            if($check !== false) {} else {
		            	$errorCount = 3;
		            } 
		            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		                && $imageFileType != "gif" ) {
		            	$errorCount = 4;
		            } 
		         	$selectimg=1;
		        }      
		    }else{
		        if(empty($imagefile)){
		        	$_POST['image_path'][] = $_POST['imageedit'][$key];
		        }else{
		        	$_POST['image_path'][] =  $path;
		            $selectimg=1; 		            
		            if (file_exists($target_file)) {
		            	$errorCount = 2;      
		            } 
		            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"][$key]);
		            if($check !== false) {} else {
		            	$errorCount = 3;
		            } 
		            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		                && $imageFileType != "gif" ) {
		            	$errorCount = 4;
		            } 	            
		        }
		    }
	    }
	}

    switch ($errorCount) {
    	case 1:
    		$errorMsg[]=array('name' => "Please Select Image");
    		$_SESSION['name'] = $_POST['name'];
    		$_SESSION['status'] = $_POST['status'];
    		break;
    	case 2:
    		$errorMsg[]=array('name' => "Sorry, image already exists.");
    		$_SESSION['name'] = $_POST['name']; 
    		$_SESSION['status'] = $_POST['status'];
    		break;
    	case 3:
    		$errorMsg[]=array('name' => "File is not an image.");
    		$_SESSION['status'] = $_POST['status'];
    		break;
    	case 4:
    		$errorMsg[]=array('name' => "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
    		$_SESSION['name'] = $_POST['name'];
    		$_SESSION['status'] = $_POST['status'];
    		break;
		case 5:
    		$errorMsg[]=array('name' => "Please Add Banners");
    		$_SESSION['name'] = $_POST['name'];
    		$_SESSION['status'] = $_POST['status'];
    		break;		
    	default:
    		# code...
    		break;
    }
    //var_dump($_POST['image_path']);
    if($errorCount == 0){
       	if ( $selectimg==1) {
		 	foreach ($_POST["title"] as $key => $value) {
		    	$target_f= $target_dir . basename($_FILES["fileToUpload"]["name"][$key]);
		    	move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$key], $target_f);
		    }
       	}

       	if(isset($_GET['bid'])){
       		if($_GET['bid'] !== '0'){
       			$banner = $model->banner_update($_POST);
       		}else{
       			$banner = $model->banner_add($_POST);
       		}
		}
		$data = $model->banner_details($_GET["bid"]);
		$img_list = $model->banner_images($id);
    }
}
?>
<div class="container">
<form action=""method="post" enctype="multipart/form-data" id="form-category">
		<div class="row">
			<div class="form-group">
				<div class="col-lg-12">
					<?php $header = $_GET['bid'] !== '0' ? $header = 'Edit Banner' : $header = 'Add New Banner'; ?>
					 <span style="font-size: 26px" class="pull-left"><?php echo $header;?></span>
					 <div class="pull-right">
						 <a href="banner_list.php" class="btn btn-danger" title="Back"><i data-feather="arrow-left"></i></a>
						 <button type="submit" class="btn btn-primary" name="update_banner" title="Save"><i data-feather="save"></i></button>
					 </div>
				</div>
			</div>
		</div>
		<br>
	   	<?php if(isset($errorMsg)){ ?>
    	<div class="alert alert-danger">
        <?php foreach ($errorMsg as $error) : ?>  
        <strong><?php echo $error['name']?></strong></br>
        <?php  endforeach;?>
    	</div>
  		<?php } ?>
		<?php if(isset($_SESSION['message'])):?>
		<?php echo $_SESSION['message'];?>		
		<?php endif;?>
		<?php unset($_SESSION['message']); ?>
		<div class="row form-group">
			<div class="col-sm-3 control-label"><label>Banner Name</label></div>					
			<div class="col-sm-6">
				<input type="hidden" name="banner_id" class="form-control" value="<?php echo $_GET['bid']; ?>" required/>
				<input type="text" name="name" class="form-control" placeholder="Banner Name" value="<?php echo isset($_SESSION['name'])? $_SESSION['name'] : $data['name']; ?>" required/>
			</div>
		</div>	
		<div class="row form-group">
			<div class="col-sm-3 control-label"><label>Status</label></div>	
			<div class="col-sm-6">
				<select name="status" id="status" class="form-control" required>
					<option value="">--Select Status--</option>
					<option value="0">Disable</option>
					<option value="1">Enable</option>
					<?php echo isset($_SESSION['name'])? "<script>$('#status').val('".$_SESSION['status']."')".";</script>'" : "<script>$('#status').val('".$data['status']."')".";</script>'"; ?>
				</select>
			</div>
		</div>
		<div class="row form-group">
			<input type="hidden" name="table_count" id="table_count" value="0">
			<div class="table-responsive" style="overflow-x: auto">
	            <table name="table"class="table table-striped table-bordered table-hover banner-table" id="table">
	            <thead>
	                <tr>
	                 <th>Title</th>
	                 <th>Link</th>
	                 <th>Image</th>
	                 <th>Sort Order</th>
	                 <th>Action</th>
	                </tr>
	            </thead> 
	            <tbody>
	            <?php                                  
                        if(count($img_list) > 0){
                        	echo '<script>$("#table_count").val("1");</script>';
                          	$rowCount = 0;
                            foreach($img_list as $images)
                            {
                            	$rowCount++;
	                            ?>
	                             <tr class="banner-tr">
	                                    <td>
	                                    	<input type="text" name="title[]" placeholder = "Title" class="form-control" value="<?php echo  $images['title'];?>" required>
	                                    </td>
	                                 	<td>
	                                    	<input type="text" name="link[]" placeholder = "Link" class="form-control" value="<?php echo  $images['link'];?>">
	                                    </td>
	                                    <td>
	                                    	<div class="col-sm-6">
									            <?php $img ="../img/".$images['image']; ?>
									            <?php if(file_exists($img) && $_GET['bid'] !== "0"): ?>
									              <div class="image-container" style=" height: 100px; width:  100px;overflow:hidden;border: groove 1px;">
									              	<img src="<?php echo $img; ?>"  class="img-responsive img_banner<?php echo $rowCount;?>" id="img_category"/>
									              </div>
									            <?php else: ?>              
									             <span>
									             	<div class="image-container" style=" height: 100px; width:  100px;overflow:hidden;border: groove 1px;">
									             		<img src="../fonts/feathericons/icons/image.svg" style=" height: 100px; width:  100px;" class="img-responsive img_banner<?php echo $rowCount;?>"/>
									             	</div>
									             </span>
									            <?php endif; ?> 
								             	<input type="file" name="fileToUpload[]" id="fileToUpload" onchange="readURL(this,<?php echo $rowCount;?>);"/>       
								     			<input type="hidden" class="form-control" name="imageedit[]" value="<?php echo $images['image']?>" >
											</div>
	                                    </td>
	                                    <td>
	                                    	<div style="width: 70px">
	                                    		<input type="number" name="sort_order[]" class="form-control" value="<?php echo  $images['sort_order'];?>">
	                                		</div>
	                                 	</td>

	                                    <td>
	                                            <button type="button" class="btn btn-danger btn-remove" title="Delete"><i class="fas fa-trash-alt"></i></button>
	                                    </td>
	                             </tr>

	                            <?php
                            }
                        }                                                                           
                ?>                
	            </tbody>
            </table>
            </div>              
        </div>
	</form>
	<div class="row form-group">					
			<div class="col-sm-12">
				<button type="button" class="btn btn-primary btn-add pull-right" title="Add"><i data-feather="plus"></i></button>
			</div>
	</div>	
</div>  

<?php include 'template/footer.php';?>                                                                  
        
 <script>
    $(document).ready(function() {

    	$('.banner-table').on('click', '.btn-remove', function () {

               $(this).parent('td').parent('tr').remove();
               if($('.banner-table tr').length == 1)
               {
           			$("#table_count").val("0");
               }
        });

        $('.btn-add').on('click',function(){	
        	var rowCount = $('.banner-table tr').length;
        	$("#table_count").val("1");
			$('.banner-table tbody').append(
				'<tr><td><input type="text" name="title[]" placeholder = "Title" class="form-control" value="" required></td>' +
             	'<td><input type="text" name="link[]" placeholder="Link" class="form-control" value=""></td>' +
                '<td>' +
                '<div class="col-sm-6"><div class="image-container" style=" height: 100px; width:  100px;overflow:hidden;border: groove 1px;"><img src="../fonts/feathericons/icons/image.svg" style=" height: 100px; width:  100px;" class="img-responsive img_banner'+rowCount+'"/></div><input type="file" name="fileToUpload[]" id="fileToUpload" onchange="readURL(this,'+rowCount+');" accept="image/*" required/><input type="hidden" class="form-control" name="imageedit[]" value=""></div>' +
				'</td>' +
            '<td><div style="width: 70px"><input type="number" name="sort_order[]" class="form-control" value="0"></div></td>' +
			'<td><button type="button" class="btn btn-danger btn-remove" title="Delete"><i class="fas fa-trash-alt"></i></button>' +
            '</td></tr>'
			);		
		});
    });

    function readURL(input,id) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.img_banner' + id)
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
 </script>

