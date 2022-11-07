<?php
include 'template/header.php';
include "model/Voucher.php";
$model = new Voucher(); 

$perm = $_SESSION['permission'];
if (!strpos($perm, "'9';") !== false){
    header("Location: landing.php");
   
} 
      
if(isset($_GET['vid'])) {
    $voucher=$_GET["vid"];
    $data = $model->voucher_details($voucher);
    $history = $model->voucher_history($voucher);
    $theme = $model->voucher_theme_list();
 }
if(isset($_POST['update_voucher'])){  
	if($_GET['vid'] !== '0') {
   		$update = $model->voucher_update($_POST);
	} else {
		$add = $model->voucher_add($_POST);
	}
	$data = $model->voucher_details($voucher);
}
?>
<div class="container">
<form action=""method="post" enctype="multipart/form-data" id="form-voucher">
	<div class="row">
		<div class="form-group">
			<div class="col-lg-12">
				<?php $header = $_GET['vid'] !== '0' ? $header = 'Edit Voucher' : $header = 'Add New Voucher'; ?>
				 <span style="font-size: 26px" class="pull-left"><?php echo $header;?></span>
				 <div class="pull-right">
					 <a href="voucher_list.php" class="btn btn-danger" title="Back"><i data-feather="arrow-left"></i></a>
					 <button type="submit" class="btn btn-primary" name="update_voucher" title="Save"><i data-feather="save"></i></button>
				 </div>
			</div>
		</div>
	</div>
	<br>
   	<?php if(isset($errorMsg)){ ?>
	<div class="alert alert-danger">
    <?php foreach ($errorMsg as $error) : ?>  
    <strong><?php echo $error['name']?></strong></br>
    <?php  endforeach;?>
	</div>
		<?php } ?>
	<?php if(isset($_SESSION['message'])):?>
	<?php echo $_SESSION['message'];?>		
	<?php endif;?>
	<?php unset($_SESSION['message']); ?>
	<ul class="nav nav-tabs justify-content-center">
        <li class="nav-item active">
            <a class="nav-link" href="#General" data-toggle="tab" aria-expanded="true">General</a>
        </li>
        <?php if(isset($_GET['vid']) && $_GET['vid'] !== '0'): ?>
        <li class="nav-item">
            <a class="nav-link" href="#History" data-toggle="tab" aria-expanded="true">History</a>
        </li>
    	<?php endif; ?>
    </ul>
    <div class="tab-content">
    	<div class="tab-pane active" id="General">
    		<br>
			<div class="row form-group">
				<div class="col-sm-3 control-label"><label>Code</label></div>					
				<div class="col-sm-6">
					<input type="hidden" name="voucher_id" class="form-control" value="<?php echo $_GET['vid']; ?>" required/>
					<input type="text" name="code" class="form-control" placeholder="Code" value="<?php echo isset($_SESSION['code'])? $_SESSION['code'] : $data['code']; ?>" maxlength= "10" required/>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-3 control-label"><label>From Name</label></div>					
				<div class="col-sm-6">
					<input type="text" name="from_name" class="form-control" placeholder="From Name" value="<?php echo isset($_SESSION['from_name'])? $_SESSION['from_name'] : $data['from_name']; ?>" required/>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-3 control-label"><label>From Email</label></div>					
				<div class="col-sm-6">
					<input type="text" name="from_email" class="form-control" placeholder="From Email" value="<?php echo isset($_SESSION['from_email'])? $_SESSION['from_email'] : $data['from_email']; ?>" required/>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-3 control-label"><label>To Name</label></div>					
				<div class="col-sm-6">
					<input type="text" name="to_name" class="form-control" placeholder="To Name" value="<?php echo isset($_SESSION['to_name'])? $_SESSION['to_name'] : $data['to_name']; ?>" required/>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-3 control-label"><label>To Email</label></div>					
				<div class="col-sm-6">
					<input type="text" name="to_email" class="form-control" placeholder="To Email" value="<?php echo isset($_SESSION['to_email'])? $_SESSION['to_email'] : $data['to_email']; ?>" required/>
				</div>
			</div>
			<div class="row form-group">
			 	<div class="col-sm-3 control-label"><label>Theme</label></div>		
			 	<div class="col-sm-6">
          		  <select class="form-control" name="voucher_theme_id" id="voucher_theme_id" required>
          		  	<option value ="">--Select Theme--</option>
				 	<?php foreach ($theme as $t): ?>
	          		<option value="<?php echo $t['voucher_theme_id']; ?>"><?php echo $t['name']; ?></option>
	          		<?php endforeach;
	          		echo "<script>$('#voucher_theme_id').val('".$data['voucher_theme_id']."')".";</script>'";?>
			      </select>	
		        </div>		 
				<div class="col-sm-2">
					<a href="voucher_theme_list.php" class="btn btn-primary" target="_blank" 
					data-toggle="tooltip" title="Add Voucher Theme"><i data-feather="plus"></i>
					</a>					
				</div>		
		 	</div>
		 	<div class="row form-group">
				<div class="col-sm-3 control-label"><label>Message</label></div>					
				<div class="col-sm-6">
					<textarea type="text" rows="5" name="message" class="form-control" placeholder="Message" required><?php echo isset($_SESSION['message'])? $_SESSION['message'] : $data['message']; ?></textarea>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-3 control-label"><label>Amount</label></div>					
				<div class="col-sm-6">
					<input type="number" name="amount" class="form-control" placeholder="Amount" value="<?php echo isset($_SESSION['amount'])? $_SESSION['amount'] : $data['amount']; ?>" required/>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-3 control-label"><label>Status</label></div>	
				<div class="col-sm-6">
					<select name="status" id="status" class="form-control" required>
						<option value="">--Select Status--</option>
						<option value="0">Disable</option>
						<option value="1">Enable</option>
						<?php echo "<script>$('#status').val('".$data['status']."')".";</script>'";?>
					</select>
				</div>
			</div>
		</div>
		<div class="tab-pane" id="History">
			<br>
			<div class="row">
				<div class="col-sm-12">
					<div class="table-responsive">					
				 		<table class="table table-striped table-bordered table-hover">
				 			<thead>
								<th>Order ID</th>								
								<th>Customer</th>
								<th>Amount</th>
								<th>Date Added</th>										
							</thead>
							<tbody>
								<?php
									if(count($history) > 0){ 
									foreach($history as $o):
								?>
								<tr>
									<td><?php echo $o['order_id'];?></td>
									<td><?php echo '';?></td>
									<td><?php echo $o['amount'];?></td>
									<td><?php echo $o['date_added'];?></td>

								</tr>
							<?php endforeach; }
								else { ?>
										<tr><td colspan="6" align="center">No data found.</td></tr>
									<?php } ?>
							</tbody>
				 		</table>
					</div>
				</div>
			</div>
		</div>
	</div>		
</form>
</div>  

<?php include 'template/footer.php';?>                                                                  
        
 <script>
    $(document).ready(function() {
    	
    });

 </script>

