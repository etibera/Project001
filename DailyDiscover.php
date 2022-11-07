<?php 
	include "common/headertest.php";
	require_once 'model/DailyDiscover.php';   
	$model_DD=new DailyDiscover();
	$custid = isset($_SESSION['user_login']) ? $_SESSION['user_login']: 0;  
	if(isset($_GET['pageNumber'])){
	 $pageNumber=$_GET['pageNumber'];
	}else{
	  $pageNumber=1;
	}
	$DiscoverProductList=$model_DD->DiscoverProductList(48,$pageNumber) ; 	
	$DDPageNumber=$model_DD->DDPageNumber(48,$pageNumber) ; 	
?>
<style type="text/css">
	.card-dpl:hover { border: 1px solid #777;}
	
</style>
<div class="container">
	<div class="" style="margin-top: 123px;"></div>
	<div class="row-home">
		<div class="row">
			<div class="col-sm-12">
				<div class="card border-0" style="background: linear-gradient(177deg, #09C6F9 10%, #045DE9 100%); margin-top: 4px;">
					<div class="card-header text-center text-dark border-0">Daily Discover</div>
				    <div class="card-body border-0">				    	
				    	<div class="row">			    	
				     		<?php foreach ($DiscoverProductList as $dpl) : ?>
								<?php  $getimg =$dpl['thumb']; $dplname=utf8_encode($dpl['name']);?>
								 <?php $namedpl = strlen($dplname) > 25 ? substr($dplname,0,25)."..." : $dplname;?>  
								<div class="col-sm-2 mb-1" style="padding-right: 1px;padding-left: 1px;">
									<div class="card card-dpl">
										<div class="card-header" >
										 	<a href="<?php echo $dpl['href']; ?>" >
								              <?php if($getimg!=""): ?>
								                <img src="<?php echo $getimg; ?>" alt="<?php echo $dpl['name']; ?>" class="rounded-3 bg-light   img-fluid" />
								              <?php else: ?>
								                 <p class="no-image"><i class="fa fa-shopping-bag"></i></p>
								              <?php endif; ?>  
								            </a>
										</div>
										<div class="card-body p-1">
								            <div class="text-center" style="height:18px;overflow: hidden;">
								              <span style="font-size: 10px;">
								                <a class="text-black  text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo utf8_encode($dpl['name']); ?>" href="<?php echo $dpl['href']; ?>"><?php echo $namedpl; ?></a>        
								              </span>
								            </div> 
								            <div class="text-center text-danger" style="height:19px;overflow: hidden;">
								              <span style="font-size: 12px;"><b class="">₱<?php echo   number_format($dpl['price'],2);?></b>  
								              </span>
								            </div> 
								        </div>  
									</div>									
								</div>
							<?php endforeach; ?> 
				     	</div>
				     	<div class="row">
				     		<nav aria-label="..." id="pagination" ></nav>
				     	</div>	
				     	
				    </div>
				</div>  
			</div>			
		</div>
	</div>
</div>
<script type="text/javascript">
let pages = <?php echo $DDPageNumber;?>;
let activepages = <?php echo $pageNumber;?>;
document.getElementById('pagination').innerHTML = createPagination(pages,activepages);
function createPagination(pages, page) {
  let str = '<ul class="pagination justify-content-center mt-3" >';
  let active;
  let bgactive;
  let pageCutLow = page - 1;
  let pageCutHigh = page + 3;
  // Show the Previous button only if you are on a page other than the first
  if (page > 1) {
    str += '<li class="page-item previous no"><a style="cursor: pointer;" class="page-link" onclick="createPagination(pages, '+(page-1)+')" href="DailyDiscover.php?pageNumber='+(page-1)+'" ><i class="fas fa-chevron-left"></i></a></li>';
  }
  // Show all the pagination elements if there are less than 6 pages total
  if (pages < 6) {
    for (let p = 1; p <= pages; p++) {
      active = page == p ? "active" : "no";
      bgactive = page == p ? "bg-danger" : "";
      str += '<li class="page-item '+active+'" aria-current="page" ><a href="DailyDiscover.php?pageNumber='+p+'" style="cursor: pointer;" class="page-link '+bgactive+'" onclick="createPagination(pages, '+p+')">'+ p +'</a></li>';
    }
  }
  // Use "..." to collapse pages outside of a certain range
  else {
    // Show the very first page followed by a "..." at the beginning of the
    // pagination section (after the Previous button)
    if (page > 2) {
      str += '<li class=" page-item no page-item"><a  style="cursor: pointer;" class="page-link" onclick="createPagination(pages, 1)" href="DailyDiscover.php?pageNumber=1">1</a></li>';
      if (page > 3) {
          str += '<li class="page-item out-of-range"><a style="cursor: pointer;" class="page-link" onclick="createPagination(pages,'+(page-2)+')" href="DailyDiscover.php?pageNumber='+(page-2)+'">...</a></li>';
      }
    }
    // Determine how many pages to show after the current page index
    if (page === 1) {
      pageCutHigh += 2;
    } else if (page === 2) {
      pageCutHigh += 1;
    }
    // Determine how many pages to show before the current page index
    if (page === pages) {
      pageCutLow -= 2;
    } else if (page === pages-1) {
      pageCutLow -= 1;
    }
    // Output the indexes for pages that fall inside the range of pageCutLow
    // and pageCutHigh
    for (let p = pageCutLow; p <= pageCutHigh; p++) {
      if (p === 0) {
        p += 1;
      }
      if (p > pages) {
        continue
      }
      active = page == p ? "active" : "no";
      bgactive = page == p ? "bg-danger" : "";
      str += '<li class="page-item '+active+'"><a style="cursor: pointer;"  class="page-link '+bgactive+'" onclick="createPagination(pages, '+p+')" href="DailyDiscover.php?pageNumber='+p+'">'+ p +'</a></li>';
    }
    // Show the very last page preceded by a "..." at the end of the pagination
    // section (before the Next button)
    if (page < pages-1) {
      if (page < pages-2) {
        str += '<li class="page-item out-of-range"><a style="cursor: pointer;"  class="page-link" onclick="createPagination(pages,'+(page+2)+')" href="DailyDiscover.php?pageNumber='+(page+2)+'">...</a></li>';
      }
      str += '<li class="page-item no"><a href="DailyDiscover.php?pageNumber='+pages+'" class="page-link" onclick="createPagination(pages, pages)">'+pages+'</a></li>';
    }
  }
  // Show the Next button only if you are on a page other than the last
  if (page < pages) {
    str += '<li class="page-item next no"><a style="cursor: pointer;" class="page-link" style="cursor: pointer;" onclick="createPagination(pages, '+(page+1)+')" href="DailyDiscover.php?pageNumber='+(page+1)+'"><i class="fas fa-chevron-right"></i></a></li>';
  }
  str += '</ul>';
  // Return the pagination string to be outputted in the pug templates
  document.getElementById('pagination').innerHTML = str;
  return str;
}
</script>
<?php include "common/footer.php"; ?>