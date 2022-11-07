<?php
include 'template/header.php';
include "model/Voucher.php";
$model = new Voucher(); 

$perm = $_SESSION['permission'];
if (!strpos($perm, "'10';") !== false){
    header("Location: landing.php");
   
} 
      
if(isset($_GET['vid'])) {
    $voucher=$_GET["vid"];
    $data = $model->voucher_theme_details($voucher);
 }
if(isset($_POST['update_voucher'])){  
	$target_dir = isset($_SERVER['HTTPS']) ? '/home/pesoappdadmin/public_html/img/catalog_new/' : "c://xampp/htdocs/peso-web-new/img/catalog/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $imagefile=basename($_FILES["fileToUpload"]["name"]);
    $img="";
    $selectimg=0;
    if(empty($_POST['imageedit']) || $_POST['imageedit']==""){        
        if(empty($imagefile)){
            $errorMsg[]=array('name' => "Please Select Image");   
            $selectimg=0;     
        }else{           
             $img= isset($_SERVER['HTTPS']) ? "catalog_new/".basename($_FILES["fileToUpload"]["name"]) : 
            "catalog/".basename($_FILES["fileToUpload"]["name"]);
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
         	$selectimg=1;
        }      
    }else{
        if(empty($imagefile)){
              $img= $_POST['imageedit']; 
              $selectimg=0; 
        }else{
              $selectimg=1; 
            $img= isset($_SERVER['HTTPS']) ? "catalog_new/".basename($_FILES["fileToUpload"]["name"]) : 
            "catalog/".basename($_FILES["fileToUpload"]["name"]);
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
    }

    if(!isset($errorMsg) ){
       	if ( $selectimg==1) {
			move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
       	}
       	if($_GET['vid'] !== '0') {
   			$update = $model->voucher_theme_update($_POST,$img);
		} else {
			$add = $model->voucher_theme_add($_POST,$img);
		}

		$data = $model->voucher_theme_details($voucher);
    }
}
?>
<div class="container">
<form action=""method="post" enctype="multipart/form-data" id="form-voucher">
	<div class="row">
		<div class="form-group">
			<div class="col-lg-12">
				<?php $header = $_GET['vid'] !== '0' ? $header = 'Edit Voucher Theme' : $header = 'Add New Voucher Theme'; ?>
				 <span style="font-size: 26px" class="pull-left"><?php echo $header;?></span>
				 <div class="pull-right">
					 <a href="voucher_theme_list.php" class="btn btn-danger" title="Back"><i data-feather="arrow-left"></i></a>
					 <button type="submit" class="btn btn-primary" name="update_voucher" title="Save"><i data-feather="save"></i></button>
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
	<br>
	<div class="row form-group">
		<div class="col-sm-3 control-label"><label>Voucher Theme Name</label></div>					
		<div class="col-sm-6">
			<input type="hidden" name="voucher_theme_id" class="form-control" value="<?php echo $_GET['vid']; ?>" required/>
			<input type="text" name="name" class="form-control" placeholder="Voucher Theme Name" value="<?php echo isset($_SESSION['name'])? $_SESSION['name'] : $data['name']; ?>" required/>
		</div>
	</div>
	<div class="row form-group">
			<div class="col-sm-3 control-label"><label>Image</label></div>					
			<div class="col-sm-6">
	            <?php  $img ="../img/".$data['image']; ?>
	            <?php if(file_exists($img) && $_GET['vid'] !== "0"): ?>
	              <div class="image-container" style=" height: 100px; width:  100px;overflow:hidden;border: groove 1px;">
	              	<img src="<?php echo $img; ?>"  class="img-responsive" id="img_voucher"/>
	              </div>
	            <?php else: ?>              
	             <span>
	             	<div class="image-container" style=" height: 100px; width:  100px;overflow:hidden;border: groove 1px;">
	             		<img src="../fonts/feathericons/icons/image.svg" style=" height: 100px; width:  100px;" class="img-responsive" id="img_voucher"/>
	             	</div>
	             </span>
	            <?php endif; ?> 
             	<input type="file" name="fileToUpload" id="fileToUpload" onchange="readURL(this);"/>       
     			<input type="hidden" class="form-control" name="imageedit" value="<?php echo $data['image']?>">
			</div>
		</div>
</form>
</div>  

<?php include 'template/footer.php';?>                                                                  
        
 <script>
    $(document).ready(function() {
    	
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#img_voucher')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
 </script>

