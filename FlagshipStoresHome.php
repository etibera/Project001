<?php 
	include "common/headertest.php";
	require_once 'model/FlagshipStoresHome.php';     
	require_once 'model/home_new.php';    
	$home_new_mod=new home_new();
    $FSmod=new FSHome();   
    $custid = isset($_SESSION['user_login']) ? $_SESSION['user_login']: 0;
    $sellerId = isset($_GET['sellerId']) ? $_GET['sellerId']: 0;
    $FSDet=$FSmod->FSDet($sellerId); 
    $PromoBundlesAnnouncement=$FSmod->GetPromoBundlesAnnouncement($sellerId,0); 
    $getFollowStats=$FSmod->getFollowStats($custid,$sellerId); 
    $Campany_Name=$FSDet['flagship_name'];
    $fldescription=$FSDet['flagship_desc'];
    $theme_color=$FSDet['theme_color'];
    $flagship_logo=$FSDet['thumb'];
    
?>

<div class="container">
	<div class="" style="margin-top: 127px;">
		<div class="row">
			<div class="col-sm-3 p-1 text-center bg-light" >
 				<a class="text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo  $Campany_Name;?>" href="FlagshipStoresHome.php?sellerId=<?php echo  $sellerId;?>&t=<?php echo  uniqid();?>"> 
			 	<img src="<?php echo $flagship_logo;?>" class="img-fluid"></a>
			</div>
			<div class="col-sm-9 p-1 bg-light">
			  	<div class="row">
			  		<div class="col-sm-12 p-2" >			  			
			  			<h1><?php echo  $Campany_Name;?></h1>
			  		</div>
			  	</div>
			  	<div class="row">
			  		<div class="col-sm-12 p-2" style="height: 108px; overflow-y: scroll;">
			  			<p ><?php echo  $fldescription;?></p>
			  		</div>
			  	</div>		
			  	<div class="row " >
			  		<div class="col-sm-12 pr-2">
			  			<div class="d-flex justify-content-between">
			  				 <div  id="FollowersCount"></div>
			  				 <div>
			  				 	<button type="button" class="btn btn-light m-1 border-primary StoreFollow d-none" id="StoreFollowing"  data-seller_id='<?php echo $sellerId;?>'data-customer_id='<?php echo $custid;?>' > Following </button>
			              <button type="button" class="btn btn-primary m-1 StoreFollow d-none" id="StoreFollow"  data-seller_id='<?php echo $sellerId;?>'data-customer_id='<?php echo $custid;?>' > Follow <i class="far fa-plus"></i> </button>
			              <button id="AddcommentId" type="button " class="btn btn-primary m-1 d-none" data-bs-toggle="modal" data-bs-target="#PBCommentMod" data-bs-toggle="tooltip" data-bs-placement="top" title="Comment on <?php echo $Campany_Name;?>" ><i class="fal fa-comment-alt-plus"></i> Add Comment</i></button>
			              <div class="d-inline-block position-relative">
			               <!--  <span class="position-absolute start-0 translate-middle badge rounded-pill bg-danger" id="comment_count" style="top: 14px;margin-left:15px;"></span> -->
			                <button id="CommentId" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne" type="button" class="btn btn-primary d-none m-1 "> <span id="comment_count" ></span> </button>
			              </div>
			  				 </div>
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
          <span id="reviewTitle"  style="font-size: 27px;"> Customer comment on <?php echo  $Campany_Name;?></span>
          <button type="button " class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#PBCommentMod" data-bs-toggle="tooltip" data-bs-placement="top" title="Comment on <?php echo  $Campany_Name;?>" style="font-size: 18px;" ><i class="fal fa-comment-alt-plus"></i> Add Comment</i></button>
        </div>
        <div class="card-body border-0">
        </div>
        <div id="pagination" class="d-flex justify-content-end pb-3">
        </div>
      </div>
    </div>
    <!-- Customer Comment -->
	<!--FLAGSHIP BANNER -->
	<div class="row-home"><!-- 	1132 × 535 px -->
		<div class="row p-0" style="background: <?php echo $theme_color;?>">
			<div class="col-sm-4 col-md-2 m-auto" >
				<h5 ><span class="text-dark p-1 rounded-3" style="background: linear-gradient(135deg, #a9bdc3 10%, #979ca3 100%);"> New Releases</span></h5>
	  		</div>	
	  		<div class="col-sm-8 col-md-10 p-2">
	  			<div class="clearfix">	
	  			    <a class="btn btn-secondary float-end m-1 text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="Category" href="FlagShipCategory.php?sellerId=<?php echo  $sellerId;?>&t=<?php echo  uniqid();?>">Category</a> 
	  			    <a class="btn btn-secondary float-end m-1 text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="Category" href="FlagShipProducts.php?sellerId=<?php echo  $sellerId;?>&t=<?php echo  uniqid();?>">Products</a> 
				</div>
	  		</div>
		</div>	
		<div class="row p-0" >
			<div class="col-sm-12 p-0">
				 <?php include "FlagShipCarousel.php"; ?> 								
			</div>
		</div>
	</div>
	<div class="row-home">
		<div class="row p-0" style="background: <?php echo $theme_color;?>">
			<div class="col-sm-12 p-2 text-center">
				<h5><span class="text-dark p-1 rounded-3" style="background: linear-gradient(135deg, #a9bdc3 10%, #979ca3 100%);"> Promo (Bundles / Announcement)</span> </h5>
	  		</div>		  		
		</div>	
		<div class="row p-0" style="background: <?php echo $theme_color;?>">
			<?php if($PromoBundlesAnnouncement){ ?>
				<?php foreach ($PromoBundlesAnnouncement as $pba) { ?>
					<div class="col-sm-12 p-0 text-center">
					    <?php if($pba['image']) {?>
						<img  class="img-fluid" src="img/<?php echo $pba['image']?>">
						<?php } else {?>
						<iframe width="100%" height="315" src="<?php echo $pba['video_link']; ?>" frameborder="0" allowfullscreen></iframe>
						<?php } ?>
					</div>
				<?php } ?>
			<?php }?>
		</div>
	</div>
	<div class="row-home">	
		<div class="row p-0">
			<div class="col-sm-12 p-1">
				<div class="card border-0" style="background: <?php echo $theme_color;?>">
					<div class="card-header text-center border-0 "><span class="text-dark p-1 rounded-3" style="background: linear-gradient(135deg, #a9bdc3 10%, #979ca3 100%);">Recommended for you</span></div>
				    <div class="card-body border-0" >	<!-- style="background: linear-gradient(135deg, #09C6F9 10%, #045DE9 100%); " -->			    	
				    	<div class="row" >	
				     		<?php include "FlagShipMainProductList.php";?>	
				     	</div>				     		
				    </div>
				</div>  
			</div>			
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="PBCommentMod" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="background: #4b6ed6; border-radius: 20px;">
      <div class="modal-header text-light">
        <h5 class="modal-title" id="staticBackdropLabel"><?php echo  $Campany_Name;?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-floating">
          <textarea class="form-control" placeholder="Leave a comment here" id="txtcomment" style="height: 100px"></textarea>
          <label for="txtcomment">Comment on  <?php echo  $Campany_Name;?></label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" id="SendComment"><i class="fal fa-paper-plane"></i> Send </button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
	 $(".StoreFollow").click(function(){           
        var customer_id=$(this).data('customer_id');
        var seller_id=$(this).data('seller_id');
        $.ajax({
            url: 'ajax_customer_comment.php?action=followStore&t=' + new Date().getTime(),
            type: 'POST',
            data: 'customer_id=' + customer_id + '&seller_id='+seller_id,
            dataType: 'json',
            success: function(json) {   
              if (json) {            
                if(json['Message']=="Followed"){
                  bootbox.alert(json['Message']);
                }else{ 
                  bootbox.alert(json['Message']);
                }
              }
              countFallowers('2',customer_id,seller_id);

            },
            error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
        }); 
    });
	 $( "#SendComment" ).click(function() {       
    var source_id='<?php echo $sellerId;?>';
    var customer_id='<?php echo $custid;?>';   
    var comment=$('#txtcomment').val();
    var type=2;
    if(comment==""){
    	 bootbox.alert("Comment Is required");
    	 return false;
    }
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
    var source_id='<?php echo $sellerId;?>';
    var customer_id='<?php echo $custid;?>';
    await GenerateBeginningFallowers('2',source_id);
    await countFallowers('2',customer_id,source_id);
    await getCustomerComment('2', location.hash.substr(6) ? location.hash.substr(6) : 1, customer_id,source_id);
    await getPagination('all','2', customer_id,source_id);
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
     var source_id='<?php echo $sellerId;?>';
     var customer_id='<?php echo $custid;?>';

    getCustomerComment('2', location.hash.substr(6) ? location.hash.substr(6) : 1, customer_id,source_id)
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
	const GenerateBeginningFallowers = (type,source_id) => {
	    return new Promise(resolve => {
	      var count=0;
	      $.ajax({
	        url: `ajax_customer_comment.php?action=GenerateBeginningFallowers&type=${type}&source_id=${source_id}&t=${ new Date().getTime()}`,
	        dataType: 'json',
	        success: response => {      
	        }
	      }).done(() => {
	        resolve();
	      })
	    })
	}
	const countFallowers = (type,customer_id,source_id) => {
    return new Promise(resolve => {
      var count=0;
      $.ajax({
        url: `ajax_customer_comment.php?action=countFallowers&customer_id=${customer_id}&type=${type}&brand_id=${source_id}&t=${ new Date().getTime()}`,
        dataType: 'json',
        success: response => {      
            if(response.fallow==0){
               $('#StoreFollow').removeClass('d-none');  
               $('#StoreFollowing').addClass('d-none');           
            }else{
              $('#StoreFollow').addClass('d-none'); 
              $('#StoreFollowing').removeClass('d-none'); 
            }
            if(response.count>response.beginning_count){
              count= response.count
            }else{
              count=response.beginning_count;
            }
            $('#FollowersCount').html(intToString(count) + " Followers");
          
        }
      }).done(() => {
        resolve();
      })
    })
  }/*
const countFallowersFS = (type,customer_id,source_id) => {
    return new Promise(resolve => {
      var count=0;
      $.ajax({
        url: `ajax_customer_comment.php?action=countFallowersFS&customer_id=${customer_id}&type=${type}&seller_id=${source_id}&t=${ new Date().getTime()}`,
        dataType: 'json',
        method:'GET',
        success: response => {  
            if(response.count>response.beginning_count){
              count= response.count
            }else{
               count=response.beginning_count;
            }
            $('#FollowersCount').html(intToString(count) + " Followers");
          
        }
      }).done(() => {
        resolve();
      })
    })
  }*/
    function intToString (value) {
	    var suffixes = ["", "K+", "M+", "B+","T+"];
	    var suffixNum = Math.floor((""+value).length/3);
	    var shortValue = parseFloat((suffixNum != 0 ? (value / Math.pow(1000,suffixNum)) : value).toPrecision(2));
	    if (shortValue % 1 != 0) {
	        shortValue = shortValue.toFixed(1);
	    }
	    return shortValue+suffixes[suffixNum];
	}
</script>
<?php include "common/footer.php"; ?>