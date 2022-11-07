<?php 
include "common/header.php";
include "model/orderhistory.php";
$session->check_the_login2();
$model=new OrderHistory;

$id = $_SESSION['user_login'] ;
$username = $_SESSION['user_name'] ;

if(isset($_GET['order_id'])){
	$order = $model->order_details($_GET['order_id']);
}else{
	$order = "_null";
}

?>

<div class="container">

  <h2>Add Reviews</h2> <button class="btn btn-primary pull-right" type="button" id="save_review">Save</button>
  
 
  <div class="tab-content" style="margin-top: 20px">
    <div id="general" class="tab-pane fade in active">
     <div class="form-group required">
     	<label for="">Author</label>
     	<input type="text" class="form-control" id="author" value="<?php echo $username ?>" required>
     </div>
       <div class="form-group required">
        <label for="">Product</label>
        <select name="status" id="allproduct" class="form-control">
        	<option value="0">-Select-</option>
        	<?php foreach($order['products'] as $product){ console.log($product['product_id']); ?>
        		<option value="<?php echo $product['product_id'] ?>"><?php echo $product['name'] ?></option>
        	<?php } ?>
        </select>
     </div>
     <div class="form-group">
     	<label for="">Text</label>
     	<textarea class="form-control" cols="30" rows="10" id="description" ></textarea>
     </div>
     <div >
     	<label for="">Ratings</label><br>
        <label style="display:inline;margin-right: 10px;"><input type="radio" name="rate" value="1">1</label>
        <label style="display:inline;margin-right: 10px;"><input type="radio" name="rate" value="2">2</label> 
        <label style="display:inline;margin-right: 10px;"><input type="radio" name="rate" value="3">3</label> 
        <label style="display:inline;margin-right: 10px;"><input type="radio" name="rate" value="4">4</label> 
        <label style="display:inline;margin-right: 10px;"><input type="radio" name="rate" value="5">5</label>
     </div>
     <div class="form-group">
        <label for="">Status</label>
        <select name="status" id="input-status" class="form-control">
          
                <option value="1" selected="selected">Enabled</option>
                <option value="0" >Disabled</option>
           
        </select>
     </div>
    </div>
   
  </div>

</div>


<?php include "common/footer.php";?>


<script type="text/javascript">
  var islog='<?php echo $is_log;?>';

      if(islog=="0"){
      location.replace("home.php");
    }
  

    $( document ).ready(function() {
         



        $('#save_review').click(function() {

        var author= $("#author").val();
        var product_name= $("#allproduct").val();
        var description= $("#description").val();
        var status= $("#input-status").val();
        var ratings= $("input[name='rate']:checked").val();
        var customerid= "<?php echo $id ; ?>";

        
        if (ratings==null|| author==""|| product_name=="0"|| description==""){

            bootbox.alert("Please complete all the fields!!");
        }else{

             savereview(author,product_name,description,status,ratings,customerid);
        }
        


        });

      

       });



        function savereview(author,product_name,description,status,ratings,customerid) {
               $.ajax({
              url: 'ajax_add_review.php',
              type: 'POST',
              data: 'author=' + author  + '&product='+ product_name+'&desc='+ description+ '&status=' + status+ '&ratings=' + ratings+ '&customerid=' + customerid,
              dataType: 'json',
              success: function(json) {
                  
                  if (json['success']=="Successfully Saved.") {
                     
                    bootbox.alert(json['success'], function(){ 
                      window.location.replace("order_history.php")
                    });
                    
                  
                  }else{
                    bootbox.alert(json['success']);
                    return false;
                  }
                
              },
                  error: function(xhr, ajaxOptions, thrownError) {
                      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                  }
              });


          
        }

</script>
