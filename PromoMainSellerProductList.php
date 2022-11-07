<style type="text/css">
	.card-PMSPL:hover { border: 1px solid #777;}
	.ribbon {
  width: 48%;
  position: relative;
  float: left;
  margin-bottom: 30px;
  background-size: cover;
  text-transform: uppercase;
  color: white;
}
.ribbon3 {
    width: 100px;
    height: 25px;
    line-height: 25px;
    padding-left: 15px;
    position: absolute;
    left: -8px;
    top: 20px;
    background: red;
    font-size: 80%;
  }
  .ribbon3:before, .ribbon3:after {
    content: "";
    position: absolute;
  }
  .ribbon3:before {
    height: 0;
    width: 0;
    top: -8.5px;
    left: 0.1px;
    border-bottom: 9px solid black;
    border-left: 9px solid transparent;
  }
  .ribbon3:after {
    height: 0;
    width: 0;
    right: -17px;
    border-top: 15px solid transparent;
    border-bottom: 10px solid transparent;
    border-left: 18px solid red;
  } 
  .no-image{
    background-color: #e0e0e0;
    height: 150px;
}
.no-image i {
    font-size: 50px;
    margin-top: 50px;
    color: #333;
}
</style>
<?php foreach ($list_lppseller as $PMSPL) : ?>
	<?php  $getimg =$PMSPL['thumb']; $mpl_name=utf8_encode($PMSPL['name']);?>
	 <?php $namempl = strlen($mpl_name) > 25 ? substr($mpl_name,0,25)."..." : $mpl_name;?>  
	<div class="col-sm-2 mb-1" style="padding-right: 1px;padding-left: 1px;">
		<div class="card card-PMSPL">			 
			<div class="ribbon">
				<span class="ribbon3">
                  	<?php if($PMSPL['deduction_type']=="0"){ ?>
                        <?php echo number_format($PMSPL['value']); ?>% OFF
                    <?php }else { ?>
                        ₱<?php echo number_format($PMSPL['value']); ?> OFF
                    <?php } ?>
                </span>
			</div>
			<?php if($PMSPL['promoImgVal']!=""){ ?> 
                <div style="width:auto;height:auto;text-align:center;
                    font-size: 12px;
                    position: absolute;
                    float: right;
                    right: 0;
                    top: 0;">
                		<img   src="<?php echo 'img/'.$PMSPL['promoImgVal']; ?>" alt="<?php echo $PMSPL['name']; ?>" class="img-fluid" />
            	</div>
            <?php } ?>  
			<div class="card-header" >
			 	<a href="<?php echo $PMSPL['href']; ?>" >
	              <?php if($getimg!=""): ?>
	                <img src="<?php echo $getimg; ?>" alt="<?php echo $PMSPL['name']; ?>" class="rounded-3 bg-light   img-fluid" />
	              <?php else: ?>
	                 <p class="no-image"><i class="fa fa-shopping-bag"></i></p>
	              <?php endif; ?>  
	            </a>
			</div>
			<div class="card-body p-1">
				<div class="row">
					<div class="col-sm-9">
						<div class="row">
							<div class="col-sm-12">
								<div class="text-center" style="height:18px;overflow: hidden;">
					              <span style="font-size: 10px;">
					                <a class="text-black  text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo utf8_encode($PMSPL['name']); ?>" href="<?php echo $PMSPL['href']; ?>"><?php echo $namempl; ?></a>        
					              </span>
					            </div> 
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="text-center text-danger" style="height:19px;overflow: hidden;">
					              <span style="font-size: 12px;"><b class="">₱<?php if($PMSPL['deduction_type']=="1"){ ?> 
				                           <?php echo number_format($PMSPL['price']-$PMSPL['rate'],2);?>
				                        <?php }else{  ?>
				                          <?php $deductval=$PMSPL['price']*$PMSPL['rate']; ?>
				                          <?php echo number_format($PMSPL['price']-$deductval,2);?>
				                        <?php } ?></b>  
					              </span>
					            </div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="text-center text-danger" style="height:19px;overflow: hidden;">
					              <span style="font-size: 12px;"><b class="" style="text-decoration: line-through; display: inline-block;color: #9e9e9e;" >₱<?php echo number_format($PMSPL['price'],2);?></b>  
					              </span>
					            </div>
							</div>
						</div>						
					</div>
					<div class="col-sm-3 p-0">
						<div class="row" style="height:38px;">
							<div class="col-sm-12"  style="    width: 52px;
								    height: 43px;
								    padding: 0px;
								    position: absolute;
								    float: right;
								    right: 0;
								    bottom: 8px;">
								<img  src="<?php echo $PMSPL['sellerimage']; ?>" alt="<?php echo $PMSPL['name']; ?>" class="img-fluid" />
							</div>
						</div>
					</div>
				</div>	
	        </div>  
		</div>		
	</div>
<?php endforeach; ?> 