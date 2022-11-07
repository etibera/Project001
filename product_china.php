<?php
include "common/header.php";
include "include/china_token.php";
include "model/bg_product.php";
$model_product = new bg_product();
$product_desc_stats=0;
//get exchange_currency_RATE
$date = new DateTime("now", new DateTimeZone('Asia/Manila'));
$date_now=$date->format('Y-m-d');
$exchange_currency_RATE=$model_product->get_fer_today($date_now);
$othercharges=1.3;

$id =isset($_SESSION['user_login'])?$_SESSION['user_login']:333;
$product_id =isset($_GET['product_idcb'])?$_GET['product_idcb']:0;
$ppp = $model_product->get_ppp($id);

$product_desc_local = $model_product->get_product_cb($product_id);
$get_product_title=$product_desc_local['product_title'];
$sku=$product_desc_local['sku'];
$goods_desc="";
//get current price of china products
$china_price_usd=0;
$post_data_gpd = array(
'token' => $token_china,
'goods_sn' => json_encode($product_id)
);
$curl_gpd = curl_init('https://gloapi.chinabrands.com/v2/product/index');
curl_setopt($curl_gpd, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl_gpd, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_gpd, CURLOPT_POST, 1);
curl_setopt($curl_gpd, CURLOPT_POSTFIELDS, $post_data_gpd);
$resul_gpd = curl_exec($curl_gpd); 
curl_close($curl_gpd);
$res_gpd= json_decode($resul_gpd);
$get_products_status= $res_gpd->status; 
$images_cb= array();
if($get_products_status){
    $get_products=$res_gpd->msg;
    foreach ($get_products as $product_desc) {
       if($product_desc->status==1){
            $product_desc_stats=1;
            $goods_desc= $product_desc->goods_desc;
            foreach ($product_desc->original_img as $cb_img) { 
                $images_cb[]= array('img' => $cb_img );
            } 
             
            foreach ($product_desc->warehouse_list as $warehouse_p) { 
                $china_price_usd=$warehouse_p->price; 
                
            } 
       }else{
         $china_price_usd=$product_desc_local['price'];
        
       } 
    }
}else{
    $china_price_usd=$product_desc_local['price'];
    $product_desc_stats=0;
} 
//var_dump($images_cb) ;
$china_pricw_php=($china_price_usd*$exchange_currency_RATE)*$othercharges;

//get current stock of china products
$warehouse_list= array(
    'FXLAWH'  => 'US-1',
    'FXLAWH2'  => 'US-2',
    'ESTJWH'  => 'ES-1',
    'HKTJWH'  => 'HK-2',
    'MXTJWH'  => 'US-3',
    'FXHKGCZY'  => 'HK-4',
    'FREDCGC'  => 'FR-1',
    'SZXIAWAN'  => 'CN-9',
    'FXCZBLG2'  => 'CZ-1',
    'FXXN'  => 'CN-12',
    'ZQFX'  => 'CN-1',
    'DSFREXIAO'  => 'CN-11',
    'FRED'  => 'UFR-2',
    'POLANDED'  => 'PL-1',
    'CBSHARE'  => 'CN-13',
    'FXZQBHWH'  => 'FXZQBHWH',
);
$stock_list=array();
$post_stock = array(
    'token' =>  $token_china,
    'goods_sn' => json_encode($product_id)
);

$curl_stock = curl_init('https://gloapi.chinabrands.com/v2/product/stock');
curl_setopt($curl_stock, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl_stock, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_stock, CURLOPT_POST, 1);
curl_setopt($curl_stock, CURLOPT_POSTFIELDS, $post_stock);
$result_stock = curl_exec($curl_stock); 
curl_close($curl_stock);
$res_stock= json_decode($result_stock);
$stats_stock=$res_stock->status;               
if($stats_stock){
    $stock_result=$res_stock->msg->page_result;
    foreach ($stock_result as $data_stock) {
        $stock_number = $data_stock->status;
        if($stock_number){
            $warehouse_code=$data_stock->warehouse;
            $goods_number = $data_stock->goods_number;
            if( $goods_number!=0){
                  $stock_list[]=array(
                    'warehouse'  =>$warehouse_list[$warehouse_code] ,
                    'warehouse_code'  =>$data_stock->warehouse ,
                    'goods_number'  =>   $goods_number
                );
            }          
        }else{
             $goods_number=0;
        }
    }
}else{
    $goods_number=0;
} 
         

// $warehouse_code='ESTJWH';
// echo "<pre>";
// 	//print_r($res_stock);
//     print_r($product_desc->warehouse_list->$warehouse_code->price);
?>
<style type="text/css">
.xxkkk {
    width: auto !important; 
}
</style>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <center><h2>Product Page</h2></center>              
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <?php if($product_desc_stats==1){ ?> 
                     <?php include "product_images_china.php";?>

                <?php }else{ ?> 
                    <div class="image-container-bg">
                        <img src="<?php echo $product_desc_local['product_img'] ?>"  class="img-responsive" />
                    </div>
                <?php } ?> 
            	
            </div>
            <div class="col-md-4">
                <div class="container-c">
                	<div class="row">
                        <div class="col-xs-12">
                        	<h1><?php echo utf8_encode($get_product_title);?></h1>
                            <p>Product Code: <?php echo utf8_encode($sku);?></p>
                            <p>Availability:
                                <?php 
                                    if(count($stock_list)){   $stlcount=0; ?>
                                        <div class="form-group">
                                            <select class="form-control cn_stoct_val" name="cn_stoct_val" onChange="getprice(this)" >
                                                <?php foreach($stock_list as $stl){?>
                                                    <option value='<?php echo $stl['warehouse_code'];?>'><?php echo $stl['warehouse']." : ". $stl['goods_number'];?> </option>
                                                <?php } ?>                                                
                                            </select>
                                        </div>                                    
                                       
                                   <?php }else{
                                        echo $goods_number;
                                    }
                                ?> 
                            </p>
                            <h3 id='price_cn'></h3>
                            <div class="form-group">
                            	<label for="">Quantity</label>
                            	<input type="number" value="1" class="form-control" readonly></br>
                                 <?php if($is_log): ?>
                                         

                                <button class="btn btn-primary btn-addtocart_china" id="btn-addtocart_china" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 18px;" data-product_id="<?php echo $product_id;?>" data-name="<?php echo $get_product_title;?>" data-user_id="<?php echo $_SESSION['user_login'];?>"><i data-feather="shopping-cart" ></i>Add to Cart</button></br></br>
                                 
                            <?php else: ?>
                              <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#LoginModal"style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 18px;" ><i data-feather="shopping-cart"></i> Add to cart</a></br></br>
                                              
                            <?php endif;?></br>
                            </div>
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
        </div>
    	</br></br>
        <div class="row">
            <div class="col-xs-12">
            	<?php echo $goods_desc;?>
            </div>
        </div>
    </div>
</div> 	
		
<?php
include "common/footer.php";
?>
 <script>
    $(document).ready(function() {        
        getprice();
        $(".btn-addtocart_china").click(function(){
            var product=$(this).data('product_id');
            var cust_id=$(this).data('user_id');
            var name=$(this).data('name');
            var stockval2=$('.cn_stoct_val').val();
          
            $.ajax({
            url: 'ajx_wallet.php?action=cn_add_to_cart',
            type: 'POST',
            data: 'product_china=' + product + '&cust_id=' + cust_id + '&stockval='+ stockval2,
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
    });
    function getprice(){
        var product_desc_stats=<?php echo $product_desc_stats?>;
        if(product_desc_stats==0){
             var china_price_php=parseFloat(<?php echo $china_pricw_php?>);
        }else{
            var stockval=$('.cn_stoct_val').val();
            var obj = <?php echo json_encode($product_desc->warehouse_list); ?>;
            var exchange_currency_RATE=parseFloat(<?php echo $exchange_currency_RATE?>);
            var othercharges=parseFloat(<?php echo $othercharges?>);
            var china_price_usd=parseFloat(obj[stockval]['price']);
            var china_price_php=(china_price_usd*exchange_currency_RATE)*othercharges;           
        }
        $("#price_cn").text("₱"+numberWithCommas(parseFloat(china_price_php).toFixed(2)) );
    }
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
 </script>
