<?php
include_once 'template/header.php';
require_once "model/FourGives_CustomerLIst.php";
$modCustomer = new FGives_Custumer();	
$list = $modCustomer->getAllCustomer();

$perm = $_SESSION['permission'];
if (!strpos($perm, "'252546';") !== false){
    header("Location: landing.php");
   
}
if(isset($_POST['save_sb'])) {
	/*echo "<pre>";
	print_r($_POST);*/
	$res=$modCustomer->save4GivesCustomer($_POST);
	$sMsg= '<div class="alert alert-success">'.$res.'</div>';
	$list = $modCustomer->getAllCustomer();
}

?>
<div class="content">
	<div class="container-fluid">
		<div class="panel panel-default">
		 	<div class="panel-heading" style="padding:20px;">
		 		<div class="row">
	          		<div class="col-lg-2">
	             		<p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i> Customer List</p>
	          		</div>
	          		<div class="col-lg-10">
	             		<a href="FourGivesCustomerUpload.php"class="btn btn-success pull-right" ><i class="fas fa-upload"></i> Upload Customer</a>
				 		<a class="btn btn-primary pull-right" id="Add4GivesCust" ><i class="fas fa-plus-square"></i> Add Customer</a>
	          		</div>
		        </div>
		 	</div>
		 	<div class="panel-body">
		 		<?php if(isset($sMsg)){ ?>
		            <div class="alert alert-success">
		              <strong><?php echo $sMsg;?></strong></br>
		            </div>
		          <?php } ?>  
	            <div class="row">
					<div class="col-xs-12">
				 		<div class="table-responsive" style="overflow-x: auto">
				 			<table class="table table-striped table-bordered table-hover table_FourGives" id="table_FourGives">
						 	  	<thead>
				                    <tr>
				                     <th>Full Name</th>
				                     <th>Email</th>
				                     <th>Mobile Number</th>
				                     <th>Birthday</th>
				                     <th>Address</th>
				                    </tr>
			                    </thead> 
			                    <tbody>
			                   	 <?php					
									if(count($list) == 0){
										?>
										 <!-- <tr>
										 	<td colspan="5" align="center">No data found.</td>
										 	
										 </tr> -->
										<?php
									}else{
										foreach($list as $data)
										{
											?>
											 <tr>
											 	<td><?php echo  $data['fullname'];?></td>
											 	<td><?php echo  $data['email'];?></td>
											 	<td><?php echo  $data['telephone'];?></td>
											 	<td><?php echo  $data['bday'];?></td>
											 	<td><?php echo  $data['address'];?></td>
											 	
											 </tr>

											<?php
										}
									}									    
								?>
	                  			</tbody>
					 		</table>
						</div>							
					</div>
		 		</div>
		 	</div>
		</div>
 	</div>
		
</div>
<div class="modal fade bd-example-modal-lg" id="AddModalAddCust" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
	 <div class="modal-dialog modal-lg">
	 	<div class="modal-content">
	 		<div class="modal-header">
          		<a type="button"  data-dismiss="modal"   style="float: right;
                    font-size: 25px;
                    font-weight: 700;
                    line-height: 1;
                    color: #000;
                    text-shadow: 0 1px 0 #fff;
                  "><i class="fa fa-times-circle " style="color: black;font-size: 25px;" ></i></a>
                 <?php $header_mod = "Add 4Gives Customer" ?>
        		<h3><?php echo $header_mod;?></h3>
        	</div>
        	<div class="modal-body">
        		<form action="FourGives_CustomerLIst.php"method="post" enctype="multipart/form-data" id="form-product" >
        		<div class="form-group navbar-right" style="margin-right: 10px;">
        			<button type="submit" class="btn btn-primary btn-bnn-SAVE_ab" name="save_sb"><i class="fa fa-save"></i> Save</button>
        		</div>
        		<ul class="nav nav-tabs">        
		          <li><a data-toggle="tab" href="#data">Data</a></li>  
		          <li><a data-toggle="tab" href="#address">Address</a></li>
		        </ul>
		        <div class="tab-content" style="margin-top: 20px">
		        	<div id="data" class="tab-pane fade in active">	
						<div class="form-group required">
							<label>First Name</label>
							<input type="text" name="fname" class="form-control" placeholder="First Name"  required/>
							
						</div>
						<div class="form-group required">
							<label>Last Name</label>
							<input type="text" name="lname" class="form-control" placeholder="Last Name"  required/>
							
						</div>
						<div class="form-group required">
							<label>Phone Number</label>
							<input type="Number" name="telephone" class="form-control" placeholder="Phone Number"  required/>
							<div id="divmobile"></div>						
						</div>
						<div class="form-group required">
							<label>Email Address</label>
							<input type="text" name="txtemail" class="form-control" placeholder="Email Address"  required/>
							<div id="email_add">
				       		</div>						
						</div>	
						<div class="form-group required">
							<label>Birthday</label>
							<input type="date" name="bday" class="form-control" placeholder="Birthday"  required/>
						</div>				
		        	</div>
		        	
		        	<div id="address" class="tab-pane fade">
		        		<div class="form-group required">
							<label><b style="color: red">*</b> House Number/Street/Building Name</label>
							<input type="text" name="address_1" class="form-control" placeholder="House Number/Street/Building Name"  required/>
						</div>
						<div class="form-group required">
							<label>Unit/Floor</label>
							<input type="text" name="address_2" class="form-control" placeholder="Unit/Floor" />
						</div>
						<div class="form-group required">
							<label><b style="color: red">*</b> Region/Province</label>
							<select name="region" id="region" class="form-control" required>	
								<option value="">--Region/Province--</option>
								<?php foreach($modCustomer->getRegion() as $c):?>
									<option value="<?php echo $c['province']; ?>"><?php echo $c['province'];?></option>
								<?php endforeach;?>			
							</select>
						</div>
						<div class="form-group required" id="fgcity" style="display: none;" >
							<label><b style="color: red">*</b> City/Municipality</label>
							<select name="city" id="city" class="form-control" required>
								
							</select>
						</div>
						<div class="form-group required" id="fgbarangay" style="display: none;" >	
							<label><b style="color: red">*</b> Barangay/District</label>
							<select name="district" id="district" class="form-control" required>
								
							</select>
						</div>
						<input type="hidden" name="postal_code" id="postal_code"  required/>
						<input type="hidden" name="tracking_id" id="tracking_id"  required/>
		        	</div>
		        </div>
        		</form>
        	</div>
	 	</div>
	 </div>
</div>
<script>
	$(document).ready(function() {
		$('.table_FourGives').DataTable({"order": [],
		      "oLanguage": {
		        "sSearch": "Quick Search:"
		      },
		      "bSort": true,
		      "dom": 'Blfrtip',
		      "buttons": [{
		          extend: 'excel',
		          title: '4Gives Customer List Report',
		        },
		        {
		          extend: 'pdf',
		          title: '4Gives  Customer List Report',
		         
		        },
		        {
		          extend: 'print',
		          title: '4Gives  Customer List Report',
		        },
		      ],
		      "lengthMenu": [
		        [15, 50, 100,-1],
		        [15, 50, 100,"all"]
		      ],});
		$("#Add4GivesCust").click(function() {
			 $('#AddModalAddCust').modal('show');
		});
		$('input[name="telephone"]').change(function(e) {
			var mobStr = $(this).val();		    
		    if(mobStr.length!= 10 || !$.isNumeric(mobStr)) {
		       $("#divmobile").empty();
		       $("#divmobile").append('<div class="text-danger">Mobile No. must be 10 characters. (Ex. 9xxxxxxxxx)</div>');
		       $(':input[type="submit"]').prop('disabled', true);
		    }else{
		    	
		    	$.ajax({
		            url: 'ajax_sms_admin.php?action=fGivesDuplicateNumber&t=' + new Date().getTime(),
		            type: 'POST',
		            data: 'mobStr=' + mobStr,
		            dataType: 'json',
		            success: function(json) {
		                if(json=="0"){
		                	$("#divmobile").empty();
		    				$(':input[type="submit"]').prop('disabled', false);
		                }else{
		                	$("#divmobile").empty();
					    	$("#divmobile").append('<div class="text-danger">Mobile No. Already Exist</div>');
					    	$(':input[type="submit"]').prop('disabled', true);

		                }
		            },
	                error: function(xhr, ajaxOptions, thrownError) {
	                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	                }
	          	});
		    }
		});
		$('input[name="txtemail"]').change(function(e) {
			var emailStr = $(this).val();
		    var regex = /^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i;
		    if(!regex.test(emailStr)) {
		       $("#email_add").empty();
		       $("#email_add").append('<div class="text-danger">Email address is not valid</div>');
		       $(':input[type="submit"]').prop('disabled', true);
		    }else{
		    	$("#email_add").empty();
		    	$(':input[type="submit"]').prop('disabled', false);
		    }
		});
		$('#region').on('change', function(){
    		$("#fgcity").css("display","block");
    		$("#city").empty();
    		$("#district").empty();
	   		var province = $(this).val();	   		
	   		$.ajax({
            url: '../ajaxAddress.php?action=getCity&t=' + new Date().getTime(),
            type: 'POST',
            data: 'province=' + province,
            dataType: 'json',
            success: function(json) {
                $("#city").append('<option value="">--Select City/Municipality--</option>');
                	//console.log(json);
                for (var i = 0; i < json.length; i++) {
                    $("#city").append('<option value="' + json[i].city + '">' + json[i].city + '</option>');
                }
            },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
          	});
	    });  
		$('#city').on('change', function(){
    		$("#fgbarangay").css("display","block");
    		$("#district").empty();
	   		var province = $("#region").val();	   		
	   		var city = $(this).val();	
	   		//alert(city);   		
	   		$.ajax({
            url: '../ajaxAddress.php?action=getDistrict&t=' + new Date().getTime(),
            type: 'POST',
            data: 'province=' + province+'&city='+city,
            dataType: 'json',
            success: function(json) {
                $("#district").append('<option value="">--Select Barangay/District--</option>');
                	//console.log(json);
                for (var i = 0; i < json.length; i++) {
                    $("#district").append('<option value="' + json[i].district + '">' + json[i].district + '</option>');
                }
            },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
          	});
	    });
		$('#district').on('change', function(){    		
	   		var province = $("#region").val();	   		
	   		var city = $("#city").val();		
	   		var district = $(this).val();	
	   		//alert(city);   		
	   		$.ajax({
            url: '../ajaxAddress.php?action=getTracking_id&t=' + new Date().getTime(),
            type: 'POST',
            data: 'province=' + province+'&city='+city+'&district='+district,
            dataType: 'json',
            success: function(json) {
                //console.log(json);
                $("#tracking_id").val(json['tracking_id']);
                $("#postal_code").val(json['postal_code']);
            },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
          	});
	    });     
	});
</script>
<?php include 'template/footer.php';?>									
