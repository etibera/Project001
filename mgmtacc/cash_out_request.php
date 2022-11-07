<?php
include 'template/header.php';		
include "model/cash_out_request.php";

$perm = $_SESSION['permission'];
if (!strpos($perm, "'17';") !== false){
    header("Location: landing.php");
   
}

$cashout = new cashout();
?>

<div id="content">

  <div class="page-header">
      <h2 class="text-center">Cash Out Request</h2>
      
    </div>
   
  <div class="container-fluid">
    
    <div class="panel panel-default">
      <div class="panel-heading" style="padding:20px;">
        <div class="row">
          <div class="col-lg-6">
            <p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i> Cash Out Request List
            </p>
          </div>
          <div class="col-lg-6">
           
          </div>
     </div>
        
      </div>
      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="cashouttb">
            <tbody>
              
                   <thead>
                    <tr>
                      <th>Request Number</th>
                      <th>Customer Name</th>
                      <th>Cash Out Mode</th>
                      <th>Account Name</th>
                      <th>Account Number</th>
                      <th>Amount</th>
                      <th>Remarks</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <?php
                  foreach($cashout->getcashoutrequest() as $cashout):?>
                  <tr>
                    <td class="text-left" ><?php echo $cashout['id'];?></td>
                    <td class="text-left" ><?php echo $cashout['firstname']." ".$cashout['lastname'];?></td>
                    <td class="text-left" ><?php echo $cashout['cash_out_type'];?></td>
                    <td class="text-left" ><?php echo $cashout['account_name'];?></td>
                    <td class="text-left" ><?php echo $cashout['account_number'];?></td>
                    <td class="text-left" ><?php echo number_format($cashout['amount'],2);?></td>
                    <td class="text-left" ><?php echo $cashout['remarks'];?></td>
                    <td class="text-left" ><?php if($cashout['status'] == '1') { echo  '<span style="color:green;">Approved</span>'; } else if ($cashout['status'] == '2'){ echo  '<span style="color:red;">Disapproved</span>';  } else if ($cashout['status'] == '3'){ echo  '<span style="color:green;">Finished</span>';  } else { echo  '<span style="color:blue;">Pending</span>'; } ?></td>
          <td>
            <?php if($cashout['status'] == '0') { ?>
            <button class="btn btn-primary" data-id="<?php echo $cashout['id'];?>" id="btnapproved"><i class="fa fa-check"></i></button>
             <button class="btn btn-danger" data-id="<?php echo $cashout['id'];?>" id="btndisapproved"><i class="fa fa-ban"></i></button>
           <?php }else if ($cashout['status'] == '1' && $cashout['remarks'] == '') { ?>
            <button class="btn btn-success" data-id="<?php echo $cashout['id'];?>" data-type="<?php echo $cashout['cash_out_type'];?>" id="btnref"><i class="fas fa-edit"></i></button>
          <?php }else if ($cashout['status'] == '3' && $cashout['remarks'] != '') { ?>
             <button class="btn btn-warning" data-id="<?php echo $cashout['id'];?>" data-type="<?php echo $cashout['cash_out_type'];?>" id="btnprint"><i class="fa fa-print"></i></button>
           <?php }else { }?>  
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
              <p style="font-size: 23px" class="modal-title" id="modallabel"><strong>Disapproved</strong></p><input type="hidden" id="modid">
              
             
            </div>
            <div class="modal-body">

               <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="particular-table">
                  <thead>
                      <tr>
                        <th>Remarks</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          <input class="form-control"  type="text" placeholder="Remarks" id="remarks">
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </table>
              </div>
              <div class="form-group navbar-right" style="margin-right: 10px;">
                <button id="disapp" type="button" class="btn btn-danger btn-category-SAVE" ><i class="fa fa-ban"></i> Disapproved</button>
              </div>
              <br><br>
            </div>
          </div>
        </div>
      </div>


<!-- Large modal -->


      <div class="modal fade bd-example-modal-lg" id="AddrefModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
              <p style="font-size: 23px" class="modal-title" id="modallabel"><strong>Reference Number</strong></p><input type="hidden" id="modid1"><input type="hidden" id="reftype">
            </div>
            <div class="modal-body">

               <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="particular-table">
                  <thead>
                      <tr>
                        <th>Reference Number</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          <input class="form-control"  type="text" placeholder="Input Reference No." id="refnum">
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </table>
              </div>
              <div class="form-group navbar-right" style="margin-right: 10px;">
                <button id="addref" type="button" class="btn btn-primary btn-category-SAVE" ><i class="fa fa-save"></i> Save</button>
              </div>
              <br><br>
            </div>
          </div>
        </div>
      </div>


     
   
		
<?php include 'template/footer.php';?>

 <script>
   
  $( document ).ready(function() {
        
      
        $("#cashouttb").on("click","#btndisapproved",function(){
          var id = $(this).data('id');
        
         
          $("#modid").val(id);

          

          $('#AddModal').modal('show');

          
        });


        


        $("#cashouttb").on("click","#btnprint",function(){
          var id = $(this).data('id');
          var type = $(this).data('type');
          var cash;

          if (type == "Bank Deposit"){
            cash="1";
          }else if (type == "GCash") {
            cash="2";
          }else if (type == "Palawan Express") {
            cash="3";
          }else if (type == "Cebuana Lhuillier") {
            cash="4";
          }else{}

            

          window.open('printcashout.php?cashid='+id+'&cashtype='+cash, '_blank');      
           
          
        });


           $("#cashouttb").on("click","#btnref",function(){
          var id = $(this).data('id');
          var type = $(this).data('type');
        
         

          $("#modid1").val(id);
          $("#reftype").val(type);

          

          $('#AddrefModal').modal('show');

          
        });

        $("#cashouttb").on("click","#btnapproved",function(){
          var id = $(this).data('id');
          
          bootbox.confirm({
              message: "Are you sure you want to approve this?",
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
                      url: 'ajax_approved_cashout.php',
                      type: 'POST',
                      data: 'id=' + id,
                      dataType: 'json',
                      success: function(json) {
                          
                          if (json['success']=="Successfully Approved.") {
                             
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


        

        $('#disapp').click(function() {
          
         
          var remarks = $("#remarks").val();
          var id = $("#modid").val();

            
            if(remarks=="" ){

              bootbox.alert("Remarks must not be empty!!");
              return false;
            }



             bootbox.confirm({
              message: "Are you sure you want to disapprove this?",
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
                      url: 'ajax_disapproved_cashout.php',
                      type: 'POST',
                      data: 'id=' + id+'&remarks=' + remarks,
                      dataType: 'json',
                      success: function(json) {
                          
                          if (json['success']=="Successfully Disapproved.") {
                             
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


         $('#addref').click(function() {
          
         
          var refnum = $("#refnum").val();
          var id = $("#modid1").val();
          var type = $("#reftype").val();

            
            if(refnum=="" ){

              bootbox.alert("Reference No. must not be empty!!");
              return false;
            }



             bootbox.confirm({
              message: "Save this reference?",
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
                      url: 'ajax_saveref_cashout.php',
                      type: 'POST',
                      data: 'id=' + id+'&refnum=' + refnum+'&type=' + type,
                      dataType: 'json',
                      success: function(json) {
                          
                          if (json['success']=="Successfully Added.") {
                             
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


         


      });



       



 </script>