<?php
include "common/header.php";
require './composer/vendor/autoload.php';
use Kreait\Firebase\Factory as Factory;
use Kreait\Firebase\DynamicLink\CreateDynamicLink\FailedToCreateDynamicLink;
use Kreait\Firebase\DynamicLink\CreateDynamicLink;
use Kreait\Firebase\DynamicLink\ShortenLongDynamicLink;
use Kreait\Firebase\ServiceAccount;


$id =isset($_SESSION['user_login'])?$_SESSION['user_login']:333;
if(isset($_GET['type'])){
    $type = 'ae';
    $product_detail = $model_product->getAliexpressProductDetails($get_product_id_val);
}
$ppp = $model_product->get_ppp($id);
$design = $model_product->get_design($get_product_id_val);

$product_id=$get_product_id_val;
//$baseurl='http://localhost/peso-web-new/';
$baseurl='https://pesoapp.ph/';
$parameters = [
    'dynamicLinkInfo' => [
        'domainUriPrefix' => 'https://pesoapp.page.link',
        'link' => $baseurl.'product.php?product_id=' . $product_id.'_fb_cust_id_'.$id,
          'androidInfo' => array('androidPackageName'=> 'xyz.appmaker.jkspsq'),
            'iosInfo' => array('iosBundleId' => 'com.ddssi.PinoyESO')
            ],
            'suffix' => ['option' => 'SHORT'],
    ];
$credential = $model_product->getCredentials();
$dynamicLinksDomain = 'https://pesoapp.page.link';
$links = (new Factory())->withServiceAccount($credential)->createDynamicLinksService($dynamicLinksDomain)->createDynamicLink($parameters);
 //$links="http://localhost/peso-web-new/product.php?product_id=6532";
$getdescription=$model_product->getdescription($get_product_id_val);
$decodedesc=html_entity_decode($getdescription['description']);

$getreviewproduct = $model_product->getreview($get_product_id_val);

$getspecification = $model_product->get_attribute($get_product_id_val);
$get_prd_name=utf8_encode($product_detail['name']);
$get_prd_price=number_format($product_detail['price'],2);

include "model/customer.php";
echo (new customer())->insertCustomerView();
?>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <center><h2>Product Page</h2></center>              
            </div>
        </div>  
        <div class="row">
            <div class="col-md-8">
                <?php if(isset($_GET['type'])): ?>
                    <?php if($_GET['type'] == 'ae'): ?>
                        <?php include "product_images_ae.php"?>
                    <?php endif;?>
                <?php else:?>
                <?php $image="img/".$product_detail['image']; ?>
                <?php if ($model_product->getimages($get_product_id_val)) { ?>
                    <?php include "product_images.php";?>
                <?php }else{ ?>
                    <div class="image-container">
                        <img src="<?php echo $image; ?>"  class="img-responsive" />
                    </div>
                <?php }?>
                <?php endif;?>
            </div>
            <div class="col-md-4">
                <div class="container-c">
                    <div class="row">
                        <div class="col-xs-12">
                            <h1><?php echo utf8_encode($product_detail['name']);?></h1>
                            <p>Product Code: <?php echo utf8_encode($product_detail['model']);?></p>
                            <p>Availability: <?php echo $product_detail['quantity'];?></p>
                            <h3>₱<?php echo number_format($product_detail['price'],2);?></h3>
                            <div class="form-group">
                            <label for="">Quantity</label>
                            <input type="number" value="1" class="form-control" readonly></br>
                            <?php if($is_log): ?>
                                <button class="btn btn-primary" id="btn-addtocart" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 18px;"><i data-feather="shopping-cart" ></i>Add to Cart</button></br></br>
                                 <button id="add_wishlist" class="btn btn-primary" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 18px;"><i data-feather="heart"></i> Add to wishlist</button>
                            <?php else: ?>
                              <a type="button" data-toggle="modal" data-target="#LoginModal"style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 18px;" ><i data-feather="shopping-cart"></i> Add to cart</a></br></br>
                             <a type="button" data-toggle="modal" data-target="#LoginModal" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 18px;" ><i data-feather="heart"></i> Add to wishlist</a>                    
                            <?php endif;?></br>
                           
                        </div>
                    </div>
                </div>
                <div class="addthis_toolbox addthis_default_style" >
                    <div class="" >
                        <h3>Recommend to friends to earn <?php if($ppp==0){ echo" Discount Wallet:"; }else{ echo" Cash Wallet:"; }?></h3>
                    </div>
                    <a id ="twitterlink" >
                      <i class="fab fa-twitter" style="background-color: #1b95e0;color: #fff;font-size: 40px; padding: 5px;border-radius: 5px;"></i>
                    </a>
                    <a id ="FBlink">
                      <i class="fab fa-facebook-square" style="background-color:#1b95e0;color:#fff;font-size:40px;padding:5px;border-radius: 5px;"></i>
                    </a>
                    <?php if($is_mobile){ ?>
                        <a id="facebook_messenger">
                            <i class="fab fa-facebook-messenger" style="background-color: #1b95e0;color: #fff;font-size: 40px; padding: 5px;border-radius: 5px;"></i>
                        </a>
                        <a id="viber_share">
                            <i class="fab fa-viber" style="background-color: #834995;color: #fff;font-size: 40px; padding: 5px;border-radius: 5px;">
                            </i>
                        </a>
                     <?php } ?>
                    </a>
                </div>
            </div>
        </div></br></br>
        <div class="">
            <div class="col-xs-12">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#Desctab">Description</a></li>
                    <?php if($design=="0"){?>
                    <li><a data-toggle="tab" href="#Spectab">Specifications</a></li>
                    <?php }?>
                    <li><a data-toggle="tab" href="#Reviewtab">Reviews</a></li>
                </ul>
                <div class="tab-content"> 
                    <div id="Desctab" class="tab-pane fade in active">
                        <div id="descdesc"></div>
                    </div>
                    <div id="Spectab" class="tab-pane fade">
                        <?php if ($getspecification) { ?>
                            <div class="tab-pane" id="tab-specification">
                              <table class="table table-bordered table-responsive">
                                <?php foreach ($getspecification as $attribute_group) { ?>
                                <thead>
                                  <tr>
                                    <td colspan="2"><strong><?php echo $attribute_group['name']; ?></strong></td>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                                  <tr>
                                    <td><?php echo $attribute['name']; ?></td>
                                    <td><?php echo $attribute['text']; ?></td>
                                  </tr>
                                  <?php } ?>
                                </tbody>
                                <?php } ?>
                              </table>
                            </div>
                        <?php } ?>
                    </div>
                    <div id="Reviewtab" class="tab-pane fade">
                        <?php if ($model_product->getreview($get_product_id_val)) { ?>
                            <table class="table" id="inviteestable" class="table table-striped table-bordered table-hover table-responsive">
                                <thead>
                                    <th>Author</th>
                                    <th>Text</th>
                                    <th>Ratings</th>
                                </thead>
                                <tbody>
                                    <?php
                                    
                                    foreach($model_product->getreview($get_product_id_val) as $invitees):?>
                                     <tr>
                                    <td class="text-left" ><?php echo $invitees['author'];?></td>
                                    <td class="text-left" ><?php echo $invitees['text'];?></td>
                                    <td class="text-left" ><?php echo $invitees['rating'];?></td>
                                    </tr>

                                  <?php endforeach;?>
                                </tbody>
                            </table>    
                        <?php } ?>        
                    </div>
                </div>       
            </div>
        </div>
    </div>
    </br></br></br>
    <?php if($design!="0"){?>
    <div class="container">
        <div class="panel panel-default">
            <?php foreach($model_product->getproduct_att($get_product_id_val) as $product_att):?>
                <div class="panel-heading" style="padding:20px;">
                    <div class="row">
                        <div class="col-lg-12">
                            <p  class="panel-title"></i><?php echo  $product_att['title'];?></p>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-xs-6">
                        <div class="image-container">
                            <?php  $getimg ="img/".$product_att['image'];?>
                            <img src="<?php echo $getimg; ?>"  class="img-responsive" />
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                  <tr>
                                    <td colspan="2" class="text-center"><strong><?php echo $product_att['title']; ?></strong></td>
                                  </tr>
                                </thead>
                                 <tbody>
                                     <?php foreach($model_product->getproduct_att_details($product_att['id']) as $product_att_details):?>
                                         <tr>
                                            <td class="text-right"><?php echo $product_att_details['name']; ?>:</td>
                                            <td class="text-left"><?php echo $product_att_details['description']; ?></td>
                                          </tr>
                                     <?php endforeach;?>
                                 </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </div>
    <?php }?> 
</div>




<?php
include "common/footer.php";
?>


<script>
    $(document).ready(function() {
        var desc='<?php echo $decodedesc ?>';
        $('#descdesc').html(desc);
        $("#add_wishlist").click(function(){
            addwishlist();
        });
        $('#twitterlink').on('click', function () {
            var prod_id="<?php echo $get_product_id_val; ?>"
            var cust_id="<?php echo $id; ?>"
            var prod_name="<?php echo $get_prd_name; ?>"
            var url_link="<?php echo $links; ?>"
            var prod_price="<?php echo $get_prd_price; ?>"
           // alert(prod_id);
            if(cust_id!=""){
               PopupForm('ajx_wallet.php?action=addclickFb&prod_id=' + prod_id + '&cust_id=' + cust_id + '&type=Twitter&prod_name=' + prod_name+'&links='+url_link+'&prod_price=' + prod_price);
            }
        });
        $('#FBlink').on('click', function () {
            var prod_id="<?php echo $get_product_id_val; ?>"
            var cust_id="<?php echo $id; ?>"
            var prod_name="<?php echo $get_prd_name; ?>"
            var url_link="<?php echo $links; ?>"
            var prod_price="<?php echo $get_prd_price; ?>"
           // alert(prod_id);
            if(cust_id!=""){
               PopupForm('ajx_wallet.php?action=addclickFb&prod_id=' + prod_id + '&cust_id=' + cust_id + '&type=Facebook&prod_name=' + prod_name+'&links='+url_link+'&prod_price=' + prod_price);
            }
        });
        $('#facebook_messenger').on('click', function () {
            var prod_id="<?php echo $get_product_id_val; ?>"
            var cust_id="<?php echo $id; ?>"
            var prod_name="<?php echo $get_prd_name; ?>"
            var url_link="<?php echo $links; ?>"
            var prod_price="<?php echo $get_prd_price; ?>"
           // alert(prod_id);
            if(cust_id!=""){
               PopupForm('ajx_wallet.php?action=addclickFb&prod_id=' + prod_id + '&cust_id=' + cust_id + '&type=FB Messager&prod_name=' + prod_name+'&links='+url_link+'&prod_price=' + prod_price);
            }
        });
        $('#viber_share').on('click', function () {
            var prod_id="<?php echo $get_product_id_val; ?>"
            var cust_id="<?php echo $id; ?>"
            var prod_name="<?php echo $get_prd_name; ?>"
            var url_link="<?php echo $links; ?>"
            var prod_price="<?php echo $get_prd_price; ?>"
           // alert(prod_id);
            if(cust_id!=""){
               PopupForm('ajx_wallet.php?action=addclickFb&prod_id=' + prod_id + '&cust_id=' + cust_id + '&type=Viber&prod_name=' + prod_name+'&links='+url_link+'&prod_price=' + prod_price);
            }
        });

        $("#btn-addtocart").click(function(){
            var stocks='<?php echo $product_detail['quantity'];?>';
            var product='<?php echo $product_id; ?>';
            var cust_id='<?php echo $id; ?>';
            var name='<?php echo $product_detail['name'];?>';
            var type='<?php echo $type;?>';

            if(stocks<1){
                bootbox.alert("No stocks Available on this product.");
                return false;

            }
            else{
            $.ajax({
            url: 'ajax_add_to_cart.php',
            type: 'POST',
            data: 'product=' + product + '&cust_id=' + cust_id + '&type=' + type,
            dataType: 'json',
            success: function(json) {
             
              if (json['success']) {
                bootbox.alert(json['success']+" ("+name+")");
              }
            },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
          });
        }
         
        });
    });

     function addwishlist(){

        var cust_id ='<?php echo $id ?>';
        var product='<?php echo $product_id; ?>';

        

          $.ajax({
            url: 'ajax_add_to_wishlist.php',
            type: 'POST',
            data: 'product=' + product + '&cust_id=' + cust_id,
            dataType: 'json',
            success: function(json) {
             
              if (json['success']) {
                bootbox.alert(json['success']);
              }
            },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
          });
    }
    function PopupForm(url, title, w = window.screen.width * window.devicePixelRatio, h = window.screen.height * window.devicePixelRatio) {
            // Fixes dual-screen position                         Most browsers      Firefox
            var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : window.screenX;
            var dualScreenTop = window.screenTop != undefined ? window.screenTop : window.screenY;

            var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
            var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

            var left = ((width / 2) - (w / 2)) + dualScreenLeft;
            var top = ((height / 2) - (h / 2)) + dualScreenTop;
            var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

            // Puts focus on the new Window
            if (window.focus) {
                newWindow.focus();
            }
  }
 </script>