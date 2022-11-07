<?php 
	include "common/headertest.php";
	require_once "include/Session.php";
  require_once "include/M360Api.php";
  require_once 'model/reg_new.php';
  $returntocart=0;
  $returntocarturl="";
  if(isset($_GET['checkout'])){
    $returntocart=1;
    $returntocarturl='&checkout=1';
  }else if(isset($_GET['retohome'])){
     $returntocart=0;
     $returntocarturl='&retohome=1';
  }else if(isset($_GET['popupchk'])){
     $returntocart=1;
     $returntocarturl='&popupchk=1';
  }
  $popupchk=$_GET['popupchk']??0;
  $regmod = new Register_new();
  $M360Api=new M360Api;
  $credentials =$M360Api->M360Credetial();
  $M360Domain =$M360Api->M360Domain();
  $M360Url = $M360Domain['production'];
	$regid_val = isset($_GET['RegIdVal']) ? $_GET['RegIdVal']: 0; 
	
	$reg=0;
	$resend_info=$regmod->get_resend_validationtime($regid_val); 
	$mobileNo_val=$regmod->get_mobileNo_val($regid_val); 
	//var_dump($resend_info);
	if($resend_info){
			 date_default_timezone_set("Asia/Manila");
			 $now = new DateTime();
			 $then = new DateTime($resend_info['date_updated']);
	         $diff = $now->diff($then);
	         $mins=$diff->format('%i');
	         $sec=$diff->format('%s');
	         $seconds=($mins*60)+$sec;
	         if($seconds<300){
	         	$diffsec=299-$seconds;
	         	 $coundown_res=$diffsec;
	         }else{
	         	 $coundown_res=0;
	         }
	        
	}else{
			 $coundown_res=0;
	}
	if (isset($_POST['resend'])){
		$count_idres=$regmod->count_resend_validationtime($regid_val);
		if($count_idres==0){
			$add_res=$regmod->resend_validationtime($regid_val);
			if($add_res['code']=="200"){          
        		$messageval='Your PESO Verification Code Is '.$add_res['confirmcode'];
            $mobileNumber=$add_res['mobile'];
            $content=$messageval;
            $M360RequestData = array(
                'username' => $credentials['username'],
                'password' => $credentials['password'],
                'msisdn' =>  $mobileNumber,
                'content' =>  $content,
                'shortcode_mask' => $credentials['shortcode_mask'],
            );
            $M360sms = curl_init($M360Url);
            curl_setopt($M360sms, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($M360sms, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'     
            ));
            curl_setopt($M360sms, CURLOPT_POST, 1);
            curl_setopt($M360sms, CURLOPT_POSTFIELDS,json_encode($M360RequestData));
            curl_setopt($M360sms, CURLOPT_FOLLOWLOCATION, 1);
            $dataM360sms = curl_exec($M360sms);
            curl_close($M360sms);
            $ResponsedataM360 = json_decode($dataM360sms);
            if($ResponsedataM360->code=="400"){
              $regmod->sendVevification($add_res['mobile'],$messageval); 
            }
        		$sMsg="Verification Code Successfully Resend";
        	}else{
        		$errorMsg[]=array('name' => $res['code']); 
        	}
		}else{
			$update_res=$regmod->resend_validationtime_update($regid_val);
			if($update_res['code']=="200"){  
        		$messageval='Your PESO Verification Code Is '.$update_res['confirmcode'];
            $mobileNumber=$update_res['mobile'];
            $content=$messageval;
            $M360RequestData = array(
                'username' => $credentials['username'],
                'password' => $credentials['password'],
                'msisdn' =>  $mobileNumber,
                'content' =>  $content,
                'shortcode_mask' => $credentials['shortcode_mask'],
            );
            $M360sms = curl_init($M360Url);
            curl_setopt($M360sms, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($M360sms, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'     
            ));
            curl_setopt($M360sms, CURLOPT_POST, 1);
            curl_setopt($M360sms, CURLOPT_POSTFIELDS,json_encode($M360RequestData));
            curl_setopt($M360sms, CURLOPT_FOLLOWLOCATION, 1);
            $dataM360sms = curl_exec($M360sms);
            curl_close($M360sms);
            $ResponsedataM360 = json_decode($dataM360sms);
            if($ResponsedataM360->code=="400"){
              $regmod->sendVevification($update_res['mobile'],$messageval); 
            }        		
        		$sMsg="Verification Code Successfully Resend";
        	}else{
        		$errorMsg[]=array('name' => $res['code']); 
        	}
		}
	}
	if (isset($_POST['Update_mobileNo'])){
		if($regmod->checkMobileNumber($_POST['txtmobile'])){
		  	$errorMsg[]=array('name' => 'Mobile Number already exists!'); 
		}else{
			$updateMOBstats=$regmod->updateMOBstats($regid_val,$_POST['txtmobile']);
			if($updateMOBstats['code']=="200"){          		
        		$sMsg="Mobile Number Successfully Updated";
        	}else{
        		$errorMsg[]=array('name' => $res['code']); 
        	}
		}
	}
	if (isset($_POST['verify'])){
		unset($_SESSION['message_regok']);
		$getVerificationCode=$regmod->getVerificationCode($regid_val);
		if($getVerificationCode==$_POST['verification_code']){
			$activateMobRes=$regmod->ActivateMobRes($regid_val);
			if($activateMobRes['code']=="200"){  

				$cust_det=$regmod->getCustomerdetails($regid_val);
				if($cust_det){
          $regmod->add_shipping_wallet($cust_det['customer_id']);
					$_SESSION['regok_mobile_verify'] = 'Mobile Number Successfully Verified'; 
					$session->login($cust_det);
					$regmod->insertactivity($cust_det['customer_id'],'login');
          if(!isset($Getdefaultaddress['region'])){ 
            $locationV="<script> window.location.href='address_mod_update.php?aid=0". $returntocarturl;
            $locationV.="';</script>";
            echo $locationV;
          }else if($returntocart==1){
            if($popupchk==1){               
                $locationV="<script> window.close();</script>";
                echo $locationV;
            }else{
                $locationV="<script> window.location.href='cart.php";
                $locationV.="';</script>";
                echo $locationV;
            }
          }else{
            $locationV="<script> window.location.href='home.php";
            $locationV.="';</script>";
            echo $locationV;
          }
					
				}
			}else{
        		$errorMsg[]=array('name' => $activateMobRes['code']); 
        	}
		}else{
			$errorMsg[]=array('name' => 'Warning: Verification code is invalid'); 
		}
	}

?>
<div class="container">
  <div class="" style="margin-top: 135px;" id="div_main_acc">	
    <div class="row">
      <div class="col-lg-12">
        <h2>Verify Your Mobile No.</h2>     
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <?php if(isset($errorMsg)){ ?>
           <div class="alert alert-danger">
               <?php foreach ($errorMsg as $error) : ?>  
                  <strong><?php echo $error['name']?></strong></br>
               <?php  endforeach;?>
           </div>
        <?php } ?>
        <?php if(isset($sMsg)){ ?>
          <div class="alert alert-success">
              <strong><?php echo $sMsg;?></strong></br>
          </div>
        <?php } ?>
        <?php if(isset($_SESSION['message_regok'])):?>
            <div class="alert alert-success"><?php echo $_SESSION['message_regok'];?></div>
        <?php endif;?>      
      </div>
    </div>
     <div class="row">
      <div class="col-lg-12">
        <form action="reg_activatemobile.php?RegIdVal=<?php echo $regid_val.$returntocarturl;?>" method="post" enctype="multipart/form-data" class="form-horizontal" id="form_id">
          <div class="row p-0">
            <div class="col-sm-4 p-1">
              <label  for="input-firstname">Please input the verification code</label>
            </div>
            <div class="col-sm-8 p-1">
              <input type="hidden" name="verify" value="verify"/>
              <input type="text" name="verification_code" value="" placeholder="Verification code" id="input-verification_code" class="full-text form-control " />
            </div>
          </div>              
          <div class="clearfix">
            <input type="button" id="btn_UpdateMOB" name ="updateMOB" value="Update Mobile Number" class="btn btn-primary float-end m-1" />
            <input type="button" id="btn_resend" name ="resend" value="Resend Verification code" class="btn btn-success float-end m-1" />
            <input type="button"  id="btn_send_code"  value="Verify" class="btn btn-primary float-end m-1" />
          </div>
        </form>
      
      </div>
    </div>
	</div>
</div>

<div class="modal fade" id="mobileconfirm" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="background: linear-gradient(-45deg , rgba(143,1,82,1) 0%, rgba(34,22,119,1) 100%); border-radius: 20px;">
      <form action="reg_activatemobile.php?RegIdVal=<?php echo $regid_val.$returntocarturl;?>" method="post" enctype="multipart/form-data" class="form-horizontal">
      <div class="modal-header">        
        <?php if($coundown_res==0){ ?>
          <h5 class="modal-title text-light w-100 text-center " id="exampleModalLabel"> Resend Verification code </h5>
        <?php }else{ ?>
          <div id="captchadiv" style="display: none;">
            <h5 class="modal-title text-light w-100 text-center ">Resend Verification code</h5>
          </div>
          <h5 class="modal-title text-light w-100 text-center " id="closedvediodesc2" style="color: red;">
            You can resend verification code in <span id="acbd"></span> seconds</h5>   
        <?php }?>
      </div>
      <div class="modal-footer">
        <?php if($coundown_res==0){ ?>
          <a href="reg_activatemobile.php?RegIdVal=<?php echo $regid_val.$returntocarturl;?>" class="btn btn-primary">Back</a>
          <input type="submit"  name ="resend" value="Ok" class="btn btn-success"/>
        <?php }else{?>
          <a href="reg_activatemobile.php?RegIdVal=<?php echo $regid_val.$returntocarturl;?>" class="btn btn-primary">Back</a>
          <input type="submit" id="btn_resendval" name ="resend" value="Ok" class="btn btn-success"   style="display: none;"/>
          <?php }?>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="MODUpdateMOB" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="background: linear-gradient(-45deg , rgba(143,1,82,1) 0%, rgba(34,22,119,1) 100%); border-radius: 20px;">
      <form action="reg_activatemobile.php?RegIdVal=<?php echo $regid_val.$returntocarturl;?>" method="post" enctype="multipart/form-data" class="form-horizontal">
      <div class="modal-header"> 
          <h5 class="modal-title text-light w-100 text-center " id="exampleModalLabel"> Update Mobile Number </h5>
      </div>
      <div class="modal-body" >
        <div class="row">
          <div class="alert alert-info">
            <strong>Mobile Number</strong>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="form-group m-1">
              <input type="text" name="txtmobile" class="form-control" placeholder="Mobile No. " value="<?php echo $mobileNo_val['telephone'];?>" required/>    
              <div id="divmobile">
              </div>        
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
          <a href="reg_activatemobile.php?RegIdVal=<?php echo $regid_val.$returntocarturl;?>" class="btn btn-primary">Back</a>
          <input type="submit"  name ="Update_mobileNo" id="Update_mobileNo" value="Update" class="btn btn-success"/>
      </div>
      </form>
    </div>
  </div>
</div>

 
<script> 
 var popupchk = '<?php echo $popupchk; ?>';
 if(popupchk==1){
      $("#nav_cat").css("display", "none");
      $("#subhead").css("display", "none");
      $("#div_main_acc").css("margin-top","50px");
    }else{
      $("#nav_cat").css("display", "block");
      $("#subhead").css("display", "block");
      $("#div_main_acc").css("margin-top","135px");
    }
$('input[name="txtmobile"]').change(function(e) {
	var mobStr = $(this).val();    
    if(mobStr.length!= 10 || !$.isNumeric(mobStr)) {
       jQuery("#divmobile").empty();
       jQuery("#divmobile").append('<div class="text-danger">Mobile No. must be 10 characters. (Ex. 9xxxxxxxxx)</div>');
       jQuery("#Update_mobileNo").prop('disabled', true);
    }else{
    	jQuery("#divmobile").empty();
    	jQuery("#Update_mobileNo").prop('disabled', false);
    }
});
    $(document).delegate('#btn_send_code', 'click', function() {
      jQuery("#btn_send_code").attr("disabled", true);
     document.getElementById("form_id").submit();
    });
    $(document).delegate('#btn_resend', 'click', function() {
      var mobileconfirm = new bootstrap.Modal(document.getElementById("mobileconfirm"), {});
      jQuery.noConflict();
      mobileconfirm.show(); 
      jQuery("#input-captcha").attr("disabled", true);  
      var minutes="<?php echo $coundown_res; ?>";
      var milisec=minutes*1000;
      if(minutes!=0){
        countdown(document.querySelector('#acbd'), minutes).then(
        function(elem) { console.log('done', elem) }
        )
     
        setInterval(function() {
         var exbtn = document.getElementById("btn_resendval");
         var closedvediodesc = document.getElementById("closedvediodesc2");
         var captchadiv = document.getElementById("captchadiv");
         exbtn.style.display = 'inline-block';
         captchadiv.style.display = 'block';
         closedvediodesc.style.display = 'none';
        jQuery("#input-captcha").attr("disabled", false);
        }, milisec); //5 seconds

      }else{
        jQuery("#input-captcha").attr("disabled", false);
      }
    });
    $(document).delegate('#btn_UpdateMOB', 'click', function() {
      jQuery("#btn_UpdateMOB").attr("disabled", true);
      jQuery('#MODUpdateMOB').modal('show');
    });
  

    function countdown(elem, s) {
    return new Promise(function(resolve) {
      function loop(s) {
        elem.innerHTML = s
        if (s === 0)
          resolve(elem)
        else
          setTimeout(loop, 1000, s - 1)
      }
      loop(s)
    })
  }
  </script>


 
<?php if($popupchk==0){include "common/footer.php";} ?>