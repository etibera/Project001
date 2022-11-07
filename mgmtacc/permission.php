<?php
include 'template/header.php';
include "model/permission.php";
if(!$session->is_signed_in()){redirect("index");}
$product = new permission();  

$perm = $_SESSION['permission'];
if (!strpos($perm, "'1';") !== false){
    header("Location: landing.php");
   
}    
     if(isset($_GET['delid']))
    {  
    
    $stats = $product->deleteuser($_GET['delid']);

    }

if(isset($_REQUEST['btn_search'])) {
    $fname=$_REQUEST["fname"];
    $lname=$_REQUEST["lname"];
    $user=$_REQUEST["user"];
 
                    
    $list = $product->getuser($fname,$lname,$user);
 }
 else{
    $list = $product->getuser('','','');
 }
     foreach ($list as $listdata ) {
        
         $list2= $product->getaccess1($listdata['user_id']);

         $permission="";

         foreach ($list2 as $list2data) {
             $permission.=$list2data['page'].",";
         }
      
         $arrayhead[] = array(
        'username'    => $listdata['username'],
        'fullname'    => $listdata['firstname']." ".$listdata['lastname'],
        'id'    =>  $listdata['user_id'],
        'details'    => $permission
        );
     }


         
?>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <center><h2>User Management</h2></center><br>
            <a href="add_permission.php"class="btn btn-primary pull-right" >Add User</a></br>
            <div class="col-xs-12 well">
                <form method="post" class="form-horizontal" action="permission.php">
                   
                    <div class="form-group">
                        <div class="col-sm-6">
                            <input type="text" name="fname"  class="form-control" placeholder="First Name" value="<?php if(isset($_REQUEST['btn_search'])){echo $_REQUEST["fname"]; }else { echo ''; }?>"/ >
                        </div>
                        <div class="col-sm-6">
                           <input type="text" name="lname"  class="form-control" placeholder="Last Name" value="<?php if(isset($_REQUEST['btn_search'])){echo $_REQUEST["lname"]; }else { echo ''; }?>"/ >
                        </div>
                    </div>
                     <div class="form-group">
                        <div class="col-sm-6">
                            <input type="text" name="user"  class="form-control" placeholder="Username" value="<?php if(isset($_REQUEST['btn_search'])){echo $_REQUEST["user"]; }else { echo ''; }?>"/ >
                        </div>
                        <div class="col-sm-6">
                            <input type="submit" name="btn_search" class="btn btn-success" value="Search">
                        </div>
                    </div>
                </form>
            </div>
         </div>
        <div class="row">
        <div class="col-xs-12">
             <div class="table-responsive" style="overflow-x: auto">
                <table class="table table-striped table-bordered table-hover" id="table-product">
                    <thead>
                        <tr>
                         <th>Username</th>
                         <th>Fullname</th>
                         <th>Pemissions</th>
                          <th>Action</th>
                        </tr>
                    </thead> 
                    <tbody>
                     <?php                  
                        if(count($list) == 0){
                            ?>
                             <tr>
                                <td colspan="6" align="center">No data found.</td>
                                
                             </tr>
                            <?php
                        }else{
                            foreach($arrayhead as $product)
                            {
                                ?>
                                 <tr>
                                    <td><?php echo  $product['username'];?></td>
                                    <td><?php echo  $product['fullname'];?></td>
                                    <td><?php echo  $product['details'];?></td>
                                   
                                   
                                    <td><a href="edit_permission.php?user_id=<?php echo $product['id'];?>" class="btn btn-primary btn-edit" data-product_id="<?php echo $product['id'];?>">Edit</a>
                                        <a class="btn btn-danger"
                                             href="permission.php?delid=<?php echo  $product['id'];?>" 
                                             onclick="return confirm('Are you sure you want to delete <?php echo $product['fullname']?>?');"  >Delete
                                         </a>
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
        </div>
    </div>  
</div>

<?php include 'template/footer.php';?>                                  
    
 <script>
    $(document).ready(function() {

        if($("#status") != '')
        {
            $("#d_status").val($("#status").val());
        }

        $("#d_status").on('change', function(){
            $('#status').val($(this).val());

        });
    });
 </script>

