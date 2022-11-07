<?php
include 'template/header.php';
include "model/Category.php";
$model = new Category();   

$perm = $_SESSION['permission'];
if (!strpos($perm, "'5';") !== false){
    header("Location: landing.php");
   
} 

if(isset($_GET['cid'])) {
        $category=$_GET["cid"];
        $data = $model->category_details($category);
 }
if(isset($_POST['update_category'])){  
    $target_dir = isset($_SERVER['HTTPS']) ? '/home/pesoappdadmin/public_html/img/catalog_new/category/' : "c://xampp/htdocs/peso-web-new/img/catalog/category/";
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
             $img= isset($_SERVER['HTTPS']) ? "catalog_new/category/".basename($_FILES["fileToUpload"]["name"]) : 
            "catalog/category/".basename($_FILES["fileToUpload"]["name"]);
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
            $img= isset($_SERVER['HTTPS']) ? "catalog_new/category/".basename($_FILES["fileToUpload"]["name"]) : 
            "catalog/category/".basename($_FILES["fileToUpload"]["name"]);
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
       	if(isset($_GET['cid'])){
       		if($_GET['cid'] !== '0'){
       			$category = $model->category_update($_POST,$img);
       		}else{
       			$category = $model->category_add($_POST,$img);
       		}
		}
		$data = $model->category_details($_GET["cid"]);
    }
}
?>
<div class="container">
<form action=""method="post" enctype="multipart/form-data" id="form-category">
		<div class="row">
			<div class="form-group">
				<div class="col-lg-12">
					<?php $header = $_GET['cid'] !== '0' ? $header = 'Edit Category' : $header = 'Add New Category'; ?>
					 <span style="font-size: 26px" class="pull-left"><?php echo $header;?></span>
					 <div class="pull-right">
						 <a href="category_list.php" class="btn btn-danger" title="Back"><i data-feather="arrow-left"></i></a>
						 <button type="submit" class="btn btn-primary" name="update_category" title="Save"><i data-feather="save"></i></button>
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
			<div class="col-sm-3 control-label"><label>Category Name</label></div>					
			<div class="col-sm-6">
				<input type="hidden" name="category_id" class="form-control" value="<?php echo $_GET['cid']; ?>" required/>
				<input type="text" name="name" class="form-control" placeholder="Category Name" value="<?php echo isset($_SESSION['name'])? $_SESSION['name'] : $data['name']; ?>" required/>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-sm-3 control-label"><label>Description</label></div>					
			<div class="col-sm-6">
				<textarea  name="description" rows="10" class="form-control" placeholder="Description"><?php echo isset($_SESSION['description'])? $_SESSION['description'] : $data['description']; ?></textarea>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-sm-3 control-label"><label>Meta Tag Title</label></div>					
			<div class="col-sm-6">
				<input type = "text"name="meta_title" class="form-control" placeholder="Meta Tag Title" value="<?php echo isset($_SESSION['meta_title'])? $_SESSION['meta_title'] : $data['meta_title']; ?>"/>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-sm-3 control-label"><label>Meta Tag Description</label></div>					
			<div class="col-sm-6">
				<textarea name="meta_description" rows="10" class="form-control" placeholder="Meta Tag Description"><?php echo isset($_SESSION['meta_description'])? $_SESSION['meta_description'] : $data['meta_description']; ?></textarea>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-sm-3 control-label"><label>Meta Tag Keyword</label></div>					
			<div class="col-sm-6">
				<textarea  name="meta_keyword" rows="10" class="form-control" placeholder="Meta Tag Keyword"><?php echo isset($_SESSION['meta_keyword'])? $_SESSION['meta_keyword'] : $data['meta_keyword']; ?></textarea>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-sm-3 control-label"><label>Image</label></div>					
			<div class="col-sm-6">
	            <?php  $img ="../img/".$data['image']; ?>
	            <?php if(file_exists($img) && $_GET['cid'] !== "0"): ?>
	              <div class="image-container" style=" height: 100px; width:  100px;overflow:hidden;border: groove 1px;">
	              	<img src="<?php echo $img; ?>"  class="img-responsive" id="img_category"/>
	              </div>
	            <?php else: ?>              
	             <span>
	             	<div class="image-container" style=" height: 100px; width:  100px;overflow:hidden;border: groove 1px;">
	             		<img src="../fonts/feathericons/icons/image.svg" style=" height: 100px; width:  100px;" class="img-responsive" id="img_category"/>
	             	</div>
	             </span>
	            <?php endif; ?> 
             	<input type="file" name="fileToUpload" id="fileToUpload" onchange="readURL(this);"/>       
     			<input type="hidden" class="form-control" name="imageedit" value="<?php echo $data['image']?>">
			</div>
		</div>
		<div class="row form-group">
			<div class="col-sm-3 control-label"><label>Top</label></div>					
			<div class="col-sm-6">
				<input type="checkbox" name="ctop" id="ctop" style="width: 20px;height: 20px;"/>
				<?php if($data['top'] === '1') echo '<script>$("#ctop").prop("checked",true)</script>'; ?>
				<input type="hidden" class="form-control" name="top" id="top" value="<?php echo isset($_SESSION['top'])? $_SESSION['top'] : $data['top']; ?>">				
			</div>
		</div>
		<div class="row form-group">
			<div class="col-sm-3 control-label"><label>Column</label></div>					
			<div class="col-sm-6">
				<input type="number" name="column" class="form-control" placeholder="Column" value="<?php echo isset($_SESSION['column'])? $_SESSION['column'] : $data['column']; ?>"/>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-sm-3 control-label"><label>Sort Order</label></div>					
			<div class="col-sm-6">
				<input type="number" name="sort_order" class="form-control" placeholder="Sort Order" value="<?php echo isset($_SESSION['sort_order'])? $_SESSION['sort_order'] : $data['sort_order']; ?>"/>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-sm-3 control-label"><label>Status</label></div>	
			<div class="col-sm-6">
				<select name="status" id="status" class="form-control" required>
					<option value="">--Select Status--</option>
					<option value="0">Disable</option>
					<option value="1">Enable</option>
					<?php echo "<script>$('#status').val('".$data['status']."')".";</script>'";?>
				</select>
			</div>
		</div>
	</form>
</div>  

<?php include 'template/footer.php';?>                                                                  
        
 <script>
    $(document).ready(function() {
    	$('#ctop').on('change',function(){
			if($(this).prop('checked') == true){
				$('#top').val('1');
			}else{
				$('#top').val('0');
			}
		});
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#img_category')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
 </script>

