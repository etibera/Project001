

<?php
include 'template/header.php';
require_once "model/AdminSendEmailBlast.php";
$model=new SendEmailBlast;
?>
<style type="text/css">
#editor-container {
  height: 40%;
}
#editor {
  height: 100%;
}
</style>
<div id="content">
    <div class="container-fluid">
    	<div class="panel panel-default">
    		<div class="panel-heading" style="padding:20px;">
    			<div class="row">
          			<div class="col-lg-6">
          				 <p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i> Email Blast</p>
          			</div>
          			<div class="col-lg-6">
          				 <a class="btn btn-info pull-right" id="send_sms" title="Send SMS" style="margin-left:5px;" onclick="semdEmailBlast()"><i class="fas fa-paper-plane"></i> Send Email Blast</a>
          			</div>
          		</div>
    		</div>
    		<div class="panel-body">  
	    		 <div class="col-lg-12" style="margin-bottom:10px">
	              <label>Subject: </label>
	              <input type="text" class="form-control" id="subject" placeholder="Input Subject">
	            </div>
	            <div class="col-lg-12" style="margin-bottom:10px">
	             <label>Message: </label>
	                <div id="summernote" ></div>
	            </div>
          <div>
    		</div>
    	</div>
    </div>

</div>
 <script>
    $('#summernote').summernote({
      placeholder: 'Message',
      tabsize: 2,
      height: 200,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
    });
  </script>
<script type="text/javascript">
function semdEmailBlast() {   
   	var message = $('#summernote').summernote('code');
   	//console.log(message);
    var subject = $('#subject').val();
   	 if(subject == ''){
        bootbox.alert('Enter Subject...');
        return false;
      } if(message == ''){
        bootbox.alert('Enter Message...');
        return false;
      }
   	$.ajax({
            url: 'ajax_sms_admin.php?action=sendEmailBlast&t=' + new Date().getTime(),
            type: 'post',
            data: {
                message: message,
                subject: subject,
            },
            dataType: 'json',
            beforeSend: function() {
                bootbox.dialog({
                      title: "Sending Email Blast",
                      message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
              });
           },
            success: function(json) {
                bootbox.alert(json['success'], function(){ 
                    window.location.reload();
                }); 
            }
        });
}
</script>