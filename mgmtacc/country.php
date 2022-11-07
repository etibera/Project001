<?php
include 'template/header.php';		
include "model/country.php";
if(!$session->is_signed_in()){redirect("index");}


$perm = $_SESSION['permission'];
if (!strpos($perm, "'12';") !== false){
    header("Location: landing.php");
   
}

$country = new country();
?>

<div id="content">

  <div class="page-header">
      <h2 class="text-center">Country Maintenance</h2>
      
    </div>
   
  <div class="container-fluid">
    
    <div class="panel panel-default">
      <div class="panel-heading" style="padding:20px;">
        <div class="row">
          <div class="col-lg-6">
            <p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i>  Country List
            </p>
          </div>
          <div class="col-lg-6">
            <div class="pull-right">

                  <button class="btn btn-primary pull-right" id="add-brand" class="btn btn-primary"><i class="fa fa-plus"></i></button>
              
            </div>
          </div>
     </div>
        
      </div>
      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="deliverytb">
            <tbody>
              
                   <thead>
                    <tr>
                      <th>Country Name</th>
                      <th>ISO Code (2)</th>
                      <th>ISO Code (3)</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <?php
                  foreach($country->getcountry() as $country):?>
                  <tr>
                    <td class="text-left" ><?php echo $country['name'];?></td>
                    <td class="text-left" ><?php echo $country['iso_code_2'];?></td>
                    <td class="text-left" ><?php echo $country['iso_code_3'];?></td>
                    
          <td>
            <button class="btn btn-primary" data-id="<?php echo $country['country_id'];?>" data-name="<?php echo $country['name'];?>" data-iso2="<?php echo $country['iso_code_2'];?>" data-iso3="<?php echo $country['iso_code_3'];?>"  id="btnedit"><i class="fa fa-edit"></i></button>
             <button class="btn btn-danger" data-id="<?php echo $country['country_id'];?>" id="btndelete"><i class="fa fa-ban"></i></button>
          </td>
          </tr>

          <?php endforeach;?>
                  
            
              
        
            </tbody>          
          </table>
        </div>
      </div>
    </div>
  </div>
</div>



 <!-- Large modal -->


      <div class="modal fade bd-example-modal-lg" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
          <div class="modal-content">
             <div class="modal-header">
              <a type="button"  data-dismiss="modal"   style="float: right;
                        font-size: 25px;
                        font-weight: 700;
                        line-height: 1;
                        color: #000;
                        text-shadow: 0 1px 0 #fff;
                      "><i class="fa fa-times-circle " style="color: black;font-size: 25px;" ></i></a>
              <br>
              <p style="font-size: 23px" class="modal-title" id="modallabel"><strong></strong></p><input type="hidden" id="modid">
              
             
            </div>
            <div class="modal-body">

               <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="particular-table">
                  <thead>
                      <tr>
                        <th>Country Name</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          <input class="form-control"  type="text" placeholder="Country" id="name">
                        </td>
                     
                      </tr>
                    </tbody>
                    <thead>
                      <tr>
                        <th>ISO Code 2</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          <input class="form-control"  type="text" placeholder="ISO Code 2" id="iso2">
                        </td>
                     
                      </tr>
                    </tbody>
                    <thead>
                      <tr>
                        <th>ISO Code 3</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          <input class="form-control"  type="text" placeholder="ISO Code 3" id="iso3">
                        </td>
                     
                      </tr>
                    </tbody>
                   
                  </table>
                </table>
              </div>
              <div class="form-group navbar-right" style="margin-right: 10px;">
                <button id="save_delivery" type="button" class="btn btn-primary btn-category-SAVE" ><i class="fa fa-save"></i> Save</button>
              </div>
              <br><br>
            </div>
          </div>
        </div>
      </div>


   
		
<?php include 'template/footer.php';?>

 <script>
   
  $( document ).ready(function() {
        
      
        $("#deliverytb").on("click","#btnedit",function(){
          var id = $(this).data('id');
          var name = $(this).data('name');
          var iso2 = $(this).data('iso2');
          var iso3= $(this).data('iso3');
   

          

          $("#modallabel").html("Update Country");
          $("#save_delivery").html('<i class="fa fa-save"></i> Update');

          $("#name").val(name);
          $("#iso2").val(iso2);
          $("#iso3").val(iso3);
          $("#modid").val(id);

          

          $('#AddModal').modal('show');

          
        });

        $("#deliverytb").on("click","#btndelete",function(){
          var id = $(this).data('id');
          
          bootbox.confirm({
              message: "Are you sure you want to delete this?",
              buttons: {
                  confirm: {
                      label: 'Yes',
                      className: 'btn-success'
                  },
                  cancel: {
                      label: 'No',
                      className: 'btn-danger'
                  }
              },
              callback: function (result) {
                  if(result==true){
                             $.ajax({
                      url: 'ajax_delete_country.php',
                      type: 'POST',
                      data: 'id=' + id,
                      dataType: 'json',
                      success: function(json) {
                          
                          if (json['success']=="Successfully Deleted.") {
                             
                            bootbox.alert(json['success'], function(){ 
                              location.reload();
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
              }
          });

          
        });


        $('#add-brand').click(function() {
          $("#modallabel").html("Add New Country");
          $("#save_delivery").html('<i class="fa fa-save"></i> Save');

          $("#name").val('');
          $("#iso2").val('');
          $("#iso3").val('');
          $("#modid").val('_null');

          $('#AddModal').modal('show');
        });

        $('#save_delivery').click(function() {
          
          var name= $("#name").val();
          var iso2 = $("#iso2").val();
          var iso3 = $("#iso3").val();
          var id = $("#modid").val();

            

            if(name=="" || iso2=="" || iso3=="" ){

              bootbox.alert("All fields must not be empty!!");
              return false;
            }

            if($("#iso2").val().length != 2){
                bootbox.alert("ISO Code 2 must contain 2 letters!!");
                return false;

            }

             if($("#iso3").val().length != 3){
                bootbox.alert("ISO Code 3 must contain 3 letters");
                return false;

            }




            if(id=="_null"){

          
               $.ajax({
              url: 'ajax_add_country.php',
              type: 'POST',
              data: 'name=' + name  + '&iso2='+ iso2+ '&iso3='+ iso3,
              dataType: 'json',
              success: function(json) {
                  
                  if (json['success']=="Successfully Saved.") {
                     
                    bootbox.alert(json['success'], function(){ 
                      location.reload();
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

            }else{

               $.ajax({
              url: 'ajax_edit_country.php',
              type: 'POST',
              data: 'id=' + id+'&name=' + name  + '&iso2='+ iso2 + '&iso3='+ iso3,
              dataType: 'json',
              success: function(json) {
                  
                  if (json['success']=="Successfully Updated.") {
                     
                    bootbox.alert(json['success'], function(){ 
                      location.reload();
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


         });


       });



       




 </script>