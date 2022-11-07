<?php $FourGivescust = isset($_GET['FourGivescust']) ? $_GET['FourGivescust'] : 0; ?>
<?php $getPrd_id = isset($_GET['product_id']) ? $_GET['product_id'] : 0;
$_SESSION['getPrd_id'] = $getPrd_id; ?>


<style type="text/css">
  .modal {
    border-radius: 20px;
    overflow: hidden;
    float: left;
    height: 100%;

  }

  .gu-btn {
    background-color: #e67e22;
    color: #fff
  }

  .social-media-button {
    border-radius: 6px;
    margin-bottom: 10px;
    font-size: 12px;
  }

  .login-divider {
    align-items: center;
    justify-content: center;
    display: flex;
    color: #fff;
    font-size: 12px;
    padding: 10px 0;
  }

  .login-divider::after,
  .login-divider::before {
    display: inline-flex;
    width: 100%;
    height: 1px;
    content: "";
    background: #fff;
  }
</style>

<div class="modal fade" id="LoginModalNew" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen-md-down">
    <div class="modal-content" style="border-radius: 23px">
      <div class="modal-body" style="background: #4b6ed6; border-radius: 20px">

        <div class="text-center" style="color: white;">
          <h2>Welcome to PESO</h2>
        </div>
        <div class="text-center" style="color: white; margin-bottom: 10px;">
          <small>Know the latest from your favorite brands and stores</small>
        </div>
        <div class="row">
          <div class="col-lg-12 text-center">
            <button class="btn social-media-button gu-btn" id="ContinueGuest">Continue as guest</button>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12 text-center">
            <div class="login-divider">OR</div>
          </div>
        </div>
        <div class="text-center" style="color: white; margin-bottom: 10px;">
          <small>*Sign in to get ₱300 E-Wallet</small>
        </div>
        <div class="row">
          <div class="col-lg-6 text-center">
            <?php if (!isset($_SESSION['access_token'])) { ?>
              <a class="btn btn" style="box-shadow: 0 0.0625rem 0.125rem 0 rgba(0,0,0,.6);border-radius: 4px;background: #fff; color: black;font-size: 13px;" href="<?php echo $google_client->createAuthUrl(); ?>"><img src="assets/sign-in-with-google.png" style="height: 18px;" /> Google Sign Up</a>
            <?php } ?>
          </div>
          <div class="col-lg-6 text-center" style="margin-bottom: 10px;">
            <a class="btn btn-light" style="font-size: 13px;" href="register.php">Sign Up</a>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12 text-center" style="margin-bottom: 10px;">
            <div class="login-divider">OR</div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12 text-center">
            <a class="btn text-white py-1" data-bs-toggle="modal" data-bs-target="#ModalLandbank" style="font-size: 13px; background: #22b24c">
              <span class="d-flex justify-content-center align-items-center">
                <img src="./assets/landbankpay.png" alt="" style="width: 30px; height: 30px">
                <span class="ms-1"> Landbank Pay Sign Up</span>
              </span>
            </a>
          </div>
          <!-- <div class="col-lg-6 text-center">
                     <a class="btn btn-info" data-bs-toggle="modal" data-bs-target="#ModalLandbank"  style="font-size: 13px;" >Landbank Pay Sign Up</a>
                </div>   
                <div class="col-lg-6 text-center">
                     <a class="btn btn-info" data-bs-toggle="modal" data-bs-target="#ModalFourGivescust"  style="font-size: 13px;" >4Gives Sign Up</a>
                </div>   -->
        </div>
        <div class="row">
          <div class="col-lg-12 text-center" style="margin-bottom: 10px;">
            <div class="login-divider">OR</div>
          </div>
        </div>
        <div class="row">

          <div class="col-lg-12 text-center">
            <a class="btn btn-light" id="BtnLoginModalNew" style="font-size: 13px;"> Login</a>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>




<!-- Modal -->
<div class="modal fade" id="ModalLandbank" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="background: #4b6ed6; border-radius: 20px">
      <div class="modal-header">
        <h5 class="modal-title text-light w-100 text-center " id="exampleModalLabel">Register Using landbank Pay </h5>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="alert alert-info">
            <strong>Your Landbank Pay Details</strong>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="form-group ">
              <input type="text" id="lbpMobileNo" name="lbpMobileNo" placeholder="Mobile Number." class="full-text form-control " required />
              <div id="divmobile">
              </div>
            </div>

          </div>
        </div><br>
        <div class="row">
          <div class="alert alert-info">
            <strong>Your Password</strong>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="form-group m-1">
              <input type="password" id="lbpPassword" name="lbpPassword" placeholder="Password" class="full-text form-control " required />
              <div id="divpassword">
              </div>
            </div>

          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="form-group m-1">
              <input type="password" id="clbpPassword" name="clbpPassword" placeholder="Confirm Password" class="full-text form-control " required />
              <div id="divcpassword">
              </div>
            </div>

          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" data-bs-toggle="modal" data-bs-target="#LoginModalNew" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="lbpRegister" class="btn btn-primary">Register</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ModalFourGivescust" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="background: #4b6ed6; border-radius: 20px;">
      <div class="modal-header">
        <h5 class="modal-title text-light w-100 text-center " id="exampleModalLabel">Register Using 4Gives </h5>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="alert alert-info">
            <strong>Your 4Gives Details</strong>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="form-group ">
              <input type="text" id="fgivesMobileNo" name="fgivesMobileNo" placeholder="Mobile Number." class="full-text form-control " required />
              <div id="divmobilefgives">
              </div>
            </div>

          </div>
        </div><br>
        <div class="row">
          <div class="alert alert-info">
            <strong>Your Password</strong>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="form-group m-1">
              <input type="password" id="fgivesPassword" name="fgivesPassword" placeholder="Password" class="full-text form-control " required />
              <div id="divpasswordfgives">
              </div>
            </div>

          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="form-group m-1">
              <input type="password" id="cfgivesPassword" name="cfgivesPassword" placeholder="Confirm Password" class="full-text form-control " required />
              <div id="divcpasswordfgives">
              </div>
            </div>

          </div>
        </div>
      </div>
      <div class="modal-footer">
        <?php if ($FourGivescust == "0") { ?> <button type="button" data-bs-toggle="modal" data-bs-target="#LoginModalNew" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button> <?php } ?>
        <button type="button" id="FgivesRegister" class="btn btn-primary">Register</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  var myModalloginNew = new bootstrap.Modal(document.getElementById("LoginModalNew"), {});
  var myModalFourGivescust = new bootstrap.Modal(document.getElementById("ModalFourGivescust"), {});
  var myModallogin = new bootstrap.Modal(document.getElementById("LoginModal"), {});
  $(document).ready(function() {
    jQuery.noConflict();
    var FourGivescust = '<?php echo $FourGivescust; ?>';
    if (FourGivescust != '0') {
      myModalFourGivescust.show()
    } else {
      myModalloginNew.show();
    }
    // console.log(FourGivescust);

  });


  $("#ContinueGuest").click(function() {
    jQuery.noConflict();
    var username = 'guest' + Math.floor(100000 + Math.random() * 900000);
    var getPrd_id = "<?php echo $getPrd_id; ?>";
    if (getPrd_id == "0") {
      location.replace("ajax_guestLogin.php?loginAsGuest=" + username + "&t=" + new Date().getTime());
    } else {
      location.replace("ajax_guestLogin.php?loginAsGuest=" + username + "&product_id=" + getPrd_id + "&t=" + new Date().getTime());
    }

  });
  $("#lbpRegister").click(function() {
    var lbpMobileNo = document.getElementById('lbpMobileNo').value
    var lbpPassword = document.getElementById('lbpPassword').value
    jQuery.ajax({
      url: 'ajax_landbankreg.php?action=lbpRegister&t=' + new Date().getTime(),
      type: 'POST',
      data: 'lbpMobileNo=' + lbpMobileNo + '&lbpPassword=' + lbpPassword,
      dataType: 'json',
      success: function(json) {

        if (json['success']['status'] == 300) {
          bootbox.alert(json['success']['data']);
        } else {
          bootbox.alert("landbank Pay Successfully Registered", function() {
            location.replace("lanbankLogin.php?lbpCustid=" + json['success']['data'] + "&t=" + new Date().getTime());
          });
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  });
  $("#FgivesRegister").click(function() {
    var fgivesMobileNo = document.getElementById('fgivesMobileNo').value
    var fgivesPassword = document.getElementById('fgivesPassword').value
    var cfgivesPassword = document.getElementById('cfgivesPassword').value
    if (fgivesMobileNo == "") {
      bootbox.alert("Mobile number is required");
      return false;
    }
    if (fgivesPassword == "" || cfgivesPassword == "") {
      bootbox.alert("Password is required");
      return false;
    }
    jQuery.ajax({
      url: 'ajax_landbankreg.php?action=fgivesRegister&t=' + new Date().getTime(),
      type: 'POST',
      data: 'fgivesMobileNo=' + fgivesMobileNo + '&fgivesPassword=' + fgivesPassword,
      dataType: 'json',
      success: function(json) {
        console.log(json);
        if (json['success']['status'] == 300) {
          bootbox.alert(json['success']['data']);
        } else {
          bootbox.alert("4Gives Customer Successfully Registered", function() {
            location.replace("lanbankLogin.php?fgivesCustid=" + json['success']['data'] + "&t=" + new Date().getTime());
          });
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  });
  $('input[name="lbpMobileNo"]').change(function(e) {
    var mobStr = document.getElementById('lbpMobileNo').value
    if (mobStr.length != 10 || !jQuery.isNumeric(mobStr)) {
      document.getElementById('divmobile').innerHTML = "";
      document.getElementById('divmobile').innerHTML = '<div class="text-danger  bg-light form-control">Mobile No. must be 10 characters. (Ex. 9xxxxxxxxx)</div>';
      document.getElementById('lbpRegister').disabled = true;
    } else {
      document.getElementById('divmobile').innerHTML = "";
      document.getElementById('lbpRegister').disabled = false;
    }
  });
  $('input[name="fgivesMobileNo"]').change(function(e) {
    var mobStr = document.getElementById('fgivesMobileNo').value
    if (mobStr.length != 10 || !jQuery.isNumeric(mobStr)) {
      document.getElementById('divmobilefgives').innerHTML = "";
      document.getElementById('divmobilefgives').innerHTML = '<div class="text-danger  bg-light form-control">Mobile No. must be 10 characters. (Ex. 9xxxxxxxxx)</div>';
      document.getElementById('FgivesRegister').disabled = true;
    } else {
      document.getElementById('divmobilefgives').innerHTML = "";
      document.getElementById('FgivesRegister').disabled = false;
    }
  });
  $('input[name="fgivesPassword"]').change(function(e) {
    var passStr = document.getElementById('fgivesPassword').value
    if (passStr.length < 6) {
      document.getElementById('divpasswordfgives').innerHTML = "";
      document.getElementById('divpasswordfgives').innerHTML = '<div class="text-danger bg-light form-control">Password must be 6 characters or more ! </div>';
      document.getElementById('FgivesRegister').disabled = true;
    } else {
      document.getElementById('divpasswordfgives').innerHTML = "";
      document.getElementById('FgivesRegister').disabled = false;
    }
  });
  $('input[name="lbpPassword"]').change(function(e) {
    var passStr = document.getElementById('lbpPassword').value
    if (passStr.length < 6) {
      document.getElementById('divpassword').innerHTML = "";
      document.getElementById('divpassword').innerHTML = '<div class="text-danger bg-light form-control">Password must be 6 characters or more ! </div>';
      document.getElementById('lbpRegister').disabled = true;
    } else {
      document.getElementById('divpassword').innerHTML = "";
      document.getElementById('lbpRegister').disabled = false;
    }
  });
  $('input[name="cfgivesPassword"]').change(function(e) {
    var CuserStr = document.getElementById('cfgivesPassword').value
    var passStr = document.getElementById('fgivesPassword').value
    if (CuserStr != passStr) {
      document.getElementById('divcpasswordfgives').innerHTML = "";
      document.getElementById('divcpasswordfgives').innerHTML = '<div class="text-danger bg-light form-control">Password Unmatched ! </div>';
      document.getElementById('FgivesRegister').disabled = true;
    } else {
      document.getElementById('divcpasswordfgives').innerHTML = "";
      document.getElementById('FgivesRegister').disabled = false;
    }
  });

  $('input[name="clbpPassword"]').change(function(e) {
    var CuserStr = document.getElementById('clbpPassword').value
    var passStr = document.getElementById('lbpPassword').value
    if (CuserStr != passStr) {
      document.getElementById('divcpassword').innerHTML = "";
      document.getElementById('divcpassword').innerHTML = '<div class="text-danger bg-light form-control">Password Unmatched ! </div>';
      document.getElementById('lbpRegister').disabled = true;
    } else {
      document.getElementById('divcpassword').innerHTML = "";
      document.getElementById('lbpRegister').disabled = false;
    }
  });
  $("#BtnLoginModalNew").click(function() {
    myModalloginNew.hide();
    myModallogin.show()
  });

  function loginAsGuest(username) {
    $.ajax({
      url: 'ajax_loginNew.php?action=login&t=' + new Date().getTime(),
      type: 'POST',
      data: 'username=' + username + '&password=' + username,
      dataType: 'json',
      success: function(json) {
        if (json['success'] == "Successfully Login...") {
          bootbox.alert("Thank You, Welcome to PESO", function() {
            window.location.reload();
          });
        } else {
          bootbox.alert(json['success']);
          return false;
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });

  }
</script>