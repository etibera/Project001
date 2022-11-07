<?php
include "common/header.php";
//include "common/headertest.php";
require './composer/vendor/autoload.php';
use Kreait\Firebase\Factory as Factory;
use Kreait\Firebase\DynamicLink\CreateDynamicLink\FailedToCreateDynamicLink;
use Kreait\Firebase\DynamicLink\CreateDynamicLink;
use Kreait\Firebase\DynamicLink\ShortenLongDynamicLink;
use Kreait\Firebase\ServiceAccount;

require_once 'model/home_new.php'; 
$home_new_mod=new home_new();

$dataBranch_idval=0;
$id =isset($_SESSION['user_login'])?$_SESSION['user_login']:333;
$customer_id =isset($_SESSION['user_login'])?$_SESSION['user_login']:0;
$product_detail = $model_product->getproductdetailsNew($get_product_id_val);
$ppp = $model_product->get_ppp($id);
$design = $model_product->get_design($get_product_id_val);
$model_product->save_view_products($get_product_id_val,$customer_id,0);
$product_id=$get_product_id_val;
if(isset($_GET['store_id'])){    
    $get_store_list=$home_new_mod->get_store_list_bystoreNew($get_product_id_val,$_GET['store_id']);    
    if(count( $get_store_list)!=0){
        $dataBranch_idval= $get_store_list[0]['branch_id'];
    }else{
        $dataBranch_idval=0;
    }
}else{
    $get_store_list=$home_new_mod->get_store_list_new($get_product_id_val);
     $dataBranch_idval=0;
}
/*echo"<pre>";
print_r($get_store_list);*/
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
//$links = "";
$links = (new Factory())->withServiceAccount($credential)->createDynamicLinksService($dynamicLinksDomain)->createDynamicLink($parameters);
 //$links="http://localhost/peso-web-new/product.php?product_id=6532";
$getdescription=$model_product->getdescription($get_product_id_val);
$decodedesc=html_entity_decode($getdescription['description']);

$getreviewproduct = $model_product->getreview($get_product_id_val);

$getspecification = $model_product->get_attribute($get_product_id_val);
$get_prd_name=str_replace(array("'", "\"", "&quot;"), "", htmlspecialchars(utf8_encode($product_detail['name'])));
$get_prd_price=number_format($product_detail['price'],2);

include "model/customer.php";
echo (new customer())->insertCustomerView();

/*require_once 'model/home_new.php'; 
$home_new_mod=new home_new();
*/
?>
<style type="text/css">
.caption-option {
  height:35px; 
 }
</style>
<script>
    fbq('track', 'ContentView');
</script>
<script type="application/ld+json">
    {
        "@context":"https://schema.org",
        "@type":"Product",
        "productID":"<?php echo $get_product_id_val ?>",
        "name":"<?php echo utf8_encode($product_detail['name']);?>",
        "description":"<?php echo utf8_encode($product_detail['name']);?>",
        "url":"https://pesoapp.ph/product.php?product_id=<?php echo $get_product_id_val; ?>",
        "image":"https://pesoapp.ph/img/<?php echo $product_detail['image']; ?>",
        "brand":"",
        "offers":[{
            "@type":"Offer",
            "price":"<?php 
                                    if(isset($_GET['store_id'])){
                                        if(isset($get_store_list[0]['deduction_data'])){ 
                                            if(count($get_store_list[0]['deduction_data'])!=0){
                                                if($get_store_list[0]['deduction_data'][0]['deduction_type']=="1"){
                                                    echo number_format($product_detail['price']-$get_store_list[0]['deduction_data'][0]['rate'],2);
                                                }else if($get_store_list[0]['deduction_data'][0]['deduction_type']=="0"){
                                                    $deductval=$product_detail['price']*$get_store_list[0]['deduction_data'][0]['rate'];
                                                    echo number_format($product_detail['price']-$deductval,2);
                                                }else{
                                                    echo number_format($product_detail['price'],2);
                                                }
                                            }else{
                                                echo number_format($product_detail['price'],2);
                                            }
                                        }else{
                                            echo number_format($product_detail['price'],2);
                                        }
                                        
                                    }else{
                                        echo number_format($product_detail['price'],2);
                                    }
                                ?>",
            "priceCurrency":"PHP",
            "itemCondition":"https://schema.org/NewCondition",
            "availability":"https://schema.org/InStock"}],
        "additionalProperty":[{"@type":"",
            "propertyID":"",
            "value":""}]
    }
</script>
<div class="wrapper" style="overflow: hidden;">
    <div class="container_prd">       
       
        <div class="panel panel-default" style="margin-top: 95px;margin-right:5px; margin-left: 5px;">
            <div class="row">       
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-xs-12">
                            <?php $imgvl=$product_detail['thumb']; ?>
                            <?php if ($model_product->getimages3($get_product_id_val)) { ?>
                                <?php include "product_images.php";?>
                            <?php }else{ ?>
                                <div class="image-container">
                                    <img src="<?php echo $imgvl; ?>"  class="img-responsive" />
                                </div>
                            <?php }?>
                        </div>
                    </div> 
                    <div class="row">
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
                                    <div id="descdesc"> <?php echo $decodedesc;?></div>
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
                <div class="col-md-8">
                    <div class="row"> 
                        <div class="col-xs-12">
                            <h2><?php echo utf8_encode($product_detail['name']);?></h2>
                            <p>Model: <?php echo utf8_encode($product_detail['model']);?></p> 
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <h4>
                                <p id="availability_ps">
                                <?php 
                                    if(isset($_GET['store_id'])){ 
                                        echo "Availability: ".$get_store_list[0]['qty'];
                                    }
                                ?>
                                </p>
                            </h4>
                            <?php 
                                $priceDisplayComingSoon="";
                                $priceDisplay="";
                                 if(isset($_GET['store_id'])){
                                    if(isset($get_store_list[0]['deduction_data'])){
                                        if(count($get_store_list[0]['deduction_data'])!=0){
                                            if($get_store_list[0]['deduction_data'][0]['commingsoon']=="0"){ 
                                                $priceDisplayComingSoon="block";
                                                $priceDisplay="none";
                                            }else{
                                                $priceDisplayComingSoon="block";
                                                $priceDisplay="none";
                                            }  
                                        }else{
                                            $priceDisplayComingSoon="none";
                                            $priceDisplay="block";
                                        }
                                    }else{
                                        $priceDisplayComingSoon="none";
                                        $priceDisplay="block";
                                    }
                                    
                                 }else{
                                     $priceDisplayComingSoon="none";
                                     $priceDisplay="block";
                                 }
                            ?>
                            <h3 id="price_id" style="display:<?php echo $priceDisplay;?>;">₱
                                <?php 
                                if(isset($_GET['store_id'])){
                                    if(isset($get_store_list[0]['deduction_data'])){
                                        if(count($get_store_list[0]['deduction_data'])!=0){
                                            if($get_store_list[0]['deduction_data'][0]['commingsoon']=="0"){
                                                if($get_store_list[0]['deduction_data'][0]['deduction_type']=="1"){
                                                    echo number_format($product_detail['price']-$get_store_list[0]['deduction_data'][0]['rate'],2);
                                                }else if($get_store_list[0]['deduction_data'][0]['deduction_type']=="0"){
                                                    $deductval=$product_detail['price']*$get_store_list[0]['deduction_data'][0]['rate'];
                                                    echo number_format($product_detail['price']-$deductval,2);
                                                }else{
                                                    echo number_format($product_detail['price'],2);
                                                }
                                            }else{
                                                echo number_format($product_detail['price'],2);
                                            }                                            
                                        }else{
                                            echo number_format($product_detail['price'],2);
                                        }
                                    }else{
                                        echo number_format($product_detail['price'],2);
                                    }
                                    
                                }else{
                                    echo number_format($product_detail['price'],2);
                                }
                                ?>
                            </h3>
                            <h3 id="priceComingSoon" style="display:<?php echo $priceDisplayComingSoon;?>;">
                                 ₱<?php 
                                    if(isset($_GET['store_id'])){
                                        if(isset($get_store_list[0]['deduction_data'])){ 
                                            if(count($get_store_list[0]['deduction_data'])!=0){
                                                if($get_store_list[0]['deduction_data'][0]['deduction_type']=="1"){
                                                    echo number_format($product_detail['price']-$get_store_list[0]['deduction_data'][0]['rate'],2);
                                                }else if($get_store_list[0]['deduction_data'][0]['deduction_type']=="0"){
                                                    $deductval=$product_detail['price']*$get_store_list[0]['deduction_data'][0]['rate'];
                                                    echo number_format($product_detail['price']-$deductval,2);
                                                }else{
                                                    echo number_format($product_detail['price'],2);
                                                }                                 
                                            }else{
                                                
                                            }
                                        }else{  
                                            echo number_format($product_detail['price'],2);
                                        }
                                        
                                    }else{
                                        echo number_format($product_detail['price'],2);
                                    }
                                ?>
                            </h3>
                        </div>
                        <div class="col-xs-6" style="margin-top: 50px">
                            <div class="container-c">
                                 <?php if($is_log): ?>
                                    <div class="row">
                                        <?php  if(isset($_GET['store_id'])){ ?>
                                            <div class="col-xs-4">
                                                 <button class="btn btn-danger" id="btn-addtocart" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"<?php if($get_store_list[0]['qty']==0){ echo "disabled"; }?>><img src="assets/CART.png"> Add to Cart</button>
                                            </div>
                                            <div class="col-xs-4">
                                                <button class="btn btn-primary" id="btn-buynow" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"<?php if($get_store_list[0]['qty']==0){ echo "disabled"; }?>><img src="assets/CART.png"> Buy Now</button>
                                            </div>
                                        <?php  }else{ ?>
                                            <div class="col-xs-4">
                                                <button class="btn btn-danger" id="btn-addtocart" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><img src="assets/CART.png" > Add to Cart</button>
                                            </div>
                                            <div class="col-xs-4">
                                                <button class="btn btn-primary" id="btn-buynow" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><img src="assets/CART.png"> Buy Now </button>
                                            </div>
                                         <?php  } ?>  
                                        <div class="col-xs-4">
                                             <button id="add_wishlist" class="btn btn-primary" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i class="far fa-heart" style="font-size: 27px;"></i>Add to wishlist</button>
                                        </div>                           
                                    </div>
                                   
                                 <?php else: ?>
                                    <div class="row">
                                        <div class="col-xs-4"> 
                                             <button class="btn btn-danger"  data-toggle="modal" data-target="#LoginModal" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;">
                                                 <img src="assets/CART.png" > Add to Cart</button>
                                        </div>                                
                                         <div class="col-xs-4"> 
                                            <button class="btn btn-primary"  data-toggle="modal" data-target="#LoginModal" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><img src="assets/CART.png" ></i> Buy Now</button> 
                                        </div>
                                        <div class="col-xs-4"> 
                                            <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#LoginModal"style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 10px;" ><i class="far fa-heart" style="font-size: 27px;"></i> Add to wishlist</a>
                                         </div>
                                    </div>
                                 <?php endif;?>
                            </div>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-xs-12" id="price_discount">
                             <?php  if(isset($_GET['store_id'])){ ?>
                                <?php if(isset($get_store_list[0]['deduction_data'])){ ?>
                                    <?php  if(count($get_store_list[0]['deduction_data'])!=0){ ?>
                                        <p style="text-decoration: line-through; display: inline-block;color: #9e9e9e;">
                                            ₱<?php echo number_format($product_detail['price'],2);?>
                                        </p>
                                        <?php if($get_store_list[0]['deduction_data'][0]['deduction_type']=="0"){ ?>
                                          <span style="color: red;">
                                            <?php echo number_format($get_store_list[0]['deduction_data'][0]['value']) ?>% OFF
                                         </span></br>
                                         <span style="color: red;">
                                             From : <?php echo $get_store_list[0]['deduction_data'][0]['date_f'];?> To: <?php echo $get_store_list[0]['deduction_data'][0]['date_t'];?> <?php ?>
                                         </span></br></br>
                                        <?php }else if($get_store_list[0]['deduction_data'][0]['deduction_type']=="1"){ ?>
                                            <span style="color: red;">
                                                ₱<?php echo number_format($get_store_list[0]['deduction_data'][0]['value'],2); ?> OFF
                                             </span></br>
                                             <i style="color: red;">From : <?php echo $get_store_list[0]['deduction_data'][0]['date_f'];?> To: <?php echo $get_store_list[0]['deduction_data'][0]['date_t'];?> 
                                             </i></br></br>

                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>  
                        </div>
                    </div>
                    <?php if($get_store_list) { ?> 
                    <div class="row">
                         <div class="col-xs-12">                        
                            <div class="form-group" style="  border: .5px solid rgba(0,0,0,0.125);padding: 5px;color: #FFF; border-radius: 8px;" >
                                <label data-toggle="collapse"  style="font-size: 17px;vertical-align: middle; color: black;">
                                    Select Store
                                </label>
                                <div class="table-responsive" style="height: 350px;overflow: auto;margin-left: 5px;  margin-right:5px;">
                                    <table class="table table-striped table-bordered table-hover" id="tbl_deductlist" style="background: #fff;font-size: 10px;">
                                        <thead style="font-size: 14px;">
                                            <th class="text-center" colspan="2" >Store Name</th>
                                            <th class="text-center" >Message</th>
                                            <th class="text-center" style="width: 300px">Promo/Discount</th>
                                            <th class="text-center" style="width: 150px">Freebies</th>
                                        </thead>
                                        <tbody >
                                             <?php foreach ($get_store_list as  $sl) {?>
                                                <?php  
                                                    $getimg =$sl['thumb'];
                                                    if($sl['deduction_data']){
                                                        $d_type=$sl['deduction_data'][0]['deduction_type'];
                                                        $rate=$sl['deduction_data'][0]['rate'];
                                                        $value=$sl['deduction_data'][0]['value'];
                                                        $commingsoondata=$sl['deduction_data'][0]['commingsoon'];
                                                    }else{
                                                        $d_type=0;
                                                        $rate=0;
                                                        $value=0;
                                                        $commingsoondata=0;
                                                    }
                                                ?> 
                                                <tr>
                                                    <td  style="vertical-align: middle;" onclick="CheckStore(<?php echo $sl['seller_id'];?>,<?php echo $sl['branch_id'];?>,<?php echo $product_detail['price'];?>,<?php echo $sl['qty'];?>)">
                                                        <div id="sl-div_<?php echo $sl['branch_id'];?>" onclick="CheckStore(<?php echo $sl['seller_id'];?>,<?php echo $sl['branch_id'];?>,<?php echo $product_detail['price'];?>,<?php echo $sl['qty'];?>)">
                                                            <input type="radio" name="rd_sl_id" value="<?php echo $sl['seller_id'];?>" <?php if(isset($_GET['store_id'])){echo "checked";}?> />
                                                        </div>
                                                    </td>
                                                    <td onclick="CheckStore(<?php echo $sl['seller_id'];?>,<?php echo $sl['branch_id'];?>,<?php echo $product_detail['price'];?>,<?php echo $sl['qty'];?>)" style="width: 300px;vertical-align: middle;">
                                                        <?php if($getimg!=""): ?> 
                                                         <img src="<?php echo $getimg; ?>" alt="<?php echo $sl['shop_name']; ?>" class="img-responsive" />
                                                         <?php else: ?>
                                                            <p class="no-image"><i class="fa fa-shopping-bag"></i></p>
                                                        <?php endif; ?> 
                                                        <?php echo $sl['shop_name']; ?> 
                                                    </td>
                                                    <td style="vertical-align: middle;">
                                                                <?php if ($is_log) { ?>
                                                        <a  href="message.php?Y2F0X2lk=<?php echo $sl['seller_id'];?>&prdval=<?php echo $get_product_id_val;?>&branch_id=<?php echo $sl['branch_id'];?>" target="_blank" class="btn btn-warning notification" title="messages">
                                                            <!-- <i style="color:  white;" data-feather="message-square"> -->
                                                             <img src="assets/SMS_2.png" class="img-responsive">
                                                            </i></a>
                                                                 <?php }else{ ?> 
                                                        <a data-toggle="modal" data-target="#LoginModal" class="btn btn-warning notification" title="messages">
                                                            <!-- <i style="color:  white;" data-feather="message-square"></i> -->
                                                            <img src="assets/SMS_2.png" class="img-responsive">
                                                        </a>
                                                                <?php }?> 
                                                    </td>
                                                    <td style="vertical-align: middle;" >
                                                        <div class="caption-option" >
                                                            <input type="hidden" name="poa_id_list_id_<?php echo $sl['seller_id'];?>" id="poa_id_list_id_<?php echo $sl['seller_id'];?>" value="<?php if(isset($sl['deduction_data'][0]['id'])){ echo $sl['deduction_data'][0]['id'];}else{echo 0;}?>"  class="form-control" />  
                                                        <!-- for Promo / Discount -->
                                                            <?php if($sl['deduction_data']){?>
                                                                <select  style="font-size: 10px;" class="form-control poa_id_list_<?php echo $sl['seller_id'];?>" name="<?php echo $sl['shop_name'];?>" id="deductOPT_<?php echo $sl['branch_id'];?>" onChange="update_price(this,<?php echo $sl['seller_id'];?>,<?php echo $sl['branch_id'];?>,<?php echo $product_detail['price'];?>)">
                                                                    <?php foreach($sl['deduction_data'] as $ded_data){ ?>
                                                                        <option value='<?php echo $ded_data['deduction_type'];?>' 
                                                                            data-rate='<?php echo $ded_data['rate'];?>'
                                                                            data-id='<?php echo $ded_data['id'];?>'
                                                                            data-description='<?php echo $ded_data['description'];?>'
                                                                            data-date_f='<?php echo $ded_data['date_f'];?>'
                                                                            data-date_t='<?php echo $ded_data['date_t'];?>'
                                                                            data-commingsoon='<?php echo $ded_data['commingsoon'];?>'
                                                                            data-date_from='<?php echo $ded_data['date_from'];?>'
                                                                            data-date_to='<?php echo $ded_data['date_to'];?>'
                                                                            data-deduction_type='<?php echo $ded_data['deduction_type'];?>'
                                                                            data-dvalue='<?php echo $ded_data['value'];?>'
                                                                            >
                                                                            <?php echo $ded_data['description'];?>
                                                                        </option>
                                                                    <?php } ?>                                                
                                                                </select>                                           
                                                            <?php }?>
                                                        <!-- end for Promo / Discount -->
                                                        </div>
                                                    </td>
                                                    <td style="vertical-align: middle;"><p id="freebies_<?php echo $sl['seller_id'];?>"><?php echo $sl['freebies'];?><p></td>
                                                </tr>
                                             <?php } ?>  
                                        </tbody>
                                    </table>
                                </div>
                            </div>                         
                        </div>
                    </div>
                    <?php } ?>
                    
                    <!-- <div class="row"> 
                        <div class="col-xs-12">
                            <label for="">Quantity</label>
                            <input type="number" value="1"  readonly></br>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-xs-12">
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
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<?php
include "common/footer.php";
?>


<script>
    var branch_idval='<?php echo $dataBranch_idval;?>';
function update_price(poa_id,pid,branch_id,price) {
    branch_idval=branch_id;
     $('#price_discount').empty();
    var poa_id_val = poa_id.options[poa_id.selectedIndex].value 
    var poa_id_text = poa_id.options[poa_id.selectedIndex].text 
    var res = poa_id_val.split("-");
    var dtype=poa_id_val
    var rate=poa_id.options[poa_id.selectedIndex].dataset.rate; 
    var commingsoon=poa_id.options[poa_id.selectedIndex].dataset.commingsoon; 
    var dvalue=poa_id.options[poa_id.selectedIndex].dataset.dvalue; 
    var date_f=poa_id.options[poa_id.selectedIndex].dataset.date_f; 
    var date_t=poa_id.options[poa_id.selectedIndex].dataset.date_t; 
    var duration= " From : "+date_f+" To: "+ date_t;
    var section_data="";
    section_data+='<p style="text-decoration: line-through; display: inline-block;color: #9e9e9e;">';
    section_data+= "₱"+numberWithCommas(parseFloat(price).toFixed(2));
    section_data += '</p>';
    //alert(date_t);
   $("#sl-div_"+branch_idval).find('input[type=radio]').each(function () { 
           this.checked = true;
    });
   if(commingsoon==0){  
    $("#price_id").css("display","block");
    $("#priceComingSoon").css("display","none")  ; 
        if(dtype==0&&rate!=0){
            var deduction=rate*price;
            var newprice=price-deduction;
            $("#price_id").text("₱"+numberWithCommas(parseFloat(newprice).toFixed(2))); 
             section_data +='<span style="color: red;"> ';
             section_data += numberWithCommas(parseFloat(dvalue).toFixed(0));
             section_data +='% OFF';
             section_data +='</span></br>';
             section_data +='<span style="color: red;"> ';
             section_data += '<i>'+duration+'</i>';
             section_data +='</span></br></br>';
             $('#price_discount').append(section_data);
             
        }else if(dtype==1&&rate!=0){
            var newprice2=price-rate;
            $("#price_id").text("₱"+numberWithCommas(parseFloat(newprice2).toFixed(2))); 
             section_data +='<span style="color: red;"> ';
             section_data += "₱"+numberWithCommas(parseFloat(dvalue).toFixed(2));
             section_data +=' OFF';
             section_data +='</span></br>';
             section_data +='<span style="color: red;"> ';
             section_data += '<i>'+duration+'</i>';
             section_data +='</span></br></br>';
             $('#price_discount').append(section_data);
        }else{
            $("#price_id").text("₱"+numberWithCommas(parseFloat(price).toFixed(2)));
        }
        
   }else{
    $("#price_id").css("display","none");
    $("#priceComingSoon").css("display","block")  ; 
        if(dtype==0&&rate!=0){
            var deduction=rate*price;
            var newprice=price-deduction;
            $("#priceComingSoon").text("₱"+numberWithCommas(parseFloat(newprice).toFixed(2))); 
             section_data +='<span style="color: red;"> ';
             section_data += numberWithCommas(parseFloat(dvalue).toFixed(0));
             section_data +='% OFF';
             section_data +='</span></br>';
             section_data +='<span style="color: red;"> ';
             section_data += '<i>'+duration+'</i>';
             section_data +='</span></br></br>';
             $('#price_discount').append(section_data);
             
        }else if(dtype==1&&rate!=0){
            var newprice2=price-rate;
            $("#price_id").text("₱"+numberWithCommas(parseFloat(newprice2).toFixed(2))); 
             section_data +='<span style="color: red;"> ';
             section_data += "₱"+numberWithCommas(parseFloat(dvalue).toFixed(2));
             section_data +=' OFF';
             section_data +='</span></br>';
             section_data +='<span style="color: red;"> ';
             section_data += '<i>'+duration+'</i>';
             section_data +='</span></br></br>';
             $('#priceComingSoon').append(section_data);
        }else{
            $("#priceComingSoon").text("₱"+numberWithCommas(parseFloat(price).toFixed(2)));
        }
        $("#price_id").text("₱"+numberWithCommas(parseFloat(price).toFixed(2)));        
   }
   
    
}
function CheckStore(pid,branch_id,price,qty) {
    $('#price_discount').empty();
     branch_idval=branch_id;
    var selecteddata = $('#deductOPT_'+branch_idval).find('option:selected');
    var commingsoonval = selecteddata.data('commingsoon');
    var rateval = selecteddata.data('rate'); 
    var dtypeval = selecteddata.data('deduction_type'); 
    var dvalue = selecteddata.data('dvalue'); 
    var date_f = selecteddata.data('date_f'); 
    var date_t = selecteddata.data('date_t'); 
    var duration= " From : "+date_f+" To: "+ date_t;
    var section_data="";
    section_data+='<p style="text-decoration: line-through; display: inline-block;color: #9e9e9e;">';
    section_data+= "₱"+numberWithCommas(parseFloat(price).toFixed(2));
    section_data += '</p>';
      //alert(rateval);
   //var commingsoon=0;
   $("#sl-div_"+branch_idval).find('input[type=radio]').each(function () {        
        if(this.checked==true){
           this.checked = false;
            if(commingsoonval==0){
                $("#price_id").css("display","block");
                $("#priceComingSoon").css("display","none")  ; 
                $("#price_id").text("₱"+numberWithCommas(parseFloat(price).toFixed(2)));
            }else{
                $("#price_id").css("display","none");
                $("#priceComingSoon").css("display","block")  ; 
                $("#priceComingSoon").text("₱"+numberWithCommas(parseFloat(price).toFixed(2)));
            }
            $("#availability_ps").text("Availability: "+0);
            $('#btn-addtocart').prop('disabled', true);
            $('#btn-buynow').prop('disabled', true);
         }else{
            $("#availability_ps").text("Availability: "+qty);
            if(qty!=0){ $('#btn-addtocart').prop('disabled', false);}else{ $('#btn-addtocart').prop('disabled', true);}
            if(qty!=0){ $('#btn-buynow').prop('disabled', false);}else{ $('#btn-buynow').prop('disabled', true);}
            this.checked = true;
            if(commingsoonval==0){
                $("#price_id").css("display","block");
                $("#priceComingSoon").css("display","none")  ;                  
                if(dtypeval==0&&rateval!=0){
                    var deduction=rateval*price;
                    var newprice=price-deduction;
                    $("#price_id").text("₱"+numberWithCommas(parseFloat(newprice).toFixed(2)));  
                    section_data +='<span style="color: red;"> ';
                    section_data += numberWithCommas(parseFloat(dvalue).toFixed(0));
                    section_data +='% OFF';
                    section_data +='</span></br>';
                    section_data +='<span style="color: red;"> ';
                    section_data += '<i>'+duration+'</i>';
                    section_data +='</span></br></br>';
                    $('#price_discount').append(section_data);
                }else if(dtypeval==1&&rateval!=0){
                    var newprice2=price-rateval;
                    $("#price_id").text("₱"+numberWithCommas(parseFloat(newprice2).toFixed(2))); 
                    section_data +='<span style="color: red;"> ';
                    section_data += "₱"+numberWithCommas(parseFloat(dvalue).toFixed(2));
                    section_data +=' OFF';
                    section_data +='</span></br>';
                    section_data +='<span style="color: red;"> ';
                    section_data += '<i>'+duration+'</i>';
                    section_data +='</span></br></br>';
                    $('#price_discount').append(section_data);
                }else{
                     $("#price_id").text("₱"+numberWithCommas(parseFloat(price).toFixed(2)));
                }
            }else{
                $("#price_id").css("display","none");
                $("#priceComingSoon").css("display","block")  ; 
                if(dtypeval==0&&rateval!=0){
                    var deduction=rateval*price;
                    var newprice=price-deduction;
                    $("#priceComingSoon").text("₱"+numberWithCommas(parseFloat(newprice).toFixed(2)));  
                    section_data +='<span style="color: red;"> ';
                    section_data += numberWithCommas(parseFloat(dvalue).toFixed(0));
                    section_data +='% OFF';
                    section_data +='</span></br>';
                    section_data +='<span style="color: red;"> ';
                    section_data += '<i>'+duration+'</i>';
                    section_data +='</span></br></br>';
                    $('#price_discount').append(section_data);
                }else if(dtypeval==1&&rateval!=0){
                    var newprice2=price-rateval;
                    $("#priceComingSoon").text("₱"+numberWithCommas(parseFloat(newprice2).toFixed(2))); 
                    section_data +='<span style="color: red;"> ';
                    section_data += "₱"+numberWithCommas(parseFloat(dvalue).toFixed(2));
                    section_data +=' OFF';
                    section_data +='</span></br>';
                    section_data +='<span style="color: red;"> ';
                    section_data += '<i>'+duration+'</i>';
                    section_data +='</span></br></br>';
                    $('#price_discount').append(section_data);
                }else{
                     $("#priceComingSoon").text("₱"+numberWithCommas(parseFloat(price).toFixed(2)));
                }
                $("#price_id").text("₱"+numberWithCommas(parseFloat(price).toFixed(2)));
            }
         }
    });
  }
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    $(document).ready(function() {
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
            var seller_id=0;
            var option_select="";
            var option_select_id=0;
            var new_price=$("#price_id").text();
           
            var stocks='<?php echo $product_detail['quantity'];?>';
            var product='<?php echo $product_id; ?>';
            var cust_id='<?php echo $id; ?>';
            var name='<?php echo $product_detail['name'];?>';
            var price=new_price.replace("₱","");
            if($('input[name="rd_sl_id"]').is(':checked')) {
                seller_id=$('input[name="rd_sl_id"]:checked').val();

                var selecteddata = $('#deductOPT_'+branch_idval).find('option:selected');
                var commingsoonval = selecteddata.data('commingsoon');
                if(commingsoonval==0){
                    option_select=selecteddata.data('description');
                    option_select_id=selecteddata.data('id');
                }
               

            }else{
                bootbox.alert("Please Select Store First.");
                return false;
            }

            var freebies=$("#freebies_"+seller_id).text();
           /* alert(option_select_id);*/
            $.ajax({
                url: 'ajax_add_to_cart_latest.php?action=addtocart_new&t=' + new Date().getTime(),
                type: 'POST',
                data: 'product=' + product + '&option_select_id='+option_select_id+'&cust_id=' + cust_id+'&freebies='+freebies+'&seller_id='+seller_id+'&option_select='+option_select+'&price='+price.replace(/,/g, '')+'&branch_id='+branch_idval,
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
            
        });
        $("#btn-buynow").click(function(){
            var seller_id=0;
            var option_select="";
            var option_select_id=0;
            var new_price=$("#price_id").text();
           
            var stocks='<?php echo $product_detail['quantity'];?>';
            var product='<?php echo $product_id; ?>';
            var cust_id='<?php echo $id; ?>';
            var name='<?php echo $product_detail['name'];?>';
            var price=new_price.replace("₱","");
            if($('input[name="rd_sl_id"]').is(':checked')) {
                seller_id=$('input[name="rd_sl_id"]:checked').val();
                var selecteddata = $('#deductOPT_'+branch_idval).find('option:selected');
                var commingsoonval = selecteddata.data('commingsoon');
                if(commingsoonval==0){
                    option_select=selecteddata.data('description');
                    option_select_id=selecteddata.data('id');
                }

            }else{
                bootbox.alert("Please Select Store First.");
                return false;
            }

            var freebies=$("#freebies_"+seller_id).text();
           /* alert(option_select_id);*/
            $.ajax({
                url: 'ajax_add_to_cart_latest.php?action=buynow_new&t=' + new Date().getTime(),
                type: 'POST',
                data: 'product=' + product + '&option_select_id='+option_select_id+'&cust_id=' + cust_id+'&freebies='+freebies+'&seller_id='+seller_id+'&option_select='+option_select+'&price='+price.replace(/,/g, '')+'&branch_id='+branch_idval,
                dataType: 'json',
                success: function(json) {                     
                  if (json['success']) {
                    location.replace("checkout.php?checkout_cart="+json['success']);
                  }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
            
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