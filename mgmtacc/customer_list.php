<?php
include 'template/header.php';
include "model/Customer.php";
$product = new Customer();      

$perm = $_SESSION['permission'];
if (!strpos($perm, "'8';") !== false){
    header("Location: landing.php");
   
} 

if(isset($_REQUEST['btn_search'])) {
    $customer=$_REQUEST["customer"]; 
    if(isset($_GET['type'])) {
        if($_GET['type']=="Verified"){
            $list = $product->CustomerListVerifiedReg($customer);
           
        }else if($_GET['type']=="VerifiedGAF"){
             $list = $product->CustomerListVerifiedGAF('');
        }else{
             $list = $product->CustomerListUnVerified($customer);
        }
       
    }else{
        $list = $product->customer_list($customer);
    }  
   
}else{
    if(isset($_GET['type'])) {
        if($_GET['type']=="Verifiedreg"){
            $list = $product->CustomerListVerifiedReg('');
           
        }else if($_GET['type']=="VerifiedGAF"){
             $list = $product->CustomerListVerifiedGAF('');
        }else{
             $list = $product->CustomerListUnVerified('');
        }
       
    }else{
         $list = $product->customer_list('');
    }  
   
}
?>
<div class="container">
<div class="row">
    <div class="form-group col-xs-12">
        <h2>Customer List</h2>
    </div>
    <div class="form-group">
        <form method="post" action="customer_list.php?type=<?php echo $_GET['type'];?>">
            <div class="form-group">
                <div class="col-lg-6">
                        <input type="text" name="customer"  class="form-control" placeholder="Search by Customer Name" value="<?php if(isset($_REQUEST['btn_search'])){echo $_REQUEST["customer"]; }?>"/ >
                </div>
                <div class="col-lg-2">
                        <button type="submit" name="btn_search" class="btn btn-success"><i data-feather="search"></i></button>
                </div>                                         
            </div>                                         
        </form>
    </div>
</div><br>
<div class="row">
        <div class="form-group col-xs-12">
                 <div class="table-responsive" style="overflow-x: auto">
                        <table class="table table-striped table-bordered table-hover customer-table">
                                <thead>
                    <tr>
                     <th>Customer Name</th>
                     <th>Email</th>
                     <th>Phone Number</th>
                     <th>Customer Group</th>
                     <th>Status</th>
                     <th>Date Added</th>
                     <th>Type</th>
                     <th>Action</th>
                    </tr>
            </thead> 
            <tbody>
                <script>showLoading(); </script>
                 <?php                                  
                        if(count($list) == 0){
                                ?>
                                 <tr>
                                        <td colspan="6" align="center">No data found.</td>
                                        
                                 </tr>
                                <?php
                        }else{
                            foreach($list as $customer)
                            {
                                    ?>
                                     <tr>
                                            <td><?php echo  $customer['customer'];?></td>
                                            <td><?php echo  $customer['email'];?></td>
                                            <td><?php echo  $customer['telephone'];?></td>
                                            <td><?php echo  $customer['customer_group'];?></td>
                                            <td><?php if($customer['status'] == '1') { 
                                                            echo '<span style="color:green;">Enabled</span>'; 
                                                    } else {
                                                            echo '<span style="color:red;">Disabled</span>';
                                                }?>                                                           
                                            </td>
                                            <td><?php echo  $customer['date_added'];?></td>
                                            <td><?php echo  $customer['type'];?></td>
                                            <td>
                                            <a href="customer_update.php?cid=<?php echo $customer['customer_id'];?>" class="btn btn-primary btn-edit" data-product_id="<?php echo $customer['customer_id'];?>"><i data-feather="edit-2"></i></a>
                                            </td>
                                     </tr>

                                    <?php
                            }
                        }                                                                           
                ?>
                <script> hideLoading();</script>
                </tbody>
                        </table>
                </div>
                
        </div>
        </div>
</div>  

<?php include 'template/footer.php';?>                                                                  
        
 <script>

    $(document).ready(function() {
    $('.customer-table').DataTable({"order": [],
      "oLanguage": {
        "sSearch": "Quick Search:"
      },
      "bSort": true,
      "dom": 'Blfrtip',
      "buttons": [{
          extend: 'excel',
          title: 'Registration Report',
        },
        {
          extend: 'pdf',
          title: 'Registration Report',
         
        },
        {
          extend: 'print',
          title: 'Registration Report',
        },
      ],
      "lengthMenu": [
        [15, 50, 100,-1],
        [15, 50, 100,"all"]
      ],});
       /* $('.customer-table').paging({   
            limit: 50,
            rowDisplayStyle: 'block',
            activePage: 0,
            rows: []
        });
*/
    });
 </script>

