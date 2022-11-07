<?php
 include 'template/header.php';
 include "model/reviews.php";
 if(!$session->is_signed_in()){redirect("index");}

$perm = $_SESSION['permission'];
if (!strpos($perm, "'13';") !== false){
    header("Location: landing.php");
   
}

$review = new Reviews();

$id = $_SESSION['user_id'] ;
$rid=$_GET['review_id'];
$edit = $review->getreview_list($_GET['review_id']);



?>
<div class="container">

  <h2>Edit Reviews</h2> <button class="btn btn-primary pull-right" type="button" id="save_review">Update</button>
  
 
  <div class="tab-content" style="margin-top: 20px">
    <div id="general" class="tab-pane fade in active">
     <div class="form-group required">
     	<label for="">Author</label>
     	<input type="text" class="form-control" id="author" value="<?php echo $edit['author']?>" required>
     </div>
       <div class="form-group required">
        <label for="">Product</label>
        <select name="status" id="allproduct" class="form-control">
        </select>
     </div>
     <div class="form-group">
     	<label for="">Text</label>
     	<textarea class="form-control" cols="30" rows="10" id="description" ><?php echo $edit['text']?></textarea>
     </div>
     <div >
     	<label for="">Ratings</label><br>
        <label style="display:inline;margin-right: 10px;"><input type="radio" name="rate" id="rad1" value="1">1</label>
        <label style="display:inline;margin-right: 10px;"><input type="radio" name="rate" id="rad2" value="2">2</label> 
        <label style="display:inline;margin-right: 10px;"><input type="radio" name="rate" id="rad3" value="3">3</label> 
        <label style="display:inline;margin-right: 10px;"><input type="radio" name="rate" id="rad4" value="4">4</label> 
        <label style="display:inline;margin-right: 10px;"><input type="radio" name="rate" id="rad5" value="5">5</label>
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

<?php include 'template/footer.php'; ?>


 <script>
   
  $( document ).ready(function() {
         getproducts();

       $("#input-status").val(<?php echo $edit['status']?>);

       if( $("#rad1").val()=='<?php echo $edit['rating']?>'){
          $( "#rad1" ).prop( "checked", true );
        
       }

       if( $("#rad2").val()=='<?php echo $edit['rating']?>'){
          $( "#rad2" ).prop( "checked", true );
       
       }

       if( $("#rad3").val()=='<?php echo $edit['rating']?>'){

          $( "#rad3" ).prop( "checked", true );
       }

       if( $("#rad4").val()=='<?php echo $edit['rating']?>'){

          $( "#rad4" ).prop( "checked", true );
       }

       if( $("#rad5").val()=='<?php echo $edit['rating']?>'){

          $( "#rad5" ).prop( "checked", true );
       }



        $('#save_review').click(function() {

        var author= $("#author").val();
        var product_name= $("#allproduct").val();
        var description= $("#description").val();
        var status= $("#input-status").val();
        var ratings= $("input[name='rate']:checked").val();
        var customerid= "<?php echo $id ; ?>";
        var reviewid= "<?php echo $rid ; ?>";

        
        if (ratings==null|| author==""|| product_name=="0"|| description==""){

            bootbox.alert("Please complete all the fields!!");
        }else{

             savereview(author,product_name,description,status,ratings,customerid,reviewid);
        }
        


        });

      

       });



        function savereview(author,product_name,description,status,ratings,customerid,reviewid) {
               $.ajax({
              url: 'ajax_edit_review.php',
              type: 'POST',
              data: 'author=' + author  + '&product='+ product_name+'&desc='+ description+ '&status=' + status+ '&ratings=' + ratings+ '&customerid=' + customerid+ '&reviewid=' + reviewid,
              dataType: 'json',
              success: function(json) {
                  
                  if (json['success']=="Successfully Updated.") {
                     
                    bootbox.alert(json['success'], function(){ 
                      window.location.replace("reviews.php")
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

          function getproducts() {
              $.ajax({
              url: 'ajax_get_reviews.php',
              type: 'GET',
              data: 'trigger=1' ,
              dataType: 'json',
              success: function(json) {

               

                $("#allproduct").empty();
                    $("#allproduct").append('<option value="0">-Select-</option>');
                     for (var i = 0; i < json.length; i++) {
                        $("#allproduct").append('<option value="'+json[i].product_id+'">'+json[i].model+'</option>');

                      

                    }
                $("#allproduct").val('<?php echo $edit['product_id']?>');
                 
                               },
                  error: function(xhr, ajaxOptions, thrownError) {
                      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                  }
              });


          
        }





 </script>