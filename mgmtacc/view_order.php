<?php 
//require_once("includes/init.php");
include 'template/header.php';
include "model/Order.php";
include "../include/banggoodAPI.php"; 
if(!$session->is_signed_in()){redirect("index");}
$model=new Order;
?>

<?php 
if(isset($_GET['order_id'])){
	$order = $model->order_details($_GET['order_id']);

	
}else{
	redirect('home');
}

?>

<?php 
if(isset($_POST['add_order_history'])){
	$order_status_id = trim($_POST['order_status_id']);
	$comment = trim($_POST['comment']);
	$order_id = trim($_GET['order_id']);
	if($order_status_id==20){
		if($comment==''){
			$message = '<div class="alert alert-danger">Please Input Seller Receipt No.</div>';
		}
	}
	if(!isset($message)){
		$result = $model->insert_order_history($order_status_id, $comment, $order_id);
		if($result){
			$order = $model->order_details($_GET['order_id']);
			$message = '<div class="alert alert-success">Successfully Added</div>';
		}else{
			$message = '<div class="alert alert-danger">There something wrong to your server</div>';
		}
	}
	
}else{
	$message = '';
}

?>
<div class="row">
	<div class="container">
		<?php 
		if(isset($message)){
			echo $message;
		}

		?>
		<h1>Order (#<?php echo $order['order_id'];?>)</h1>
		<br>
		<div class="col-md-4">
			<div class="panel panel-default">
			  <div class="panel-heading"><b>Order Details</b></div>
			  <div class="panel-body">
			  	<ul>
			  		<li><?php echo $order['store_name'];?></li>
			  		<li><?php echo $order['date_added'];?></li>
			  		<li><?php echo $order['payment_method'];?></li>
			  		<li><?php echo $order['shipping_method'];?></li>
			  	</ul>
			  </div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="panel panel-default">
			  <div class="panel-heading"><b>Customer Details</b></div>
			  <div class="panel-body">
			  	<ul>
			  		<li><?php echo $order['customer'];?></li>
			  		<li><?php echo $order['email'];?></li>
			  		<li><?php echo $order['telephone'];?></li>
			  	</ul>
			  </div>
			</div>
		</div>
		
	</div>
</div>
<div class="row">
	<div class="container">
		<div class="col-md-6">
			<div class="panel panel-default">
			  <div class="panel-heading"><b>Payment Address</b></div>
			  <div class="panel-body">
			  	<ul>
			  		<li><?php echo $order['payment_firstname'] . ' '. $order['payment_lastname'];?></li>
			  		<li><?php echo $order['payment_company'];?></li>
			  		<li><?php echo $order['payment_address_1'];?></li>
			  		<li><?php echo $order['payment_address_2'];?></li>
			  		<li><?php echo $order['payment_city'] . ' ' . $order['payment_postcode'];?></li>
			  		<li><?php echo $order['payment_zone'];?></li>
			  		<li><?php echo $order['payment_country'];?></li>
			  	</ul>
			  </div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-default">
			  <div class="panel-heading"><b>Shipping Adress</b></div>
			  <div class="panel-body">
			  	<ul>
			  		<li><?php echo $order['shipping_firstname'] . ' '. $order['shipping_lastname'];?></li>
			  		<li><?php echo $order['shipping_company'];?></li>
			  		<li><?php echo $order['shipping_address_1'];?></li>
			  		<li><?php echo $order['shipping_address_2'];?></li>
			  		<li><?php echo $order['shipping_city'] . ' ' . $order['shipping_postcode'];?></li>
			  		<li><?php echo $order['shipping_zone'];?></li>
			  		<li><?php echo $order['shipping_country'];?></li>
			  	</ul>
			  </div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="container">
		<table class="table table-bordered">
			<thead>
				<td class="text-center" colspan="2">Add Serial</td>
				<th>Product</th>
				<th>Model</th>
				<th>Options</th>
				<th>Order Status</th>
				<th>Tracking Number</th>
				<th>Track Info</th>
				<th>Quantity</th>
				<th>Shipping Fee</th>
				<th>Unit Price</th>				
				<th>Total</th>
				
			</thead>
			<tbody>
				<?php foreach($order['products'] as $product):
					$bg_order_id=0;
					$bg_order_status="";
					$bg_Track_Info="";
					$track_number_bg="";
				?>
				<tr>
				<?php
				if($product['p_type']=="2"){
					$params_goi = array('sale_record_id' => $_GET['order_id'],'lang' => 'en');
					$banggoodAPI->setParams($params_goi);
					$result_goi = $banggoodAPI->getOrderInfo();
					$status_goi=$result_goi['code'];
					if($status_goi==0){
						$bg_order_id=$result_goi['sale_record_id_list'][0]['order_list'][0]['order_id'];
						$bg_order_status=$result_goi['sale_record_id_list'][0]['order_list'][0]['status'];
						//get GetTrackInfo API
						$params_gti = array('order_id' =>$bg_order_id,'lang' => 'en');
						$banggoodAPI->setParams($params_gti);
						$result_gti = $banggoodAPI->getTrackInfo();
						$bg_Track_Info=$result_gti['track_info'][0]['event']. "(".$result_gti['track_info'][0]['time'].")";
						
						//GetOrderHistory API
						$params_goh = array('sale_record_id' => $_GET['order_id'],'order_id' =>$bg_order_id,'lang' => 'en');
						$banggoodAPI->setParams($params_goh);
						$result_goh = $banggoodAPI->getOrderHistory();
						$track_number_bg=$result_goh['track_number'];
						/*echo'<pre>';
						print_r($result_goh);*/

					}else{
						$bg_order_id=0;
						$bg_order_status=$result_goi['msg'];
						$bg_Track_Info="";
					}
					
				}
				
              if($product['NOA']==$product['quantity']){
              ?>
             <td class="text-right">
                <button id="button-clear_serial" 
                          class="btn btn-danger"
                          data-op_id="<?php echo $product['order_product_id']; ?>"
                          data-order_id_s="<?php echo $_GET['order_id']; ?>"
                          data-model="<?php echo $product['model']; ?>"
                    <i class="fa fa-trash"></i> 
                    Clear
                </button> 
             </td>
             <td class="text-center"> You have Already added Serial </td>
              <?php
              }else{
              $remain=$product['quantity']-$product['NOA'];
              if( $remain==$product['quantity']){
              ?>
              <td class="text-right"> 
                <button id="button-serial" 
                          class="btn btn-primary"
                          data-op_id="<?php echo $product['order_product_id']; ?>"
                          data-op_id2="<?php echo $product['NOA']; ?>"
                          data-c_p_id="<?php echo $product['product_id']; ?>"
                          data-order_id_s="<?php echo $_GET['order_id']; ?>"
                          data-op_qty="<?php echo $product['quantity'];?>">
                    <i class="fa fa-plus-circle"></i> 
                    Add Serial
                </button>
              </td >
              <?php
              }else{
              ?>
               <td class="text-right"> 
                <button id="button-serial" 
                          class="btn btn-primary"
                          data-op_id="<?php echo $product['order_product_id']; ?>"
                          data-op_id2="<?php echo $product['NOA']; ?>"
                          data-c_p_id="<?php echo $product['product_id']; ?>"
                          data-order_id_s="<?php echo $_GET['order_id']; ?>"
                          data-op_qty="<?php echo $product['quantity'];?>">
                    <i class="fa fa-plus-circle"></i> 
                    Add Serial
                </button>
                 <button id="button-clear_serial" 
                          class="btn btn-danger"
                          data-op_id="<?php echo $product['order_product_id']; ?>"
                          data-order_id_s="<?php echo $_GET['order_id']; ?>"
                          data-model="<?php echo $product['model']; ?>"
                    <i class="fa fa-trash"></i> 
                    Clear
                </button> 
              </td >
              <?php
              }
              ?>
              
             <td class="text-left"> Remain Items (<?php echo $remain;?>)</td>
              
              <?php
              }
              ?> 
					<td><?php echo $product['name'];?></td>
					<td><?php echo $product['model'];?></td>
					<td><?php echo $product['poa_name'];?></td>
					<td><?php echo $bg_order_status;?></td>
		            <td><?php echo $track_number_bg;?></td>
		            <td><?php echo $bg_Track_Info;?></td>
					
					<?php if($product['NOA']==$product['quantity']){?>
		                <td class="text-right">1</td>
		                <td class="text-right"><?php echo $product['price']; ?></td>
		                <td class="text-right"><?php echo $product['price']; ?></td>
		             <?php }else{ ?>
		                 <td class="text-right"><?php echo $product['quantity']; ?></td>
		                 <td class="text-right"><?php echo $product['shipping_fee']; ?></td>
		                 <td class="text-right"><?php echo $product['price']; ?></td>		                 
		                 <td class="text-right"><?php echo $product['total']; ?></td>
		             <?php }?>
				</tr>
				<?php endforeach;?>
			<?php foreach ($order['total'] as $totals) { ?>
            <tr>
              <td colspan="11" class="text-right"><?php echo $totals['title']." : "; ?></td>
              <td class="text-right"><?php echo number_format($totals['value'],2); ?></td>
            </tr>
            <?php } ?>
			</tbody>
		</table>
	</div>
</div>
<div class="row">
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading"><b>Order History</b></div>
			  	<div class="panel-body">
				   <ul class="nav nav-tabs" role="tablist">
				    <li role="presentation" class="active"><a href="#history" role="tab" data-toggle="tab">History</a></li>
				    <li role="presentation"><a href="#additional"  role="tab" data-toggle="tab">Additional</a></li>
				  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="history">
		<div class="table-responsive">
    		<table class="table table-bordered">
    			<thead>
    				<tr>
    				<th>Date Added</th>
    				<th>Comment</th>
    				<th>Status</th>
    				<th>Customer Notified</th>
    				</tr>
    			</thead>
    			<tbody>
    				<?php foreach($order['history'] as $history):?>
					<tr>
						<td><?php echo $history['date_added'];?></td>
						<td><?php echo nl2br($history['comment']);?></td>
						<td><?php echo $history['status'];?></td>
						<td><?php echo $history['notify'];?></td>
					</tr>
    				<?php endforeach;?>
    			</tbody>
    		</table>
		</div>
    </div>
    <div role="tabpanel" class="tab-pane" id="additional">
    	<div class="container">
    		<ul>
    			<li><?php echo $order['ip'];?></li>
    			<li><?php echo $order['user_agent'];?></li>
    			<li><?php echo $order['accept_language'];?></li>
    		</ul>
    	</div>
    </div>
  </div>
			  </div>
			</div>
	</div>
</div>
<div class="row">
	<div class="container">
		<div class="col-md-6">
			<h4>Add Order History</h4>
		<form method="post">
			<div class="form-group">
				<select class="form-control" name="order_status_id" id="order_status_id">
					<?php
					foreach($model->order_status() as $status) {
						echo '<option value='.$status['order_status_id'].'>'.$status['name'].'</option>';
					}
					?>
				</select>
			</div>
			<label  id="lbltxt"></label> <i id="itxt"></i>
			<div class="form-group">
				<textarea name="comment" cols="30" rows="10" class="form-control"></textarea>
			</div>
			<button type="submit" name="add_order_history" class="btn btn-primary pull-right">Add History</button>
		</form>
		</div>

	</div>
</div>

<?php include 'template/footer.php';?>

<div  class="modal fade" 
        id="MOD-add-serial" 
        tabindex="-1" role="dialog" 
        aria-labelledby="myModalLabel" 
        aria-hidden="true" 
        style="display: none;">
     <div class="modal-dialog modal-error-dialog">
       <div class="modal-content">
          <div class="modal-header">
            <label ><h2>Add Serial</h2></label>
             <button type="button" class="close" id="closemod" data-dismiss="modal" aria-hidden="true">×</button>
             <h4 class="modal-title" id="myModalLabel"></h4>
          </div>
          <div class="modal-body" >
             <div class="form-group">
                <label>Serial Code</label>
                 <input  type ="text" class="form-control" id ="OP_serial_code" name="OP_serial_code" placeholder="Input Serial Code Here" autofocus>
                 <label>Product Cost</label>
                  <input  type ="number" class="form-control" id ="pruduct_cost" name="pruduct_cost" placeholder="Input Product Cost Here" autofocus>
                 <input type ="hidden" name="OP_id" class="form-control" id ="OP_id">
                 <input type ="hidden"  name ="OP_quantity" class="form-control" id ="OP_quantity">
                 <input type ="hidden"  name ="OP_NOA" class="form-control" id ="OP_NOA">
                 <input type ="hidden"  name ="c_p_id" class="form-control" id ="c_p_id">
                  <input type ="hidden"  name ="order_id_s" class="form-control" id ="order_id_s">
            </div>

            <div class="row">
              <div class="col-sm-4">
                <div class="form-group">
                  <input type="button" value="Save" class="btn btn-primary" id="save_serial"  /> 
                  <a type="button" class="btn btn-default" data-dismiss="modal" >Cancel</a>
                </div>
              </div>
            </div>
          </div>
       </div>
     </div>
            
  </div>

<script type="text/javascript">
	$(document).ready(function() {
		$("#lbltxt").text("Input Comment: ");
		$("#itxt").text("");
		$('#order_status_id').on('change', function(){
			var op= $( "#order_status_id option:selected" ).val();
			if(op=="20"){
				$("#lbltxt").text("Seller Receipt No. ");
				$("#itxt").text("(Only the reciept details.)");
			}else{
				$("#lbltxt").text("Input Comment: ");
				$("#itxt").text("");
			}
			
		});
    });

	$(document).delegate('#button-serial', 'click', function() {
       $("#OP_serial_code").val("");
        $('#MOD-add-serial').modal('show');       
        var op_id=$(this).data('op_id');
        var op_qty=$(this).data('op_qty');
        var op_NOA=$(this).data('op_id2');
        var c_p_id=$(this).data('c_p_id');
        var order_id_s=$(this).data('order_id_s');

          $("#OP_id").val(op_id);
          $("#OP_quantity").val(op_qty);
          $("#OP_NOA").val(op_NOA);
          $("#c_p_id").val(c_p_id);
          $("#order_id_s").val(order_id_s);

    })
    $(document).delegate('#save_serial', 'click', function() {
	  var order_pid=$("#OP_id").val();
	  var order_pqty=$("#OP_quantity").val();
	  var order_NOA=$("#OP_NOA").val();
	  var c_p_id=$("#c_p_id").val();
	  var order_id_s=$("#order_id_s").val();
	  var OP_serial_code=encodeURIComponent($('input[name=\'OP_serial_code\']').val())
	  var pruduct_cost=encodeURIComponent($('input[name=\'pruduct_cost\']').val())
	  
	  if(OP_serial_code==""){
	    alert("Serial no. is required");
	  }else if (pruduct_cost==""){
	    alert("Product cost is required");
	  }else{
	   //alert(order_id_s+"--"+order_pid+"--"+order_pqty+"--"+order_NOA+"--"+c_p_id+"--"+OP_serial_code+"--"+pruduct_cost);
	    $.ajax({
            url: 'ajax_save_serial.php',
            type: 'POST',
            data: '&order_pid='+order_pid+'&order_pqty='+order_pqty+'&order_NOA='+order_NOA+'&pruduct_cost='+pruduct_cost+'&c_p_id='+c_p_id+'&OP_serial_code='+OP_serial_code+'&order_id='+order_id_s,
            dataType: 'json',
            success: function(json) {
             
              if (json['success']) {
				alert(json['success']);
				 location.replace("../mgmtacc/view_order.php?order_id="+order_id_s);
              }
            },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
          });
	  }  
	});

	$(document).delegate('#button-clear_serial', 'click', function() {

	 var Cmodel=$(this).data('model');
	 var op_id=$(this).data('op_id');
     var order_id_s=$(this).data('order_id_s');
    	//alert(op_id);
	  $.ajax({
            url: 'ajax_save_serial.php',
            type: 'POST',
            data: 'op_id='+op_id+'&clorder_id_s='+order_id_s,
            dataType: 'json',
            success: function(json) {
             
              if (json['success']) {
				alert(json['success']);
				 location.replace("../mgmtacc/view_order.php?order_id="+order_id_s);
              }
            },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
          });

	 

	});
</script>