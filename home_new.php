<?php 
    include "common/header.php";
    require_once 'model/home_new.php'; 
    $home_new_mod=new home_new();
    $custid = isset($_SESSION['user_login']) ? $_SESSION['user_login']: 0;
    $gethomecategorylist = $home_new_mod->gethomecategorylist_new($custid); 
    if($gethomecategorylist['countid']==0){  
    $results1 = $home_new_mod->gethomecategorylistbydef_new(0);
    }else{     
      $results1 =  $home_new_mod->gethomecategorylistbydef_new($custid);
    }
    $recommended_product=$home_new_mod->recommended_product_new();
    $most_popular=$home_new_mod->most_popular_new();
    $getBanner_new=$home_new_mod->getBanner_new(11) ;
    $getCategories_new=$home_new_mod->getCategoriesNew(0);
    $get_latest_promonew=$home_new_mod->get_latest_promonew(1);
    $list_bg = $home_new_mod->get_banggood_products_new(1);
    $data_recomended=array();
    $data_most_mopular=array();
    $data_home_page_category=array();
    foreach ($recommended_product as $products_rec) {
        if($products_rec['type']=='0'){
              $data_recomended[]= $home_new_mod->getproduct_new($products_rec['product_id']);
        }else{
              $data_recomended[]= $home_new_mod->getproduct_bg_new($products_rec['product_id']);
        }
    }
    foreach ($most_popular as $products_mpl) {
        if($products_mpl['type']=='0'){
              $data_most_mopular[]= $home_new_mod->getproduct_new($products_mpl['product_id']);
        }else{
              $data_most_mopular[]= $home_new_mod->getproduct_bg_new($products_mpl['product_id']);
        }
    }
    foreach ($results1 as $categoryhome) {
        $data_home_page_product=array();
        $category_name=$categoryhome['name'];
        $category_id=$categoryhome['cid'];
        $show_limit=$categoryhome['show_limit'];
        $sort_order=$categoryhome['sort_order'];
        $status=$categoryhome['status'];

        if($show_limit==0){
          $show=12;
        }else{
          $show=$show_limit;
        }
        if($status=='1'){
          $results = $home_new_mod->getProductsbycategory_product_new($category_id,$show,$sort_order);
        }else{
          $results = $home_new_mod->getProductsbycategory_new($category_id,$show,$sort_order);
        }
        foreach ($results as $p_id) {
            $data_home_page_product[]= $home_new_mod->getproduct_new($p_id['product_id']);
        }
        $data_home_page_category[] = array(
                'category_name' => $category_name,
                'product' => $data_home_page_product
        );
    }
   /* echo"<pre>";
    print_r($data_home_page_category);*/
?>
<style type="text/css">
.container { width: 100%; }
.speech-bubble-pagesample {
  position: relative;
  background: #0078D7;
  padding: 30px;
  opacity: 0.90;
}
.btn.app-download:hover{
  color: #fff;
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
.store-card {
  margin: 0 10px;
  background-repeat: no-repeat; 
  background-size: cover;
  background-position: center !important;
}

</style>
<div class="wrapper">
    <div class="container_home" >
        <!--  Banner -->  
        <div class="row">
            <div class="col-lg-12 ca-home" >
                 <?php include "carousel_new.php";?>
            </div>     
        </div>
        <!--  Banner end -->  
        <!-- Categories -->
        <div class="row" > 
            <div class="col-lg-12 categoryhomes">
                <h5>Categories</h5>
            </div>  
            <div class="col-lg-12 Most-Popular">
                 <?php include "product_homepage_category_new.php";?>      
            </div>
        </div>
        <!-- Categories End -->
         <!-- get_latest_promonew -->
        <div class="row" > 
            <div class="col-lg-12 categoryhomes">
                <h5>Latest Promo</h5>
            </div>  
            <div class="col-lg-12 Most-Popular">
                <?php include "product_latest_promo_new.php";?>  
            </div>
        </div>
        <!-- get_latest_promonew End -->
        <!--  for Recommended -->
        <div class="row">        
            <div class="col-lg-12 recommended-cat">
              <h5>Recommended for you</h5>
            </div>
            <div class="col-lg-12 recommended-cat">
                <?php include "product_recommended_new.php";?> 
            </div>      
        </div>    
        <!--  for Recommended End--> 
        <!--  for Most Popula--> 
        <div class="row">  
            <div class="col-lg-12 categoryhomes">
              <h5>Most Popular</h5>
            </div>  
            <div class="col-lg-12 Most-Popular">
              <?php include "product_most_popular_new.php";?>       
            </div> 
        </div> 
        <!--  for Most Popula End-->
         <!--  peso_mall and pesoglobal -->
      <?php include "home_page_product_new.php";?> 
      <div class="row">
        <div class="col-xs-12">
          <ul class="nav nav-tabs">
            <li class="active">
              <a data-toggle="tab" href="#pesoglobal">
                  <img src="https://pesoapp.ph/img/mobile/discover/peso-global.webp" style="width: 120px; height: 20px">        
              </a>  
            </li>
          </ul>
          <div class="tab-content">
            <div id="pesoglobal" class="tab-pane fade in active">  
             <?php include "bg_new_product_list.php";?>  
            </div>
           
          </div>
        </div>
      </div>
    </div>
</div>
<?php include "model/customer.php"; ?>
<?php echo (new customer())->insertCustomerView(); ?>

<?php include "common/footer.php"; ?>