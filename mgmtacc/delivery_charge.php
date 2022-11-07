<?php
include 'template/header.php';		
include "model/delivery_charge.php";
if(!$session->is_signed_in()){redirect("index");}

$delivery = new delivery_charge();

$perm = $_SESSION['permission'];
if (!strpos($perm, "'4';") !== false){
    header("Location: landing.php");
   
} 
?>

<div id="content">

  <div class="page-header">
      <h2 class="text-center">Manage Delivery Charge</h2>
      
    </div>
   
  <div class="container-fluid">
    
    <div class="panel panel-default">
      <div class="panel-heading" style="padding:20px;">
        <div class="row">
          <div class="col-lg-6">
            <p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i> Delivery Charge List
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
           
              <thead>
                <tr>
                  <th>Name</th>                     
                  <th>NCR /Same Province</th>
                  <th>Provincial /Cross Province</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              <?php
              foreach($delivery->getdelivery() as $delivery):?>
                <tr>
                  <td class="text-left" ><?php echo $delivery['name'];?></td>
                  <td class="text-left" ><?php echo $delivery['amount'];?></td>
                  <td class="text-left" ><?php echo $delivery['provincial_amount'];?></td>
                  <td>
                  <button class="btn btn-primary" data-prv="<?php echo $delivery['provincial_amount'];?>" data-id="<?php echo $delivery['id'];?>" data-name="<?php echo $delivery['name'];?>" data-mqty="<?php echo $delivery['max_quantity'];?>" data-cinto="<?php echo $delivery['convert_into'];?>" data-cqty="<?php echo $delivery['convert_quantity'];?>" data-amount="<?php echo $delivery['amount'];?>" data-cid="<?php echo $delivery['convert_id'];?>" id="btnedit"><i class="fa fa-edit"></i></button>
                   <button class="btn btn-danger" data-id="<?php echo $delivery['id'];?>" id="btndelete"><i class="fa fa-ban"></i></button>
                  </td>
                </tr>
              <?php endforeach;?>
            </tbody>          
          </table>
        </div>
      </div>
    </div>
  </div>
</div> <!-- Large modal -->
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
                        <th colspan="2">Name</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td colspan="2">
                          <input class="form-control"  type="text" placeholder="Name" id="name">
                          <input class="form-control"  type="hidden" value="1" id="max_quantity">
                          <input class="form-control"  type="hidden" placeholder="Convert Quantity" value="1" id="convert_quantity">
                          <input class="form-control"  type="hidden" placeholder="Convert Quantity" value="1" id="delivery_option">
                        </td>
                      </tr>
                    </tbody>
                    <thead>
                      <tr>
                        <th colspan="2">NCR /Same Province Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td colspan="2"><input class="form-control"  type="number" placeholder="Amount" id="txtamount"></td>
                      </tr>
                    </tbody>
                     <thead>
                      <tr>
                        <th colspan="2">Provincial /Cross Province Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td colspan="2"><input class="form-control"  type="number" placeholder="Amount" id="txtamountprv"></td>
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
        getdeliverycharge();
      
        $("#deliverytb").on("click","#btnedit",function(){
          var id = $(this).data('id');
          var name = $(this).data('name');
          var max_quantity = $(this).data('mqty');
          var convert_into = $(this).data('cinto');
          var convert_quantity = $(this).data('cqty');
          var amount = $(this).data('amount');
          var convert_id = $(this).data('cid');
          var prv_amount = $(this).data('prv');
          $("#modallabel").html("Update Delivery Charge");
          $("#save_delivery").html('<i class="fa fa-save"></i> Update');

          $("#name").val(name);
          $("#max_quantity").val(max_quantity);
          $("#convert_quantity").val(convert_quantity);
          $("#convert_into").val(convert_into);
          $("#txtamount").val(amount);
          $("#txtamountprv").val(prv_amount);
          $("#delivery_option").val(convert_id);
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
                      url: 'ajax_delete_deliverycharge.php',
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
          $("#modallabel").html("Add Delivery Charge");
          $("#save_delivery").html('<i class="fa fa-save"></i> Save');
          $("#modid").val('_null');
          $('#AddModal').modal('show');
        });
        $('#save_delivery').click(function() {          
          var name= $("#name").val();
          var max_quantity = $("#max_quantity").val();
          var convert_quantity = $("#convert_quantity").val();
          var amount = $("#txtamount").val();
          var delivery_option = $("#delivery_option").val();
          var amountprv = $("#txtamountprv").val();

          var id = $("#modid").val();            
            if(name=="" || max_quantity=="" || convert_quantity =="" || amount =="" || amountprv ==""  || delivery_option == "0"  ){
              bootbox.alert("All fields must not be empty!!");
              return false;
            }

            if(id=="_null"){
               $.ajax({
              url: 'ajax_add_deliverycharge.php',
              type: 'POST',
              data: 'name=' + name  + '&max_quantity='+ max_quantity+ '&convert_quantity=' + convert_quantity+ '&amount=' + amount+ '&delivery_option=' + delivery_option + '&amountprv=' + amountprv,
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
              url: 'ajax_edit_deliverycharge.php',
              type: 'POST',
              data: 'id=' + id+'&name=' + name  + '&max_quantity='+ max_quantity+ '&convert_quantity=' + convert_quantity+ '&amount=' + amount+ '&delivery_option=' + delivery_option + '&amountprv=' + amountprv,
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



        function getdeliverycharge() {
              $.ajax({
              url: 'ajax_get_deliverycharge.php',
              type: 'GET',
              data: 'trigger=1' ,
              dataType: 'json',
              success: function(json) {

                

                $("#delivery_option").empty();
                    $("#delivery_option").append('<option value="0">-Select-</option>');
                     for (var i = 0; i < json.length; i++) {
                        $("#delivery_option").append('<option value="'+json[i].id+'">'+json[i].name+'</option>');
                      

                    }
                 
                               },
                  error: function(xhr, ajaxOptions, thrownError) {
                      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                  }
              });


          
        }




 </script>