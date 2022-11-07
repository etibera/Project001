<?php
 include 'template/header.php'; 
 include "model/Product.php";

$perm = $_SESSION['permission'];
if (!strpos($perm, "'15';") !== false){
    header("Location: landing.php");
   
}
 
$model = new Product();
$attribute_arr=array();
foreach($model->get_attribute() as $attribute_data) {
    $attribute_arr[] = array(
          'id'  => $attribute_data['id'],
          'name'       =>  $attribute_data['name']      
        );

}
$js_array_att = json_encode($attribute_arr);

 if(isset($_REQUEST['save_product'])) {
   $target_dir_p = isset($_SERVER['HTTPS']) ? '/home/pesoappdadmin/public_html/img/catalog_new/img/' : "c://xampp/htdocs/peso-web-new/img/catalog_new/img/";
  //$target_dir_p = "c://xampp/htdocs/peso-web-new/img/catalog_new/img/";
    //for attributes
    if(isset($_REQUEST["product_att"])){
      $product_att_data = $_REQUEST['product_att'];     
      //var_dump($product_att_data);
      $error_att=0;
      foreach ($product_att_data as $product_att_datas) {
          if($product_att_datas['id']==""){
                $error_att++;
          }
      }
      if($error_att!=0){
            $errorMsg[]=array('name' => "Please Select Attribute");  
      }
    }
    //for images
    if (isset($_REQUEST["product_image"])) {
        $product_images = $_REQUEST['product_image'];           
        $no=0;
        $empty_img=0;
        $error_img=0;
        $file_exists_img=0;
        $file_not_img=0;
        $file_type_img=0;
        $f_sort_order=0;
        foreach ($product_images as $product_image) {  
            $ADDname="product_image".$no;              
            $target_file_p = $target_dir_p . basename($_FILES[$ADDname]["name"]);
            $imageFileType_p = strtolower(pathinfo($target_file_p,PATHINFO_EXTENSION));
            $imagefile_p=basename($_FILES[$ADDname]["name"]);
            if($product_image['sort_order']==""){
                $f_sort_order++;
            }
            if(empty($imagefile_p)){
               $empty_img++; 
            }else{
                if (file_exists($target_file_p)) {
                    $file_exists_img++;
                }
                $check_p = getimagesize($_FILES[$ADDname]["tmp_name"]);
                if($check_p !== false) {} else {
                   $file_not_img++;
                } 
                if($imageFileType_p != "jpg" && $imageFileType_p != "png" && $imageFileType_p != "jpeg"&& $imageFileType_p != "gif" ) {
                    $file_type_img++;                       
                } 
            }
            $imgp="catalog_new/img/".basename($_FILES[$ADDname]["name"]);
            $p_img[] = array(
                'product_image'  =>  $imgp,
                'sort_order'       =>  $product_image['sort_order'],
            );
           $no++;
        }
        if($empty_img!=0){
            $errorMsg[]=array('name' => "Please Select Images");  
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
     //  var_dump($p_img);
    }
   

   $target_dir = isset($_SERVER['HTTPS']) ? '/home/pesoappdadmin/public_html/img/catalog_new/' : "c://xampp/htdocs/peso-web-new/img/catalog_new/";
   // $target_dir =  "c://xampp/htdocs/peso-web-new/img/catalog_new/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $img="catalog_new/".basename($_FILES["fileToUpload"]["name"]);
    $product_name=$_REQUEST["product_name"];
    $description=$_REQUEST["description"];
    $delivery_category=$_REQUEST["delivery_category"];
    $product_category=$_REQUEST["product_category"];
    $product_brand=$_REQUEST["product_brand"];
    $product_tags=$_REQUEST["product_tags"];
    $modeladd=$_REQUEST["model"];
    $price=$_REQUEST["price"];
    $quantity=$_REQUEST["quantity"];
    $status=$_REQUEST["status"];
    $imagefile=basename($_FILES["fileToUpload"]["name"]);
    if($delivery_category==""){
        $DC_error="Please Select Delivery Charge";
        $errorMsg[]=array('name' => "Please Select Delivery Charge");
    }   
    if($product_category==""){
        $PC_error="Please Select Product Category";
        $errorMsg[]=array('name' => "Please Select Product Category");
    }  
    if($product_brand==""){
        $PB_error="Please Select Product Brand";
        $errorMsg[]=array('name' => "Please Select Product Brand");
    }                        
    if(empty($description)){
        $errorMsg[]=array('name' => "Please Input Description");        
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
                    'product_name' => $product_name,
                    'description' => $description,
                    'delivery_category' => $delivery_category,
                    'product_tags' => $product_tags,
                    'modeladd' => $modeladd,
                    'price' => $price,
                    'img' => $img,
                    'quantity' => $quantity,
                    'product_category' => $product_category,
                    'product_brand' => $product_brand,
                    'product_image' => $p_img,
                    'product_att' => $_REQUEST['product_att'],
                    'status' => $status);
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {           
            $error_img_up=0;
            if (isset($_REQUEST["product_image"])) {  
                $count_img=0;
                $get_product_images = $_REQUEST['product_image'];  
                foreach ($get_product_images as $save_img) { 
                    $target_file_img = $target_dir_p . basename($_FILES["product_image".$count_img]["name"]);
                    if (move_uploaded_file($_FILES["product_image".$count_img]["tmp_name"], $target_file_img)) {
                    }else{
                        $error_img_up++;
                    }
                    $count_img++;
                }        
            }
            if($error_img_up==0){
                 $res=$model->add_product($data_insert);
                     if($res){
                    redirect('productlist');
                }else{
                    $errorMsg[]=array('name' => "Error Occured.");   
                }
            }else{
                $errorMsg[]=array('name' => "Sorry, there was an error uploading your Other image.");
            }
            
        } else {
             $errorMsg[]=array('name' => "Sorry, there was an error uploading your image.");
        }        
    }
  }

?>
<div class="container">
  <form action="add_product.php" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
    <h2>Add Product</h2> <button class="btn btn-primary pull-right" type="submit" name="save_product" id="save_product"><i class="fas fa-save"></i> Save</button>
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
      <div class="form-group required">
       	<label for="">Product Name</label>
       	<input type="text" class="form-control" name="product_name" value="<?php if(isset($_REQUEST['save_product'])){echo $_REQUEST['product_name'];}?>" required>
      </div>
      <div class="form-group">
       	<label for="">Description</label>
        <div id="summernote" ></div>
        <input type="hidden" class="form-control" name="description" id="prdDescription">
      </div>
      <div class="form-group">
       	<label for="">Product Tags</label>
       	<input type="text" class="form-control" name="product_tags" value="<?php if(isset($_REQUEST['save_product'])){echo $_REQUEST['product_tags'];}?>"  required>
      </div>
      <div class="form-group">
       	<label for="">Delivery Charge Category</label>
       	<select name="delivery_category" class="form-control" id="delivery_category">
    		   <?php
            if(isset($_REQUEST['delivery_category']) && $_REQUEST['delivery_category']!=""){  
              foreach($model->getdelvery_charge() as $status) {
                if($_REQUEST['delivery_category']==$status['id']){
                  echo '<option value='.$status['id'].' selected="selected">'.$status['name'].'</option>';
                }else{
                  echo '<option value='.$status['id'].'>'.$status['name'].'</option>';
                }
              }
            }else{ ?>
              <option value=''>Select Product Delivery Charge</option>
              <?php foreach($model->getdelvery_charge() as $status) {
                echo '<option value='.$status['id'].'>'.$status['name'].'</option>';
              }
            } ?>
  		  </select>
          <?php if (isset($DC_error)) { ?>
            <div class="text-danger"><?php echo $DC_error; ?></div>
          <?php } ?>
      </div>
      <div class="form-group">
          <label for="">Product Category</label>
          <select name="product_category" class="form-control" id="product_category">
              <?php
                  if(isset($_REQUEST['product_category']) && $_REQUEST['product_category']!=""){   
                      foreach($model->getCategories(0) as $getcategory) {
                          if($getcategory['top']){
                              if($_REQUEST['product_category']==$getcategory['category_id']){
                                  echo '<option value='.$getcategory['category_id'].'  selected="selected">'.$getcategory['name'].'</option>';
                              }else{
                                  echo '<option value='.$getcategory['category_id'].'>'.$getcategory['name'].'</option>';
                              }
                          }
                      }
                     
                  }else{
                      ?>
                      <option value=''>Select Product Category</option>
                      <?php
                      foreach($model->getCategories(0) as $getcategory) {
                          if($getcategory['top']){
                              echo '<option value='.$getcategory['category_id'].'>'.$getcategory['name'].'</option>';
                          }
                      }
                  }
             ?>
          </select>
          <?php if (isset($PC_error)) { ?>
              <div class="text-danger"><?php echo $PC_error; ?></div>
          <?php } ?>
      </div>
      <div class="form-group">
          <label for="">Product Brand</label>
          <select name="product_brand" class="form-control" id="product_brand">
            <?php
              if(isset($_REQUEST['product_brand']) && $_REQUEST['product_brand']!=""){   
                foreach($model->get_product_brand(1) as $p_brnd) {
                    if($_REQUEST['product_brand']==$p_brnd['id']){
                        echo '<option value='.$p_brnd['id'].'  selected="selected">'.$p_brnd['name'].'</option>';
                    }else{
                        echo '<option value='.$p_brnd['id'].'>'.$p_brnd['name'].'</option>';
                    }
                }
              }else{ ?>
                <option value=''>Select Product Brand</option>
                <?php 
                  foreach($model->get_product_brand(1) as $p_brnd) {
                    echo '<option value='.$p_brnd['id'].'>'.$p_brnd['name'].'</option>';
                  }
              }?>
          </select>
          <?php if (isset($PB_error)) { ?>
              <div class="text-danger"><?php echo $PB_error; ?></div>
          <?php } ?>
      </div>
    </div>
      <div id="data" class="tab-pane fade">
          <div class="form-group">
              <span><i class="fa fa-image"style=" font-size: 70px;margin-top: 30px;color: #333;"></i></span>
             <input type="file" name="fileToUpload" id="fileToUpload" class="form-control"/>           
          </div>
     	 <div class="form-group">
       	<label for="">Model</label>
       	<input type="text" class="form-control" name="model" value="<?php if(isset($_REQUEST['save_product'])){echo $_REQUEST['model'];}?>" required>
       </div>
        <div class="form-group">
       	<label for="">Price</label>
       	<input type="number" class="form-control" name="price" min="0.01" step="0.01" value="<?php if(isset($_REQUEST['save_product'])){echo $_REQUEST['price'];}?>" required>
       </div>
         <div class="form-group">
       	<label for="">Quantity</label>
       	<input type="number" class="form-control" name="quantity" value="<?php if(isset($_REQUEST['save_product'])){echo $_REQUEST['quantity'];}?>" required>
       </div>
        <div class="form-group">
       	<label for="">Status</label>
       	<select name="status" id="input-status" class="form-control">
              <?php if(isset($_REQUEST['save_product']))
              {
                   if($_REQUEST['status']=="1"){
                      ?>
                      <option value="1">Enabled</option>
                      <option value="0" selected="selected">Disabled</option>
                   <?php
                   }else{
                      ?>
                      <option value="0" selected="selected">Disabled</option>                    
                      <option value="1">Enabled</option>
                      <?php
                   }
              }else{
                  ?>
                  <option value="1">Enabled</option>
                  <option value="0" selected="selected">Disabled</option>
                   <?php
              }?>
                     
          </select>
          <?php if (isset($DC_error)) { ?>
                  <div class="text-danger"></div>
          <?php } ?>
       </div>
      </div>
      <div id="links" class="tab-pane fade">
       	<p>links</p>
      </div>
      <div id="attribute" class="tab-pane fade">
    <!--    	<p>attribute</p> -->
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
                      <!-- <tr id="att-row<?php echo $att_row; ?>">                        
                          <td class="text-right">
                             <select name="product_att[<?php echo $att_row; ?>][id]" class="form-control" id="product_att">
                               <option value=''>Select Product Attribute</option>
                               <?php
                                  foreach($attribute_arr as $attribute) {                                    
                                    echo '<option value='.$attribute['id'].'>'.$attribute['name'].'</option>';                                       
                                  }
                               ?>
                             </select>
                            
                          </td>
                          <td class="text-left">
                              <button type="button" onclick="$('#att-row<?php echo $att_row; ?>').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
                          </td>
                      </tr>      -->            
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
                      <tr id="image-row<?php echo $image_row; ?>">
                          <td class="text-left">
                              <span><i class="fa fa-image"style=" font-size: 70px;margin-top: 30px;color: #333;"></i></span>
                              <input type="file" name="product_image<?php echo $image_row; ?>" class="form-control"  />  
                          </td>
                          <td class="text-right">
                              <input type="text" name="product_image[<?php echo $image_row; ?>][sort_order]" value="" placeholder="Sort Order" class="form-control" />
                          </td>
                          <td class="text-left">
                              <button type="button" onclick="removeImage()" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
                          </td>
                      </tr>                 
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
    var att_row = <?php echo $att_row; ?>;
    $('#summernote').summernote({
      placeholder: 'Product Description',
      tabsize: 2,
      height: 200,
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
        image_row++;
        var name="product_image"+image_row;       
        html  = '<tr id="image-row' + image_row + '">';
        html += '  <td class="text-left"><span><i class="fa fa-image"style=" font-size: 70px;margin-top: 30px;color: #333;"></i></span><input type="file" name="' + name + '"   class="form-control" /></td>';
        html += '  <td class="text-right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="" placeholder="Sort Order" class="form-control" /></td>';
        html += '  <td class="text-left"><button type="button" onclick="removeImage();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';

        $('#images tbody').append(html);
        
    }
    function removeImage() {
        $('#image-row'+image_row).remove();       
        image_row--;

    }
    function add_attribute() {
      att_row++;
      var att_array = <?php echo $js_array_att; ?>; 
      var options_att_add = "";
      for (var i = 0; i < att_array.length; i++) {
       options_att_add =  options_att_add + '<option value="'+att_array[i].id+'">'+att_array[i].name+'</option>';
      }
        
      html  = '<tr id="att-row' + att_row + '">';
      html += '  <td class="text-right"><select  name="product_att[' + att_row + '][id]" class="form-control" ><option value="">Select Product Attribute</option>'+options_att_add+'</select></td>';
      html += '  <td class="text-left"><button type="button" onclick="$(\'#att-row' + att_row  + '\').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
      html += '</tr>';
      $('#att_tbl tbody').append(html);
    }

    
</script>