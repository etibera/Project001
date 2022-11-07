<?php
include "common/headertest.php"; 
require_once 'model/product_store.php';
require_once 'model/home_new.php'; 
require_once 'model/SellerLatestPromo.php'; 
$model_SLP=new SellerLatestPromo();
$home_new_mod=new home_new();
$model_store=new product_store();
$custid = isset($_SESSION['user_login']) ? $_SESSION['user_login']: 0;
$b_id=0;
if(isset($_GET['Y2F0X2lk'])){
  $b_id=base64_decode($_GET['Y2F0X2lk']);
  $productsBname=$home_new_mod->get_productsBname2($b_id)  ; 
}

if(isset($_GET['cat_id'])){
  $productBrand=$home_new_mod->get_p_bybrand_category(base64_decode($_GET['Y2F0X2lk']),$_GET['cat_id']);
}else{
  $productBrand=$home_new_mod->getproduct_by_brand(base64_decode($_GET['Y2F0X2lk']));
}
/* echo "<pre><br><br><br><br><br><br>";
 print_r($productsBname);*//*<img src="http://localhost/peso-web-new/img/cache//lc_brand/msi-75x75.png" class="w-100 m-1 rounded-circle bg-light">*/
?>
<style> 
  .swiper {
    width: 100%;
    height: 100%;
  }
  .swiper-slide img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  body{
    overflow-x: hidden;
  }
</style>
<div class="container">
  <div class="position-relative" style="margin-top: 125px;">
    <div class="row">
      <div class="col-sm-2 px-1 text-center bg-light d-flex flex-column justify-content-center" style="border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;"> 
         <div class="row">
            <a data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $productsBname[0]['name'];?>" href="product_brand2.php?Y2F0X2lk=<?php echo $_GET['Y2F0X2lk'];?>&t=<?php echo  uniqid();?>" class="thumbnail"><img src="<?php echo $productsBname[0]['thumb'];?>" class="w-70 m-1 rounded-circle bg-light"></a>
         </div>       
         <div class="row">
            <div class="col-sm-12 p-2" >              
              <h1><?php echo $productsBname[0]['name'];?></h1>
            </div>
          </div>
      </div>
      <div class="col-sm-10 px-1 bg-light" style="border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;">
          <?php if($productsBname[0]['banner_img']==""){ ?>
              <div class="row">
                <div class="col-sm-12 p-0"><!-- Size 830X320 -->
                    <div ><?php include "homepageMainCarousel.php";?></div>
                </div>
              </div>
          <?php }else{ ?>
              <div class="row">
                <div class="col-sm-12 px-1">
                    <div><?php include "carousel_brand.php";?></div>
                </div>
              </div>
          <?php } ?>    
       </div>
    </div>
     <div class="row position-absolute w-100" style="z-index: 1;bottom:0px;">
        <div class="col-sm-12 pr-2">
          <div class="d-flex justify-content-between">
            <div style="margin-left: 25px; margin-top: 10px;" id="FollowersCount"></div>
             <div>
               <button type="button" class="btn btn-light m-1 border-primary BrandFollow d-none" id="BrandFollowing"  data-b_id='<?php echo $b_id;?>'data-customer_id='<?php echo $custid;?>' > Following </button>
              <button type="button" class="btn btn-primary m-1 BrandFollow d-none" id="BrandFollow"  data-b_id='<?php echo $b_id;?>'data-customer_id='<?php echo $custid;?>' > Follow <i class="far fa-plus"></i> </button>
              <button id="AddcommentId" type="button " class="btn btn-primary m-1 d-none" data-bs-toggle="modal" data-bs-target="#PBCommentMod" data-bs-toggle="tooltip" data-bs-placement="top" title="Comment on <?php echo $productsBname[0]['name'];?>" ><i class="fal fa-comment-alt-plus"></i> Add Comment</i></button>
              <div class="d-inline-block position-relative">
               <!--  <span class="position-absolute start-0 translate-middle badge rounded-pill bg-danger" id="comment_count" style="top: 14px;margin-left:15px;"></span> -->
                <button id="CommentId" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne" type="button" class="btn btn-primary m-1 d-none"> <span id="comment_count" ></span> </button>
              </div>
             </div>

            </div>
          </div>            
        </div> 
      </div>
        <!--  Customer Comment -->
        <div  id="flush-collapseOne" class="mt-3 pb-3accordio n-collapse collapse " aria-labelledby="flush-headingOne" >
          <div id="review" class="card border-0 accordion-body" style=" border-radius: 25px;">
            <div class="card-header bg-light py-3" style="border-top-left-radius: 25px; border-top-right-radius: 25px;">
              <span id="reviewTitle"  style="font-size: 27px;"> Customer comment on <?php echo $productsBname[0]['name'];?></span>
              <button type="button " class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#PBCommentMod" data-bs-toggle="tooltip" data-bs-placement="top" title="Comment on <?php echo $productsBname[0]['name'];?>" style="font-size: 18px;" ><i class="fal fa-comment-alt-plus"></i> Add Comment</i></button>
            </div>
            <div class="card-body border-0">
            </div>
            <div id="pagination" class="d-flex justify-content-end pb-3">
            </div>
          </div>
        </div>
        <!-- Customer Comment -->



    
      <!-- For store Products -->
      <div class="row p-0">
          <div class="col-sm-12 p-1"> 
            <div class="card border-0" style="background: linear-gradient(177deg, #09C6F9 10%, #045DE9 100%);border-radius: 25px;">
              <div class="card-header text-center text-light border-0" style="border-top-left-radius: 25px; border-top-right-radius: 25px;"> <span class="align-middle" style="font-size: 27px;">
                <a href="product_brand.php?Y2F0X2lk=<?php echo $_GET['Y2F0X2lk'];?>" class="text-decoration-none  text-light">
                    <?php echo $productsBname[0]['name'];?>
                </a>                 
                <?php if(isset($_GET['cat_id'])){ echo " > ".$_GET['cat_name']; }else{ echo" Products";} ?>
               </span> 
              </div>
              <div class="card-body border-0">  
                <div class="row">
                  <?php $list_lpp = $productBrand; ?> 
                   <?php include "PromoMainProductList.php"; ?>
                </div>
              </div>
            </div>                                      
          </div>
        </div>
        <!-- end For store Products -->
        
    </div>
</div>
<?php include "model/customer.php"; ?>
<?php echo (new customer())->insertCustomerView(); ?>

<!-- Modal -->
<div class="modal fade" id="PBCommentMod" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="background: #4b6ed6; border-radius: 20px;">
      <div class="modal-header text-light">
        <h5 class="modal-title" id="staticBackdropLabel"><?php echo $productsBname[0]['name'];?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-floating">
          <textarea class="form-control" placeholder="Leave a comment here" id="txtcomment" style="height: 100px"></textarea>
          <label for="txtcomment">Comment on  <?php echo $productsBname[0]['name'];?></label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" id="SendComment"><i class="fal fa-paper-plane"></i> Send </button>
      </div>
    </div>
  </div>
</div>
<script >
  $(".BrandFollow").click(function(){           
        var customer_id=$(this).data('customer_id');
        var brand_id=$(this).data('b_id');
        $.ajax({
            url: 'ajax_customer_comment.php?action=followBrand&t=' + new Date().getTime(),
            type: 'POST',
            data: 'customer_id=' + customer_id + '&brand_id='+brand_id,
            dataType: 'json',
            success: function(json) {   
              if (json) {            
                if(json['Message']=="Followed"){
                  bootbox.alert(json['Message']);
                }else{ 
                  bootbox.alert(json['Message']);
                }
              }
              countFallowers(customer_id,brand_id);

            },
            error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
        }); 
    });
  $( "#SendComment" ).click(function() {       
    var source_id='<?php echo $b_id;?>';
    var customer_id='<?php echo $custid;?>';   
    var comment=$('#txtcomment').val();
    var type=1;
     $.ajax({
            url: 'ajax_customer_comment.php?action=SendComment&t='+new Date().getTime(),
            type: 'post',
            data: 'source_id=' + source_id+'&comment='+comment+'&customer_id='+customer_id+'&type='+type,
            dataType: 'json',
            success: function(json) {
              bootbox.alert(json['success'], function(){ 
                    location.reload();   
              });
            }
        });
  });

  $(document).ready(async () => {
    var source_id='<?php echo $b_id;?>';
    var customer_id='<?php echo $custid;?>';
    await countFallowers(customer_id,source_id);
    await getCustomerComment('1', location.hash.substr(6) ? location.hash.substr(6) : 1, customer_id,source_id);
    await getPagination('all','1', customer_id,source_id);
   //await getPagination('all');
  });
   $(window).on('hashchange', function(e) {
    generatePagination();
  });
  const generatePagination = () => {
    $('#review .card-body>:not(:first-child)').remove();
    $('#review .card-body').append(`
    <div class="text-center my-5 p-5">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    `);
     var source_id='<?php echo $b_id;?>';
     var customer_id='<?php echo $custid;?>';

    getCustomerComment('1', location.hash.substr(6) ? location.hash.substr(6) : 1, customer_id,source_id)
  }
  
  const getPagination = (page,type,customer_id,source_id) => {
    return new Promise(resolve => {
      if (page === "all") page = location.hash.substr(6) ? location.hash.substr(6) : 1;
      $.ajax({
         url: `ajax_customer_comment.php?action=getPagination&source_id=${source_id}&customer_id=${customer_id}&type=${type}&t=${ new Date().getTime() }`,
        dataType: 'json',
        success: response => {
          if(response.count!=0){
            if(response.count==1){
               $('#comment_count').html(response.count+" Comment");
             }else{
               $('#comment_count').html(response.count+" Comments");
             }
          }else{
            $('#comment_count').html("Comment");
          }
          if (response.bool) {
            $('#pagination').pagination({
              items: response.count,
              itemsOnPage: 5,
              edges: 1,
              currentPage: page,
              prevText: '<i class="fas fa-angle-left"></i>',
              nextText: '<i class="fas fa-angle-right"></i>',
              cssStyle: 'light-theme'
            }).removeClass('d-none')
          } else {
            $('#pagination').addClass('d-none');
           
          }
        }
      }).done(() => {
        resolve();
      })
    })
  }
  const getCustomerComment = (type, page,customer_id,source_id) => {
    return new Promise(resolve => {
      $.ajax({
        url: `ajax_customer_comment.php?action=CustomerComment&source_id=${source_id}&customer_id=${customer_id}&type=${type}&page=${page}&t=${ new Date().getTime()}`,
        dataType: 'json',
        success: response => {
          if(page==1){   
          console.log(response.length);         
            if(response.length==0){
              $('#review').addClass('d-none');                
              $('#CommentId').addClass('d-none');  
              $('#AddcommentId').removeClass('d-none');              
            }else{
              $('#CommentId').removeClass('d-none');  
              $('#AddcommentId').addClass('d-none');
            }
          }
          response.reviews?.list?.map(res => {
            $('#review .card-body').append(`
            <div class="mt-3">
              <h6 class="my-0">${res.author}</h6>             
              <p class="text-muted">${res.date_added}</p>
              <p class="default">${res.text}</p>
              <hr>
            </div>
            `);
          });
        }
      }).done(() => {
        resolve();
      })
    })
  }
  const countFallowers = (customer_id,source_id) => {
    return new Promise(resolve => {
      var count=0;
      $.ajax({
        url: `ajax_customer_comment.php?action=countFallowers&customer_id=${customer_id}&brand_id=${source_id}&t=${ new Date().getTime()}`,
        dataType: 'json',
        success: response => {      
            if(response.fallow==0){
               $('#BrandFollow').removeClass('d-none');  
               $('#BrandFollowing').addClass('d-none');           
            }else{
              $('#BrandFollow').addClass('d-none'); 
              $('#BrandFollowing').removeClass('d-none'); 
            }
            if(response.count>1200){
              count= response.count
            }else{
              count=1300;
            }
            $('#FollowersCount').html(intToString(count) + " Followers");
          
        }
      }).done(() => {
        resolve();
      })
    })
  }
  function intToString (value) {
    var suffixes = ["", "K", "M", "B","Y"];
    var suffixNum = Math.floor((""+value).length/3);
    var shortValue = parseFloat((suffixNum != 0 ? (value / Math.pow(1000,suffixNum)) : value).toPrecision(2));
    if (shortValue % 1 != 0) {
        shortValue = shortValue.toFixed(1);
    }
    return shortValue+suffixes[suffixNum];
}
</script>
<?php
include "common/footer.php";
?>

