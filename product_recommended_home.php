<?php 
    include "common/header.php";
    require_once 'model/home_new.php'; 
    $home_new_mod=new home_new();
    $custid = isset($_SESSION['user_login']) ? $_SESSION['user_login']: 0;
   
?>
<div class="wrapper">
    <div class="container_home" >
    	<div class="row">        
	        <div class="col-lg-12 recommended-cat">
	          <h5>Recommended for you</h5>
	        </div>
        	<div class="col-lg-12 recommended-cat">
        		<?php $recommended_product=$home_new_mod->recommended_product_new($custid,9);?>
        		<div class="ct-header ct-header--slider ct-slick-custom-dots" id="home">
        			<div class="ct-slick-homepage" data-arrows="true" data-autoplay="true">
        			<?php foreach ($recommended_product as $productdesc_rec) : ?>
        				<?php  if($productdesc_rec['type']==0){$getimg = $productdesc_rec['thumb']; }else{ $getimg = $productdesc_rec['image']; } ?>
        				<div class="ct-header tablex item">
        					<img data-lazy="images/slide4.jpg">
        				</div>
        			<?php endforeach; ?> 
        			</div>
        		</div>
        	</div>
    	</div>
    </div>
</div>
<script type="text/javascript">
		$(document).ready(function(){
			$('.ct-slick-homepage').slick({
				lazyLoad: 'ondemand',
			});
		}); 
	</script>
 <?php include "common/footer.php"; ?>