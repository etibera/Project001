<?php 
	include 'template/header.php'; 
	include "model/Specification.php";

$perm = $_SESSION['permission'];
if (!strpos($perm, "'14';") !== false){
    header("Location: landing.php");
   
}
	
	$model = new Specification(); 
	if(isset($_REQUEST['save_Attribute'])) {
		if (isset($_REQUEST["attribute_item"])) {
			$attribute_items = $_REQUEST['attribute_item'];
			$error_name=0;
			$error_desc=0;
			$error_sort_order=0;
			foreach ($attribute_items as $attribute_item) { 
				if($attribute_item['name']==""){
					$error_name++;
				}
				if($attribute_item['desc']==""){
					$error_desc++;
				}
				if($attribute_item['sort_order']==""){
					$error_sort_order++;
				}
				$a_items[] = array(
	                'name'  =>   $attribute_item['name'],
	                'desc'  =>   $attribute_item['desc'],
	                'sort_order'       =>  $attribute_item['sort_order'],
	            );
			}
			if($error_name!=0){
	            $errorMsg[]=array('name' => "Please Input Item Name");  
	        }
	        if($error_desc!=0){
	            $errorMsg[]=array('name' => "Please Input Item Description");  
	        }
	        if($error_sort_order!=0){
	            $errorMsg[]=array('name' => "Please Input Sort Order");  
	        }
		}
		$target_dir = isset($_SERVER['HTTPS']) ? '/home/pesoappdadmin/public_html/img/catalog_new/attribute/' : "c://xampp/htdocs/peso-web-new/img/catalog_new/attribute/";
	    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	    $img="catalog_new/attribute/".basename($_FILES["fileToUpload"]["name"]);
    	$attribute_name=$_REQUEST["attribute_name"];
    	$description=$_REQUEST["description"];
    	$imagefile=basename($_FILES["fileToUpload"]["name"]);

    	if($description==""){
			 $errorMsg[]=array('name' => "Please Input Attribute Description"); 
		}
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
                    'attribute_name' => $attribute_name,
                    'description' => $description,
                    'img' => $img,
                    'a_items' => $a_items);
	    	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	    	 	$res=$model->add_attribute($data_insert);
                if($res){
                	redirect('specification');
            	}
	    	 }
	    }
	}
?>

<div id="content">
	<div class="page-header">
     <h2 class="text-center">Add Attribute </h2>   
    </div>
    <form action=""method="post" enctype="multipart/form-data" id="form-voucher">
    <div class="container-fluid">
    	 <div class="panel panel-default">
    	 	<div class="panel-heading" style="padding:20px;">
    	 		<div class="row">
          			<div class="col-lg-12">
          				 <ul class="nav nav-tabs">
						    <li class="active"><a data-toggle="tab" href="#general">General</a></li>
						    <li><a data-toggle="tab" href="#Items">Items</a></li>
						</ul>
          			</div>
          		</div>
    	 	</div>
    	 	<div class="panel-body">
    	 		<div class="row">
    	 			<div class="col-lg-12">
	    	 			<div class="pull-right">
					        <button type="submit" class="btn btn-primary" name="save_Attribute" title="Save"><i data-feather="save"></i></button>
					     </div>
					 </div>
    	 		</div>
				<?php if(isset($errorMsg)){ ?>
				    <div class="alert alert-danger">
				        <?php foreach ($errorMsg as $error) : ?>  
				                <strong><?php echo $error['name']?></strong></br>
				        <?php  endforeach;?>
				    </div>
				<?php } ?>
				<div class="tab-content" style="margin-top: 20px">
					<div id="general" class="tab-pane fade in active">
						<div class="form-group">
				           <span><i class="fa fa-image"style=" font-size: 70px;margin-top: 30px;color: #333;"></i></span>
				           <input type="file" name="fileToUpload" id="fileToUpload" class="form-control"/>           
				        </div>
					     <div class="form-group required">
					     	<label for="">Attribute Name</label>
					     	<input type="text" class="form-control" name="attribute_name" value="<?php if(isset($_REQUEST['save_Attribute'])){echo $_REQUEST['attribute_name'];}?>" required>
					     </div>
					     <div class="form-group">
					     	<label for="">Description</label>
					     	<textarea class="form-control" cols="30" rows="10" name="description" ></textarea>
					     </div>
					</div>
					<div id="Items" class="tab-pane fade">
				         <div class="table-responsive">
				             <table id="Items_tbl" class="table table-striped table-bordered table-hover">
				                 <thead>
				                    <tr>
				                      <th class="text-center">Item Name</th>
				                      <th class="text-center">Description</th>
				                      <th class="text-center">Sort Order</th>
				                      <th>Action</th>
				                    </tr>
				                </thead>
				                <tbody>
				                    <?php $items_row = 0; ?>
				                    <tr id="items-row<?php echo $items_row; ?>">
				                        <td class="text-center">
				                        	<input type="text" name="attribute_item[<?php echo $items_row; ?>][name]" value="" placeholder="Item Name" class="form-control" />
				                        </td>
				                        <td class="text-center">
				                           <input type="text" name="attribute_item[<?php echo $items_row; ?>][desc]" value="" placeholder="Description" class="form-control" />
				                        </td>
				                        <td class="text-center">
				                        	<input type="number" name="attribute_item[<?php echo $items_row; ?>][sort_order]" value="" placeholder="Sort Order" class="form-control" />
				                        </td>
				                        <td class="text-center">
				                            <button type="button" onclick="$('#items-row<?php echo $items_row; ?>').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
				                        </td>
				                    </tr>                 
				                </tbody>
				                 <tfoot>
				                    <tr>
				                      <td colspan="3"></td>
				                      <td class="text-center"><button type="button" onclick="additems();" data-toggle="tooltip" title="Add Items" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
				                    </tr>
				                  </tfoot>
				            </table>
				         </div>
				    </div>
				</div>
    	 	</div>
    	 </div>
    </div>
	</form>
</div>
<?php include 'template/footer.php'; ?>

<script type="text/javascript">
	var items_row = <?php echo $items_row; ?>;
	function additems() {
		items_row++;
		html  = '<tr id="items-row' + items_row + '">';
		html += '  <td class="text-center"><input type="text" name="attribute_item[' + items_row + '][name]" value="" placeholder="Item Name" class="form-control" /></td>';
		html += '  <td class="text-center"><input type="text" name="attribute_item[' + items_row + '][desc]" value="" placeholder="Description" class="form-control" /></td>';
		html += '  <td class="text-center"><input type="number" name="attribute_item[' + items_row + '][sort_order]" value="" placeholder="Sort Order" class="form-control" /></td>';
		html += '  <td class="text-center"><button type="button" onclick="$(\'#items-row' + items_row  + '\').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
		html += '</tr>';

		$('#Items_tbl tbody').append(html);
	}
</script>