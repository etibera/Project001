<?php
	require_once("includes/init.php");
	include 'template/header.php';
	require_once "model/sellerMessages.php";	
	$mod_SM=new sellerMessage;
	$SellerMessageList=$mod_SM->getSellerMessageList($_GET['seller_id']);
  $SellerDetails=$mod_SM->getSellerDetails($_GET['seller_id']);
  //$SellerMessageListdata=$mod_SM->adGetConversations($_GET['seller_id'],4271);
  $perm = $_SESSION['permission'];
  if (!strpos($perm, "'252525';") !== false){
      header("Location: landing.php");
  }
  
  $keys = array_column($SellerMessageList, 'timestamp');
  array_multisort($keys, SORT_DESC, $SellerMessageList);
/* echo "<pre>";
  print_r($SellerMessageList);*/
?>
<div id="content">
  	<div class="container-fluid">
    	<div class="panel panel-default">
    		<div class="panel-heading" style="padding:20px;">
		        <div class="row">
			          <div class="col-lg-12">
			             <p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i> <?php echo  $SellerDetails['shop_name'];?> Messages</p>
			          </div>
		      	</div>
		    </div>
		    <div class="panel-body">
          <div class="well">
              <div class="row">
                <input class="form-control pull-right" type="text" id="InputsearchOrders" onkeyup="searchOrders()" placeholder="Search for Customer Name , Last Message or Date"> 
              </div>
          </div>
			    <div class="table-responsive">
        			<table class="table table-bordered table-hover msg-table" id="pendingPrd">
        				<thead>        					
                  <th class="text-center">Seller Branch Name</th>
                  <th class="text-center">Customer Name</th>
                  <th class="text-center">Last Message</th>
	                <th class="text-center">Date</th>
                  <th class="text-center">Action</th>
        				</thead>
        				<tbody id="tblTbody">
        					<?php foreach ($SellerMessageList as $sml) { ?>
        						<tr>
                      <td><?php echo $sml['b_name'];?></td>
                      <td><?php echo $sml['fullname'];?></td>
                      <td><?php echo $sml['message'];?></td>
                      <td><?php echo $sml['timestampval'];?></td>
                      <td>
                        <a class="btn btn-sm btn-warning notification btn-msg-list" 
                            data-toggle="modal" 
                            data-target="#MessageModal"
                            data-seller_id="<?php echo $_GET['seller_id'];?>"
                            data-seller_id="<?php echo $_GET['seller_id'];?>"
                            data-branch_id="<?php echo $sml['branch_id'];?>"
                            data-customer_id="<?php echo $sml['customer_id'];?>"
                            data-fullname="<?php echo $sml['fullname'];?>"
                            title="messages">
                          <i class="fas fa-envelope"></i>
                          <?php if($sml['no_unreadmsg']){ ?>
                            <span class="badge"><?php echo $sml['no_unreadmsg'];?></span>
                          <?php }?> 
                        </a>
                      </td>
        						</tr>
        					<?php } ?> 
        				</tbody>
        			</table> 
		      	</div>
		    </div>
   	 	</div>
	</div>
</div>

<div  class="modal fade" id="MessageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-error-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#483b45;border:0px;color:white;">
               <h4 class="modal-title" style="float:left;color:white;" id="modal-title">Loading...</h4>
                <button type="button" style="color:white" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body" style="background-image: rgb(2,0,36);width: 100%;background-image: linear-gradient(180deg, rgb(165 165 193) 0%, rgb(161 153 161 / 91%) 49%, rgb(151 134 134) 100%);">
        <input type="hidden" id="seller_id">
        <input type="hidden" id="customer_id">
        <input type="hidden" id="branch_id">
        <input type="hidden" id="convo_length" value="0">
        <div class="row">
            <div class="col-lg-12 message-content" id="message-content" style="height: 250px; overflow: auto;">
            </div>
        </div>         
        </div>
      </div>

      </div>
      </div>
  </div>
</div>

<?php include 'template/footer.php'; ?>
<script type="text/javascript">
   function searchOrders() {    
      var input, filter, table, tr, td,td2,td3, i, txtValue,txtValue2,txtValue3;
      input = document.getElementById("InputsearchOrders");
      filter = input.value.toUpperCase().trim();
      table = document.getElementById("pendingPrd");
      var tBody = table.tBodies.namedItem("tblTbody");
      var tableRow = tBody.getElementsByTagName('tr');
      for (var t = 0; t < tableRow.length; t++){
          td = tableRow[t].getElementsByTagName("td")[0];
          td2 = tableRow[t].getElementsByTagName("td")[1];
          td3 = tableRow[t].getElementsByTagName("td")[2];
         // console.log(td);

          if (td) {
            txtValue = td.textContent || td.innerText;
            txtValue2 = td2.textContent || td2.innerText;
            txtValue3 = td3.textContent || td3.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
              tableRow[t].style.display = "";
            } else if (txtValue2.toUpperCase().indexOf(filter) > -1){
                tableRow[t].style.display = "";
            }else if(txtValue3.toUpperCase().indexOf(filter) > -1){
               tableRow[t].style.display = "";
            }else{
               tableRow[t].style.display = "none";
            }
          }       
          
      }
      $('.order-table').paging({   
      limit: 20,
      rowDisplayStyle: 'block',
      activePage: 0,
      rows: []
    });
  }
  $(document).ready(function() {

    
  });
  $('.msg-table').on('click','.btn-msg-list',function(){   
     var sender_name = $(this).data('fullname');
     var seller_id = $(this).data('seller_id');
     var branch_id = $(this).data('branch_id');    
     var customer_id = $(this).data('customer_id');    
     $('#customer_id').val(customer_id);
     $('#seller_id').val(seller_id);
     $('#branch_id').val(branch_id);
     $('.modal-title').html(sender_name);
     console.log(seller_id);
     console.log(customer_id);
     console.log(sender_name);
     console.log(branch_id);
    GetConversation(seller_id, customer_id,branch_id);
    $('.message-content').animate({scrollTop:999999}, 'fast');
  });
  function GetConversation(seller_id, customer_id,branch_id) {
        $.ajax({
         url: 'ajax_message.php?action=GetConversationsADMIN&t=' + new Date().getTime(),
          data: {
              seller_id: seller_id,
              customer_id: customer_id,
              branch_id: branch_id,
          },
          type: "GET",
          datatype: "json"
        }).done(function (data){
          var list =JSON.parse(data);
          $('.message-content').empty();
            for(var i = 0; i < list.length; i++)
            {
              if(list[i].product_id != 0){
                $('.message-content').append(
                    '<div style="justify-content: flex-end">'+
                    '<div style="width: 50%; padding: 10px; display: inline-flex; border-radius: 10px; background-color: #fff; margin-left: auto; margin-top: 30px">'+
                    '<div class="col-md-6"><img src="'+list[i].product.image+'" width="100"/></div>'+
                    '<div class="col-md-6">' +
                        '<div style="height: 40px; overflow: hidden">'+list[i].product.name+'</div>' +
                        '<div style="color: red">₱'+parseFloat(list[i].product.price).toFixed(2)+'</div>' +
                        '<div style="position: absolute; bottom: 0; right: 0"><button class="btn btn-primary" >View Product</button></div>' +
                    '</div>'+
                    '</div>' +
                    '</div>'
                );
                    
                }
              if(branch_id==0){
                if(list[i]['receiver'] != seller_id ){
                 $('.message-content').append(
                    '<div style="width:100%";display: inline-block;">' + 
                      '<div style="border:1px solid blue;clear:right;float:right;background-color:blue;color:white;border-radius:10px 10px 0px 10px;padding:10px;overflow-wrap:break-word;margin-bottom:10px;max-width:75%;">' + list[i]['message'] + '</div><br><p style="clear:right;float:right;color:gray;font-size:9px">'+ list[i]['timestamp']+'</p>' + 
                    '</div><br>' 
                  );
                }else{
                   $('.message-content').append(
                    
                    '<div style="width:100%;display:inline-block;">' + 
                      '<div style="border:1px solid gray;clear:left;float:left;background-color:white;border-radius:10px 10px 10px 0px;padding:10px;overflow-wrap:break-word;margin-bottom:10px;max-width:75%;">' + list[i]['message'] +
                      '</div><br><p style="clear:left;float:left;color:gray;font-size:9px">'+ list[i]['timestamp']+'</p>' + 
                    '</div><br>'
                  );
                }
              }else{
                if(list[i]['receiver'] != branch_id ){
                 $('.message-content').append(
                    '<div style="width:100%";display: inline-block;">' + 
                      '<div style="border:1px solid blue;clear:right;float:right;background-color:blue;color:white;border-radius:10px 10px 0px 10px;padding:10px;overflow-wrap:break-word;margin-bottom:10px;max-width:75%;">' + list[i]['message'] + '</div><br><p style="clear:right;float:right;color:gray;font-size:9px">'+ list[i]['timestamp']+'</p>' + 
                    '</div><br>' 
                  );
                }else{
                   $('.message-content').append(
                    
                    '<div style="width:100%;display:inline-block;">' + 
                      '<div style="border:1px solid gray;clear:left;float:left;background-color:white;border-radius:10px 10px 10px 0px;padding:10px;overflow-wrap:break-word;margin-bottom:10px;max-width:75%;">' + list[i]['message'] +
                      '</div><br><p style="clear:left;float:left;color:gray;font-size:9px">'+ list[i]['timestamp']+'</p>' + 
                    '</div><br>'
                  );
                }
              }
              
            }
            $('#convo_length').val(list.length);
           
        });
       
    }
 
</script>