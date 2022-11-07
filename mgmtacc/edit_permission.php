<?php
 include 'template/header.php';
 include "model/permission.php"; 
 if(!$session->is_signed_in()){redirect("index");}
$id = $_SESSION['user_id'] ;
$uid=$_GET['user_id'];
$permission = new permission();

$perm = $_SESSION['permission'];
if (!strpos($perm, "'1';") !== false){
    header("Location: landing.php");
   
}     

$getuser = $permission->getuserdetails($_GET['user_id']);  
$getpermission = $permission->getaccess2($_GET['user_id']);  
$getpermission1=array();

foreach ($getpermission as $perm) {
  $getpermission1[]=$perm['user_pages'];
}

if(isset($_REQUEST["Submit"]))
    { 

      if(isset($_POST['chkpage']))
      {
       $chkpage=$_POST['chkpage']; 
       $fname=$_REQUEST['fname']; 
       $lname=$_REQUEST['lname']; 
       $user=$_REQUEST['username']; 
       $email=$_REQUEST['email']; 
       $pass=$_REQUEST['pass'];
       
       foreach($chkpage as $chkdata) {
        $data_chk[] = array(
              'id'       =>  $chkdata      
            );
        }
        $l_id = $permission->updateuser($user,$pass,$fname,$lname,$email,$uid);
        


        if($l_id!=="error"){
            $stats=$permission->updatepermission($uid,$data_chk);
        }else{
          $stats="500";
        }
        if ($stats =="200"){

        echo '<div class="alert alert-success" role="alert">User Successfully Updated</div>';
          header("Location: permission.php");
         $_REQUEST['fname']=""; 
         $_REQUEST['lname']="";
         $_REQUEST['username']=""; 
         $_REQUEST['email']=""; 
         $_REQUEST['pass']="";
         $l_id="";
        }else{
          echo '<div class="alert alert-danger" role="alert">There is an error.</div>';
        }


      }else {
        echo '<div class="alert alert-danger" role="alert">Please Select at least one or more permission!!</div>';
      }

       
       
       
    }

   

?>
<div class="container">
<form method="post" class="form-horizontal" action="edit_permission.php?user_id=<?php echo $uid; ?>">
  <a href="permission.php"><- Go back to Permission Page</a>
  <h2>Edit User</h2> <button class="btn btn-primary pull-right" type="submit" name="Submit" id="save_review">Save</button>
  <br>
  
 
  <div class="tab-content" style="margin-top: 20px">
    <div class="row">
        <div class="col-sm-12">
         <div class="form-group required">
         	<label for="">First Name</label>
         	<input type="text" class="form-control" name="fname" id="fname" value="<?php if(isset($_REQUEST['fname'])){echo $_REQUEST['fname'];}else{echo $getuser['firstname'];}?>" required>
         </div>
        </div> 
        <div class="col-sm-12">
         <div class="form-group required">
          <label for="">Last Name</label>
          <input type="text" class="form-control" name="lname" id="fname" value="<?php if(isset($_REQUEST['lname'])){echo $_REQUEST['lname'];}else{echo $getuser['lastname'];}?>" required>
         </div>
        </div>
        <div class="col-sm-12">
         <div class="form-group required">
          <label for="">Username</label>
          <input type="text" class="form-control" name="username" id="fname" value="<?php if(isset($_REQUEST['username'])){echo $_REQUEST['username'];}else{echo $getuser['username'];}?>" required>
         </div>
       </div>
       <div class="col-sm-12">
         <div class="form-group required">
          <label for="">Email</label>
          <input type="text" class="form-control" name="email" id="fname" value="<?php if(isset($_REQUEST['email'])){echo $_REQUEST['email'];}else{echo $getuser['email'];}?>"email required>
         </div>
       </div>
       <div class="col-sm-12">
         <div class="form-group required">
          <label for="">Password <small style="color: red;"><em>**note: Leave it blank if you do not want to change your password.</em></small></label>
          <input type="password" class="form-control" name="pass" id="fname" value="<?php if(isset($_REQUEST['pass'])){echo $_REQUEST['pass'];}?>" >
         </div>
       </div>
       
       
         
     </div>
  </div>

  <div>
    <div><h3>Pages</h3></div>
    <div class="">
            <div class="col-xs-12">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#Desctab">File Maintenance</a></li>
                    
                    <li><a data-toggle="tab" href="#Spectab">Transactions</a></li>                    
                    <li><a data-toggle="tab" href="#Reviewtab">Reports</a></li>
                    <li><a data-toggle="tab" href="#China_Brands">China Brands</a></li>
                </ul>
                <div class="tab-content"> 
                    <div id="Desctab" class="tab-pane fade in active">
                      <br>
                        <div class="form-group required">
                     <?php if(isset($_REQUEST['save_product'])){ ?>
                        <input type="checkbox" name="chkpage[]" value="1" <?php if (in_array("1",$_POST['chkpage'])){echo "checked"; }?>/> <label> User Administration</label><br />
                        <input type="checkbox" name="chkpage[]" value="2" <?php if (in_array("2", $_POST['chkpage'])){echo "checked"; }?>/> <label> Dashboard</label><br />

                        <input type="checkbox" name="chkpage[]" value="3" <?php if (in_array("3", $_POST['chkpage'])){echo "checked"; }?>/> <label> Home Page Category</label><br />
                        <input type="checkbox" name="chkpage[]" value="4" <?php if (in_array("4", $_POST['chkpage'])){echo "checked"; }?>/> <label> Manage Delivery Charge</label><br />
                        <input type="checkbox" name="chkpage[]" value="5" <?php if (in_array("5", $_POST['chkpage'])){echo "checked"; }?>/> <label> Categories</label><br />
                        <input type="checkbox" name="chkpage[]" value="6" <?php if (in_array("6", $_POST['chkpage'])){echo "checked"; }?>/> <label> Banners</label><br />
                        <input type="checkbox" name="chkpage[]" value="7" <?php if (in_array("7", $_POST['chkpage'])){echo "checked"; }?>/> <label> Product Brand</label><br />
                        <input type="checkbox" name="chkpage[]" value="8" <?php if (in_array("8", $_POST['chkpage'])){echo "checked"; }?>/> <label> Customers</label><br />
                        <input type="checkbox" name="chkpage[]" value="9" <?php if (in_array("9", $_POST['chkpage'])){echo "checked"; }?>/> <label> Gift Vouchers</label><br />
                        <input type="checkbox" name="chkpage[]" value="10" <?php if (in_array("10", $_POST['chkpage'])){echo "checked"; }?>/> <label> Voucher Themes</label><br />
                        <input type="checkbox" name="chkpage[]" value="11" <?php if (in_array("11", $_POST['chkpage'])){echo "checked"; }?>/> <label> Coupons</label><br />
                        <input type="checkbox" name="chkpage[]" value="12" <?php if (in_array("12", $_POST['chkpage'])){echo "checked"; }?>/> <label> Country List</label><br />
                        <input type="checkbox" name="chkpage[]" value="13" <?php if (in_array("13", $_POST['chkpage'])){echo "checked"; }?>/> <label> Manage Reviews</label><br />
                        <input type="checkbox" name="chkpage[]" value="14" <?php if (in_array("14", $_POST['chkpage'])){echo "checked"; }?>/> <label> Manage Attribute</label><br />
                        
                        <input type="checkbox" name="chkpage[]" value="23" <?php if (in_array("23", $_POST['chkpage'])){echo "checked"; }?>/> <label> Return Status List</label><br />
                        <input type="checkbox" name="chkpage[]" value="24" <?php if (in_array("24", $_POST['chkpage'])){echo "checked"; }?>/> <label> Return Action List</label><br />
                        <input type="checkbox" name="chkpage[]" value="25" <?php if (in_array("25", $_POST['chkpage'])){echo "checked"; }?>/> <label> Pending Payables</label><br />
                        <input type="checkbox" name="chkpage[]" value="2525" <?php if (in_array("2525", $_POST['chkpage'])){echo "checked"; }?>/> <label> Messages</label><br />
                        <input type="checkbox" name="chkpage[]" value="2526" <?php if (in_array("2526", $_POST['chkpage'])){echo "checked"; }?>/> <label> Pending Producs</label><br />
                        <input type="checkbox" name="chkpage[]" value="252525" <?php if (in_array("252525", $_POST['chkpage'])){echo "checked"; }?>/> <label> Login Store</label><br />
                        <input type="checkbox" name="chkpage[]" value="252526" <?php if (in_array("252526", $_POST['chkpage'])){echo "checked"; }?>/> <label> Manage Wallet</label><br />
                         <input type="checkbox" name="chkpage[]" value="252527" <?php if (in_array("252527", $getpermission1)){echo "checked"; }?>/> <label> Pending Receivables</label><br />
                       
                     <?php } else { ?>
                        
                         <input type="checkbox" name="chkpage[]" value="1" <?php if (in_array("1",$getpermission1)){echo "checked"; }?>/> <label> User Administration</label><br />
                        <input type="checkbox" name="chkpage[]" value="2" <?php if (in_array("2", $getpermission1)){echo "checked"; }?>/> <label> Dashboard</label><br />

                        <input type="checkbox" name="chkpage[]" value="3" <?php if (in_array("3", $getpermission1)){echo "checked"; }?>/> <label> Home Page Category</label><br />
                        <input type="checkbox" name="chkpage[]" value="4" <?php if (in_array("4", $getpermission1)){echo "checked"; }?>/> <label> Manage Delivery Charge</label><br />
                        <input type="checkbox" name="chkpage[]" value="5" <?php if (in_array("5", $getpermission1)){echo "checked"; }?>/> <label> Categories</label><br />
                        <input type="checkbox" name="chkpage[]" value="6" <?php if (in_array("6", $getpermission1)){echo "checked"; }?>/> <label> Banners</label><br />
                        <input type="checkbox" name="chkpage[]" value="7" <?php if (in_array("7", $getpermission1)){echo "checked"; }?>/> <label> Product Brand</label><br />
                        <input type="checkbox" name="chkpage[]" value="8" <?php if (in_array("8", $getpermission1)){echo "checked"; }?>/> <label> Customers</label><br />
                        <input type="checkbox" name="chkpage[]" value="9" <?php if (in_array("9", $getpermission1)){echo "checked"; }?>/> <label> Gift Vouchers</label><br />
                        <input type="checkbox" name="chkpage[]" value="10" <?php if (in_array("10",$getpermission1)){echo "checked"; }?>/> <label> Voucher Themes</label><br />
                        <input type="checkbox" name="chkpage[]" value="11" <?php if (in_array("11",$getpermission1)){echo "checked"; }?>/> <label> Coupons</label><br />
                        <input type="checkbox" name="chkpage[]" value="12" <?php if (in_array("12", $getpermission1)){echo "checked"; }?>/> <label> Country List</label><br />
                        <input type="checkbox" name="chkpage[]" value="13" <?php if (in_array("13", $getpermission1)){echo "checked"; }?>/> <label> Manage Reviews</label><br />
                        <input type="checkbox" name="chkpage[]" value="14" <?php if (in_array("14",$getpermission1)){echo "checked"; }?>/> <label> Manage Attribute</label><br />
                        
                        <input type="checkbox" name="chkpage[]" value="23" <?php if (in_array("23", $getpermission1)){echo "checked"; }?>/> <label> Return Status List</label><br />
                        <input type="checkbox" name="chkpage[]" value="24" <?php if (in_array("24", $getpermission1)){echo "checked"; }?>/> <label> Return Action List</label><br />
                         <input type="checkbox" name="chkpage[]" value="25" <?php if (in_array("25", $getpermission1)){echo "checked"; }?>/> <label> Pending Payables</label><br />
                        <input type="checkbox" name="chkpage[]" value="2525" <?php if (in_array("2525", $getpermission1)){echo "checked"; }?>/> <label> Messages</label><br />
                        <input type="checkbox" name="chkpage[]" value="2526" <?php if (in_array("2526", $getpermission1)){echo "checked"; }?>/> <label> Pending Producs</label><br />
                        <input type="checkbox" name="chkpage[]" value="252525" <?php if (in_array("252525", $getpermission1)){echo "checked"; }?>/> <label> Login Store</label><br />
                        <input type="checkbox" name="chkpage[]" value="252526" <?php if (in_array("252526", $getpermission1)){echo "checked"; }?>/> <label> Manage Wallet</label><br />
                        <input type="checkbox" name="chkpage[]" value="252527" <?php if (in_array("252527", $getpermission1)){echo "checked"; }?>/> <label> Pending Receivables</label><br />
                     <?php } ?>
                     
                       </div>
                    </div>
                    <div id="Spectab" class="tab-pane fade">
                       <br>
                        <div class="form-group required">
                     <?php if(isset($_REQUEST['save_product'])){ ?>
                        
                        <input type="checkbox" name="chkpage[]" value="15" <?php if (in_array("15", $_POST['chkpage'])){echo "checked"; }?>/> <label> Products</label><br />
                        <input type="checkbox" name="chkpage[]" value="16" <?php if (in_array("16", $_POST['chkpage'])){echo "checked"; }?>/> <label> Returns</label><br />
                        <input type="checkbox" name="chkpage[]" value="17" <?php if (in_array("17", $_POST['chkpage'])){echo "checked"; }?>/> <label> Cash Out Request</label><br />
                        
                       
                     <?php } else { ?>
                       
                        <input type="checkbox" name="chkpage[]" value="15" <?php if (in_array("15",$getpermission1)){echo "checked"; }?>/> <label> Products</label><br />
                        <input type="checkbox" name="chkpage[]" value="16" <?php if (in_array("16", $getpermission1)){echo "checked"; }?>/> <label> Returns</label><br />
                        <input type="checkbox" name="chkpage[]" value="17" <?php if (in_array("17", $getpermission1)){echo "checked"; }?>/> <label> Cash Out Request</label><br />
                        
                        
                     <?php } ?>
                     
                       </div>
                          
                    </div>
                    <div id="Reviewtab" class="tab-pane fade">
                             <br>
                        <div class="form-group required">
                     <?php if(isset($_REQUEST['save_product'])){ ?>
                        
                        <input type="checkbox" name="chkpage[]" value="18" <?php if (in_array("18", $_POST['chkpage'])){echo "checked"; }?>/> <label> Sales Report</label><br />
                        <input type="checkbox" name="chkpage[]" value="19" <?php if (in_array("19", $_POST['chkpage'])){echo "checked"; }?>/> <label> PESO Partner Program Report</label><br />
                        <input type="checkbox" name="chkpage[]" value="20" <?php if (in_array("20", $_POST['chkpage'])){echo "checked"; }?>/> <label> Customer Activity Log</label><br />
                        <input type="checkbox" name="chkpage[]" value="21" <?php if (in_array("21", $_POST['chkpage'])){echo "checked"; }?>/> <label> Products Purchased Report</label><br />
                        <input type="checkbox" name="chkpage[]" value="22" <?php if (in_array("22", $_POST['chkpage'])){echo "checked"; }?>/> <label> Products Viewed Report</label><br />
                         <input type="checkbox" name="chkpage[]" value="22" <?php if (in_array("22", $_POST['chkpage'])){echo "checked"; }?>/> <label> Registration Report</label><br />
                      
                       
                     <?php } else { ?>
                        
                        <input type="checkbox" name="chkpage[]" value="18" <?php if (in_array("18", $getpermission1)){echo "checked"; }?>/> <label> Sales Report</label><br />
                        <input type="checkbox" name="chkpage[]" value="19" <?php if (in_array("19", $getpermission1)){echo "checked"; }?>/> <label> PESO Partner Program Report</label><br />
                        <input type="checkbox" name="chkpage[]" value="20" <?php if (in_array("20", $getpermission1)){echo "checked"; }?>/> <label> Customer Activity Log</label><br />
                        <input type="checkbox" name="chkpage[]" value="21" <?php if (in_array("21", $getpermission1)){echo "checked"; }?>/> <label> Products Purchased Report</label><br />
                        <input type="checkbox" name="chkpage[]" value="22" <?php if (in_array("22", $getpermission1)){echo "checked"; }?>/> <label> Products Viewed Report</label><br />
                        <input type="checkbox" name="chkpage[]" value="252528" <?php if (in_array("252528", $getpermission1)){echo "checked"; }?>/> <label> Registration Report</label><br />
                     <?php } ?>
                     
                      </div>
                    </div>
                    <div id="China_Brands" class="tab-pane fade">
                        <br>
                        <div class="form-group required">
                        <?php if(isset($_REQUEST['save_product'])){ ?>                        
                          <input type="checkbox" name="chkpage[]" value="29" <?php if (in_array("25", $_POST['chkpage'])){echo "checked"; }?>/> <label>Download China Products</label><br />
                          <input type="checkbox" name="chkpage[]" value="26" <?php if (in_array("26", $_POST['chkpage'])){echo "checked"; }?>/> <label>China Orders</label><br />
                          <input type="checkbox" name="chkpage[]" value="27" <?php if (in_array("27", $_POST['chkpage'])){echo "checked"; }?>/> <label>China Pending Orders</label><br />
                          <input type="checkbox" name="chkpage[]" value="28" <?php if (in_array("28", $_POST['chkpage'])){echo "checked"; }?>/> <label>Batch Orders</label><br />
                        <?php } else { ?>
                          <input type="checkbox" name="chkpage[]" value="29" <?php if (in_array("25", $getpermission1)){echo "checked"; }?>/> <label>Download China Products</label><br />
                          <input type="checkbox" name="chkpage[]" value="26" <?php if (in_array("26", $getpermission1)){echo "checked"; }?>/> <label>China Orders</label><br />
                          <input type="checkbox" name="chkpage[]" value="27" <?php if (in_array("27", $getpermission1)){echo "checked"; }?>/> <label>China Pending Orders</label><br />
                          <input type="checkbox" name="chkpage[]" value="252528" <?php if (in_array("252528", $getpermission1)){echo "checked"; }?>/> <label>Batch Orders</label><br />
                        <?php } ?>
                     
                       </div> 
                     </div>
                </div>       
            </div>
        </div>
   
  </div>
</form>
</div>

<?php include 'template/footer.php'; ?>
