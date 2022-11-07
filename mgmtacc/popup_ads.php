<?php
include 'template/header.php';
?>
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 55px;
  height: 25px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 20px;
  width: 20px;
  left: 4px;
  bottom: 3px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #3bc157;
}

input:focus + .slider {
  box-shadow: 0 0 1px #3bc157;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
	<div class="page-header">
      <h2 class="text-center">Popup Ads</h2>
    </div>
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="row">
          			<div class="col-lg-12">
          				<button class="btn btn-primary pull-right btn-add" title="Add"><i data-feather="plus-square"></i></button> <!-- 
          				 <a class="btn btn-primary pull-right" id="add-lp" class="btn btn-primary"><i class="fa fa-plus"></i></button>               -->
          			</div>
      </div>
    </div>
    <div class="panel-content">
    <table class="table" id="ads-table">
      <thead>
        <th>Image</th>
        <th>Web URL</th>
        <th>Webmobile URL</th>
        <th>Mobile URL</th>
        <th>Position</th>
        <th>Status</th>
        <th>Actions</th>
      </thead>
      <tbody>
      </tbody>
    </table>
    </div>
  </div>
</div>
<div  class="modal" id="lp_opnen_mdl" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-error-dialog" >
        <!-- <form role="form"> -->

        <div class="modal-content" >
        <div class="modal-header">
          Add Advertisement <button type="button" class="close" onclick="clearing()">&times;</button>
        </div>
            <div class="panel-body">
                  <form id="a-submit" action="./api/popup_ads.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="ads_id" value="0" name="id"/>
                    <div class="form-group">
                        <img src="../fonts/feathericons/icons/image.svg"  class="img-responsive img_banner_lp" style="margin:auto;width:150px;height:150px" />
                        <small style="color: #888">Please upload a size of 300x300 pixels</small>
                        <input required type="file" name="image_file" id="fileToUpload" class="form-control" onchange="readURL(this);"/>
                      </div>
                      <div class="form-group">
                        <label>Web Redirect URL <small style="color: #888">(example: https://pesoapp.ph/home.php)</small></label>
                        <input class="form-control" required name="web_url"/>
                      </div>
                      <div class="form-group">
                        <label>Web Mobile Redirect URL <small style="color: #888">(example: https://mb.pesoapp.ph/tabs/home)</small></label>
                        <input class="form-control" required name="webmobile_url"/>
                      </div>
                      <div class="form-group">
                        <label>Mobile Redirect URL <small style="color: #888">(example: /tabs/home)</small></label>
                        <input class="form-control" required name="mobile_url"/>
                      </div>
                      <div class="form-group">
                        <label>Position</label>
                        <div class="row container">
                        <input style="margin: 10px" type="radio" name="position" value="top"/> <label>Top</label>
                        <input style="margin: 10px" type="radio" name="position" value="left"/> <label>Left</label>
                        <input style="margin: 10px" type="radio" name="position" value="right"/> <label>Right</label>
                        <input style="margin: 10px" type="radio" name="position" value="center"/> <label>Center</label>
                        <input style="margin: 10px" type="radio" name="position" value="bottom"/> <label>Bottom</label>
                        </div>
                        <div class="row container">
                        <input style="margin: 10px" type="radio" name="position" value="bottom-left"/> <label>Bottom Left</label>
                        <input style="margin: 10px" type="radio" name="position" value="bottom-right"/> <label>Bottom Right</label>
                        <input style="margin: 10px" type="radio" name="position" value="top-left"/> <label>Top Left</label>
                        <input style="margin: 10px" type="radio" name="position" value="top-right"/> <label>Top Right</label>
                        </div>
                      </div>
                      <button class="btn btn-primary btn-submit" type="submit">Add</button>
                  </form>
                  </div>
             </div>
            </div><!--modal-content-->
        </div><!-- modal-dialog modal-error-dialog-->
</div><!-- open_category_module_modal-->
<script>
$(document).ready(function() {
  getAll();
  $('.btn-add').click(function () {
    $('#lp_opnen_mdl').modal('show');
    $('input[name="image_file"]').prop("required", true);
    $('button.btn-submit').text('Add');
     $('form#a-submit input[name="position"]').each(function(){
      $(this).prop("checked", false)
  });
  });


$("form#a-submit").submit(function(e) {
  e.preventDefault();    
    var formData = new FormData(this);
    $.ajax({
        url: $(this).attr('action') + '?action=add_popup&t=' + new Date().getTime(),
        type: 'POST',
        data: formData,
        success: function (data) {
          clearing();
          var data = JSON.parse(data)
          getAll();
          //  $('table#ads-table tbody').append('<tr>'+
          //  '<td> <img width="100" height="100" src="' + data.image +'"/></td>' +
          //  '<td> ' + data.web_url + '</td>' +
          //  '<td> ' + data.webmobile_url +'</td>' +
          //  '<td> ' + data.mobile_url + '</td>' +
          //  '<td><label class="switch">' +
          //               "<input type='checkbox' checked >" +
          //               "<span class='slider round'></span>"+
          //               "</label></td>" + 
          //  '<td>'+
          //  '<button class="btn btn-info"><i class="fa fa-edit"></i></button>'+
          //  '<button class="btn btn-danger" onclick="onDelete('+ data.id +')"><i class="fa fa-trash"></i></button>'+
          //  '</td>' +
          //  +'</tr>');
        },
        cache: false,
        contentType: false,
        processData: false
    });
  });
});
function clearing(){
  $('#lp_opnen_mdl').modal('hide');
  $('.img_banner_lp').attr('src', '../fonts/feathericons/icons/image.svg');
  $('form#a-submit input[name="image_file"]').val(null)
  $('form#a-submit input[name="web_url"]').val(null)
  $('form#a-submit input[name="webmobile_url"]').val(null)
  $('form#a-submit input[name="mobile_url"]').val(null)
  // $('form#a-submit input[name="position"]').each(function(){
  //     $(this).attr("checked", false)
  // });
  //$('form#a-submit input[name="position"]').attr('checked', false);
}
function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.img_banner_lp')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
}
function getAll(){
  $.ajax({
       type: "GET",
       url: './api/popup_ads.php?action=popup_ads&t=' + new Date().getTime(),
       success: function(data)
       {
        $('table#ads-table tbody tr').remove();
        var data = JSON.parse(data)
        for(var i = 0; i < data.length; i++){
          var checked  = data[i].status == 1 ? 'checked' : ''
          $('table#ads-table tbody').prepend('<tr>'+
           '<td> <img width="100" height="100" src="' + data[i].image +'"/></td>' +
           '<td> ' + data[i].web_url +'</td>' +
           '<td> ' + data[i].webmobile_url +'</td>' +
           '<td> ' + data[i].mobile_url + '</td>' +
           '<td> ' + data[i].position + '</td>' +
           '<td><label class="switch">' +
                        "<input type='checkbox' " +checked+ " onchange='setStatus("+ data[i].id +","+i+", event)' >" +
                        "<span class='slider round'></span>" +
                        "</label><br><span id='s-label-"+i+"'>"+ (checked ? 'Enabled' : 'Disabled')+"</span></td>" +
                        '<td>'+
           '<button class="btn btn-info btn-edit" onclick="onEdit('+data[i].id+')"><i class="fa fa-edit"></i></button>'+
           '<button class="btn btn-danger" onclick="onDelete('+ data[i].id +')"><i class="fa fa-trash"></i></button>'+
           '</td>' +
           +'</tr>');
        }
       }
     });
}
function setStatus(id, index, event){
    setTimeout(() => {
        var checked = event.target.checked ? 'Enabled' : 'Disabled'
        $('#s-label-'+ index).text(checked)
        $.post({url: "./api/popup_ads.php?action=setStatus", 
                data: {id: id, status: (event.target.checked ? 1 : 0)},
                success: function(result){

                }
        });
    }, 500);
}
function onDelete(id){
  if (confirm('Are you sure you want to delete this advertisement?')) {
        $.post({url: "./api/popup_ads.php?action=delete", 
                data: { id: id },
                success: function(result){
                  getAll();
                }
        });
    
    }
}
function onEdit(id){
  $('#ads-table').on('click', '.btn-edit', function () {
    $('#ads_id').val(id);
    var currentRow=$(this).closest("tr");
    $('.img_banner_lp').attr('src', currentRow.find("td:eq(0) img").attr('src')); 
    $('input[name="web_url"]').val(currentRow.find("td:eq(1)").text().trim())
    $('input[name="webmobile_url"]').val(currentRow.find("td:eq(2)").text().trim())
    $('input[name="mobile_url"]').val(currentRow.find("td:eq(3)").text().trim())
    //$('input[name="position"]').val(currentRow.find("td:eq(4)").text().trim())
    $('#lp_opnen_mdl').modal('show');
    $('input[name="image_file"]').prop("required", false);
    $('button.btn-submit').text('Update');
    $('form#a-submit input[name="position"]').each(function(){
      $(this).prop("checked", false)
    });

    $('input[name="position"]').each(function(){
      console.log($(this).val(), currentRow.find("td:eq(4)").text().trim())
        if($(this).val() == currentRow.find("td:eq(4)").text().trim()){
            $(this).prop("checked", "checked");    
        }
    });

    // $('input[name="position"]').each(function(){
    //    console.log($('input[name="position"]').val())
      //  if(currentRow.find("td:eq(4)").text().trim() == $('input[name="position"]').val()){
      //   $('input[name="position"]').attr('checked', 'checked');
      //  }
    // })
    //var data=col1+"\n"+col2+"\n"+col3;
    //alert(data);
  })
}
</script>

<?php
include "template/footer.php";
?>