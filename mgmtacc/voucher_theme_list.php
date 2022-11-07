<?php
include 'template/header.php';
include "model/Voucher.php";
$voucher = new Voucher();   


$perm = $_SESSION['permission'];
if (!strpos($perm, "'10';") !== false){
    header("Location: landing.php");
   
} 

$list = $voucher->voucher_theme_list();
?>
<div class="container">
<div class="row">
    <div class="form-group">
        <div class="col-sm-12">
             <span style="font-size: 26px" class="pull-left">Voucher Theme List</span>
             <div class="pull-right">
                 <a href="./home.php" class="btn btn-danger" title="Back"><i data-feather="arrow-left"></i></a>
                 <a href="./voucher_theme_update.php?vid=0" class="btn btn-primary" title="Add Voucher Theme"><i data-feather="plus-circle"></i></a> 
             </div>
        </div>
    </div>
</div>
<br>
<div class="row">
        <div class="form-group col-xs-12">
                 <div class="table-responsive" style="overflow-x: auto">
            <table class="table table-striped table-bordered table-hover voucher-table">
            <thead>
                <tr>
                 <th>Theme Name</th>
                 <th>Theme Image</th>
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
                            foreach($list as $voucher)
                            {
                                    ?>
                                     <tr>
                                            <td><?php echo  $voucher['name'];?></td>
                                            <td>
                                                <?php  $img ="../img/".$voucher['image']; ?>
                                                <?php if(file_exists($img)): ?>
                                                <div class="image-container" style=" height: 50px; width:  50px;overflow:hidden;border: groove 1px;">
                                                    <img src="<?php echo $img; ?>" class="img-responsive" id="img_voucher"/>
                                                </div>
                                                <?php else: ?>              
                                                <span>
                                                <div class="image-container" style=" height: 50px; width:  50px;border: groove 1px;">
                                                    <img src="../fonts/feathericons/icons/image.svg" style=" height: 50px; width:  50px;" class="img-responsive" id="img_voucher"/>
                                                </div>
                                                </span>
                                                <?php endif; ?> 
                                            </td>
                                            <td>
                                                <div class="pull-right">
                                                    <a href="voucher_theme_update.php?vid=<?php echo $voucher['voucher_theme_id'];?>" class="btn btn-primary btn-edit"><i data-feather="edit-2"></i></a>
                                                    <button data-id="<?php echo $voucher['voucher_theme_id'];?>"class="btn btn-danger btn-remove" title="Delete"><i data-feather="trash-2"></i></button>
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

        $('.voucher-table').paging({   
            limit: 10,
            rowDisplayStyle: 'block',
            activePage: 0,
            rows: []
        });

        $('.voucher-table').on('click', '.btn-remove', function () {

            var getid = $(this).data("id");

            bootbox.confirm("Remove this voucher theme?", function (result) {
                if (result == true) {
                    $.ajax({
                    url: 'ajax_delete_voucher.php',
                    type: 'POST',
                    data: {
                        voucher_id : getid,
                        type : '1'
                    },
                    dataType: 'json',
                    success: function(json) {
                        bootbox.alert(''+json.success,function(){
                            location.replace('./voucher_theme_list.php');
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

