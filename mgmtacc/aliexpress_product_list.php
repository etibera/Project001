<?php 
include 'template/header.php';
include "model/Banner.php";
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
<div class="row">
    <div class="container">
        <div class="panel panel-default">
        <div class="panel-heading text-center">
        <h4>AliExpress Products</h4>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
            <table class="table table-bordered" id="ae-products">
                <thead>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Action</th>
                <thead>
                <tbody>
                </tbody>
            </table>
            </div>
        
        </div>
        <div class="panel-footer">Panel Footer</div>
        </div>
    </div>
</div>
<script>
$( document ).ready(function() {
loadData();
});
function loadData(){
    
    $.get({url: "./api/aliexpress.php?action=productLists", success: function(result){
        $('table#ae-products tbody tr').remove();
        var product = JSON.parse(result);
        for(var i = 0; i < product.length; i++ ){
                    var checked  = product[i].status == 1 ? 'checked' : ''
                    $('table#ae-products tbody').append("<tr>"+
                    "<td>" + product[i].product_id + "</td>"+
                    "<td><img width='100' src=" + product[i].image + "/></td>"+
                    "<td>" + product[i].name +"</td>"+
                    "<td><input class='form-control' oninput='setQuantity("+product[i].id+", event)' style='width: 80px'type='number' value='"+product[i].quantity+"'/></td>"+
                    "<td><label class='switch'>" +
                        "<input type='checkbox' "+checked+" onchange='setStatus("+ product[i].id +","+i+", event)'>" +
                        "<span class='slider round'></span>"+
                        "</label> <span id='s-label-"+i+"'>"+ (checked ? 'Enabled' : 'Disabled')+"</span></td>"+
                    "<td><button class='btn btn-danger' onclick='deleteProduct("+product[i].id+")'><i class='fa fa-trash'></i></button></td>"+
                    "</tr>");
                }
    }});
}
function setQuantity(id, event){
    var value = '';
    var timer = setTimeout(() => {
        if(value == event.target.value){
            $.post({url: "./api/aliexpress.php?action=setQuantity", 
                data: {id: id, quantity: event.target.value},
                success: function(result){

                }
            });
        }
    }, 500);
    value = event.target.value;
}
function setStatus(id, index, event){
    setTimeout(() => {
        var checked = event.target.checked ? 'Enabled' : 'Disabled'
        $('#s-label-'+ index).text(checked)
        $.post({url: "./api/aliexpress.php?action=setStatus", 
                data: {id: id, status: (event.target.checked ? 1 : 0)},
                success: function(result){

                }
        });
    }, 500);
}
function deleteProduct(id){
    if (confirm('Are you sure you want to delete this product?')) {
        $.post({url: "./api/aliexpress.php?action=deleteProductList", 
                data: { id: id },
                success: function(result){
                   
                    loadData();
                }
        });
    
    }
}
</script>

<?php include 'template/footer.php';?>      
