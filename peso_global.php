<?php
include "common/header.php";
require_once 'model/home_new.php';
$home_new_mod=new home_new();
$custid = isset($_SESSION['user_login']) ? $_SESSION['user_login']: 0; 
$getBanner_new=$home_new_mod->getBanner_new(11) ;


$category_id =isset($_GET['cat_id'])?$_GET['cat_id']:0;
if(isset($_GET['cat_id'])){
  $bg_product_by_cat=$home_new_mod->getbg_product_by_cat_new($category_id);
}
if(isset($_GET['active'])){
    if($_GET['active']=="1"){
        $active=1;
    }else{
      $active=$_GET['active'];
    }
}else{
  $active=uniqid();
}

?>
<style type="text/css"> 
  #div_card_bg {
    margin: auto;
    text-align: center;
    font-family: arial
    font-size: 6px;
    box-shadow: 0 0.0625rem 0.125rem 0 rgba(0,0,0,.6);
    border-radius:10px;background: #fff;
  }
  #div_card_bg:hover { border: 1px solid #777;}

.container { 
  width: 100%; 
}
.dropdown-submenu {
  position: relative;
}
.dropdown-submenu>.dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: -6px;
    margin-left: -1px;
    -webkit-border-radius: 0 6px 6px 6px;
    -moz-border-radius: 0 6px 6px;
    border-radius: 0 6px 6px 6px;
}
.dropdown-submenu:hover>.dropdown-menu {
    display: block;
}
.dropdown-toggle:hover>{
    display: block;
}
.dropdown-submenu:hover>a:after {
    display: block;
    content: " ";
    float: right;
    width: 0;
    height: 0;
    border-color: transparent;
    border-style: solid;
    border-width: 5px 0 5px 5px;
    border-left-color: #ccc;
    margin-top: 5px;
    margin-right: -10px;
}
.dropdown-submenu:hover>a:after {
    border-left-color: #fff;
}
.dropdown-submenu.pull-left {
    float: none;
}
.dropdown-submenu.pull-left>.dropdown-menu {
    left: -100%;
    margin-left: 10px;
    -webkit-border-radius: 6px 0 6px 6px;
    -moz-border-radius: 6px 0 6px 6px;
    border-radius: 6px 0 6px 6px;
}
@media (max-width: 575.98px){
  #categorydiv{
    margin-top: 58px;
    margin:0px;padding:0px;
  }
}
@media (min-width: 576px){
  #categorydiv{
    margin:0px;padding:0px;
    margin-top: 58px;
    margin-left:170px;
  }
}
</style>
<?php if($active!="1"){ ?>
  <div class="wrapper">
    <div class="container_home" >
      <div class="row">
        <div class="col-lg-12 ca-home" >
          <?php include "carousel_new.php";?>
        </div>     
      </div>
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
                <center><h1>Under maintenance..</h1></center>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php }else{?>
  <div class="wrapper">
  <div class="container_home" >
    <div class="row">
     <div class="col-lg-12 ca-home" >
        <?php include "carousel_new.php";?>
      </div>     
    </div>

     <!-- Categories -->
      <div class="row" > 
          <div class="col-lg-12 categoryhomes">
             <div  id="categorydiv2" >
                <div class="dropdown">
                    <a class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" title="See More" aria-expanded="false" style="border: none ;float:left;border-radius: 50px;"> 
                    <i class="fas fa-list" style="float:left; font-size: 25px;color: black;"></i>
                  </a>
                  <ul class="dropdown-menu multi-level" id="ul_menu_data" role="menu" aria-labelledby="dropdownMenu" style="position: absolute;top: 37px;">
                     <?php $first_cat=$home_new_mod->getCategories_global_1st(0); 
                          foreach ($first_cat as  $fcat) { ?>
                      <li class="dropdown-submenu fist_li" data-cat_id="<?php echo $fcat['cat_id']?>">
                        <a href="peso_global.php?active=0&cat_id=<?php echo $fcat['cat_id']?>"><small><?php echo $fcat['cat_name']?></small></a>
                        <ul class="dropdown-menu secondcat" id="secondcat">
                          <?php foreach ($fcat['label'] as  $fcat2) { ?>
                             <li class="dropdown-submenu second_li" data-cat_id="<?php echo $fcat2['categoryId']?>">
                               <a href="peso_global.php?active=0&cat_id=<?php echo $fcat2['categoryId']?>"><small><?php echo $fcat2['name']?></small></a>
                               <?php if(count($fcat2['items'])!=0){ ?>
                                   <ul class="dropdown-menu thirdcat" id="thirdcat">
                                       <?php foreach ($fcat2['items'] as  $fcat3) { ?>
                                           <li class="dropdown-submenu thirdcat" data-cat_id="<?php echo $fcat3['categoryId']?>">
                                             <a href="peso_global.php?active=0&cat_id=<?php echo $fcat3['categoryId']?>"><small><?php echo $fcat3['name']?></small></a>
                                           </li>
                                       <?php } ?>
                                   </ul>
                               <?php }?>
                             </li>
                          <?php } ?>
                        </ul>
                      </li>
                    <?php } ?>
                  </ul>
                </div>
             </div>
              <h5>Choose Categories</h5>
          </div>  
          <div class="col-lg-12 Most-Popular">
            <?php 
            include "product_category_global.php";?>    
          </div>
      </div>
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
            <?php  if($category_id==0){ 
              $list_bg = $home_new_mod->get_banggood_products_new(1);
              include "bg_new_product_list.php"; }else{?>
                <?php foreach ($bg_product_by_cat as $p) { 
                    $p_name=utf8_encode($p['name']);
                    $name = strlen($p_name) > 25 ? substr($p_name,0,25)."..." : $p_name; ?>
                    <?php if($is_mobile){ ?>
                      <div class="col-xs-6" style="padding:2px;">
                    <?php }else{ ?>  
                      <div class="col-xs-6 col-md-3 col-lg-2" style="padding:5px;">
                    <?php }?>
                        <div class="card" id="div_card_bg">
                          <div class="product-thumb transition">
                            <div class="image-container">
                              <div data-toggle="tooltip" title="Click for more details" class="image">
                                <a  href="<?php echo $p['href']; ?>">
                                <?php  $getimg =$p['image']; ?>
                                  <img src="<?php echo $getimg; ?>" alt="<?php echo $p['name']; ?>" class="img-responsive" />
                                </a>                    
                              </div>
                            </div>
                            <div class="caption" style="text-align:center;">
                              <h6 ><a data-toggle="tooltip" title="<?php echo utf8_encode($p['name']); ?>" href="<?php echo $p['href']; ?>"><?php echo $name; ?></a></h6>                             
                            </div>
                            <div class="price" style="text-align:center;">  
                              <?php $bg_price_ragemin= $p['price']-50;
                                if($bg_price_ragemin < 0){
                                  $bg_price_ragemin=$bg_price_php;
                                }
                                 $bg_price_ragemax= $$p['price']*1.25; ?>                            
                                
                                  <p  style="color:#e81b30" ><b>₱<?php echo   number_format($bg_price_ragemin,2);?> - ₱<?php echo   number_format($bg_price_ragemax,2);?></b></p>
                            </div>
                            <div>
                              <?php if($is_log){ ?>
                                  <a type="button"class="btn btn-pink btn_addtocart_bg" href="<?php echo $p['href']; ?>"  style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i data-feather="shopping-cart"></i> Add to cart</a> 
                              <?php }else{ ?>
                                  <a type="button" data-toggle="modal" data-target="#LoginModal"  style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i data-feather="shopping-cart" ></i> Add to cart</a>
                              <?php } ?>
                            </div>
                          </div>
                        </div>
                      </div> 
                <?php }?> 
            <?php }?> 
          </div>
        </div>
      </div>
  </div>
</div>
 <?php }?>
<?php include "model/customer.php"; ?>
<?php echo (new customer())->insertCustomerView(); ?>
<?php
include "common/footer.php";
?>

<!-- <script type="text/javascript">
   var first_cat= <?php echo json_encode($first_cat); ?>;
      console.log(first_cat);
   $(document).ready(function() {
    

      $(".fist_li").hover(function(){
          var cat_idsecond=$(this).data('cat_id')
          $('#secondcat_'+cat_idsecond).empty();
          $.ajax({
              url:'ajx_wallet.php?action=getcategorybg',
              type: 'post', 
              data: 'catecory_id=' + cat_idsecond,
              dataType: 'json',
              success: function(json) {
                 for (var i = 0; i < json.length; i++) {

                      $.ajax({
                          url:'ajx_wallet.php?action=getcategorybgsecond',
                          type: 'post', 
                          data: 'catecory_id=' + json[i]['cat_id'] + '&cat_name=' + json[i]['cat_name'] + '&cat_idsecond=' + cat_idsecond,
                          dataType: 'json',
                          success: function(json2) {
                              var second_cat_data = "";
                              if(json2['cat'].length!=0){
                                  second_cat_data = second_cat_data + '<li class="dropdown-submenu second_li"  data-cat_id="'+json2['cat_id']+'">';
                                  second_cat_data = second_cat_data + '<a href="peso_global.php?cat_id='+json2['cat_id']+'"><small>'+json2['catname']+'</small></a>';
                                  second_cat_data = second_cat_data + '<ul class="dropdown-menu secondcat" id="thridcat_'+json2['cat_id']+'">';
                                  for (var y = 0; y < json2['cat'].length; y++) {
                                    second_cat_data = second_cat_data +'<li class="dropdown-item">';
                                    second_cat_data = second_cat_data +'<a href="peso_global.php?cat_id='+json2['cat'][y]['cat_id']+'"><small>'+json2['cat'][y]['cat_name']+'</small></a>';
                                    second_cat_data = second_cat_data +'</li>';
                                  }
                                  second_cat_data = second_cat_data + '</ul>';
                                  second_cat_data = second_cat_data + '</li>'
                              }else{
                                second_cat_data = second_cat_data +' <li class="dropdown-item">';
                                second_cat_data = second_cat_data +'<a href="peso_global.php?cat_id='+json2['cat_id']+'"><small>'+json2['catname']+'</small></a>';
                                second_cat_data = second_cat_data +'</li>';
                              }
                              $('#secondcat_'+json2['cat_idsecond']).append(second_cat_data);
                          },
                          error: function(xhr, ajaxOptions, thrownError) {
                              alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                          }
                      });
                 }
              },
              error: function(xhr, ajaxOptions, thrownError) {
                  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
              }
          });
      });
   });
</script> -->