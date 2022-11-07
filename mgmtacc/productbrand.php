<?php
include 'template/header.php';		
include "model/productbrand.php";
if(!$session->is_signed_in()){redirect("index");}

$perm = $_SESSION['permission'];
if (!strpos($perm, "'7';") !== false){
    header("Location: landing.php");
} 
$brand = new productbrand();
$list_b=$brand->getbrand();
if(isset($_GET['br_searchvalue'])){
  $searchvla  =strip_tags($_GET["br_searchvalue"]);  
  $searchvl=str_replace("_20"," ",$searchvla);
  $list_b=$brand->search_brand($searchvl);
}
if(isset($_GET['prod_disable'])){
  $delete=$brand->brand_disable($_GET['prod_disable']);
  if($delete=="200"){
   $sMsg="Successfully Disabled"; 
   $list_b=$brand->getbrand();
  }
}

if(isset($_GET['prod_enable'])){
  $brand_enable=$brand->brand_enable($_GET['prod_enable']);
  if($brand_enable=="200"){
   $sMsg="Successfully Enabled"; 
   $list_b=$brand->getbrand();
  }
}
if(isset($_REQUEST['lp_save'])) {
   if($_POST['imageedit']=="0"){
       $target_dir = isset($_SERVER['HTTPS']) ? '/home/pesoappdadmin/public_html/img/lc_brand/' : "c://xampp/htdocs/peso-web-new/img/lc_brand/";
      $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
      $img="/lc_brand/".basename($_FILES["fileToUpload"]["name"]);
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
          $res=$brand->UpdateImageBrand($data_insert);
          if($res=="200"){
                $sMsg="Successfully Save";
                $list_b=$brand->getbrand();
            }else{
               $errorMsg[]=array('name' => $res); 
            }
        }
      }
   }else{
     $target_dir_update = isset($_SERVER['HTTPS']) ? '/home/pesoappdadmin/public_html/img/lc_brand/' : "c://xampp/htdocs/peso-web-new/img/lc_brand/";
      $target_file_update = $target_dir_update . basename($_FILES["fileToUpload"]["name"]);
      $imageFileType_update = strtolower(pathinfo($target_file_update,PATHINFO_EXTENSION));
      $img_update="/lc_brand/".basename($_FILES["fileToUpload"]["name"]);
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
        $res_update=$brand->UpdateImageBrand($data_update);
        if($res_update=="200"){
          $sMsg="Successfully Updated";
          $list_b=$brand->getbrand();
        }else{
          $errorMsg[]=array('name' => $res_update); 
        }
      }
    }

   }
}

if(isset($_REQUEST['lp_save_bnn'])) {
   if($_POST['imageedit_bnn']=="0"){
       $target_dir = isset($_SERVER['HTTPS']) ? '/home/pesoappdadmin/public_html/img/lc_brand/' : "c://xampp/htdocs/peso-web-new/img/lc_brand/";
      $target_file = $target_dir . basename($_FILES["fileToUpload_bnn"]["name"]);
      $target_file_mob = $target_dir . basename($_FILES["fileToUpload_bnn_mob"]["name"]);
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
      $imageFileType_mob = strtolower(pathinfo($target_file_mob,PATHINFO_EXTENSION));
      $img="/lc_brand/".basename($_FILES["fileToUpload_bnn"]["name"]);
      $img_mob="/lc_brand/".basename($_FILES["fileToUpload_bnn_mob"]["name"]);
      $imagefile=basename($_FILES["fileToUpload_bnn"]["name"]);
      $imagefile_mob=basename($_FILES["fileToUpload_bnn_mob"]["name"]);

    if(empty($imagefile)){
            $errorMsg[]=array('name' => "Please Select Image for web");        
    }else{
      if (file_exists($target_file)) {
        $errorMsg[]=array('name' => "Sorry, image already exists  for web.");      
      } 
      $check = getimagesize($_FILES["fileToUpload_bnn"]["tmp_name"]);
      if($check !== false) {} else {
          $errorMsg[]=array('name' => "File is not an image  for web."); 
      } 
      if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
          && $imageFileType != "gif" ) {
          $errorMsg[]=array('name' => "Sorry, only JPG, JPEG, PNG & GIF files are allowed  for web."); 
      } 
    }
    //for mobile
    if(empty($imagefile_mob)){
            $errorMsg[]=array('name' => "Please Select Image for MObile");        
    }else{
      if (file_exists($target_file_mob)) {
        $errorMsg[]=array('name' => "Sorry, image already exists  for MObile.");      
      } 
      $check = getimagesize($_FILES["fileToUpload_bnn_mob"]["tmp_name"]);
      if($check !== false) {} else {
          $errorMsg[]=array('name' => "File is not an image  for MObile."); 
      } 
      if($imageFileType_mob != "jpg" && $imageFileType_mob != "png" && $imageFileType_mob != "jpeg"
          && $imageFileType_mob != "gif" ) {
          $errorMsg[]=array('name' => "Sorry, only JPG, JPEG, PNG & GIF files are allowed  for MObile."); 
      } 
    }
      if(!isset($errorMsg) ){
        $data_insert_bnn[]=array(
                'edit_id' =>  $_POST['edit_id_bnn'],
                'image_mobile' => $img_mob,
                'image' => $img);
        //var_dump($data_insert_bnn);
        if (move_uploaded_file($_FILES["fileToUpload_bnn"]["tmp_name"], $target_file)) {
          move_uploaded_file($_FILES["fileToUpload_bnn_mob"]["tmp_name"], $target_file_mob);
          $res=$brand->UpdateImagebanner($data_insert_bnn);
          if($res=="200"){
                $sMsg="Successfully Save";
                $list_b=$brand->getbrand();
            }else{
               $errorMsg[]=array('name' => $res); 
            }
        }
      }
   }else{
     $target_dir_update = isset($_SERVER['HTTPS']) ? '/home/pesoappdadmin/public_html/img/lc_brand/' : "c://xampp/htdocs/peso-web-new/img/lc_brand/";
      $target_file_update = $target_dir_update . basename($_FILES["fileToUpload_bnn"]["name"]);
      $target_file_update_mob = $target_dir_update . basename($_FILES["fileToUpload_bnn_mob"]["name"]);
      $imageFileType_update = strtolower(pathinfo($target_file_update,PATHINFO_EXTENSION));
      $imageFileType_update_mob = strtolower(pathinfo($target_file_update_mob,PATHINFO_EXTENSION));
      $img_update="/lc_brand/".basename($_FILES["fileToUpload_bnn"]["name"]);
      $img_update_mob="/lc_brand/".basename($_FILES["fileToUpload_bnn_mob"]["name"]);
      $imagefile=basename($_FILES["fileToUpload_bnn"]["name"]);
      $imagefile_mob=basename($_FILES["fileToUpload_bnn_mob"]["name"]);
    if(empty($imagefile)){
      $img_update=$_POST['imageedit_bnn'];     
    }else{
      if (file_exists($target_file_update)) {
        $errorMsg[]=array('name' => "Sorry, image already exists For Web.");      
      } 
      $check = getimagesize($_FILES["fileToUpload_bnn"]["tmp_name"]);
      if($check !== false) {} else {
          $errorMsg[]=array('name' => "File is not an image For Web."); 
      } 
      if($imageFileType_update != "jpg" && $imageFileType_update != "png" && $imageFileType_update != "jpeg"
          && $imageFileType_update != "gif" ) {
          $errorMsg[]=array('name' => "Sorry, only JPG, JPEG, PNG & GIF files are allowed For Web."); 
      } 
    }
    //for mobile
    if(empty($imagefile_mob)){
      $img_update_mob=$_POST['imageedit_bnn_mob'];     
    }else{
      if (file_exists($target_file_update_mob)) {
        $errorMsg[]=array('name' => "Sorry, image already exists For Mobile.");      
      } 
      $check = getimagesize($_FILES["fileToUpload_bnn_mob"]["tmp_name"]);
      if($check !== false) {} else {
          $errorMsg[]=array('name' => "File is not an image For Mobile.."); 
      } 
      if($imageFileType_update_mob != "jpg" && $imageFileType_update_mob != "png" && $imageFileType_update_mob != "jpeg"
          && $imageFileType_update_mob != "gif" ) {
          $errorMsg[]=array('name' => "Sorry, only JPG, JPEG, PNG & GIF files are allowed For Mobile.."); 
      } 
    }
    if(!isset($errorMsg) ){
      $data_update_bnn[]=array(
              'edit_id' =>  $_POST['edit_id_bnn'],
              'image_mobile' => $img_update_mob,
              'image' => $img_update);
      
        $res_update=$brand->UpdateImagebanner($data_update_bnn);
        if($res_update=="200"){
          move_uploaded_file($_FILES["fileToUpload_bnn"]["tmp_name"], $target_file_update); 
          move_uploaded_file($_FILES["fileToUpload_bnn_mob"]["tmp_name"], $target_file_update_mob);
          $sMsg="Successfully Updated";
          $list_b=$brand->getbrand();
        }else{
          $errorMsg[]=array('name' => $res_update); 
        }
      
    }

   }
   
}
/*if(isset($_GET['prod_enable'])){
    $delete=$brand->bg_prod_enable($_GET['prod_enable']);
    if($delete=="200"){
     $sMsg="Successfully Enabled"; 
    }
  }*/
if(isset($_GET['lp_edit_id'])){
  $edit_id=$_GET['lp_edit_id'];
  if($edit_id==0){
    $edit_id=$_GET['brand_id'];
  }
  $bg_brand_image=$brand->getbg_brand_image($edit_id); 
}else{
  $edit_id="not_set";
}
if(isset($_GET['lp_edit_id_bnn'])){
  $edit_id_bnn=$_GET['lp_edit_id_bnn'];
  if($edit_id_bnn==0){
    $edit_id_bnn=$_GET['brand_id'];
  }
  $bg_brand_image_bnn=$brand->getbg_brand_image($edit_id_bnn); 
}else{
  $edit_id_bnn="not_set";
}/*
echo "<pre>";print_r($list_b);*/
?>
<div id="content">
  <div class="page-header">
    <h2 class="text-center">Product Brand Maintenance</h2>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading" style="padding:20px;">
        <div class="row">
          <div class="col-lg-3">
            <p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i>  Brand List
            </p>
          </div>
          <div class="col-sm-6 col-xs-6">
              <div  class="input-group" >
                <input style=" font-size: 14px; height: 30.5px;
                  padding: 11px 10px;
                  border-radius: 1px;
                  margin:0 auto;
                  opacity: .90; margin-left: 30px;" 
                  type="text" id="br_search_val_input"  value="<?php if(isset($_GET['br_searchvalue'])){ echo str_replace("_20"," ",$_GET['br_searchvalue']);} ?>" placeholder="Search" class="form-control"/>
                <span class="input-group-btn">
                <a type="button" class="btn btn-default btn-sm" id="btn_br_search" style=" padding: 6px 6px; margin:0 auto;  margin-left: 30px;"><i class="fa fa-search fa-sm" style=" font-size: 14px;"></i></a>             
                </span>
              </div>
            </div>   
          <div class="col-lg-3">
            
            <div class="pull-right">
                  <button class="btn btn-primary pull-right" id="add-brand" class="btn btn-primary"><i class="fa fa-plus"></i></button>
            </div>
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
          <table class="table table-bordered table-hover" id="deliverytb">
            <thead>
              <tr>
                <th>Brand Name</th>
                <th>Description</th>
                <th>Sort Order</th>
                <th>Image</th>
                <th>Banner</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($list_b as $brand):?>
              <tr>
                <td class="text-left" ><?php echo $brand['name'];?></td>
                <td class="text-left" ><?php echo $brand['description'];?></td>
                <td class="text-left" ><?php echo $brand['sort_order'];?></td>
                <td class="text-left" >
                  <?php if($brand['image']!=""){ ?>
                    <img src="<?php echo "../img/".$brand['image']; ?>" class="img-responsive" style="width: 80px; width: 80px; display:inline-block;" />
                    <a href="productbrand.php?lp_edit_id=<?php echo $brand['id'];?>" class="btn btn-success btn-edit"  title="Update Image" style="display: inline-block;"><i data-feather="edit"></i></a>
                  <?php }else{ ?> 
                    <a class="btn btn-primary pull-right" href="productbrand.php?lp_edit_id=0&brand_id=<?php echo $brand['id'];?>" class="btn btn-success btn-edit"  title="Add Image"><i data-feather="plus-square"></i></a>
                  <?php } ?>
                </td>
                <td class="text-left" >
                  <?php if($brand['banner_img']!=""){ ?>
                    <img src="<?php echo "../img/".$brand['banner_img']; ?>" class="img-responsive" style="width: 80px; width: 80px; display:inline-block;" />
                    <?php if($brand['banner_moblie_img']!=""){ ?>
                     <img src="<?php echo "../img/".$brand['banner_moblie_img']; ?>" class="img-responsive" style="width: 80px; width: 80px; display:inline-block;" />
                    <?php } ?>
                    <a href="productbrand.php?lp_edit_id_bnn=<?php echo $brand['id'];?>" class="btn btn-success btn-edit"  title="Update Image" style="display: inline-block;"><i data-feather="edit"></i></a>
                  <?php }else{ ?> 
                    <a class="btn btn-primary pull-right" href="productbrand.php?lp_edit_id_bnn=0&brand_id=<?php echo $brand['id'];?>" class="btn btn-success btn-edit"  title="Add Image"><i data-feather="plus-square"></i></a>
                  <?php } ?>
                </td>
                <td>
                  <?php if($brand['status'] == '1') { ?>
                      <a href="productbrand.php?prod_disable=<?php echo $brand['id'];?>" class="btn btn-danger btn-edit"  title="Disable"><i data-feather="x-circle"></i></a> 
                    
                    <?php } else { ?>
                      <a href="productbrand.php?prod_enable=<?php echo $brand['id'];?>" class="btn btn-success btn-edit" title="Enable"><i data-feather="check"></i></a> 
                    <?php }?>
                  <button class="btn btn-primary" data-id="<?php echo $brand['id'];?>" data-name="<?php echo $brand['name'];?>" data-sort_order="<?php echo $brand['sort_order'];?>" data-desc="<?php echo $brand['description'];?>"  id="btnedit"><i class="fa fa-edit"></i></button>
                  <!-- <button class="btn btn-danger" data-id="<?php echo $brand['id'];?>" id="btndelete"><i class="fa fa-ban"></i></button> -->
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
<?php include 'template/footer.php';?>
 <!-- Large modal -->
<div class="modal fade bd-example-modal-lg" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <a type="button"  data-dismiss="modal"   style="float: right;
            font-size: 25px;
            font-weight: 700;
            line-height: 1;
            color: #000;
            text-shadow: 0 1px 0 #fff;
          "><i class="fa fa-times-circle " style="color: black;font-size: 25px;" ></i>
        </a><br>
        <p style="font-size: 23px" class="modal-title" id="modallabel"><strong></strong></p><input type="hidden" id="modid">
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover" id="particular-table">
            <thead>
              <th>Brand Name</th>
            </thead>
            <tbody>
              <tr>
                <td>
                  <input class="form-control"  type="text" placeholder="Name" id="name">
                </td>
              </tr>
            </tbody>
            <thead>
              <th>Description</th>
            </thead>
            <tbody>
              <tr>
                <td>
                  <input class="form-control"  type="text" placeholder="Name" id="desc">
                </td>
              </tr>
            </tbody>
             <thead>
              <th>Sort Order</th>
            </thead>
            <tbody>
              <tr>
                <td>
                  <input class="form-control"  type="Number" placeholder="Sort Order" id="sort_order">
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="form-group navbar-right" style="margin-right: 10px;">
          <button id="save_delivery" type="button" class="btn btn-primary btn-category-SAVE" ><i class="fa fa-save"></i> Save</button>
        </div><br><br>
      </div>
    </div>
  </div>
</div>

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
        <form action="productbrand.php"method="post" enctype="multipart/form-data" id="form-category"> 
          <div class="form-group">
            <?php if(isset($bg_brand_image['image'])){ ?>
              <?php if($bg_brand_image['image']==""){  ?>
                <span>
                  <div class="image-container" style=" height: 100px; width:  100px;overflow:hidden;border: groove 1px;">
                    <img src="../fonts/feathericons/icons/image.svg" style=" height: 100px; width:  100px;" class="img-responsive img_banner_lp"/>
                  </div>
                </span>
                <input type="hidden" class="form-control" name="imageedit" value="0" >
              <?php }else{ ?>
                <?php $img ="../img/".$bg_brand_image['image']; ?>
                <div class="image-container" style=" height: 100px; width:  100px;overflow:hidden;border: groove 1px;">
                  <img src="<?php echo $img; ?>"  class="img-responsive img_banner_lp" />
                </div>
                <input type="hidden" class="form-control" name="imageedit" value="<?php echo $bg_brand_image['image'];?>" >
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

<div  class="modal" id="lp_opnen_mdl_bnn" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-error-dialog modal-lg">
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
        <?php $header_mod_bnn = $_GET['lp_edit_id_bnn'] !== '0' ? $header_mod = 'Edit Banner' : $header_mod = 'Add Banner'; ?>
        <h3><?php echo $header_mod_bnn;?></h3>
      </div><!-- /.panel-heading -->
      <div class="panel-body">
        <form action="productbrand.php"method="post" enctype="multipart/form-data" id="form-category"> 
          <div class="form-group">
            <label>Banner For Web (Image size should be  2560 X 560)</label>
            <?php if(isset($bg_brand_image_bnn['banner_img'])){ ?>
              <?php if($bg_brand_image_bnn['banner_img']==""){  ?>
                <span>
                  <div class="image-container" style=" height: 180px; width:  880px;overflow:hidden;border: groove 1px;">
                    <img src="../fonts/feathericons/icons/image.svg" style=" height: 100px; width:  100px;" class="img-responsive img_banner_lp"/>
                  </div>
                </span>
                <input type="hidden" class="form-control" name="imageedit_bnn" value="0" >
              <?php }else{ ?>
                <?php $img ="../img/".$bg_brand_image_bnn['banner_img']; ?>
                <div class="image-container" style=" height: 180px; width:  880px;overflow:hidden;border: groove 1px;">
                  <img src="<?php echo $img; ?>"  class="img-responsive img_banner_lp" />
                </div>
                <input type="hidden" class="form-control" name="imageedit_bnn" value="<?php echo $bg_brand_image_bnn['banner_img'];?>" >
              <?php }?>
            <?php }else{ ?>
              <span>
                <div class="image-container" style="height: 180px; width:  880px;overflow:hidden;border: groove 1px;">
                  <img src="../fonts/feathericons/icons/image.svg" style=" height: 180px; width:  880px;" class="img-responsive img_banner_lp"/>
                </div>
              </span>
              <input type="hidden" class="form-control" name="imageedit_bnn" value="0" >
            <?php }?>
            <input type="hidden" class="form-control" name="edit_id_bnn" value="<?php echo $edit_id_bnn;?>" >
            <input type="file" name="fileToUpload_bnn" id="fileToUpload" class="form-control" onchange="readURL(this);"/>
          </div>
        <!-- for mobile -->
          <div class="form-group">
            <label>Banner For Mobile (Image size should be  1140 X 250)</label>
            <?php if(isset($bg_brand_image_bnn['banner_moblie_img'])){ ?>
              <?php if($bg_brand_image_bnn['banner_moblie_img']==""){  ?>
                <span>
                  <div class="image-container" style=" height: 180px; width:  250px;overflow:hidden;border: groove 1px;">
                    <img src="../fonts/feathericons/icons/image.svg" style=" height: 100px; width:  100px;" class="img-responsive img_banner_lp_mob"/>
                  </div>
                </span>
                <input type="hidden" class="form-control" name="imageedit_bnn_mob" value="0" >
              <?php }else{ ?>
                <?php $img ="../img/".$bg_brand_image_bnn['banner_moblie_img']; ?>
                <div class="image-container" style=" height: 180px; width:  250px;overflow:hidden;border: groove 1px;">
                  <img src="<?php echo $img; ?>"  class="img-responsive img_banner_lp" />
                </div>
                <input type="hidden" class="form-control" name="imageedit_bnn_mob" value="<?php echo $bg_brand_image_bnn['banner_moblie_img'];?>" >
              <?php }?>
            <?php }else{ ?>
              <span>
                <div class="image-container" style="height: 180px; width:  250px;overflow:hidden;border: groove 1px;">
                  <img src="../fonts/feathericons/icons/image.svg" style=" height: 180px; width:  250px;" class="img-responsive img_banner_lp_mob"/>
                </div>
              </span>
              <input type="hidden" class="form-control" name="imageedit_bnn_mob" value="0" >
            <?php }?>
            <input type="file" name="fileToUpload_bnn_mob" id="fileToUpload" class="form-control" onchange="readURL2(this);"/>
          </div>
          <div class="form-group navbar-right" style="margin-right: 10px;">
            <button type="submit" class="btn btn-primary btn-bnn-SAVE_bnn" name="lp_save_bnn"><i class="fa fa-save"></i> Save</button>
          </div>
        </form>
      </div><!-- /.panel-body -->
    </div><!--modal-content-->
  </div><!-- modal-dialog modal-error-dialog-->
</div><!-- open_category_module_modal-->

<script>
$( document ).ready(function() {
  var edit_id='<?php echo $edit_id;?>';
  var edit_id_bnn='<?php echo $edit_id_bnn;?>';
  if(edit_id!="not_set"){ $('#lp_opnen_mdl').modal('show');}
  if(edit_id_bnn!="not_set"){ $('#lp_opnen_mdl_bnn').modal('show');}
  $("#deliverytb").on("click","#btnedit",function(){
    var id = $(this).data('id');
    var name = $(this).data('name');
    var description = $(this).data('desc');
    var sort_order = $(this).data('sort_order');
    $("#modallabel").html("Update Brand");
    $("#save_delivery").html('<i class="fa fa-save"></i> Update');
    $("#name").val(name);
    $("#desc").val(description);
    $("#modid").val(id);
    $("#sort_order").val(sort_order);
    $('#AddModal').modal('show');
  });
  $("#deliverytb").on("click","#btndelete",function(){
    var id = $(this).data('id');
    bootbox.confirm({
      message: "Are you sure you want to delete this?",
      buttons: {
        confirm: {
          label: 'Yes',
          className: 'btn-success'
        },
        cancel: {
          label: 'No',
          className: 'btn-danger'
        }
      },
        callback: function (result) {
          if(result==true){
            $.ajax({
              url: 'ajax_delete_productbrand.php',
              type: 'POST',
              data: 'id=' + id,
              dataType: 'json',
              success: function(json) {
                if (json['success']=="Successfully Deleted.") {
                  bootbox.alert(json['success'], function(){ 
                    location.reload();
                  });
                }else{
                  bootbox.alert(json['success']);
                  return false;
                }
              },
              error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
          }
        }
      });
    });
  $('#add-brand').click(function() {
    $("#modallabel").html("Add Brand");
    $("#save_delivery").html('<i class="fa fa-save"></i> Save');
    $("#name").val('');
    $("#desc").val('');
    $("#modid").val('_null');
    $('#AddModal').modal('show');
  });
  $('#save_delivery').click(function() {
    var name= $("#name").val();
    var description = $("#desc").val();
    var sort_order = $("#sort_order").val();
    var id = $("#modid").val();
    if(name=="" || description=="" ){
      bootbox.alert("All fields must not be empty!!");
      return false;
    }
    if(id=="_null"){
      $.ajax({
        url: 'ajax_add_productbrand.php',
        type: 'POST',
        data: 'name=' + name  + '&description='+ description+'&sort_order='+ sort_order,
        dataType: 'json',
        success: function(json) {
          if (json['success']=="Successfully Saved.") {
            bootbox.alert(json['success'], function(){ 
              location.reload();
            });
          }else{
            bootbox.alert(json['success']);
            return false;
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }else{
      $.ajax({
        url: 'ajax_edit_productbrand.php',
        type: 'POST',
        data: 'id=' + id+'&name=' + name  + '&description='+ description+'&sort_order='+ sort_order,
        dataType: 'json',
        success: function(json) {
          if (json['success']=="Successfully Updated.") {
            bootbox.alert(json['success'], function(){ 
              location.reload();
            });
          }else{
            bootbox.alert(json['success']);
            return false;
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }
  });
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
function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.img_banner_lp_mob')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
  $(document).delegate('#btn_br_search', 'click', function() {
    var search_val_input=$('#br_search_val_input').val();   
    var res=search_val_input.split(' ').join('_20');
    window.location.href = "productbrand.php?br_searchvalue=" +res;
  });
  $(document).delegate('#br_search_val_input', 'keyup', function(e) {
     var key = e.which;
        if(key == 13){
          var search_val_input=$('#br_search_val_input').val();   
          var res=search_val_input.split(' ').join('_20');
          window.location.href = "productbrand.php?br_searchvalue=" +res;
        }
    
  });
 </script>