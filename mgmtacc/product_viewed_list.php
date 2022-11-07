<?php
include 'template/header.php';
include "model/ProductViewed.php";

$perm = $_SESSION['permission'];
if (!strpos($perm, "'22';") !== false){
    header("Location: landing.php");
   
}

$viewed = new ProductViewed();       
$list = $viewed->product_viewed_list('');
if(isset($_REQUEST['btn_search'])) {
    $product_name=$_REQUEST["product_name"];                
    $list = $viewed->product_viewed_list($product_name);
}
else{
    $list = $viewed->product_viewed_list('');
}
?>
<div class="container">
<div class="row">
    <div class="form-group">
        <div class="col-sm-12">
             <span style="font-size: 26px" class="pull-left">Products Viewed Report</span>
        </div>
    </div>
    <br>
    <br>
    <div class="form-group col-sm-12 well">
        <form method="post" class="form-horizontal" action="product_viewed_list.php">
            <div class="form-group">
                <div class="col-sm-6" style="margin-bottom: 5px">
                    <input type="text" name="product_name"  class="form-control" placeholder="Product Name" value="<?php if(isset($_REQUEST['btn_search'])){echo $_REQUEST["product_name"]; }?>"/ >
                </div>    
                <div class="col-sm-3">
                    <button type="submit" name="btn_search" class="btn btn-success" title="Search"><i class="fas fa-search"></i></button> <!-- 
                    <button type="button" class="btn btn-danger btn-reset" title="Reset"><i class="fas fa-sync-alt"></i></button>  -->
                </div>                
            </div>        
        </form>
    </div>
</div>
<br>
<div class="row">
        <div class="form-group col-xs-12">
                 <div class="table-responsive" style="overflow-x: auto">
            <table class="table table-striped table-bordered table-hover viewed-table">
            <thead>
                <tr>
                 <th>Product Name</th>
                 <th>Model</th>
                 <th>Type</th>
                 <th>Viewed</th>
                 <th>Percent</th>
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
                            foreach($list as $viewed)
                            {
                                    ?>
                                     <tr>
                                            <td><?php echo  $viewed['name'];?></td>
                                            <td><?php echo  $viewed['model'];?></td>
                                            <td><?php if($viewed['p_type']==0){echo "Local Producs";}else{echo "Global Producs";}?></td>
                                            <td><?php echo  $viewed['viewed'];?></td>
                                            <td><?php echo  $viewed['percentage'].'%';?></td>
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

<?php include 'template/footer.php';?>                                                                  
        
 <script>
    $(document).ready(function() {

        $('.viewed-table').paging({   
            limit: 100,
            rowDisplayStyle: 'block',
            activePage: 0,
            rows: []
        });

        $('.btn-reset').on('click', function () {

            bootbox.confirm("Are you sure you want to reset?", function (result) {
                if (result == true) {
                    $.ajax({
                    url: 'ajax_reset_viewed.php',
                    type: 'POST',
                    dataType: 'json',
                    success: function(json) {
                        bootbox.alert(''+json.success,function(){
                            location.replace('./product_viewed_list.php');
                        });
                    },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }

            });

        });

    });
 </script>

