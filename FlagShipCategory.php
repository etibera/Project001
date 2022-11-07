<?php 
	include "common/headertest.php";
	require_once 'model/FlagshipStoresHome.php';     
	require_once 'model/home_new.php';    
	$home_new_mod=new home_new();
    $FSmod=new FSHome();   
    $custid = isset($_SESSION['user_login']) ? $_SESSION['user_login']: 0;
    $sellerId = isset($_GET['sellerId']) ? $_GET['sellerId']: 0;
    $FSDet=$FSmod->FSDet($sellerId); 
    $PromoBundlesAnnouncement=$FSmod->GetPromoBundlesAnnouncement($sellerId,1); 
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
			  	<div class="row">
			  		<div class="col-sm-12 pr-2">
			  			<div class="clearfix">
			  				<div class="float-start m-1">1.2K Followers</div>
			  				<button type="button" class="btn btn-primary float-end m-1">Contact Us</button>
			  				<?php  if($getFollowStats!=0){ $displayF="none";$displayUF="block"; }else{  $displayF="block" ;$displayUF="none"; } ?>
			  			<button type="button" class="btn btn-primary float-end m-1 flagshipFollow" id="flagshipFollow"  data-seller_id='<?php echo $sellerId;?>'data-customer_id='<?php echo $custid;?>' style="display: <?php echo $displayF;?>;" > Follow <i class="fas fa-plus"></i> </button>
			  			<button type="button" class="btn btn-light float-end m-1 border-primary flagshipFollow" id="flagshipUnFollow"  data-seller_id='<?php echo $sellerId;?>'data-customer_id='<?php echo $custid;?>' style="display: <?php echo $displayUF;?>;"> Following </button> 
			  				
						</div>
			  		</div>			  		
			  	</div>	
			 </div>
		</div>
	</div>
	<!--FLAGSHIP BANNER -->
	<div class="row-home"><!-- 	1132 × 535 px -->
		<div class="row p-0" style="background: <?php echo $theme_color;?>">
			<div class="col-sm-4 col-md-2 m-auto">
				<h5 ><span class="text-dark p-1 rounded-3" style="background: linear-gradient(135deg, #a9bdc3 10%, #979ca3 100%);">Category</span></h5>
	  		</div>	
	  		<div class="col-sm-8 col-md-10 p-2">
	  			<div class="clearfix">	
	  			    <a class="btn btn-info float-end m-1 text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="Category" href="FlagShipCategory.php?sellerId=<?php echo  $sellerId;?>&t=<?php echo  uniqid();?>">Category</a> 
	  			    <a class="btn btn-secondary float-end m-1 text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="Category" href="FlagShipProducts.php?sellerId=<?php echo  $sellerId;?>&t=<?php echo  uniqid();?>">Products</a>
				</div>
	  		</div>
		</div>			
	</div>
	<div class="row-home">
		<div class="row p-0" >
			<div class="col-sm-12 p-1">
			    <div class="card border-0"style="background: <?php echo $theme_color;?>">
				    <div class="card-body border-0">	
				    	<?php include "FlagShipCarouselSwiper.php";?>	
				    </div>
				</div> 		 
			 </div>	  		
		</div>	
		<div class="row p-0" style="background: <?php echo $theme_color;?>">
			<?php if($PromoBundlesAnnouncement){ ?>
				<?php foreach ($PromoBundlesAnnouncement as $pba) { ?>
					<div class="col-sm-12 p-0 text-center">
						<img  class="img-fluid" src="img/<?php echo $pba['image']?>">
					</div>
				<?php } ?>
			<?php }?>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">
	$(".flagshipFollow").click(function(){           
        var customer_id=$(this).data('customer_id');
        var seller_id=$(this).data('seller_id');
        $.ajax({
            url: 'ajax_add_to_cart_latest.php?action=followSeller&t=' + new Date().getTime(),
            type: 'POST',
            data: 'customer_id=' + customer_id + '&seller_id='+seller_id,
            dataType: 'json',
            success: function(json) {   
              if (json['success']) {
              	//console.log(json['success']['message']);
               
                if(json['success']['message']=="Followed"){
				    $("#flagshipFollow").css("display","none");
				    $("#flagshipUnFollow").css("display","block");
				     bootbox.alert(json['success']['message']);
                }else{
                	$("#flagshipFollow").css("display","block");
				    $("#flagshipUnFollow").css("display","none");
				     bootbox.alert(json['success']['message']);
                }
              }

            },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
        }); 
    });
</script>
<?php include "common/footer.php"; ?>