<?php 
	include 'template/header.php'; 
	include "model/Specification.php";
	$model = new Specification(); 


	$currency_active=$model->currency_active();
	if($currency_active){
		$BASE=$currency_active['base'];
		$CNTO=$currency_active['exchange_currency'];
		$curl_exchangerates =curl_init('http://api.currencylayer.com/live?access_key=a24fe932b4671dfc479a0457a965e7f6&source='.$BASE);
		curl_setopt($curl_exchangerates, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl_exchangerates, CURLOPT_RETURNTRANSFER, 1);
		$result_excr = curl_exec($curl_exchangerates); 
		curl_close($curl_exchangerates);
		$res_excr= json_decode($result_excr);
		
		$RATE=$res_excr->quotes->{$CNTO};
		$date = new DateTime("now", new DateTimeZone('Asia/Manila'));
		$date_now=$date->format('Y-m-d');
		$api_date='2020-11-26';//$date_now;
		$count_fer=$model->count_fer($date_now);
		if($count_fer==0){
			//insert
			$model->insert_fer($date_now,$BASE,$CNTO,$RATE,$api_date);
		}else{
			//update
			$model->update_fer($date_now,$BASE,$CNTO,$RATE,$api_date);
		}
	}
	
	

	if(isset($_GET['cl_del'])){
		$delete=$model->delete_currency($_GET['cl_del']);
		if($delete=="200"){
		 $sMsg="Successfully Deleted currency"; 
		}else{
			$errorr_msg=$delete;
		}
	}
	if(isset($_GET['cl_disable'])){
		$cl_disable=$model->currency_disable($_GET['cl_disable']);
		if($cl_disable=="200"){
			$sMsg="Successfully Disabled"; 
		}else{
			$errorr_msg=$cl_disable;
		}
	}
	if(isset($_GET['cl_enable'])){
		$cl_enable=$model->currency_enable($_GET['cl_enable']);
		if($cl_enable=="200"){
		 	$sMsg="Successfully Enabled"; 
		}else{
			$errorr_msg=$cl_enable;
		}
	}
	
	
	
	
?>
<div id="content">
	<div class="page-header">
		 <h2 class="text-center">Select currency</h2>
	</div>
	<?php if(isset($sMsg)){ ?>
	    <div class="alert alert-success">
	        <strong><?php echo $sMsg;?></strong></br>
	    </div>
	<?php } ?>
	<?php if(isset($errorr_msg)){ ?>
	    <div class="alert alert-danger">
	        <strong><?php echo $errorr_msg;?></strong></br>
	    </div>
	<?php } ?>
	<div class="container-fluid">
		 <div class="panel panel-success">
		 	<div class="panel-heading" style="padding:20px;">
		 		<div class="row">
          			<div class="col-lg-6">
          				<p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i> Currency List</p>
          			</div>
          			<div class="col-lg-6">
			            <div class="pull-right">
			            	<button class="btn btn-primary pull-right" id="add-Currency" title="Add Currency" class="btn btn-primary"><i class="fa fa-plus"></i></button>
			            </div>
			        </div>
          		</div>
		 	</div>
		 	<div class="panel-body">
    	 		<div class="table-responsive">
    	 			<table class="table table-bordered table-hover">
    	 				<thead>
							<th>Description</th>
							<th>Status </th>
							<th>Action </th>
						</thead>
						 <tbody>
						 	<?php foreach($model->currency_list() as $cl): ?>
						 		<tr>
						 			<td class="text-left" ><?php echo $cl['base']." To ".$cl['exchange_currency'];?></td>		 			
						 			<td><?php if($cl['status'] == '1') { 
								 			echo '<span style="color:green;">Enabled</span>'; 
								 		} else {
								 			echo '<span style="color:red;">Disabled</span>';
								 		}?>
								 	</td>
								 	<td> 
								 		<a href="selected_currency.php?cl_del=<?php echo $cl['id'];?>" class="btn btn-danger btn-edit"  title="Delete" ><i data-feather="trash-2"></i></a>
								 		<?php if($cl['status'] == '1') { ?>
								 			<a href="selected_currency.php?cl_disable=<?php echo $cl['id'];?>" class="btn btn-warning btn-edit"  title="Disable"><i data-feather="x-circle"></i></a>
								 			
								 		<?php } else { ?>
								 			<a href="selected_currency.php?cl_enable=<?php echo $cl['id'];?>" class="btn btn-primary btn-edit"  title="Enable"><i data-feather="check"></i></a>
								 		<?php }?>
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
      <div class="modal fade bd-example-modal-lg" 
      		id="AddModal" tabindex="-1" 
      		role="dialog" aria-labelledby="myLargeModalLabel" 
      		aria-hidden="true">
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
              <p style="font-size: 23px" class="modal-title"><strong>Add Currency</strong></p>
            </div>
            <div class="modal-body">
               <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="particular-table">
                  <thead>
                      <tr>
                        <th colspan="3">Description</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                        <select name="base_c" id="base_c" class="form-control">							
							<option value="USD">USD</option>
						</select>
                        </td>
                        <td>
                          To
                        </td>
                        <td>
                         <select name="exchange_currency" id="exchange_currency" class="form-control">
                            <option value="USDPHP">PHP</option>
							<option value="USDHKD">HKD</option>
							<option value="USDEUR">EUR</option>
						</select>
                        </td>
                      </tr>
                  </table>
                </table>
              </div>
              <div class="form-group navbar-right" style="margin-right: 10px;">
                <button id="save_currency" type="button" class="btn btn-primary" ><i class="fa fa-save"></i> Save</button>
              </div>
              <br><br>
            </div>
          </div>
        </div>
      </div>
<?php include 'template/footer.php'; ?>


<script type="text/javascript">
	$( document ).ready(function() {
	 	$('#add-Currency').click(function() {
        	$('#AddModal').modal('show');
    	});
    	$('#save_currency').click(function() {
        	var exchange_currency= $('#exchange_currency').val();
        	var base_c= $('#base_c').val();
        	$.ajax({
		        url:'ajax_homecategory.php?action=add_currency',
		        type: 'post',
		        data: 'base_c=' + base_c+'&exchange_currency='+exchange_currency,
		        dataType: 'json',
		        success: function(json) {
		          if (json['success']) {
		             location.replace('selected_currency.php');
		          }
		        },
		        error: function(xhr, ajaxOptions, thrownError) {
		            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		        }
	      }); 
        	
    	});

	});
</script>