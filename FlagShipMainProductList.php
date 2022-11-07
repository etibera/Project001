<style type="text/css">
	.card-Mprl:hover { border: 1px solid #777;}
</style>
<?php $GetFlagShipDiscoverProducts=$FSmod->GetFlagShipProduct($sellerId,84); ?>
<?php foreach ($GetFlagShipDiscoverProducts as $products_hppn) : ?>
	<?php  $getimg =$products_hppn['thumb']; $mpl_name=utf8_encode($products_hppn['name']);?>
	 <?php $namempl = strlen($mpl_name) > 25 ? substr($mpl_name,0,25)."..." : $mpl_name;?>  
	<div class="col-sm-2 mb-1" style="padding-right: 1px;padding-left: 1px;">
		<div class="card card-Mprl">
			<div class="card-header" >
			 	<a href="<?php echo $products_hppn['href']; ?>" >
	              <?php if($getimg!=""): ?>
	                <img src="<?php echo $getimg; ?>" alt="<?php echo $products_hppn['name']; ?>" class="rounded-3 bg-light   img-fluid" />
	              <?php else: ?>
	                 <p class="no-image"><i class="fa fa-shopping-bag"></i></p>
	              <?php endif; ?>  
	            </a>
			</div>
			<div class="card-body p-1">
	            <div class="text-center" style="height:18px;overflow: hidden;">
	              <span style="font-size: 10px;">
	                <a class="text-black  text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo utf8_encode($products_hppn['name']); ?>" href="<?php echo $products_hppn['href']; ?>"><?php echo $namempl; ?></a>        
	              </span>
	            </div> 
	            <div class="text-center text-danger" style="height:19px;overflow: hidden;">
	              <span style="font-size: 12px;"><b class="">₱<?php echo   number_format($products_hppn['price'],2);?></b>  
	              </span>
	            </div> 
	        </div>  
		</div>
		
	</div>
<?php endforeach; ?> 