<?php
//require_once("includes/init.php");
 include 'template/header.php'; 
 include "model/Home.php";
 require_once "model/message.php";
 $modelM=new message;
 $model=new Home;
$perm = $_SESSION['permission'];

if (!strpos($perm, "'2';") !== false){
	if (!strpos($perm, "'252546';") !== false){
		 header("Location: landing.php");
		}else{
			 header("Location: FourGives_CustomerLIst.php?t=12345j-SZGwsL!$9o");
		}
   
   
}   
if(isset($_REQUEST['btn_search']))	
{	
	if(isset($_REQUEST['filter_order_id']) && $_REQUEST['filter_order_id']!="")
	{
		$order_id=$_REQUEST["filter_order_id"];
	}else{ $order_id="notset";}
		
	if(isset($_REQUEST['filter_customer'])  && $_REQUEST['filter_customer']!="" )
	{
		$customer=$_REQUEST["filter_customer"];	
	}else{ $customer="notset";}
		
	if(isset($_REQUEST['order_status_id']) && $_REQUEST['order_status_id']!="*")	{
			$order_status=$_REQUEST["order_status_id"];
	}else{ $order_status="notset";}	

			//var_dump($customer);
	$data=$model->order_historyS($order_id,$customer,$order_status);
}else{
	$data=$model->order_history();
}
 

?>

<div class="row">
	<div class="container">
		<div class="col-md-3">
			<div class="panel panel-default">
			  <div class="panel-heading">Total Orders</div>
			  <div class="panel-body">
			   <?php echo $model->total_order(); ?>
			  </div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
		   <div class="panel-heading">Total Sales</div>
		   <div class="panel-body">
		    <?php echo $model->total_sales(); ?>
		   </div>
		</div>

	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
				<a title="View" href="customer.php?type=Verified_Customer"> Total verified Customers (Manual Reg)</a>
	        </div>
			<div class="panel-body">
				<?php echo $model->CountTotalCustomer("Verified_Customer"); ?>
			</div>
		</div>
	</div>

	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
				<a title="View" href="customer.php?type=google_apple_facebook"> Total verified Customers (Google/Apple/Facebook)</a>
	        </div>
			<div class="panel-body">
				<?php echo $model->CountTotalCustomer("google_apple_facebook"); ?>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
				<a title="View" href="customer.php?type=4Gives_Account"> Total 4Gives Customer</a>
	        </div>
			<div class="panel-body">
				<?php echo $model->CountTotalCustomer("4Gives_Account"); ?>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
				<a title="View" href="customer.php?type=Landbank_Account"> Total Lanbank Customer</a>
	        </div>
			<div class="panel-body">
				<?php echo $model->CountTotalCustomer("Landbank_Account"); ?>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
				<a  title="View" href="customer.php?type=Unverified_Customer" >Total Unverified Customers  </a>
			</div>
			<div class="panel-body">
				<?php echo $model->CountTotalCustomer("Unverified_Customer");?>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
				<a  title="View" href="customer.php?type=Guest" >Total  Unverified Customers (Guest)</a>
			</div>
			<div class="panel-body">
				<?php echo $model->CountTotalCustomer("Guest"); ?>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
				<a  title="View" href="customer.php" >Total Members</a>
			</div>
			<div class="panel-body">
				<?php echo $model->GrandTotalCustomer(); ?>
			</div>
		</div>
	</div>
</div>

<?php  include 'chart.php';?>
<div class="row">
	<div class="container">
		<div class="well">
          <div class="row">
          	<form method="post" class="form-horizontal">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-order-id">Order ID</label>
                <input type="text" name="filter_order_id" value="<?php if(isset($_REQUEST['btn_search'])){echo $_REQUEST['filter_order_id'];}?>" placeholder="Order ID" id="input-order-id" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-customer">Customer</label>
                <input type="text" name="filter_customer" value="<?php if(isset($_REQUEST['btn_search'])){echo $_REQUEST['filter_customer'];}?>" placeholder="Customer" id="input-customer" class="form-control" />
              </div>
            </div>            
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-order-status">Order Status</label>
                <select class="form-control" name="order_status_id">
                	<?php 
                	
					
						if(isset($_REQUEST['order_status_id']) && $_REQUEST['order_status_id']!="*")
						{	
							foreach($model->order_status() as $status) {
								if($_REQUEST['order_status_id']==$status['order_status_id']){
									echo '<option value='.$status['order_status_id'].'>'.$status['name'].'</option>';
								}
							}
							foreach($model->order_status() as $status) {
								if($_REQUEST['order_status_id']!=$status['order_status_id']){
									echo '<option value='.$status['order_status_id'].'>'.$status['name'].'</option>';
								}
							}
							
							
						}else{
							?>
							<option value='*'></option>
						    <?php
							foreach($model->order_status() as $status) {
							echo '<option value='.$status['order_status_id'].'>'.$status['name'].'</option>';
							}
						}
						
					
					?>
				</select>               
              </div>              
            </div>
            	<button type="submit" name="btn_search" title= "Search" class="btn btn-success pull-right" value="">
            		  <i data-feather="search"></i>
            	</button>
              
              </form>
            </div>
          </div>
        </div>
    </div>
</div>	
<div class="row">
	<div class="container">
			<table class="table table-bordered order-table">
		<thead>
			<th>Order ID</th>
			<th>Customer</th>
			<th>Status</th>
			<th>Date Added</th>
			<th>Total</th>
			<th>Tracking Number</th>
			<th>Action</th>
		</thead>
		<tbody>
			<script>
				 showLoading(); 
			</script>
			<?php

				foreach($data as $o):
					$olddata=$model->count_OlddataOrder($o['order_id']);
					$countOrderNumber=$model->countOrderNumber($o['order_id']);	
					$TrackingNumber=$model->GetTrackingNumber($o['order_id']);
			?>
			<tr>
				<td><?php echo $o['order_id'];?></td>
				<td><?php echo $o['customer'];?></td>
				<td><?php echo $o['status'];?></td>
				<td><?php echo date('F j, Y, g:i A',strtotime($o['date_added']));?></td>
				<td><?php echo number_format($o['total'],2);?></td>
				<td>
					<?php 
						if(count($TrackingNumber)!=0){
							foreach ($TrackingNumber as $tn) {
								echo $tn['tracking_number']." ,";
							}
						}
					?>
						
				</td>
				<td>
					<?php if($olddata!=0){ ?>
						<?php if($countOrderNumber==0){ ?>
							<a class="btn btn-sm btn-info" title="View"
		                     href="view_order_new.php?order_id=<?php echo  $o['order_id'];?>" >
		                     <i data-feather="eye"></i>
		                 	</a>
	                 	<?php }else{ ?> 
	                 		<a class="btn btn-sm btn-info"
			                     href="viewStoreOrderDetails.php?order_id=<?php echo  $o['order_id'];?>" >
			                     <i data-feather="eye"></i>
			                 </a>
	                 	<?php }?>
					<?php }else{ ?> 
						<a class="btn btn-sm btn-info" title="View"
	                     href="view_order.php?order_id=<?php echo  $o['order_id'];?>" >
	                    <i data-feather="eye"></i>
	                 </a>
	                 </a>
					<?php }?>
					
					 <a class="btn btn-sm btn-warning" title="Print SI"
	                     href="invoice.php?orderid=<?php echo  $o['order_id'];?>" target="_blank">
	                     <i data-feather="printer"></i>
	                 </a>
	                 <a class="btn btn-sm btn-danger" title="Print DR"
	                     href="dreceipt.php?orderid=<?php echo  $o['order_id'];?>" target="_blank">
                       	 <i data-feather="printer"></i>
	                 </a>
	                <?php $no_unreadmsg=$modelM->getUnreadAdminMessageByCustomer($o['customer_id'],0);?>
	                <a class="btn btn-sm btn-warning notification btn-msg-list" 
                              data-toggle="modal" 
                              data-target="#MessageModal"
                              data-admin_id="<?php echo 0;?>"
                              data-customer_id="<?php echo $o['customer_id'];?>"
                              data-order_id="<?php echo $o['order_id'];?>"
                              data-fullname="<?php echo $o['customer'];?>"
                              title="messages">
                            <i class="fas fa-envelope" style="font-size: 22px;"></i>
                            <?php if($no_unreadmsg){ ?>
                              <span class="badge"><?php echo $no_unreadmsg;?></span>
                            <?php }?> 
                          </a>
                 </td>
			</tr>
		<?php endforeach;?>
		<script> hideLoading();</script>
		</tbody>
	</table>
	</div>
</div>
<br>

<?php include 'recent_activity.php';?>
<div  class="modal fade" id="MessageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-error-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#483b45;border:0px;color:white;">
               <h4 class="modal-title" style="float:left;color:white;" id="modal-title">Loading...</h4>
                <button type="button" style="color:white" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body" style="background-image: rgb(2,0,36);width: 100%;background-image: linear-gradient(180deg, rgb(165 165 193) 0%, rgb(161 153 161 / 91%) 49%, rgb(151 134 134) 100%);">
        <input type="hidden" id="admin_id">
        <input type="hidden" id="customer_id">
        <input type="hidden" id="order_id">
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

<?php include 'template/footer.php';?>
<script>
	$(document).ready(function() {
		/*$('.order-table').paging({	 
			limit: 20,
			rowDisplayStyle: 'block',
			activePage: 0,
			rows: []
		});*/
		 $('.order-table').DataTable({"order": [],
		      "oLanguage": {
		        "sSearch": "Quick Search:"
		      },
		      "bSort": true,
		      "dom": 'Blfrtip',
		      "buttons": [{
		          extend: 'excel',
		          title: 'Order Report',
		        },
		        {
		          extend: 'pdf',
		          title: 'Order Report',
		         
		        },
		        {
		          extend: 'print',
		          title: 'Order Report',
		        },
		      ],
		      "lengthMenu": [
		        [15, 50, 100,-1],
		        [15, 50, 100,"all"]
		      ],});
		$('.btn-send').on('click',function(){
          var admin_id = $('#admin_id').val();
          var customer_id = $('#customer_id').val();
          var order_id = $('#order_id').val();
          var message = $('#text_message').val();
          InsertMessageCA(admin_id,customer_id,message,order_id);
          $('#text_message').val('');
        });
		$('.order-table').on('click','.btn-msg-list',function(){ 
			var sender_name = $(this).data('fullname');
     		var admin_id = $(this).data('admin_id');
     		var customer_id = $(this).data('customer_id');
     		var order_id = $(this).data('order_id');
     		var message_id = $(this).data('message_id');        
     		$('#customer_id').val(customer_id);
     		$('#admin_id').val(admin_id);
     		$('#order_id').val(order_id);
     		$('.modal-title').html(sender_name);  
        	UpdateToIsReadCA(admin_id, customer_id);        
        	GetConversationCA(admin_id, customer_id);
        	$('.message-content').animate({scrollTop:999999}, 'fast');
        });
        
	});
	function InsertMessageCA(admin_id, customer_id,message,order_id){
	    $.ajax({
	      url: 'ajax_message.php?action=InsertMessageCA&t=' + new Date().getTime(),
	      data: {
	          admin_id: admin_id,
	          customer_id: customer_id,
	          message: message,
	          order_id: order_id,
	      },
	      type: "POST",
	      datatype: "json"
	    }).done(function (data){
	      var status =JSON.parse(data);
	      GetConversationCA(admin_id, customer_id);
	      $('.message-content').animate({scrollTop:999999}, 'fast');
	    });
	}
	function UpdateToIsReadCA(admin_id, customer_id) {
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
	function GetConversationCA(admin_id, customer_id){
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
	        for(var i = 0; i < list.length; i++){
	        	if(list[i]['receiver'] != admin_id){	               
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
	        $('#convo_length').val(list.length);	           
	    });	       
	}
</script>