<?php
  include 'template/header.php'; 
  require_once "model/uploadexcel.php";
  require_once('../composer/vendor/autoload.php');
  use PhpOffice\PhpSpreadsheet\IOFactory;
  use PhpOffice\PhpSpreadsheet\Spreadsheet;

  $model=new uploadexcel;
  if(isset($_POST['Uploadsave'])){    
    if($_FILES["uploadfile"]["name"] != ''){
      $allowed_extension = array('xls', 'csv');
      $file_array = explode(".", $_FILES['uploadfile']['name']);
      $file_extension = end($file_array);

      if(in_array($file_extension, $allowed_extension)){
        $file_name = time() . '.' . $file_extension;
        move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file_name);
        $file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_name);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);
        $spreadsheet = $reader->load($file_name);
        unlink($file_name);
        $data = $spreadsheet->getActiveSheet()->toArray();
        $j = count($data);
        for($i = 1; $i < $j ; $i++) {
            $Product_Name=$data[$i][0];
            $Model=$data[$i][1];
            $Description=$data[$i][2];
            $Price=$data[$i][3];
            $Quantity=$data[$i][4];
            $Image='upload_image/'.$data[$i][5].'.jpg';
            $Image2='upload_image/'.$data[$i][6].'.jpg';
            $Image3='upload_image/'.$data[$i][7].'.jpg';
            $Product_Tags=$data[$i][8];
            $Category_Id=$data[$i][13];
            $Delivery_Charge_Id=$data[$i][14];
            $Product_Brand_Id=$data[$i][15];
            $Status_Code=$data[$i][16];
            $status2=$model->savedata($Product_Name,$Model,$Description,$Price,$Quantity,$Image,$Image2,$Image3,$Product_Tags,$Category_Id,$Delivery_Charge_Id,$Product_Brand_Id,$Status_Code);
           /* echo 'Product_Name :'.$Product_Name.'</br>';
            echo 'Model :'.$Model.'</br>';
            echo 'Price :'.$Price.'</br>';
            echo 'Quantity :'.$Quantity.'</br>';
            echo 'Image :'.$Image.'</br>';
            echo 'Image2 :'.$Image2.'</br>';
            echo 'Image3 :'.$Image3.'</br>';
            echo 'Product_Tags :'.$Product_Tags.'</br>';
            echo 'Category_Id :'.$Category_Id.'</br>';
            echo 'Delivery_Charge_Id :'.$Delivery_Charge_Id.'</br>';
            echo 'Product_Brand_Id :'.$Product_Brand_Id.'</br>';
            echo 'Status_Code :'.$Status_Code.'</br>';
            echo '</br></br>---------------------------------------------------------</br>';*/
        }
        $sMsg= '<div class="alert alert-success">'.$status2.'</div>';
       
      }else{
         $sMsg = '<div class="alert alert-danger">Only .xls .csv or file allowed</div>';
      }

    }else{
       $sMsg = '<div class="alert alert-danger">Please Select file to upload</div>';
    }
  }

?>
<form method="post" action="uploadexcel.php" enctype="multipart/form-data">
  <div class="content">
    <div class="container-fluid">
      <div class="panel panel-default">
        <div class="panel-heading" style="padding:20px;">

          <div class="row">
            <div class="col-lg-2">
               <p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i> Upload Products</p>
            </div>
            <div class="col-lg-10">
                 <button  class="btn btn-primary  pull-right" type="submit" name="Uploadsave" ><i data-feather="save"> </i> Upload</button> 
            </div>
          </div>
        </div>
        <div class="panel-body">  
          <?php if(isset($sMsg)){ ?>
            <div class="alert alert-success">
              <strong><?php echo $sMsg;?></strong></br>
            </div>
          <?php } ?>         
          <div class="form-group">
            <label >Select File</label>               
              <input type="file" name="uploadfile" class="form-control"/>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
<?php include 'template/footer.php';?>  




