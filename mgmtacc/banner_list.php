<?php
include 'template/header.php';
include "model/Banner.php";

$model = new Banner();       

$perm = $_SESSION['permission'];
if (!strpos($perm, "'6';") !== false){
    header("Location: landing.php");
   
} 

$list = $model->banner_list();
?>
<div class="container">
<div class="row">
    <div class="form-group">
        <div class="col-sm-12">
             <span style="font-size: 26px" class="pull-left">Banner List</span>
             <div class="pull-right">
                 <a href="./home.php" class="btn btn-danger" title="Back"><i data-feather="arrow-left"></i></a>
                 <a href="./banner_update.php?bid=0" class="btn btn-primary" title="Add Banner"><i data-feather="plus-circle"></i></a> 
             </div>
        </div>
    </div>
</div>
<br>
<div class="row">
        <div class="form-group col-xs-12">
        <div class="table-responsive" style="overflow-x: auto">
            <table class="table table-striped table-bordered table-hover banner-table">
            <thead>
                <tr>
                 <th>Banner Name</th>
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
                            foreach($list as $banner)
                            {
                                    ?>
                                     <tr>
                                            <td><?php echo  $banner['name'];?></td>
                                            <td><?php echo  $banner['stats'];?></td>
                                            <td>
                                                <div class="pull-right">
                                                    <a href="banner_update.php?bid=<?php echo $banner['banner_id'];?>" class="btn btn-primary btn-edit" data-banner_id="<?php echo $category['banner_id'];?>"><i data-feather="edit-2"></i></a>
                                                    <button data-id="<?php echo $banner['banner_id'];?>"class="btn btn-danger btn-remove" title="Delete"><i data-feather="trash-2"></i></button>
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

        $('.banner-table').paging({   
            limit: 10,
            rowDisplayStyle: 'block',
            activePage: 0,
            rows: []
        });

        $('.banner-table').on('click', '.btn-remove', function () {

            var getid = $(this).data("id");

            bootbox.confirm("Remove this Banner?", function (result) {
                if (result == true) {
                    $.ajax({
                    url: 'ajax_delete_banner.php',
                    type: 'POST',
                    data: 'banner_id=' + getid,
                    dataType: 'json',
                    success: function(json) {
                        bootbox.alert(''+json.success,function(){
                            location.replace('./banner_list.php');
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

