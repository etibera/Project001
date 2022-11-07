<?php 
	include "common/headertest.php";
	require_once 'model/FlagshipStoresHome.php';     
	require_once 'model/home_new.php';    
	$home_new_mod=new home_new();
    $FSmod=new FSHome();   
    $custid = isset($_SESSION['user_login']) ? $_SESSION['user_login']: 0;
    $sellerId = isset($_GET['sellerId']) ? $_GET['sellerId']: 0;
    $cat_id = isset($_GET['cat_id']) ? $_GET['cat_id']: 0;
    $catname = isset($_GET['catname']) ? $_GET['catname']: '';
    $FSDet=$FSmod->FSDet($sellerId); 
    $PromoBundlesAnnouncement=$FSmod->GetPromoBundlesAnnouncement($sellerId,0); 
    $FSBrand_id=$FSmod->GetFlasShipSelectedBrand($sellerId);
    $Campany_Name=$FSDet['flagship_name'];
    $fldescription=$FSDet['flagship_desc'];
    $theme_color=$FSDet['theme_color'];
    $flagship_logo=$FSDet['thumb'];
   
?>
<style type="text/css">
    .image-overlay {
    height: 100px;
    width: 100%;   
    background-size: cover;
    background-position: top;
    position: relative; 
}
  
  .overlay-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #aaa;
    white-space: nowrap;
  }
</style>
<div class="container">
    <div class="" style="margin-top: 123px;">
        <div class="row">
            <div class="col-sm-12 p-1 text-center" style="background: <?php echo $theme_color;?>">
                <div class="image-overlay" >
                    <div class="overlay-text">
                      <div  style="z-index: 2">
                        <a class="nav-item nav-link text-dark" style="font-size: 50px;" href="FlagShipProducts.php?sellerId=<?php echo  $sellerId;?>&t=<?php echo  uniqid();?>"  role="tab" data-toggle="tab"><span class="text-dark p-1 rounded-3" style="background: linear-gradient(135deg, #a9bdc3 10%, #979ca3 100%);"> Products</span></a>
                      </div>
                    </div>
                </div>
            </div>             
        </div>
    </div>
    <div class="row-home">  
        <div class="row p-0">
            <div class="col-sm-12 p-1">
                <div class="card border-0" style="background: <?php echo $theme_color;?>">
                    <div class="card-header text-center text-light border-0">
                        <span class="text-dark p-1 rounded-3 align-middle" style="background: linear-gradient(135deg, #a9bdc3 10%, #979ca3 100%);"> Most Popular</span>  
                        <button type="button" class="btn float-end " style="font-size: 10px;background: linear-gradient(135deg, #a9bdc3 10%, #979ca3 100%);">See All></button>
                    </div>
                    <div class="card-body border-0"> 
                        <?php $FlagShipMostPular=$FSmod->FlagShipMostPular(15,$FSBrand_id);?> 
                        <?php include "FlagShipMostPopular.php";?>  
                    </div>
                </div>                                                                          
            </div>            
        </div>
    </div>
     <div class="row-home">  
        <div class="row p-0">
            <div class="col-sm-12 p-1">
               <div class="card border-0 " style="background: <?php echo $theme_color;?>">
                    <div class="card-header text-center border-0">
                        <span class="text-dark p-1 rounded-3 align-middle" style="background: linear-gradient(135deg, #a9bdc3 10%, #979ca3 100%);"> Recommended for you</span>   
                        <button type="button" class="btn float-end " style="font-size: 10px;background: linear-gradient(135deg, #a9bdc3 10%, #979ca3 100%);">See All></button>
                    </div>
                    <div class="card-body border-0"> 
                        <?php $FlagShipRecommendedForYou=$FSmod->FlagShipRecommendedForYou($custid,15,$FSBrand_id);?>
                        <?php include "FlagShipRecommendedForYou.php";?> 
                    </div>
                </div>                                                                          
            </div>            
        </div>
    </div>
    <div class="row-home">  
        <div class="row p-0">
            <div class="col-sm-12 p-1">
               <div class="card border-0" style="background: <?php echo $theme_color;?>">

                    <div class="card-header text-center text-dark border-0"> </div>
                    <div class="card-body border-0"> 
                        <?php $FlagShipBestSeller=$FSmod->GetFlagShipProduct($sellerId,15); ?>
                        <?php include "FlagShipBestSeller.php";?> 
                    </div>
                </div>                                                                          
            </div>            
        </div>
    </div>
</div>
<?php include "common/footer.php"; ?>