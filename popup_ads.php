<div id="myModal" class="modal fade" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style="background-color: transparent;border:none; box-shadow: none; margin-top: 30%">
      <div class="modal-body" style="text-align:center">
        <div style="position: absolute;
    right: 21%;
    top: 0;">
    <a type="button"  class="float-end"    data-bs-dismiss="modal" style="float: right;"><i class="fa fa-times-circle " style="color: #FFF;font-size: 25px;" ></i></a>            
        </div>
        <a id="popup-link" href="#">
        <img id="popup-img" style="width: 300px; height: 300px"/>
        </a>
      </div>
    </div>
  </div>
</div>
<script>
    $(document).ready(function() {
         var myModalpopup = new bootstrap.Modal(document.getElementById("myModal"), {});     
        jQuery.ajax({
          url: './api/5.0.20/url/home.php?action=popup_ads&t=' + new Date().getTime(),
            type: 'POST',
            dataType: 'json',         
          success: function (json) {
            //console.log(json);
            if(json.image){
                myModalpopup.show();
                document.getElementById('popup-img').src=json.image;
                document.getElementById('popup-link').href=json.webUrl;
            }
         }
        })
    })
</script>