<?php
include 'template/header.php';
require_once "model/sms_admin.php";
$model=new sms_admin;
if(isset($_GET['type'])){
  $data = $model->GetCustomerList($_GET['type']);
}else{
  $data = $model->GetCustomerList("All");
}

     
if(!isset($_SESSION['user_id']))  //check unauthorize user not access in "print.php" page
{
    header("location: index.php"); 
}
$user_id = $_SESSION['user_id'];
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
              <div class="col-lg-4">
                 <p style="font-weight: 700;" class="panel-title"> <i class="fa fa-envelope"></i> SMS to Customer</p>
              </div>
              <div class="col-lg-2">
                 <label for="cars">Choose Customer Type:</label>
                  <select class="form-control" id="CustomerType">
                    <option value="All" 
                    <?php if(isset($_GET['type']) && $_GET['type']=="All"){echo"selected";}?>  >All Customers</option>
                    <option value="1" <?php if(isset($_GET['type']) && $_GET['type']=="1"){echo"selected";}?>>Landbank Cusomers</option>
                    <option value="2" <?php if(isset($_GET['type']) && $_GET['type']=="2"){echo"selected";}?>>4Gives Customers</option>
                    <option value="0" <?php if(isset($_GET['type']) && $_GET['type']=="0"){echo"selected";}?>>Regular Customers</option>
                  </select>
              </div>
               <div class="col-lg-6">
                  <a class="btn btn-info pull-right" id="send_sms" title="Send SMS" style="margin-left:5px; "><i class="fas fa-paper-plane"></i> Send SMS</a>
              </div>
          </div>    
        </div>
        <div class="panel-body">
           <div class="col-lg-6" style="margin-bottom:10px">
              <textarea class="form-control message-content" placeholder="Enter Message..."></textarea>
            </div>
          <div class="row">
            <div class="col-lg-12">
             <table class="customer-table table table-striped table-bordered table-hover nowrap w-100">
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
                 <th>Customer Id</th>
                 <th>Customer Name</th>
                 <th>Contact No.</th>
              </thead>
              <tbody id="customer-table">
                <?php
                if(count($data) > 0){ 
                foreach($data as $o):
              ?>
              <tr>
                <td class="text-center">
                  <div class="tdchkCust_div"  id="customer-div_<?php echo $o['customer_id'];?>" >
                    <input  type="checkbox" name="chkMobileNumber[]" value="<?php  echo '0'.$o['telephone'];?>" />
                  </div>
                  </td>
                  <td>
                     <?php echo $o['customer_id'];?>
                  </td>
                <td><?php echo $o['firstname'].' '.$o['lastname'];?></td>
                <td><?php echo '0'.$o['telephone'];?></td>
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
  $(document).ready(function() {
    $('.customer-table').DataTable({"order": [],
        "oLanguage": {
        "sSearch": "Quick Search:"
        },
        "bSort": true,
        "dom": 'Blfrtip',
        "buttons": [{
          extend: 'excel',
          title: 'Customer Report',
        },
        {
          extend: 'pdf',
          title: 'Customer Report',
         
        },
        {
          extend: 'print',
          title: 'Customer Report',
        },
        ],
        "lengthMenu": [
        [15, 50, 100,-1],
        [15, 50, 100,"all"]
        ],
    });
  });

$( "#CustomerType" ).change(function() {
  window.location.href = "sms_admin.php?type="+this.value+'&t=' + new Date().getTime();
});
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
 $(document).delegate('#send_sms', 'click', function() {
      $('#send_sms').prop('disabled', true);
      var chk_mobilenumber = [];
       $.each($("input[name='chkMobileNumber[]']:checked"), function(){
            chk_mobilenumber.push($(this).val());
      });
      var message = $('.message-content').val();
      if(message == ''){
        bootbox.alert('Enter Message...');
        return false;
      }
      console.log(JSON.stringify(chk_mobilenumber));
      if(chk_mobilenumber.length==0){
        bootbox.alert("Please select Customer first");
        $('#send_sms').prop('disabled', false);
         return false;
      }
      bootbox.dialog({ 
          title: '',
          message: 'Please Choose SMS Platform',
          size: 'large',
          onEscape: true,
          backdrop: true,
          buttons: {
              Pesoapp: {
                  label: '<i class="fas fa-paper-plane"></i> Pesoapp PH',
                  className: 'btn-primary',
                  callback: function(){
                     $.ajax({
                        url: 'ajax_sms_admin.php?action=sendSMSPesoapp&t=' + new Date().getTime(),
                        type: 'post',
                        data: {
                            chk_mobilenumber: JSON.stringify(chk_mobilenumber),
                            message: message,
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            bootbox.dialog({
                                  title: "Sending SMS",
                                  message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
                          });
                       },
                        success: function(json) {
                            bootbox.alert(json['success'], function(){ 
                                bootbox.hideAll()
                            }); 
                        }
                    });              
                  }
              },
              Zeritex: {
                  label: '<i class="fas fa-paper-plane"></i> Zeritex',
                  className: 'btn-primary',
                  callback: function(){
                    $.ajax({
                        url: 'ajax_sms_admin.php?action=sendSMS&t=' + new Date().getTime(),
                        type: 'post',
                        data: {
                            chk_mobilenumber: JSON.stringify(chk_mobilenumber),
                            message: message,
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            bootbox.dialog({
                                  title: "Sending SMS",
                                  message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
                          });
                       },
                        success: function(json) {
                            bootbox.alert(json['success'], function(){ 
                                bootbox.hideAll()
                            }); 
                        }
                    });
                                      
                  }
              }
          }
      })
    });
    $(document).ready(function() { 
      $('#customer-name').on('input change',function(){
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