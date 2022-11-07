<?php
include 'template/header.php';
include "model/Category.php";
$category = new Category();       

$perm = $_SESSION['permission'];
if (!strpos($perm, "'5';") !== false){
    header("Location: landing.php");
   
} 

$list = $category->category_list();
?>
<div class="container">
<div class="row">
    <div class="form-group">
        <div class="col-sm-12">
             <span style="font-size: 26px" class="pull-left">Category List</span>
             <div class="pull-right">
                 <a href="./home.php" class="btn btn-danger" title="Back"><i data-feather="arrow-left"></i></a>
                 <a href="./category_update.php?cid=0" class="btn btn-primary" title="Add Category"><i data-feather="plus-circle"></i></a> 
             </div>
        </div>
    </div>
</div>
<br>
<div class="row">
        <div class="form-group col-xs-12">
                 <div class="table-responsive" style="overflow-x: auto">
            <table class="table table-striped table-bordered table-hover category-table">
            <thead>
                <tr>
                 <th>Category Name</th>
                 <th>Sort Order</th>
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
                            foreach($list as $category)
                            {
                                    ?>
                                     <tr>
                                            <td><?php echo  $category['name'];?></td>
                                            <td><?php echo  $category['sort_order'];?></td>
                                            <td>
                                                <div class="pull-right">
                                                    <a href="category_update.php?cid=<?php echo $category['category_id'];?>" class="btn btn-primary btn-edit" data-category_id="<?php echo $category['category_id'];?>"><i data-feather="edit-2"></i></a>
                                                    <button data-id="<?php echo $category['category_id'];?>"class="btn btn-danger btn-remove" title="Delete"><i data-feather="trash-2"></i></button>
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

        $('.category-table').paging({   
            limit: 10,
            rowDisplayStyle: 'block',
            activePage: 0,
            rows: []
        });

        $('.category-table').on('click', '.btn-remove', function () {

            var getid = $(this).data("id");

            bootbox.confirm("Remove this Category?", function (result) {
                if (result == true) {
                    $.ajax({
                    url: 'ajax_delete_category.php',
                    type: 'POST',
                    data: 'category_id=' + getid,
                    dataType: 'json',
                    success: function(json) {
                        bootbox.alert(''+json.success,function(){
                            location.replace('./category_list.php');
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

