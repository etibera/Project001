<?php include 'template/header.php'; ?>

<?php
include "model/Product.php";

$perm = $_SESSION['permission'];
if (!strpos($perm, "'15';") !== false){
    header("Location: landing.php");
   
}
 
$product = new Product();
$attribute_arr=array();
foreach($product->get_attribute() as $attribute_data) {
  $attribute_arr[] = array(
        'id'  => $attribute_data['id'],
        'name'       =>  $attribute_data['name']      
      );
}
$js_array_att_edit = json_encode($attribute_arr);
$edit_cat = $product->get_product_cat($_GET['prod_id']);
$edit_brand = $product->get_brand_id($_GET['prod_id']);


$edit = $product->get_product($_GET['prod_id']);
if(isset($_POST['save'])){ 
   
    $target_dir_p = isset($_SERVER['HTTPS']) ? '/home/pesoappdadmin/public_html/img/catalog_new/img/' : "c://xampp/htdocs/peso-web-new/img/catalog_new/img/";
   // $target_dir_p ="c://xampp/htdocs/peso-web-new/img/catalog_new/img/";
    //for attributes
    if(isset($_REQUEST["product_att"])){
      $product_att_data = $_REQUEST['product_att'];     
     // var_dump($product_att_data);
      $error_attedit=0;
      foreach ($product_att_data as $product_att_data_edit) {
          if($product_att_data_edit['id']==""){
                $error_att++;
          }
           $p_att[] = array(
                'id'  =>   $product_att_data_edit['id'],
                'product_id'       =>  $_GET['prod_id']
            );
      }
      if($error_attedit!=0){
            $errorMsg[]=array('name' => "Please Select Attribute");  
      }
    }else{
      $p_att=array();
    }
    // var_dump($p_att);
    if (isset($_REQUEST["product_image"])) {
        $product_images = $_REQUEST['product_image'];     
        $no=0;
        $empty_img=0;
        $error_img=0;
        $file_exists_img=0;
        $file_not_img=0;
        $file_type_img=0;
        $f_sort_order=0;
        $imgp="";
        foreach ($product_images as $product_image) {
            $editname="product_image_edit".$no;
            $target_file_p = $target_dir_p . basename($_FILES[$editname]["name"]);
            $imageFileType_p = strtolower(pathinfo($target_file_p,PATHINFO_EXTENSION));
            $imagefile_p=basename($_FILES[$editname]["name"]);
            if($product_image['sort_order']==""){
                $f_sort_order++;
            }
            if(empty($imagefile_p)){
                if($product_image['img_edit']=="0"){
                 $empty_img++; 
               }else{
                 $imgp=$product_image['image'];
               }
            }else{
                if (file_exists($target_file_p)) {
                    $file_exists_img++;
                }
                $check_p = getimagesize($_FILES[$editname]["tmp_name"]);
                if($check_p !== false) {} else {
                   $file_not_img++;
                } 
                if($imageFileType_p != "jpg" && $imageFileType_p != "png" && $imageFileType_p != "jpeg"&& $imageFileType_p != "gif" ) {
                    $file_type_img++;                       
                } 
                $imgp="catalog_new/img/".basename($_FILES[$editname]["name"]);
            }
           
            $p_img[] = array(
                'product_img'  =>   $imgp,
                'sort_order'       =>  $product_image['sort_order'],
                'id_edit'       =>  $product_image['img_edit'],
            );
            $no++;
        }
       if($empty_img!=0){
            $errorMsg[]=array('name' => "Please Select Imagesr");  
        }
        if($file_exists_img!=0){
            $errorMsg[]=array('name' => "Sorry, Other Images already exists.");  
        }
        if($file_not_img!=0){
            $errorMsg[]=array('name' => "Other File is not an images.");  
        }
        if($file_type_img!=0){
            $errorMsg[]=array('name' => "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");  
        }
        if($f_sort_order!=0){
            $errorMsg[]=array('name' => "Please Input Sort Order");  
        }
    }




    $target_dir = isset($_SERVER['HTTPS']) ? '/home/pesoappdadmin/public_html/img/catalog_new/' : "c://xampp/htdocs/peso-web-new/img/catalog_new/";
    //$target_dir = "c://xampp/htdocs/peso-web-new/img/catalog_new/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $imagefile=basename($_FILES["fileToUpload"]["name"]);
    $img="";
    $selectimg=0;
	$_POST['product_id'] = $_GET['prod_id'];
    if(empty($_POST['imageedit']) || $_POST['imageedit']==""){        
        if(empty($imagefile)){
            $errorMsg[]=array('name' => "Please Select Image");   
            $selectimg=0;     
        }else{           
            $img="catalog_new/".basename($_FILES["fileToUpload"]["name"]);
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
            $img="catalog_new/".basename($_FILES["fileToUpload"]["name"]);
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
        $error_img_up=0;
       if (isset($_REQUEST["product_image"])) {  
            $count_img=0;
           

            $get_product_images = $_REQUEST['product_image'];  
            foreach ($get_product_images as $save_img) { 
               
                if($save_img['img_edit']=="0"){
                    $target_file_img = $target_dir_p . basename($_FILES["product_image_edit".$count_img]["name"]);
                    if (move_uploaded_file($_FILES["product_image_edit".$count_img]["tmp_name"], $target_file_img)) {
                    }else{
                        $error_img_up++;
                    }
                }else{
                     $imagefile_ps=basename($_FILES["product_image_edit".$count_img]["name"]);
                     if(!empty($imagefile_ps)){
                        $target_file_img = $target_dir_p . basename($_FILES["product_image_edit".$count_img]["name"]);
                        if (move_uploaded_file($_FILES["product_image_edit".$count_img]["tmp_name"], $target_file_img)) {
                        }else{
                            $error_img_up++;
                        }
                     }
                }
                
                $count_img++;
            }        
        }
        if ( $selectimg==1) {
            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        }
        if($error_img_up==0){
          
            $product = $product->edit_product($_POST,$img,$p_img,$p_att);
            if($product){
                 redirect('productlist');
            }
        }else{
                $errorMsg[]=array('name' => "Sorry, there was an error uploading your Other image.");
        }
    }

	
}


?>
	<div class="container">
<form action=""method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
  <h2>Edit Product</h2> <button class="btn btn-primary pull-right" type="submit" name="save"  id="save_product"> <i class="fas fa-save"></i> Save</button>
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#general">General</a></li>
    <li><a data-toggle="tab" href="#data">Data</a></li>    
    <li><a data-toggle="tab" href="#image">Image</a></li>
    <li><a data-toggle="tab" href="#links">Links</a></li>
    <li><a data-toggle="tab" href="#attribute">Attribute</a></li>
  </ul>
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
     	<label for="">Product Name</label>
     	<input type="text" class="form-control" name="name" value="<?php echo $edit['name']?>">
     </div>
     <div class="form-group">
     	<label for="">Description</label>
      <div id="summernote" ></div>
      <?php  $get_prd_desc=str_replace(array("'", "\"", "&quot;"), "SingleQuoteSymbol",$edit['description']);?>
      <script>
        var get_prd_desc='<?php echo $get_prd_desc;?>';
        var res=get_prd_desc.replaceAll("SingleQuoteSymbol","'") 
        $('#summernote').summernote('code',  res);
      </script>
      <input type="hidden" class="form-control" name="description" id="prdDescription">
     </div>
     <div class="form-group">
     	<label for="">Product Tags</label>
     	<input type="text" class="form-control" name="tag" value="<?php echo $edit['tag']?>">
     </div>
     <div class="form-group">
     	<label for="">Delivery Charge Category</label>
     	<select name="charge" class="form-control" value="<?php echo $edit['charge']?>">
            <?php
            foreach($product->getdelvery_charge() as $status) {
                if($status['id']==$edit['charge']){
                    echo '<option value='.$status['id'].' selected="selected">'.$status['name'].'</option>';
                }else{
                    echo '<option value='.$status['id'].'>'.$status['name'].'</option>';
                }
            }
            ?>
		</select>
     </div>
     <div class="form-group">
        <label for="">Product Category</label>
        <select name="product_category" class="form-control" id="product_category">
            <?php
            foreach($product->getCategories(0) as $getcategory) {
                if($getcategory['top']){
                    if($edit_cat==$getcategory['category_id']){
                        echo '<option value='.$getcategory['category_id'].'  selected="selected">'.$getcategory['name'].'</option>';
                    }else{
                        echo '<option value='.$getcategory['category_id'].'>'.$getcategory['name'].'</option>';
                    }
                }
            }
            ?>
        </select>
     </div>
     <div class="form-group">
        <label for="">Product Brand</label>
        <select name="product_brand" class="form-control" id="product_brand">
            <?php
              if($edit_brand==null){
                echo '<option value="">Select Product Brand</option>';
                foreach($product->get_product_brand(1) as $p_brnd) {
                  echo '<option value='.$p_brnd['id'].'>'.$p_brnd['name'].'</option>';
                }
              }else{
                foreach($product->get_product_brand(1) as $p_brnd) {
                  if($edit_brand==$p_brnd['id']){
                      echo '<option value='.$p_brnd['id'].'  selected="selected">'.$p_brnd['name'].'</option>';
                  }else{
                      echo '<option value='.$p_brnd['id'].'>'.$p_brnd['name'].'</option>';
                  }
                }
              }
             
            ?>
        </select>
     </div>
    </div>
    <div id="data" class="tab-pane fade">
        <div class="form-group">
            <?php  $getimg ="../img/".$edit['image']; ?>
            <?php if(file_exists($getimg)): ?>
              <div class="image-container" style=" height: 100px; width:  100px;overflow:hidden;"><img src="<?php echo $getimg; ?>"  class="img-responsive" /></div>
            <?php else: ?>              
              <span><i class="fa fa-image"style=" font-size: 70px;margin-top: 30px;color: #333;"></i></span>
            <?php endif; ?>                    
             <input type="file" name="fileToUpload" id="fileToUpload" class="form-control"/>       
             <input type="hidden" class="form-control" name="imageedit" value="<?php echo $edit['image']?>">   
         </div>         
       	 <div class="form-group">
         	<label for="">Model</label>
         	<input type="text" class="form-control" name="model" value="<?php echo $edit['model']?>">
         </div>
        <div class="form-group">
         	<label for="">Price</label>
         	<input type="number" class="form-control" min="0.01" step="0.01" name="price" value="<?php echo $edit['price']?>">
        </div>
       <div class="form-group">
     	  <label for="">Quantity</label>
     	  <input type="number" class="form-control" name="quantity" value="<?php echo $edit['quantity']?>">
        </div>
      <div class="form-group">
     	<label for="">Status</label>
     	<select name="status" id="input-status" class="form-control" value="<?php echo $edit['status']?>">
                   <option value="1"  <?php if($edit['status']=="1") echo 'selected="selected"'; ?>>Enabled</option>
                    <option value="0"  <?php if($edit['status']=="0") echo 'selected="selected"'; ?>>Disabled</option>
        </select>
     </div>
    </div>
    <div id="links" class="tab-pane fade">
     	<p>links</p>
    </div>
    <div id="attribute" class="tab-pane fade">
     	<div class="table-responsive">
             <table id="att_tbl" class="table table-striped table-bordered table-hover">
                 <thead>
                    <tr>
                      <td class="text-left">Attribute</td>
                      <td>Action</td>
                    </tr>
                </thead>
                <tbody>
                  <?php $att_row = 0; ?>
                  <?php   foreach($product->get_product_att($_GET['prod_id']) as $edit_att_data) { ?>   
                    <tr id="att-row<?php echo $att_row; ?>">                        
                        <td class="text-right">
                           <select name="product_att[<?php echo $att_row; ?>][id]" class="form-control" id="product_att">
                             <option value=''>Select Product Attribute</option>
                             <?php
                                foreach($attribute_arr as $attribute) {    
                                  if($attribute['id']==$edit_att_data['specification_id']){
                                      echo '<option value='.$attribute['id'].'  selected="selected">'.$attribute['name'].'</option>';
                                  }else{
                                     echo '<option value='.$attribute['id'].'>'.$attribute['name'].'</option>';   
                                  }                                
                                                                      
                                }
                             ?>
                           </select>
                          
                        </td>
                        <td class="text-left">
                            <button type="button" onclick="$('#att-row<?php echo $att_row; ?>').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
                        </td>
                    </tr>     
                    <?php $att_row ++; ?>
                  <?php } ?>             
                </tbody>
                 <tfoot>
                    <tr>
                      <td ></td>
                      <td class="text-left"><button type="button" onclick="add_attribute();" data-toggle="tooltip" title="Add Attribute" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
            </table>
         </div>
    </div>
    <div id="image" class="tab-pane fade">
         <div class="table-responsive">
             <table id="images" class="table table-striped table-bordered table-hover">
                 <thead>
                    <tr>
                      <td class="text-left">Image</td>
                      <td class="text-right">Sort Order</td>
                      <td>Action</td>
                    </tr>
                </thead>
                <tbody>
                    <?php $image_row = 0; ?>
                    <?php   foreach($product->get_product_img($_GET['prod_id']) as $p_images) { ?>                    
                        <tr id="image-row<?php echo $image_row; ?>">
                            <td class="text-left">
                                <?php  $getimgs ="../img/".$p_images['image']; ?>
                             
                                  <div class="image-container" style=" height: 100px; width:  100px;overflow:hidden;"><img src="<?php echo $getimgs; ?>"  class="img-responsive" /></div>
                                 
                                <input type="file" name="product_image_edit<?php echo $image_row; ?>" class="form-control"  />  
                                <input type="hidden" class="form-control" name="product_image[<?php echo $image_row; ?>][img_edit]" value="<?php echo $p_images['product_image_id']?>">  
                                <input type="hidden" class="form-control" name="product_image[<?php echo $image_row; ?>][image]" value="<?php echo $p_images['image']?>">   
                            </td>

                            <td class="text-right">
                                <input type="text" name="product_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $p_images['sort_order']?>" placeholder="Sort Order" class="form-control" />
                            </td>
                            <td class="text-left">
                                <button type="button" onclick="deleteImage(<?php echo  $p_images['product_image_id'];?>);"data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
                            </td>
                        </tr>   
                        <?php $image_row ++; ?>
                    <?php } ?>              
                </tbody>
                 <tfoot>
                    <tr>
                      <td colspan="2"></td>
                      <td class="text-left"><button type="button" onclick="addImage();" data-toggle="tooltip" title="Add Image" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
            </table>
         </div>
    </div>
</div>
</form>
</div>

<?php include 'template/footer.php'; ?>
<script type="text/javascript">
    var image_row = <?php echo $image_row; ?>;
    var  att_row= <?php echo $att_row; ?>;
    $('#summernote').summernote({
      placeholder: 'Product Description',
      tabsize: 2,
      height: 120,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
    });
    $(document).delegate('#save_product', 'click', function() {
        var markupStr = $('#summernote').summernote('code');
        $('#prdDescription').val(markupStr);
    })
    function addImage() {
        var name="product_image_edit"+image_row;    
        html  = '<tr id="image-row' + image_row + '">';
        html += '  <td class="text-left"><span><i class="fa fa-image"style=" font-size: 70px;margin-top: 30px;color: #333;"></i></span><input type="file" name="' + name + '"   class="form-control" /><input type="hidden" name="product_image[' + image_row + '][img_edit]" value="0" placeholder="Sort Order" class="form-control" /><input type="hidden" name="product_image[' + image_row + '][image]" value="0" placeholder="Sort Order" class="form-control" /></td>';
        html += '  <td class="text-right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="" placeholder="Sort Order" class="form-control" /></td>';
        html += '  <td class="text-left"><button type="button" onclick="removeImage();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';
        $('#images tbody').append(html);
         image_row++;
    }
     function removeImage() {
        $('#image-row'+image_row).remove();       
        image_row--;

    }
    function deleteImage(deletid) {
        
        $.ajax({
            url: 'ajx_ppp_rep.php?action=remove_image',
            type: 'post',
            data: 'deletid=' + deletid,
            dataType: 'json',
            success: function(json) {
                if (json['success']) {
                    bootbox.alert(json['success'], function(){ 
                       window.location.reload();
                    });
                }   
                 
            }
        });
    }
    function add_attribute() {
    
      var att_array = <?php echo $js_array_att_edit; ?>; 
      var options_att_add = "";
      for (var i = 0; i < att_array.length; i++) {
       options_att_add =  options_att_add + '<option value="'+att_array[i].id+'">'+att_array[i].name+'</option>';
      }
        
      html  = '<tr id="att-row' + att_row + '">';
      html += '  <td class="text-right"><select  name="product_att[' + att_row + '][id]" class="form-control" ><option value="">Select Product Attribute</option>'+options_att_add+'</select></td>';
      html += '  <td class="text-left"><button type="button" onclick="$(\'#att-row' + att_row  + '\').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
      html += '</tr>';
      $('#att_tbl tbody').append(html);

      att_row++;
    }
    
</script>