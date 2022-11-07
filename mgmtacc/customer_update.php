<?php
include 'template/header.php';
include "model/Customer.php";
$model = new Customer(); 

$perm = $_SESSION['permission'];
if (!strpos($perm, "'8';") !== false){
    header("Location: landing.php");
   
} 

if(isset($_GET['cid'])) {
        $customer=$_GET["cid"];
        $data = $model->customer_details($customer);
 }
?>
<div class="container">
<form action="submit_customer.php" method="post">
		<div class="row">
			<div class="form-group">
				<div class="col-lg-12">
					<?php $header = $_GET['cid'] !== '0' ? $header = 'Edit Customer' : $header = 'Add New Customer'; ?>
					 <span style="font-size: 26px" class="pull-left"><?php echo $header;?></span>
					 <div class="pull-right">
						 <a href="customer_list.php" class="btn btn-danger" title="Back"><i data-feather="arrow-left"></i></a>
						 <button type="submit" class="btn btn-primary" name="update_customer" title="Save"><i data-feather="save"></i></button>
					 </div>
				</div>
			</div>
		</div>
		<br>
		<?php if(isset($_SESSION['message'])):?>
		<?php echo $_SESSION['message'];?>		
		<?php endif;?>
		<?php unset($_SESSION['message']); ?>
		<div class="row form-group">
			<input type="hidden" name="customer_id" value="<?php echo $_GET['cid']?>">
			<div class="col-sm-3 control-label"><label>Customer Group</label></div>	
			<div class="col-sm-6">
				<select name="customer_group_id" id="customer_group_id" class="form-control" required>
					<option value="">--Select Customer Group--</option>
					<?php foreach($model->customer_group() as $c):?>
					<option value="<?php echo $c['customer_group_id']; ?>"><?php echo $c['name'];?></option>
					<?php endforeach;
					echo "<script>$('#customer_group_id').val('".$data['customer_group_id']."')".";</script>'";?>
				</select>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-sm-3 control-label"><label>First Name</label></div>					
			<div class="col-sm-6">
				<input type="text" name="firstname" class="form-control" placeholder="First Name" value="<?php echo isset($_SESSION['firstname'])? $_SESSION['firstname'] : $data['firstname']; ?>" required/>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-sm-3 control-label"><label>Last Name</label></div>
			<div class="col-sm-6">
				<input type="text" name="lastname" class="form-control" placeholder="Last Name" value="<?php echo isset($_SESSION['lastname'])? $_SESSION['lastname'] : $data['lastname']; ?>" required/>
			</div>
		</div>	
		<div class="row form-group">
			<div class="col-sm-3 control-label"><label>Email</label></div>
			<div class="col-sm-6">
				<input type="text" name="email" class="form-control" placeholder="Email" value="<?php echo isset($_SESSION['email'])? $_SESSION['email'] : $data['email']; ?>" required/>
			</div>
		</div>			
		<div class="row form-group">
				<div class="col-sm-3 control-label"><label>Mobile No.</label></div>
				<div class="col-sm-6">
					<input type="text" name="telephone" class="form-control" placeholder="63XXXX" value="<?php echo isset($_SESSION['telephone'])? $_SESSION['telephone'] : $data['telephone']; ?>" required/>
				</div>
		</div>
		<div class="row form-group">
				<div class="col-sm-3 control-label"><label>Fax</label></div>
				<div class="col-sm-6">
					<input type="text" name="fax" class="form-control" placeholder="Fax" value="<?php echo isset($_SESSION['fax'])? $_SESSION['fax'] : $data['fax']; ?>"/>
				</div>
		</div>		
		<div class="row form-group">
				<div class="col-sm-3 control-label"><label>Password</label></div>
				<div class="col-sm-6">
					<input type="text" name="password" class="form-control" placeholder="Password" value="<?php echo isset($_SESSION['password'])? $_SESSION['password'] : ''; ?>"/>
				</div>
		</div>
		<div class="row form-group">
				<div class="col-sm-3 control-label"><label>Confirm Password</label></div>
				<div class="col-sm-6">
					<input type="text" name="confirmpassword" class="form-control" placeholder="Confirm Password" value="<?php echo isset($_SESSION['confirmpassword'])? $_SESSION['confirmpassword'] : ''; ?>"/>
				</div>
		</div>
		<div class="row form-group">
			<div class="col-sm-3 control-label"><label>Newsletter</label></div>	
			<div class="col-sm-6">
				<select name="newsletter" id="newsletter" class="form-control" required>
					<option value="">--Select Newsletter--</option>
					<option value="0">Disable</option>
					<option value="1">Enable</option>
					<?php echo "<script>$('#newsletter').val('".$data['newsletter']."')".";</script>'";?>
				</select>
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
		<div class="row form-group">
			<div class="col-sm-3 control-label"><label>Approved</label></div>	
			<div class="col-sm-6">
				<select name="approved" id="approved" class="form-control" required>
					<option value="">--Select--</option>
					<option value="1">Yes</option>
					<option value="0">No</option>
					<?php echo "<script>$('#approved').val('".$data['approved']."')".";</script>'";?>
				</select>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-sm-3 control-label"><label>Safe</label></div>	
			<div class="col-sm-6">
				<select name="safe" id="safe" class="form-control" required>
					<option value="">--Select--</option>
					<option value="1">Yes</option>
					<option value="0">No</option>
					<?php echo "<script>$('#safe').val('".$data['safe']."')".";</script>'";?>
				</select>
			</div>
		</div>
	</form>
</div>  

<?php include 'template/footer.php';?>                                                                  
        
 <script>
    $(document).ready(function() {

        $('.customer-table').paging({   
            limit: 50,
            rowDisplayStyle: 'block',
            activePage: 0,
            rows: []
        });

    });
 </script>

