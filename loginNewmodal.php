<style type="text/css">
    .modal {
        
        border-radius: 20px;
        overflow: hidden;
        float: left;
        height: 100%;
        
    }
    
    .gu-btn{
        background-color: #e67e22;
        color: #fff
    }
    .social-media-button {
        border-radius: 6px;
        margin-bottom: 10px;
        font-size: 12px;    
    }
</style>

<div id="LoginModalNew" class="modal fade" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow: hidden;">
  <div class="modal-dialog modal-sm" style="    height: 100%;
    margin-top: 120px;">
    <div class="modal-content" style="border-radius: 23px">
      <div class="modal-body" style="background: linear-gradient(#031a3d, #3f1c59, #7f005b, #b60040, #d30000); border-radius: 20px;">
       
            <div class="text-center" style="color: white;">
                <h2>Welcome to PESO</h2>
            </div>
            <div class="text-center" style="color: white; margin-bottom: 10px;">
                <small >get to know the latest from your favorite brand and store</small>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <button  class="btn social-media-button gu-btn" id="ContinueGuest">Continue as guest</button>
                </div>
            </div>
             <div class="row">
                <div class="col-lg-12 text-center">
                   <img src="https://mb.pesoapp.ph/assets/on-boarding/ob-divider.png" style="width: 80%">
                </div>
            </div>
             <div class="text-center" style="color: white; margin-bottom: 10px;">
                <small >Sign in to get ₱300  wallet</small>
            </div>
             <div class="row">
                <div class="col-lg-6 text-center">
                    <?php if(!isset($_SESSION['access_token'])){ ?>
                      <a class="btn btn" style="box-shadow: 0 0.0625rem 0.125rem 0 rgba(0,0,0,.6);border-radius: 4px;background: #fff; color: black;font-size: 13px;" href="<?php echo $google_client->createAuthUrl();?>"><img src="assets/sign-in-with-google.png"  style="height: 18px;" /> Google Sign Up</a>
                     <?php } ?>
                </div>
                <div class="col-lg-6 text-center" style="margin-bottom: 10px;">
                     <a class="btn btn-info"   style="font-size: 13px;"  href="register.php"> Manual Sign Up</a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center" style="margin-bottom: 10px;">
                   <img src="https://mb.pesoapp.ph/assets/on-boarding/ob-divider.png" style="width: 80%">
                </div>
            </div>
             <div class="row">
                <div class="col-lg-12 text-center">
                     <a class="btn btn-primary" id="BtnLoginModalNew"  style="font-size: 13px;" > Login</a>
                </div>
            </div>
      
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {      
      jQuery.noConflict();
        $('#LoginModalNew').modal('show');
        $( "#BtnLoginModalNew" ).click(function() {    
              $('#LoginModalNew').modal('hide');
              $('#LoginModal').modal('show');
        }); 
        $("#BtnLoginModalNew").click(function() {    
              $('#LoginModalNew').modal('hide');
              $('#LoginModal').modal('show');
        });
        $( "#ContinueGuest" ).click(function() {
            jQuery.noConflict(); 
            var username='guest' + Math.floor(100000 + Math.random() * 900000);
            location.replace("ajax_guestLogin.php?loginAsGuest="+username+"&t="+ new Date().getTime());
            /*$.ajax({
              url: 'ajax_guestLogin.php?action=loginAsGuest&t=' + new Date().getTime(),
              type: 'POST',
              data: 'username=' + username  +'&password=' + username,
              dataType: 'json',
              success: function(json) {
                if (json['success']) {                
                   loginAsGuest(json['success']);
                }else{
                  bootbox.alert(json['success']);
                  return false;
                }
              },
              error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
              }
            });*/
        });
    });
  function loginAsGuest(username) {
    $.ajax({
      url: 'ajax_loginNew.php?action=login&t=' + new Date().getTime(),
      type: 'POST',
      data: 'username=' + username  +'&password=' + username,
      dataType: 'json',
      success: function(json) {
        if (json['success']=="Successfully Login...") {
           bootbox.alert("Thank You, Welcome to PESO", function(){ 
           window.location.reload();
          });
        }else{
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
