<?php 
include 'template/header.php';
include "model/Banner.php";
?>
<div class="row">
    <div class="container">
        <div class="panel panel-default">
        <div class="panel-heading">
        <div class="row">
            <div class="col-md-3">
                <select class="form-control" id="p-cat">
                    <option value=''>Select Category</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-control" id='c-cat' disabled>
                    <option value=''>Select option</option>
                </select>
            </div>
            <div class="col-md-3" id="load-icon" style="display:none">
              <i class="fa fa-sync fa-spin"></i>
            </div>

            <div class="col-md-3" id="download-btn" style="display:none">
              <button class="btn btn-warning" type="button"></button>
            </div>
        </div>
        
        </div>
        <div class="panel-body">
            <p>Total: <b><span class="total-product">0</span></b></p>
            <table class="table table-bordered" id="ae-products">
                <thead>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Action</th>
                <thead>
                <tbody>
                </tbody>
            </table>
            <ul class="pagination pull-right">
                <!-- <li class="disabled"><a href="#" ><i class="fa fa-arrow-left"></i></a></li> -->
                <!-- <li><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
                <li><a href="">...</a></li>
                <li><a href="#">100</a></li> -->
                <!-- <li class="disabled"><a href="#" ><i class="fa fa-arrow-right"></i></a></li> -->
            </ul>
        </div>
        <div class="panel-footer">

            </div>
        </div>
    </div>
</div>
<script>
    var currentPage = 1;
    var rowCountPage = 5;
    var totalRow = 0;
    var pageRange= 2;
    var category_id = '';

    var countInserted = 0;
    var totalProduct = 0;
$( document ).ready(function() {
    $.get({url: "./api/aliexpress.php?action=parent_category", success: function(result){
        var data = JSON.parse(result);
        for(var i = 0; i < data.length; i++){
            $('#p-cat').append($('<option>', {value:data[i].category_id, text:data[i].category_name}));
        }
        
    }});
    $('#p-cat').on('change', function() {
        $('#load-icon').show();
        if(this.value){
            $.get({url: "./api/aliexpress.php?action=child_category", 
            data: {category_id: this.value},
            success: function(result){
            $('#load-icon').hide();
            $('.cat-op').remove();
            $('#c-cat').removeAttr('disabled');
            var data = JSON.parse(result);
            for(var i = 0; i < data.length; i++){
                $('#c-cat').append($('<option>', {class:'cat-op',value:data[i].category_id, text:data[i].category_name}));
            }
            }});
        }else{
            $('#c-cat').attr("disabled", true);
            $('.cat-op').remove();
            $('#load-icon').hide();
        }
    });

    $('#c-cat').on('change', function() {
        if(this.value){
            currentPage = 1
            category_id = this.value;
            getData();
        }
    });
    $('#download-btn').on('click', function(e) {
        var rowCount = $('#ae-products tbody tr').length -1;
        $('#download-btn button').prop('disabled',true);
        var counter = 0;
        $('table#ae-products tbody tr').each(function() {
            var td = $(this).find('td')
            var id = td.eq(0).text();
            var button = td.eq(3).children();
            if(button[0].classList[1] === 'btn-warning'){
                downloadProduct(id, counter)
            }
            counter++;
        });
    })
});
function getData(){
    $('#download-btn').hide();
    $('table#ae-products tbody tr').remove();
        $('table#ae-products tbody').append("<tr style='height: 585px'>"+
                    "<td colspan='4'><center><h1><i class='fa fa-sync fa-spin fa-lg'></i></h1></center></td>"+
                    "</tr>");
        $('.total-product').text(0);
        $('#c-cat').attr("disabled", true);
        $('#p-cat').attr("disabled", true);
        
            $.get({url: "./api/aliexpress.php?action=products", 
            data: {category_id: category_id, page_number: currentPage},
            success: function(result){
            $('#c-cat').removeAttr('disabled');
            $('#p-cat').removeAttr('disabled');
            var data = JSON.parse(result);
            $('table#ae-products tbody tr').remove();
            if(data.code == '405'){
                $('table#ae-products tbody').append("<tr style='height: 585px'>"+
                    "<td colspan='4'><center><h1>"+data.msg+"</h1></center></td>"+
                    "</tr>");
            }else{
                totalProduct = data.total_product
                $('#download-btn').show();
                var product = data.products;
                if(data.total_product == 0){
                    $("#download-btn button").attr('disabled', true).attr('class', 'btn btn-success' ).text('All products are downloaded');
                }else{
                    $("#download-btn button").attr('disabled', false).attr('class', 'btn btn-warning' ).text("Download "+data.total_product+" Products");
                }
                for(var i = 0; i < product.length; i++ ){
                    var button = product[i].exist ? 
                    '<button class="btn btn-success" disabled ><i class="fa fa-check"></i></button>' : 
                    "<button class='btn btn-warning' onclick='downloadProduct("+product[i].product_id+", "+i+")'><i class='fa fa-download'></i></button>";
                    $('table#ae-products tbody').append("<tr style='height: 100px'>"+
                    "<td>" + product[i].product_id + "</td>"+
                    "<td><img width='100' src=" + product[i].img + "/></td>"+
                    "<td>" + product[i].name +"</td>"+
                    "<td>"+button+"</td>"+
                    "</tr>");
                }
                $('.total-product').text(data.total);
                totalRow = data.total
                createPagination();
            }
            

            // console.log(data);
            }});
}
function createPagination(){
    var firstEllipsis = firstPage() ? '<li><a href="javascript:void(0)">...</a></li>' : '';
    var lastEllipsis = lastPage() ? '<li><a href="javascript:void(0)">...</a></li>' : '';
    var firstPages = firstPage() ? '<li><a href="javascript:void(0)" onclick="updatePages(1)">1</a></li>' : '';
    var lastPages = lastPage() ? '<li><a href="javascript:void(0)" onclick="updatePages('+(totalPages()-1)+')">'+ (totalPages()-1) +'</a></li>' : '';
    var showPrevBtn = showPrevLink() ? '<li><a href="javascript:void(0)" onclick="updatePages(prev())" ><i class="fa fa-arrow-left"></i></a></li>': ''
    var showNextBtn = showNextLink() ? '<li><a href="javascript:void(0)" onclick="updatePages(next())" ><i class="fa fa-arrow-right"></i></a></li>': ''
    $('.pagination li').remove();
    $('.pagination').append(
            showPrevBtn +
            firstPages + 
            firstEllipsis +
            pageList() +
            lastEllipsis +
            lastPages + 
            showNextBtn
        );

        // console.log(rangeStart(), rangeEnd())
}
var pageList = () => {
    var p = pages();
    var pagesList = '';
    for(var i = 1; i < p.length; i++){
        var active = currentPage == p[i] ? ' active' : '';
        var num = p[i];
        pagesList += '<li class="page-num'+active+'"><a href="javascript:void(0)" onclick="updatePages('+num+')">'+num+'</a></li>';
    }
    return pagesList;
}
var updatePages = (cp) => {
    currentPage = cp;
    createPagination();
    setTimeout(() => {
        if(currentPage == cp){
            getData()
        }
    }, 500);
    
   
}
var firstPage = () => {
    return rangeStart() !== 0;
}
var lastPage = () => {
    return rangeEnd() < totalPages()
}
var prev = () => {
    return currentPage -= 1;
   
}
var next = () => {
    return currentPage += 1;
}
var rangeStart = () => {
    var start = currentPage - pageRange;
    return (start > 0 ) ? start : 0;
}
var rangeEnd = () => {
    var end = currentPage + pageRange
    return (end < totalPages()) ? end : totalPages()
}
var totalPages = () => {
    return Math.ceil(totalRow / rowCountPage);
}
var pages = () => {
    var pages = [];
    for(var i = rangeStart(); i < rangeEnd(); i++){
        pages.push(i);
    }
    return pages;
}
var showPrevLink = () => {
        return currentPage == 1 ? false : true;
}
var showNextLink = () => {
    return currentPage == (totalPages() - 1) ? false : true;
}
function loadPages(){
    firstPage();
    lastPage();
    rangeStart();
    rangeEnd();
    pages();
    totalPages();
}
function downloadProduct(product_id, i){
    $("#download-btn button").attr('disabled', true).attr('class', 'btn btn-warning' ).text("Downloading...");
    $("table#ae-products button:eq("+i+") i").attr('class', 'fa fa-sync fa-spin' );
    $.post({url: "./api/aliexpress.php?action=insertProduct", 
            data: {product_id: product_id},
            success: function(result){
             $("table#ae-products button:eq("+i+")").attr("disabled", true).attr('class', 'btn btn-success');
             $("table#ae-products button:eq("+i+") i").attr('class', 'fa fa-check' );
             countInserted += 1;
             var countProduct = totalProduct;
             if(totalProduct == 0){
                $("#download-btn button").attr('disabled', true).attr('class', 'btn btn-success' ).text('All products are downloaded');
             }else{
                totalProduct -= 1;
                $("#download-btn button").attr('disabled', false).attr('class', 'btn btn-warning' ).text("Download "+totalProduct+" Products");

                if(totalProduct == 0){
                    $("#download-btn button").attr('disabled', true).attr('class', 'btn btn-success' ).text('All products are downloaded');
                }
             }
             console.log(totalProduct, countInserted)
    }});
}
function downloadAllProduct(){
    $("#download-btn").attr('disabled', true);
}
</script>

<?php include 'template/footer.php';?>      
