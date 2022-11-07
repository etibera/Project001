<?php
include 'template/header.php';
include "model/Coupon.php";
$coupon = new Coupon();       

$perm = $_SESSION['permission'];
if (!strpos($perm, "'11';") !== false){
    header("Location: landing.php");
   
} 

$list = $coupon->coupon_list();
?>
<div class="container">
<div class="row">
    <div class="form-group">
        <div class="col-sm-12">
             <span style="font-size: 26px" class="pull-left">Coupon List</span>
             <div class="pull-right">
                 <a href="./home.php" class="btn btn-danger" title="Back"><i data-feather="arrow-left"></i></a>
                 <a href="./coupon_update.php?cid=0" class="btn btn-primary" title="Add Coupon"><i data-feather="plus-circle"></i></a> 
             </div>
        </div>
    </div>
</div>
<br>
<div class="row">
        <div class="form-group col-xs-12">
                 <div class="table-responsive" style="overflow-x: auto">
            <table class="table table-striped table-bordered table-hover coupon-table">
            <thead>
                <tr>
                 <th>Coupon Name</th>
                 <th>Code</th>
                 <th>Discount</th>
                 <th>Date Start</th>
                 <th>Date End</th>
                 <th>Status</th>
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
                            foreach($list as $coupon)
                            {
                                    ?>
                                     <tr>
                                            <td><?php echo  $coupon['name'];?></td>
                                            <td><?php echo  $coupon['code'];?></td>
                                            <td><?php echo  $coupon['discount'];?></td>
                                            <td><?php echo  $coupon['date_start'];?></td>
                                            <td><?php echo  $coupon['date_end'];?></td>
                                            <td><?php echo  $coupon['stats'];?></td>
                                            <td>
                                                <div class="pull-right">
                                                    <a href="coupon_update.php?cid=<?php echo $coupon['coupon_id'];?>" class="btn btn-primary btn-edit"><i data-feather="edit-2"></i></a>
                                                    <button data-id="<?php echo $coupon['coupon_id'];?>"class="btn btn-danger btn-remove" title="Delete"><i data-feather="trash-2"></i></button>
                                                </div>
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

<?php include 'template/footer.php';?>                                                                  
        
 <script>
    $(document).ready(function() {

        $('.coupon-table').paging({   
            limit: 10,
            rowDisplayStyle: 'block',
            activePage: 0,
            rows: []
        });

        $('.coupon-table').on('click', '.btn-remove', function () {

            var getid = $(this).data("id");

            bootbox.confirm("Remove this Coupon?", function (result) {
                if (result == true) {
                    $.ajax({
                    url: 'ajax_delete_coupon.php',
                    type: 'POST',
                    data: {
                        coupon_id : getid
                    },
                    dataType: 'json',
                    success: function(json) {
                        bootbox.alert(''+json.success,function(){
                            location.replace('./coupon_list.php');
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

