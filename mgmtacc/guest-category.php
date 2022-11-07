<?php
 include 'template/header.php'; ?>
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
      <h2 class="text-center">Guest Category</h2>
    </div>
    <div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="row">
        <div class="col-lg-12">
            <button class="btn btn-primary pull-right btn-add" title="Add"><i data-feather="plus-square"></i></button>
        </div>
      </div>
    </div>
    <div class="panel-content">
    <table class="table" id="ads-table">
      <thead>
          <th>Image</th>
          <th>Name</th>
        <th>Status</th>
        <th>Action</th>
      </thead>
      <tbody>
      </tbody>
    </table>
    </div>
  </div>
</div>
<div  class="modal" id="lp_opnen_mdl" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-error-dialog modal-sm" >
        <!-- <form role="form"> -->

        <div class="modal-content" >
        <div class="modal-header">
          Add Category <button type="button" class="close" onclick="clearing()">&times;</button>
        </div>
            <div class="panel-body">
                  <form id="a-submit" action="./api/guest-category.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="ads_id" value="0" name="id"/>
                    <div class="form-group">
                        <img src="../fonts/feathericons/icons/image.svg"  class="img-responsive img_banner_lp" style="margin:auto;width:50px;height:50px" />
                        <input required type="file" name="image_file" id="fileToUpload" class="form-control" onchange="readURL(this);"/>
                      </div>
                      <div class="form-group">
                        <label>Name</label>
                        <input class="form-control" required name="name"/>
                      </div>
                      <button class="btn btn-primary btn-submit" type="submit">Add</button>
                  </form>
                  </div>
             </div>
            </div><!--modal-content-->
        </div><!-- modal-dialog modal-error-dialog-->
</div>
<script>
    $(document).ready(function() {
        getAll();
        $('.btn-add').click(function () {
            $('#lp_opnen_mdl').modal('show');
           
        });
    });
    function clearing(){
    $('#lp_opnen_mdl').modal('hide');
    $('.img_banner_lp').attr('src', '../fonts/feathericons/icons/image.svg');
    $('form#a-submit input[name="image_file"]').val(null)
    $('form#a-submit input[name="name"]').val(null)
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
    $("form#a-submit").submit(function(e) {
        e.preventDefault();    
            var formData = new FormData(this);
            $.ajax({
                url: $(this).attr('action') + '?action=add&t=' + new Date().getTime(),
                type: 'POST',
                data: formData,
                success: function (data) {
                clearing();
                //   var data = JSON.parse(data)
                getAll();
                },
                cache: false,
                contentType: false,
                processData: false
            });
});

function getAll(){
  $.ajax({
       type: "GET",
       url: './api/guest-category.php?action=all&t=' + new Date().getTime(),
       success: function(data)
       {
        $('table#ads-table tbody tr').remove();
        var data = JSON.parse(data)
        for(var i = 0; i < data.length; i++){
          var checked  = data[i].status == 1 ? 'checked' : ''
          $('table#ads-table tbody').prepend('<tr>'+
           '<td> <img width="100" height="100" src="' + data[i].image +'"/></td>' +
           '<td> ' + data[i].name +'</td>' +
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
        $.post({url: "./api/guest-category.php?action=setStatus", 
                data: {id: id, status: (event.target.checked ? 1 : 0)},
                success: function(result){

                }
        });
    }, 500);
}
function onDelete(id){
  if (confirm('Are you sure you want to delete this advertisement?')) {
        $.post({url: "./api/guest-category.php?action=delete", 
                data: { id: id },
                success: function(result){
                  var result = JSON.parse(result);
                  if(result.error == 1){
                    alert(result.message)
                  }else{
                    getAll();
                  }
                }
        });
    
    }
}
function onEdit(id){
  $('#ads-table').on('click', '.btn-edit', function () {
    $('#ads_id').val(id);
    var currentRow=$(this).closest("tr");
    $('.img_banner_lp').attr('src', currentRow.find("td:eq(0) img").attr('src')); 
    $('input[name="name"]').val(currentRow.find("td:eq(1)").text().trim())
    $('#lp_opnen_mdl').modal('show');
    $('input[name="image_file"]').prop("required", false);
    $('button.btn-submit').text('Update');
  })
}
</script>
<?php include 'template/footer.php';?>