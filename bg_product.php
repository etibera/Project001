<?php
 include "common/header.php";
 require_once 'include/banggoodAPI.php';
 include "model/bg_product.php";
    $model = new bg_product();
    $id =isset($_SESSION['user_login'])?$_SESSION['user_login']:333;
    $customer_id =isset($_SESSION['user_login'])?$_SESSION['user_login']:0;
    $product_id =isset($_GET['product_idbg'])?$_GET['product_idbg']:0;
    $ppp = $model->get_ppp($id);
    $product_bg = $model->get_product($product_id);
    $model->save_view_products($product_id,$customer_id,2);

    //get latest stock
    $params_gps = array('product_id' => $product_bg['product_id'],);
    $banggoodAPI->setParams($params_gps);
    $result_gps = $banggoodAPI->getstocks(); 
     // getShipments
    $params_gs = array('product_id' =>$product_bg['product_id'],'warehouse' => $product_bg['warehouse'],'quantity' => 1,'currency'=>'PHP','country'=>'Philippines');
    $banggoodAPI->setParams($params_gs);
    $result_gs = $banggoodAPI->getShipments();
    
    $stoc_val_arr= array();
    $price_val_arr= array();
    $val_poa_id_stock="";
    $count_stock_val=0;
    if($result_gps['code']==0){
        foreach ($result_gps['stocks'][0]['stock_list'] as $stk_val) {
             $stoc_val_arr[] = array(
                  'poa_id' =>  $stk_val['poa_id'],     
                  'stock' =>  $stk_val['stock'],     
                  'stock_msg' =>  $stk_val['stock_msg'],     
                  'poa' =>  $stk_val['poa'],     
                );
            if($stk_val['stock']!=0){
                $stock_gps=$stk_val['stock'];
                $stock_msg_gps=$stk_val['stock_msg'];
                $val_poa_id_stock=$stk_val['poa_id'];
                $count_stock_val++;
            }
        }
        if($count_stock_val==0){
            $stock_gps=$result_gps['stocks'][0]['stock_list'][0]['stock'];
            $stock_msg_gps=$result_gps['stocks'][0]['stock_list'][0]['stock_msg'];
            $val_poa_id_stock=$result_gps['stocks'][0]['stock_list'][0]['poa_id'];
        }
    }else{
         $stoc_val_arr[] = array(
                  'poa_id' =>  $val_poa_id_stock,     
                  'stock' =>  0,     
                  'stock_msg' =>  $stock_msg_gps,     
                  'poa' =>  '',     
                );
        $stock_gps=0;
        $stock_msg_gps=$result_gps['msg'];
    }
     
    //get latest price
    $params_pp = array('product_id' => $product_bg['product_id'],'warehouse' => $product_bg['warehouse'],'currency' => 'PHP');
    $banggoodAPI->setParams($params_pp);
    $result_pp = $banggoodAPI->getproductprice();
    if($result_pp['code']==0){
         foreach ($result_pp['productPrice'] as $prc_val) {
            $price_val_arr[] = array(
                  'poa_id' =>  $prc_val['poa_id'],     
                  'price' =>  $prc_val['price']+$result_gs['shipmethod_list'][0]['shipfee'] 
            ); 
            if($prc_val['poa_id']==$val_poa_id_stock){
               $price_pp=$prc_val['price'];  
            } 
         }
    }else{
        $price_val_arr[] = array(
                  'poa_id' => $val_poa_id_stock,     
                  'price' =>  $product_bg['price']
            ); 
        $price_pp=$product_bg['price'];
    }
    
    // getProductInfo
    $params_pi = array('product_id' =>$product_bg['product_id']);
    $banggoodAPI->setParams($params_pi);
    $result_pi = $banggoodAPI->getProductInfo();
    if($result_pi['code']==0){
        $product_desc=1;
        $p_imagelist=$result_pi['image_list']['view'];
        $p_description=$result_pi['description'];
        $poa_list_pi=$result_pi['poa_list']; 
    }else{
        $product_desc=0;
    }
     /*echo "<pre>";*/
    $poa_id_wth_stk=explode(",",$val_poa_id_stock);
    /* echo "<pre>";
    echo"<br><br><br><br><br>";
     print_r($poa_list_pi);
    print_r($poa_id_wth_stk);
    print_r($stoc_val_arr);
    print_r($price_val_arr);
    print_r($result_pp);*/
    
     //print_r($poa_id_wth_stk);
   
   
     $price_pp+=$result_gs['shipmethod_list'][0]['shipfee'];
?>
<style type="text/css">
.xxkkk-bg {
    width: 100% !important; 
    overflow: auto !important;
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
                <?php if($product_desc==1){ ?> 
                    <?php include "bg_product_img.php";?>
                <?php }else{ ?> 
                    <div class="image-container-bg">
                        <img src="<?php echo $product_bg['img'] ?>"  class="img-responsive" />
                    </div>
                <?php } ?> 
            </div>
                    
                
           
            <div class="col-md-4">
                <div class="container-c">
                    <div class="row">
                        <div class="col-xs-12">
                            <h4><?php echo utf8_encode($product_bg['product_name']);?></h4>
                            <p>Product Code: <?php echo utf8_encode($product_bg['meta_desc']);?></p>
                             <?php if($result_gps['code']==0){ ?>
                                <p id="stk_valbg">Availability: <?php  if($stock_gps==0){  echo $stock_gps.' ('.$stock_msg_gps.')'; }else{ echo $stock_gps; } ?> </p>
                             <?php } ?>
                             <div >
                                <?php 
                                 $discoubtedp=$price_pp;
                                 $originalp=$product_bg['price']*1.25;
                                 $pecentdis=($originalp-$discoubtedp)/ $originalp;
                                 $pecentdisfinal=$pecentdis*100;

                                ?>
                                <h3 id="pice_bg" >₱<?php echo number_format($price_pp,2);?></h3>
                                <div >
                                    <p style="text-decoration: line-through; display: inline-block;color: #9e9e9e;">₱<?php echo number_format($originalp,2);?></p> 
                                    <span style="color: #9e9e9e;"> <?php echo (int) $pecentdisfinal?>% OFF</span>
                                </div>
                             </div>
                            
                              <?php if(count($poa_list_pi)){?>                              
                                <?php foreach($poa_list_pi as $pol):  ?>
                                    <?php 
                                        $count_poa_lis_save = $model->get_count_poa_lis_save($pol['option_id'],$id,$product_id);
                                        if($count_poa_lis_save==0){ 
                                            foreach($pol['option_values'] as $pollistdata){
                                                if (in_array($pollistdata['poa_id'], $poa_id_wth_stk)){
                                                    $model->poal_save_buffer($pol,$id,$product_id,$pollistdata['poa_id'],$pollistdata['poa_name']);
                                                }
                                            }
                                        }
                                    ?> 
                                        <label class="control-label" for="input-order-status"> <?php echo $pol['option_name'];?> : </label>
                                        <div class="form-group">
                                            <select class="form-control poa_id_list" name="<?php echo $pol['option_id'];?>" onChange="update_poa(this,<?php echo $pol['option_id'];?>,<?php echo $id;?>,<?php echo $product_id;?>,'<?php echo $product_bg['warehouse'];?>')" >
                                                <?php foreach($pol['option_values'] as $pollist){ ?>
                                                    <option value='<?php echo $pollist['poa_id'];?>' <?php if (in_array($pollist['poa_id'], $poa_id_wth_stk)){echo "selected"; }?>><?php echo $pollist['poa_name'];?> </option>
                                                <?php } ?>                                                
                                            </select>
                                        </div>
                                <?php endforeach;?> 
                            <?php } ?>
                            <p>Ship From: <?php echo utf8_encode($product_bg['warehouse']);?></p>
                             <?php  if($result_gs['code']==0){ ?>
                                 <p>Shipping: ₱<?php echo number_format($result_gs['shipmethod_list'][0]['shipfee'],2);?></p>
                                 <p>to Philippines via <?php echo str_replace("_"," ",$result_gs['shipmethod_list'][0]['shipmethod_name']);?></p>
                                 <p>Shipping Time: <?php echo $result_gs['shipmethod_list'][0]['shipday'];?> business days</p>
                            <?php } ?>
                            <div class="form-group">
                                <label for="">Quantity</label>
                                <input type="number" value="1" class="form-control" readonly></br>
                                <?php if($is_log): ?>
                                    <button class="btn btn-primary btn-addtocart_bg" id="btn-addtocart_bg" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 18px;"data-product_id="<?php echo $product_bg['product_id'];?>" data-name="<?php echo $product_bg['product_name'];?>" data-user_id="<?php echo $_SESSION['user_login'];?>" <?php if($stock_gps==0){echo "disabled"; }?>><i data-feather="shopping-cart"></i> Add to cart</button></br></br>
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
        <?php if($product_desc==1){ ?> 
        <div class="row">
            <div class="col-xs-12 xxkkk-bg" >
                <?php echo $p_description;?>
            </div>
        </div>
        <?php }?>
    </div>
</div>  
        

<?php
include "common/footer.php";
?>
 <script>
     var obj_stk= <?php echo json_encode($stoc_val_arr); ?>;
     var obj_price= <?php echo json_encode($price_val_arr); ?>;

    $(document).ready(function() {

        $(".btn-addtocart_bg").click(function(){
            var product=$(this).data('product_id');
            var cust_id=$(this).data('user_id');
            var name=$(this).data('name');
             $.ajax({
                url: 'ajx_wallet.php?action=bg_add_to_cart',
                type: 'post',
                data: 'product_bg=' + product + '&cust_id=' + cust_id,
                dataType: 'json',
                success: function(json) {
                    if (json['success']) {
                           bootbox.alert(json['success']+" ("+name+")");
                    }     
                }
            });
           
        });
    });
    function update_poa(poa_id,poa_option_id,cust_id,p_id,cn) {
        var poa_id_val = poa_id.options[poa_id.selectedIndex].value 
        var poa_id_text = poa_id.options[poa_id.selectedIndex].text 
        $.ajax({
            url: 'ajx_wallet.php?action=update_bg_poa_list',
            type: 'post',
            data: 'poa_option_id=' +poa_option_id +'&cust_id=' + cust_id+'&p_id=' + p_id+'&poa_id=' + poa_id_val+'&poa_name=' + poa_id_text,
            dataType: 'json',
            success: function(json) {
                if (json['success']) {
                    
                }

            }
        });
        $.ajax({
            url: 'ajx_wallet.php?action=bg_get_latest_price',
            type: 'post',
            data: 'poa_option_id=' +poa_option_id +'&cust_id=' + cust_id+'&p_id=' + p_id+'&poa_id=' + poa_id_val+'&poa_name=' + poa_id_text+'&warehouse='+cn,
            dataType: 'json',
            success: function(json) {
               // console.log(json);

                if (json['success_stock']) {
                    //console.log(json['success']);
                    var price=0;
                    var stock=0;
                    var stock_msg="";
                    for (var i = 0; i < obj_price.length; i++) {                         
                      if(obj_price[i]['poa_id']==json['success_stock']){
                        price=obj_price[i]['price'];
                      }     
                    }
                    for (var y = 0; y < obj_stk.length; y++) {                         
                      if(obj_stk[y]['poa_id']==json['success_stock']){
                        stock=obj_stk[y]['stock'];
                        stock_msg=obj_stk[y]['stock_msg'];
                      }     
                    }
                  

                    if(price==0){
                        price=obj_price[0]['price'];
                    }
                    $("#stk_valbg").text("Availability: "+ stock + " ("+stock_msg+")");
                    $("#pice_bg").text("₱"+numberWithCommas(parseFloat(price).toFixed(2)));  
                    if(stock==0){
                         $('#btn-addtocart_bg').prop('disabled', true);
                    }else{
                         $('#btn-addtocart_bg').prop('disabled', false);
                    }
                }
           }
        });

       
       
    }

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    console.log(obj_stk);
    console.log(obj_price);
 </script>
