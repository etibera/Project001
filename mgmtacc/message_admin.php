<?php
include 'template/header.php';
require_once "model/message.php";
$modelM=new message;
$AdminMessagesList=$modelM->getGetAdminMessagesList(0);

$perm = $_SESSION['permission'];
if (!strpos($perm, "'2525';") !== false){
    header("Location: landing.php");
}
$user_id = $_SESSION['user_id'];
$keys = array_column($AdminMessagesList, 'timestamp');
array_multisort($keys, SORT_DESC, $AdminMessagesList);

?>
<link rel="stylesheet" href="../assets/css/acc_nav.css">


<div id="content" style="margin-top: 10px">
  <div class="form-group acc" style="margin:10px">
        <ul>
          <li><a  class="active" href="message_admin.php">Customer</a></li>
          <li><a href="#">Seller</a></li>
        </ul>
  </div>
    <div class="container-fluid">
    	<div class="panel panel-default">
    		<div class="panel-heading" style="padding:20px;">
    			<div class="row">
          			<div class="col-lg-6">
          				 <p style="font-weight: 700;" class="panel-title"> <i class="fa fa-envelope"></i> Messages</p>
          			</div>
          		</div>
    		</div>
    		<div class="panel-body">
    			<div class="table-responsive">
    				<table class="table table-bordered table-hover msg-table">
    					<thead>
			              <tr>
			                <th>Customers</th>
			                <th>Last Message</th>
			                <th>Date</th>
			                <th>Action</th>
			              </tr>
			            </thead>
			            <tbody>
                    <?php foreach ($AdminMessagesList as $aml) { ?>
                      <tr>
                        <td><?php echo $aml['fullname'];?></td>
                        <td><?php echo $aml['message'];?></td>
                        <td><?php echo $aml['timestampval'];?></td>
                        <td>
                          <a class="btn btn-sm btn-warning notification btn-msg-list" 
                              data-toggle="modal" 
                              data-target="#MessageModal"
                              data-admin_id="<?php echo 0;?>"
                              data-customer_id="<?php echo $aml['customer_id'];?>"
                              data-fullname="<?php echo $aml['fullname'];?>"
                              title="messages">
                            <i class="fas fa-envelope"></i>
                            <?php if($aml['no_unreadmsg']){ ?>
                              <span class="badge"><?php echo $aml['no_unreadmsg'];?></span>
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
      <div class="modal-header" style="background-color:black;border:0px;color:white;">
               <h4 class="modal-title" style="float:left;color:white;" id="modal-title">Loading...</h4>
                <button type="button" style="color:white" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body" style="background-image: rgb(2,0,36);width:100%;
             background-image: linear-gradient(180deg, rgba(2,0,36,1) 0%, rgba(104,11,110,0.908000700280112) 49%, rgba(222,14,14,1) 100%);">
        <input type="hidden" id="admin_id">
        <input type="hidden" id="customer_id">
        <input type="hidden" id="convo_length" value="0">
        <div class="row">
            <div class="col-lg-12 message-content" id="message-content" style="height: 250px; overflow: auto;">
            </div>
        </div>
          <div class="row">
            <div class="col-lg-12 message-text-area" style="margin-top:10px">
              <textarea id="text_message" class="form-control" placeholder="Type a message..." cols="40" style="resize: none;"></textarea>
            </div>
            
          </div>
           <div class="row">
              <div class="col-lg-12 message-send" style="margin-top:10px">
                <button class="btn btn-primary btn-block btn-send" title="Send"><i class="fas fa-paper-plane"></i> Send</button>
            </div>
          </div>
        </div>
      </div>

      </div>
      </div>
  </div>
</div>


<script>
    var msg_interval;
    var new_msg_interval;
    function doLoadMessage(){

          var admin_id = $('#admin_id').val();
          var customer_id = $('#customer_id').val();
          if(customer_id != '') {
            GetConversationCA(admin_id, customer_id);
            // console.log(parseFloat($('#convo_length').val()) + ' : '+ flagId);
            if(parseFloat($('#convo_length').val()) != flagId){
              isNewMessage();
            }
            flagId = parseFloat($('#convo_length').val());
          }
    }

    var flagId = 0;

    function isNewMessage(){
        var element = document.getElementById('message-content');
            if(!atBottom(element)){
               $('.message-content').animate({scrollTop:999999}, 'fast');
             }
    }


    $(document).ready(function() { 
      var admin_id = <?php echo 0;?>;
      $('.msg-table').on('click','.btn-msg-list',function(){ 

         var sender_name = $(this).data('fullname');
         var admin_id = $(this).data('admin_id');
         var customer_id = $(this).data('customer_id');
         var message_id = $(this).data('message_id');
        
         $('#customer_id').val(customer_id);
         $('#admin_id').val(admin_id);
         $('.modal-title').html(sender_name);


         

         //$('#MessageModal').modal('show');
        
        UpdateToIsReadCA(admin_id, customer_id);
        
        GetConversationCA(admin_id, customer_id);

      //  GetAdminMessagesCA(admin_id);

        $('.message-content').animate({scrollTop:999999}, 'fast');

      });

      $('.close').on('click', function(){
      //  GetAdminMessagesCA($('#admin_id').val());
          clearInterval(msg_interval);
          console.log(msg_interval);
          $('.message-content').empty();
      });

      $('.btn-send').on('click',function(){
          var admin_id = $('#admin_id').val();
          var customer_id = $('#customer_id').val();
          var message = $('#text_message').val();
          InsertMessageCA(admin_id,customer_id,message);
           $('#text_message').val('');
      });

      $('#text_message').on('click',function(){
          $('.message-content').animate({scrollTop:999999}, 'fast');
           msg_interval = setInterval(function(){ 
              doLoadMessage();
           }, 1000);
      });
    });

   

    function GetAdminMessagesCA(admin_id)
    {
         $.ajax({
          url: 'ajax_message.php?action=GetAdminMessagesCA&t=' + new Date().getTime(),
          data: {
              admin_id: admin_id
          },
          type: "GET",
          datatype: "json"
        }).done(function (data){
          var list =JSON.parse(data);
          console.log(list);
          $('.msg-table tbody').empty();

            for(var i = 0; i < list['list'].length; i++){

              var Unread = '';
              if(list['list'][i]['sender'] != admin_id){
                Unread = list['list'][i]['read'] == '' ? 'style="font-weight:bold";' : '';
              }
              
              var UnreadCount = '';
              for(var j = 0; j < list['unreads'].length; j++){
                if(list['list'][i]['customer_id'] == list['unreads'][j]['customer_id'])
                {
                  if(list['unreads'][j]['unreads'] != '0'){
                    UnreadCount = '<span class="badge">'+list['unreads'][j]['unreads'] +'</span>';
                  }
                }
              }


              $('.msg-table tbody').append(  
                  '<tr>' +
                    '<td '+ Unread + '>' + list['list'][i]['fullname'] + '</td>' +
                    '<td '+ Unread + '>' + list['list'][i]['message'] + '</td>' +
                    '<td '+ Unread + '>' + list['list'][i]['timestamp'] + '</td>' +
                    '<td>'+
                      '<button' + 
                            ' data-msg_id="' + list['list'][i]['message_id'] + '"' +
                            ' data-admin_id="' + admin_id + '"' +
                            ' data-customer_id="' + list['list'][i]['customer_id'] + '"' +
                            ' data-sender_name="' + list['list'][i]['fullname'] + 
                            '" class="btn btn-warning btn-msg-list notification" title="messages"><i class="fas fa-envelope"></i> '+UnreadCount+
                      '</button>' +
                    '</td>' +
                  '</tr>'
              );

            }
            if(list['list'].length == 0)
            {
              $('.msg-table tbody').append(
                  '<tr>' +
                    '<td colspan ="4" style="text-align:center;">No Messages Found...</td>' +
                    
                  '</tr>'
              );
            }
            
        });
    }

    function GetConversationCA(admin_id, customer_id) 
    {
        $.ajax({
          url: 'ajax_message.php?action=GetConversationsCA&t=' + new Date().getTime(),
          data: {
              admin_id: admin_id,
              customer_id: customer_id
          },
          type: "GET",
          datatype: "json"
        }).done(function (data){
          var list =JSON.parse(data);
          $('.message-content').empty();
            for(var i = 0; i < list.length; i++)
            {

              if(list[i]['receiver'] != admin_id){
               
               $('.message-content').append(
                  '<div style="width:100%";display: inline-block;">' + 
                    '<div style="border:1px solid blue;clear:right;float:right;background-color:blue;color:white;border-radius:10px 10px 0px 10px;padding:10px;overflow-wrap:break-word;margin-bottom:10px;max-width:75%;">' + list[i]['message'] + '</div><br><p style="clear:right;float:right;color:gray;font-size:9px">'+ list[i]['timestamp']+'</p>' + 
                  '</div><br>' 
                );
              }
              else{

                 $('.message-content').append(
                  
                  '<div style="width:100%;display:inline-block;">' + 
                    '<div style="border:1px solid gray;clear:left;float:left;background-color:white;border-radius:10px 10px 10px 0px;padding:10px;overflow-wrap:break-word;margin-bottom:10px;max-width:75%;">' + list[i]['message'] +
                    '</div><br><p style="clear:left;float:left;color:gray;font-size:9px">'+ list[i]['timestamp']+'</p>' + 
                  '</div><br>'
                );
              }
            }
            $('#convo_length').val(list.length);
           
        });
       
    }

    function UpdateToIsReadCA(admin_id, customer_id) 
    {
        $.ajax({
          url: 'ajax_message.php?action=UpdateToIsReadCA&t=' + new Date().getTime(),
          data: {
              admin_id: admin_id,
              customer_id: customer_id
          },
          type: "POST",
          datatype: "json"
        }).done(function (data){
          var status =JSON.parse(data); 
           
        });
    }

    function InsertMessageCA(admin_id, customer_id,message) 
    {
        $.ajax({
          url: 'ajax_message.php?action=InsertMessageCA&t=' + new Date().getTime(),
          data: {
              admin_id: admin_id,
              customer_id: customer_id,
              message: message,
          },
          type: "POST",
          datatype: "json"
        }).done(function (data){
          var status =JSON.parse(data);
           
        });
    }

    function atBottom(ele) {
      var sh = ele.scrollHeight;
      var st = ele.scrollTop;
      var ht = ele.offsetHeight;
      if(ht==0) {
          return true;
      }
      if(st == sh - ht)
          {return true;} 
      else 
          {return false;}
    }

</script>
<?php include 'template/footer.php';?>