<?php
  include_once 'template/header.php';
  require_once "model/FourGives_CustomerLIst.php";
  require_once('../composer/vendor/autoload.php');
  use PhpOffice\PhpSpreadsheet\IOFactory;
  use PhpOffice\PhpSpreadsheet\Spreadsheet;
  $perm = $_SESSION['permission'];
  if (!strpos($perm, "'252547';") !== false){
      header("Location: landing.php");
     
  }
  $modCustomer = new FGives_Custumer(); 
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
        $status2="";
        for($i = 2; $i < $j ; $i++) {
            $firstname=$data[$i][0];
            $lastname=$data[$i][1];
            $email=$data[$i][2];
            $telephone=$data[$i][3];
            $b_date=$data[$i][4];
            $address_1=$data[$i][5];
            $address_2=$data[$i][6];
            $district=$data[$i][7];
            $city=$data[$i][8];
            $region=$data[$i][9];
            $postcode=$data[$i][10];    
            $date=date_create($b_date);
            $bday=date_format($date,"Y-m-d");
            if( $firstname !="" || $lastname!="" || $email!=""  || $telephone!=""){
               $status2=$modCustomer->savedata($firstname,$lastname,$email,$telephone,$bday,$address_1,$address_2,$district,$city,$region,$postcode);
            }
           
          /*  echo 'firstname :'.$firstname.'</br>';
            echo 'lastname :'.$lastname.'</br>';
            echo 'email :'.$email.'</br>';
            echo 'telephone :'.$telephone.'</br>';
            echo 'b_date :'.$bday.'</br>';
            echo 'address_1 :'.$address_1.'</br>';
           
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
<form method="post" action="FourGivesCustomerUpload.php" enctype="multipart/form-data">
  <div class="content">
    <div class="container-fluid">
      <div class="panel panel-default">
        <div class="panel-heading" style="padding:20px;">

          <div class="row">
            <div class="col-lg-2">
               <p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i> Upload Customer</p>
            </div>
            <div class="col-lg-10">
              
              <button class="btn btn-primary  pull-right" type="submit" name="Uploadsave" ><i class="fas fa-save"></i> Upload </button>
              <a href="FourGivesUploadTemplate.php"class="btn btn-success pull-right" ><i class="fas fa-download"></i> Download Template</a>

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




