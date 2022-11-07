<?php
include 'template/header.php';
require_once "model/Pendingreceivables.php";
require_once "model/Home.php";
$modelHome=new Home;
$model=new PendingReceivables;
$date_to="notset";
$date_from="notset";
$OrderId="notset";
$FundStatus="notset";
if(isset($_GET['OrderId'])){
 $OrderId=$_GET['OrderId'];
}
if(isset($_GET['FundStatus'])){
 $FundStatus=$_GET['FundStatus'];
}
if(isset($_GET['date_to'])){
 $date_to=$_GET['date_to'];
}
if(isset($_GET['date_from'])){
 $date_from=$_GET['date_from'];
}
if(isset($_GET['PageNumber'])){
 $PageNumber=$_GET['PageNumber'];
}else{
  $PageNumber=1;
}
if(isset($_GET['order_status'])){
 $status_id=$_GET['order_status'];
}else{
 $status_id=17;
}


$perm = $_SESSION['permission'];	
if (!strpos($perm, "'252527';") !== false){
    header("Location: landing.php");
}
$data=array();
if($status_id=="All"){
  $data = $model->GetDefPendingReceivables($PageNumber,$date_from,$date_to,$OrderId,$FundStatus);
  $dataPageNumber = $model->getDeffRGPageNumber($date_from,$date_to,$OrderId,$FundStatus);
}else{
  $data = $model->GetPendingReceivables($PageNumber,$status_id,$date_from,$date_to,$OrderId,$FundStatus);
  $dataPageNumber = $model->getRGPageNumber($status_id,$date_from,$date_to,$OrderId,$FundStatus);
}
?>
<style type="text/css">
      .ui-autocomplete {
        z-index: 215000000 !important;
        position: relative;
        font-family: Nunito,'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
    }
    .ui-front {
        z-index: 9999;
    }
</style>

<div id="content" style="margin-top: 10px">
    <div class="container-fluid">
      <div class="panel panel-default">
        <div class="panel-heading" style="padding:20px;">
          <div class="row">
              <div class="col-lg-6">
                 <p style="font-weight: 700;" class="panel-title"><a class="btn btn-success" href="PendingreceivablesPrint.php?order_status=<?php echo $status_id;?>&date_to=<?php echo $date_to;?>&date_from=<?php echo $date_from;?>&OrderId=<?php echo $OrderId;?>&FundStatus=<?php echo $FundStatus;?>&t=<?php echo uniqid();?>" id="add-brand" data-toggle="tooltip" title="Print" class="btn btn-primary"><i class="fa fa-print"></i></a> Pending Receivables</p>
                
              </div>             
               <div class="col-lg-6">
                  <a class="btn btn-primary pull-right" id="approveReceivables" title="Confirm Payment" style="margin-left:5px;"><i class="fas fa-check-circle"></i> Confirm Payment</a>
              </div>
          </div>    
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-sm-12">
            <div class="well">
              <div class="row">
                 <div class="col-sm-2">
                  <div class="form-group">
                    <label class="control-label" for="input-order-id">Order Id</label>
                    <input type="text" id="OrderId" class="form-control" value="<?php echo isset($_GET['OrderId']) && $_GET['OrderId']!='notset'? $_GET['OrderId'] :''; ?>" required/>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label class="control-label" for="input-order-id">Date From:</label>
                    <input type="date" id="date_from" class="form-control" value="<?php echo isset($_GET['date_from'])? $_GET['date_from'] :''; ?>" required/>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label class="control-label" for="input-order-id">Date To:</label>
                    <input type="date" id="date_to" class="form-control" value="<?php echo isset($_GET['date_to'])? $_GET['date_to'] :''; ?>" required/>
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="form-group">
                    <label class="control-label" for="input-order-id">Fund status</label>
                     <select name="FundStatus" id="FundStatus" class="form-control" required>
                        <option value="">--Select Fund status--</option>
                        <?php if(isset($_GET['FundStatus'])){ ?> 
                          <?php if($_GET['FundStatus']=="Paid"){ ?> 
                            <option value="Paid" selected>Paid</option>
                            <option value="UnPaid">UnPaid</option>
                          <?php }else if($_GET['FundStatus']=="UnPaid"){ ?> 
                             <option value="Paid" >All Paid</option>
                             <option value="UnPaid" selected>UnPaid</option>
                          <?php }else{ ?>
                             <option value="Paid" >All Paid</option>
                             <option value="UnPaid">UnPaid</option>
                          <?php } ?>
                        <?php }else{ ?> 
                            <option value="Paid">Paid</option>
                            <option value="UnPaid">UnPaid</option>
                        <?php } ?>
                      
                     </select>
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="form-group">
                    <label class="control-label" for="input-order-id">Order Status:</label>
                    <select name="order_status" id="order_status" class="form-control" required>  
                      <option value="">--Select Status--</option>
                      <?php if(isset($_GET['order_status'])){ ?> 
                        <?php if($_GET['order_status']=="All"){ ?> 
                          <option value="All" selected>All Status</option>
                        <?php }else{ ?> 
                          <option value="All">All Status</option>
                        <?php } ?>
                      <?php }else{ ?> 
                         <option value="All">All Status</option>
                      <?php } ?>
                     
                      <?php foreach($modelHome->order_status() as $stats):?>
                        <?php if(isset($_GET['order_status'])){ ?>    
                          <?php if($stats['order_status_id']==$_GET['order_status']){ ?> 
                             <option value="<?php echo $stats['order_status_id']; ?>" selected ><?php echo $stats['name'];?></option>
                          <?php }else{ ?> 
                             <option value="<?php echo $stats['order_status_id']; ?>"><?php echo $stats['name'];?></option>
                          <?php } ?>  
                        <?php }else{ ?> 
                          <option value="<?php echo $stats['order_status_id']; ?>"><?php echo $stats['name'];?></option>
                        <?php } ?>
                      <?php endforeach;?>     
                  </select>
                  </div>
                </div>                
              </div>
            </div>
            </div> 
          </div><br>
          <div class="row">
            <div class="col-lg-12">
             <table class="customer-table table table-striped table-bordered table-hover nowrap w-100">
              <thead>                 
                 <th class="text-center" colspan="8"style="vertical-align: middle;">
                      <input type="text" id="customer-name"  class="form-control customer-name" placeholder="Quick search">
                  </th>
                  <th class="text-center"colspan="3" style="vertical-align: middle;width: 10px">
                  	<?php if($dataPageNumber!=0){?>
                      <select class="form-control" name="pageNumber" id="pageNumber">
                        <?php for ($x = 1; $x <=$dataPageNumber; $x++) { ?>
                            <?php if($x==$PageNumber){ ?>
                               <option value="<?php echo $x;?>" selected>Page <?php echo $x;?></option>
                            <?php }else{ ?>
                              <option value="<?php echo $x;?>">Page <?php echo $x;?></option>
                            <?php }?>
                           
                         <?php  }  ?>                       
                      </select> 
                    <?php  }  ?>   
                  </th>
              </thead>               
              <thead>
                 <th class="text-center" style="width: 120px;"style="vertical-align: middle;">
                    <a class="btn btn-success" id="tbl_select_all" title="Select All Ccustomer" onclick="tbl_CheckAllCustomer()" style="width: 90px;">
                       <i class="fa fa-check-square"></i>
                    </a>
                    <span id="tbl_select_all_span"><b>Select All</b></span>
                    <a style="display: none;width: 90px;"class="btn btn-success" id="tbl_unselect_all" title="Un Select All Product" onclick="tbl_UnCheckAllCustomer()" >
                        <i class="fa fa-square"></i>
                    </a>
                    <span id="tbl_unselect_all_span"style="display: none;"><b> Un Select All</b></span> 
                </th>
               

              	<th >Order Id</th>	
                <th>Customer Name</th>
                <th>Payment Method</th>
                <th>Order Status</th>
                <th>Amount</th>
                <th>Bank Accounts</th>
                <th>OPS Verification</th>
                <th>Fund status</th>
                <th >Date Added</th>
                <th >Date of Sales</th>
              </thead>
              <tbody id="customer-table">
                <?php
                if(count($data) > 0){ 
                foreach($data as $o):
              ?>
              <tr>
                <td class="text-center">
                  <div class="tdchkCust_div"  id="customer-div_<?php echo $o['order_id'];?>" >
                    <input  type="checkbox" name="chkMobileNumber[]" value="<?php  echo $o['order_id'];?>" />
                  </div>
                </td>
               	<td><a href="view_order_new.php?order_id=<?php echo $o['order_id'];?>" target="_blank"> <?php echo $o['order_id'];?></a></td>  			
                <td><?php echo $o['fullname'];?></td>
                <td><?php echo $o['payment_method'];?></td>
                <td><?php echo $o['statusName'];?></td>
                <td><?php echo number_format($o['grandTotal'],2);?></td>
                <?php if($o['payment_code']=="bank_transfer"){ ?> 
                	<td>PC vill account</td>
                <?php }else{  ?> 
                	<td>AHUB account</td>
                <?php } ?>
                <td>  
                  <?php 
                    if($o['order_status_id']==17){ 
                        if($o['ops_verification']!=""){  
                          echo $o['ops_verification'];
                        }else{
                          echo "Unverified";
                        }
                      }else{ echo $o['ops_verification']; }?>
                  <?php ?></td>
                <td><?php echo $o['fund_status'];?></td>
                <td><?php echo $o['date_added'];?></td>
                <td><?php echo $o['date_modified'];?></td>
              </tr>
            <?php 
              endforeach;
              }
              else { ?>
                  <tr><td colspan="6" align="center">No data found.</td></tr>
                <?php } ?>
              </tbody>
             </table>
            </div>
          </div>
          <div class="row">
           
          </div>
        </div> 
      </div>
    </div>
</div>


<script>
function tbl_CheckAllCustomer() {
  $("#tbl_unselect_all").css("display","block")
  $("#tbl_unselect_all_span").css("display","block")
  $("#tbl_select_all").css("display","none")
  $("#tbl_select_all_span").css("display","none")
  $(".tdchkCust_div").find('input[type=checkbox]').each(function () {
    this.checked = true;
  });
 }
function tbl_UnCheckAllCustomer() {
    $("#tbl_unselect_all").css("display","none")
    $("#tbl_unselect_all_span").css("display","none")
    $("#tbl_select_all").css("display","block")
    $("#tbl_select_all_span").css("display","block");
    $(".tdchkCust_div").find('input[type=checkbox]').each(function () {
      this.checked = false;
    });
 }
$(document).delegate('#pageNumber', 'change', function() { 
  var order_status='<?php echo $status_id;?>';
  var pageNumber=$(this).val();
   var date_from=$('#date_from').val();
  var date_to=$('#date_to').val();
  if(date_from==""){
    date_from="notset";
  }
  if(date_to==""){
    date_to="notset";
  }
  location.replace("Pendingreceivables.php?order_status="+order_status+"&PageNumber="+pageNumber+'&date_to='+date_to+'&date_from='+date_from);
});
$(document).delegate('#order_status', 'change', function() { 
  var order_status=$(this).val();
  var FundStatus=$('#FundStatus').val();
  var date_from=$('#date_from').val();
  var OrderId=$('#OrderId').val();
  var date_to=$('#date_to').val();
  if(date_from==""){
    date_from="notset";
  }
  if(date_to==""){
    date_to="notset";
  }
  if(FundStatus==""){
    FundStatus="notset";
  }
  if(OrderId==""){
    OrderId="notset";
  }
  location.replace("Pendingreceivables.php?order_status="+order_status+'&date_to='+date_to+'&date_from='+date_from+'&OrderId='+OrderId+'&FundStatus='+FundStatus);
});
$(document).delegate('#FundStatus', 'change', function() { 
  var FundStatus=$(this).val();
  var order_status=$('#order_status').val();
  var OrderId=$('#OrderId').val();
  var date_from=$('#date_from').val();
  var date_to=$('#date_to').val();
  if(FundStatus==""){
    FundStatus="notset";
  }
  if(date_from==""){
    date_from="notset";
  }
  if(date_to==""){
    date_to="notset";
  }
  if(OrderId==""){
    OrderId="notset";
  }
  location.replace("Pendingreceivables.php?order_status="+order_status+'&date_to='+date_to+'&date_from='+date_from+'&OrderId='+OrderId+'&FundStatus='+FundStatus);
});
$(document).delegate('#OrderId', 'change', function() { 
  var FundStatus=$('#FundStatus').val();
  var order_status=$('#order_status').val();
  var OrderId=$('#OrderId').val();
  var date_from=$('#date_from').val();
  var date_to=$('#date_to').val();
  if(FundStatus==""){
    FundStatus="notset";
  }
  if(date_from==""){
    date_from="notset";
  }
  if(date_to==""){
    date_to="notset";
  }
  if(OrderId==""){
    OrderId="notset";
  }
  location.replace("Pendingreceivables.php?order_status="+order_status+'&date_to='+date_to+'&date_from='+date_from+'&OrderId='+OrderId+'&FundStatus='+FundStatus);
});
 /*$('#pageNumber').on('change', function(){
        alert($(this).val());
      });*/
$(document).delegate('#approveReceivables', 'click', function() {
      $('#approveReceivables').prop('disabled', true);
      var chk_customer_id = [];
       $.each($("input[name='chkMobileNumber[]']:checked"), function(){
            chk_customer_id.push($(this).val());
      });
     
      //console.log(JSON.stringify(chk_customer_id));
      if(chk_customer_id.length==0){
        bootbox.alert("Please select Order Id first");
        $('#approveReceivables').prop('disabled', false);
         return false;
      }
        $.ajax({
            url: 'ajax_sms_admin.php?action=approveReceivables&t=' + new Date().getTime(),
            type: 'post',
            data: {
                chk_customer_id: JSON.stringify(chk_customer_id)
            },
            dataType: 'json',
            beforeSend: function() {
                bootbox.dialog({
                      title: "Approving  Receivables",
                      message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
              });
           },
            success: function(json) {
                bootbox.alert(json['success'], function(){ 
                    window.location.reload();
                }); 
            }
        });
    });
    $(document).ready(function() { 
      $('#customer-name').on('input keyup',function(){
        SearchFilter();
      });

      $('.customer-table').on('click', '.btn-send', function () {
           var customer_id = $(this).data('customer_id');
           var number = $(this).data('number');
           var message = $('.message-content').val();
          if(message == '')
          {
            bootbox.alert('Enter Message...');
            return false;
          }
          InsertMessageCA(0,customer_id,message,number);
         $('.message-content').val('');
      });      
    });
    function SearchFilter() {
      var input, filter, table, tr, td, i;
      input = document.getElementById("customer-name");
      filter = input.value.toUpperCase();
      table = document.getElementById("customer-table");
      tr = table.getElementsByTagName("tr");
      for (i = 0; i < tr.length; i++) {
        tr[i].style.display = "none";
        td = tr[i].getElementsByTagName("td");
        for (var j = 0; j < td.length; j++) {
          cell = tr[i].getElementsByTagName("td")[j];
          if (cell) {
            if (cell.innerHTML.toUpperCase().indexOf(filter) > -1) {
              tr[i].style.display = "";
              break;
            } 
          }
        }      
      }
    }

    function InsertSMS(number,message)
    {
      $.ajax({
          url: 'ajax_sms_admin.php?action=InsertSMS&t=' + new Date().getTime(),
          data: {
              number: number,
              message: message
          },
          type: "POST",
          async: false,
          datatype: "json"
        }).done(function (data){
          var status =JSON.parse(data);
            if(status == 200){
              bootbox.alert('Successfully Sent');
            }
            else{
              bootbox.alert('Something went wrong...');
            }
        });
    }

    function InsertMessageCA(admin_id, customer_id,message,number) 
    {
        $.ajax({
          url: 'ajax_message.php?action=InsertMessageCA&t=' + new Date().getTime(),
          data: {
              admin_id: admin_id,
              customer_id: customer_id,
              message: message,
          },
          type: "POST",
          async: false,
          datatype: "json"
        }).done(function (data){
          var status =JSON.parse(data);
            if(status == 200){
              InsertSMS(number,message);
            }
            else{
              bootbox.alert('Something went wrong.');
            }
        });
    }
 
  
</script>
<?php include 'template/footer.php';?>