<?php
require_once '../init.php';
class ChinaBrands {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    function __destruct(){
        $this->conn = null;
    }
    public function getProducts($token, $page_number){
        global $currency;
        $data = array();
        $prices = array();
        $no_per_page = 12;
        $get_products = array();
        $offset = ($page_number-1) * $no_per_page;
        $st = $this->conn->prepare('SELECT * FROM oc_china_product where status = 1 LIMIT :offset , :no_per_page');
        $st->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $st->bindValue(':no_per_page', $no_per_page, PDO::PARAM_INT);
        $st->execute();
        $product_ids = '';
        if($st->rowCount() > 0){
            foreach($st->fetchAll(PDO::FETCH_ASSOC) as $ids){
                // $product_ids .= $ids['goods_sn']. ',';
                $post_data_gpd = array(
                    'token' => $token,
                    'goods_sn' => json_encode($ids['goods_sn'])
                    );
                    $curl_gpd = curl_init('https://gloapi.chinabrands.com/v2/product/index');
                    curl_setopt($curl_gpd, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($curl_gpd, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($curl_gpd, CURLOPT_POST, 1);
                    curl_setopt($curl_gpd, CURLOPT_POSTFIELDS, $post_data_gpd);
                    $resul_gpd = curl_exec($curl_gpd); 
                    curl_close($curl_gpd);
                    $res_gpd= json_decode($resul_gpd);
                    $get_products= $res_gpd->msg;
                    if(is_array($get_products)){
                        if(count($get_products) > 0){
                            foreach((array) $get_products as $product){
                                if($product->status == 1){
                                    $data[] = array(
                                        'sku' => $product->sku,
                                        'name'=> $product->title,
                                        'thumb' => $product->original_img[0],
                                        'price' => $currency->setPriceForCB(intval($ids['price']))
                                        // 'warehouse_list' => $product->warehouse_list,
                                        // 'priceRange' => $this->getMinMaxPrice($product->warehouse_list)
                                    );
                                }
                            }
                        }
                    }
            } 
        }
        return $data;
    }
    public function getProduct($token, $product_id){
        global $product;
        global $currency;
        $data = array();
        $post_data_gpd = array(
        'token' => $token,
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
        // return $res_gpd;
        $prod= $res_gpd->msg[0];
        if(isset($prod)){
            if(is_string($prod)){
                $data = $this->productSetDataDefault($product_id);
            }else{
                if(is_array($prod)){
                    if(array_key_exists("msg",$prod[0])){
                       $data = $this->productSetDataDefault($product_id);
                    }else{
                        $warehouse = $this->getStock($token, $product_id, $prod->warehouse_list, $prod->original_img[0]);    
                        $data = array(
                                'productId' => $prod->sku,
                                'name' => $prod->title,
                                'price' => $warehouse['value'][0]['price'],
                                'oldPrice' => false,
                                'thumb' => $prod->original_img[0],
                                'image' => $prod->original_img,
                                'description' => html_entity_decode(utf8_encode($prod->goods_desc)),
                                'rating' => 0,
                                // 'quantity' => $this->getFirstQuantity($prod->warehouse_list),
                                'quantity' => $warehouse['value'][0]['stock'],
                                'tag' => 'test',
                                'viewed' => 0,
                                'totalSold'=> '10 sold',
                                'warehouse' => array($warehouse),
                                'store' => $product->store_by_id(null),
                                'freebie'=> [],
                                'discount' => [],
                                'reviews' => [],
                                'detail'  => array(
                                    // array(
                                    //     'label' => 'stocks',
                                    //     'value' => 1
                                    // ), 
                                    array(    
                                        'label' => 'tags',
                                        'value' => ''
                                    ), 
                                    array(
                                        'label' => 'model',
                                        'value' => ''
                                    ),
                                    array(
                                        'label' => 'category',
                                        'value' => ''
                                    ),
                                ),
                                'attribute' => []
                            );
                    }
                }else{
                    $data = $data = $this->productSetDataDefault($product_id);
                }
            }
        }
        return $data;
    }
    public function productSetDataDefault($product_id){
        global $product;
        $cb_product = $this->getCBProduct($product_id);
        return array(
                'productId' => $cb_product['goods_sn'],
                'name' => $cb_product['product_title'],
                'price' => 0,
                'oldPrice' => false,
                'thumb' => $cb_product['product_img'],
                'image' => array($cb_product['product_img']),
                'description' => '',
                'rating' => 0,
                'quantity' => 0,
                'tag' => '',
                'viewed' => 0,
                'totalSold'=> '10 sold',
                'warehouse' => [],
                'store' => $product->store_by_id(null),
                'freebie'=> [],
                'discount' => [],
                'reviews' => [],
                'detail'  => array(
                    array(
                        'label' => 'stocks',
                        'value' => 0
                    ), 
                    array(    
                        'label' => 'tags',
                        'value' => ''
                    ), 
                    array(
                        'label' => 'model',
                        'value' => ''
                    ),
                    array(
                        'label' => 'category',
                        'value' => ''
                    ),
                ),
                'attribute' => []
            );
    }
    public function getMinMaxPrice($list){
        global $currency;
        $prices = array();
        foreach($list as $key => $value){
            $prices[] = $currency->setPriceForCB($value->price);
        }
        return array(
            'max' => max($prices),
            'min' => min($prices)
        );
    }
    public function getPrice($sku){
        global $currency;
        $st = $this->conn->prepare('SELECT * FROM oc_china_product where sku = :sku LIMIT 1');
        $st->bindValue(':sku', $sku);
        $st->execute();
        $price = $st->fetch()['price'];
        return $currency->setPriceForCB($price);
    }
    public function getStockByWarehouse($warehouse){
        $stock = 0;
        // foreach($warehouse as $list){
            foreach($warehouse['value'] as $value){
                if($value['selected'] == true){
                    $stock = $value['stock'];
                }
            }
        // }
        return $stock;
        
    }
    
    public function getStockChinaApi($token, $product_id){
        $data = array();
        
        
        $post_stock = array(
            'token' =>  $token,
            'goods_sn' => json_encode($product_id)
        );
        
        $curl_stock = curl_init('https://gloapi.chinabrands.com/v2/product/stock');
        curl_setopt($curl_stock, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_stock, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_stock, CURLOPT_POST, 1);
        curl_setopt($curl_stock, CURLOPT_POSTFIELDS, $post_stock);
        $result_stock = curl_exec($curl_stock); 
        curl_close($curl_stock);
        return json_decode($result_stock);           
    }
    public function getStock($token, $product_id, $lists, $image){
        global $currency;
        $data = array();
        $lists = json_decode(json_encode($lists), true);
        $res_stock = $this->getStockChinaApi($token, $product_id);
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
            if($res_stock->status){
                $counter = 0;
                foreach ($res_stock->msg->page_result as $data_stock) {
                    if($data_stock->status){
                        $goods_number = $data_stock->goods_number;
                        if( $goods_number!=0){
                            $data['label'] = 'Available';
                            $data['value'][] =  array(
                                'id' => $data_stock->goods_sn,
                                'name'  =>$warehouse_list[$data_stock->warehouse] ,
                                'code'  =>$data_stock->warehouse,
                                'stock'  => intval($goods_number),
                                'img' => $image,
                                'msg' => '',
                                // 'price' => 12.3,
                                'price' => round($currency->setPriceForCB($lists[$data_stock->warehouse]['price']), 2),
                                'selected' => false
                            );
                        }        
                    }else{
                         $goods_number=0;
                    }
                    
                }
            }else{
                $goods_number=0;
            }
            return $data;
    }
    public function getCBPrice($token, $product_id){
        $post_data_gpd = array(
            'token' => $token,
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
            $get_products= $res_gpd->msg;
            // return $get_products;
            if(is_string($get_products)){
                return 0;
            }else{
                if(is_array($get_products)){
                    if(array_key_exists("msg",$get_products[0])){
                        return 0;
                    }else{
                        return $this->getStock($token, $product_id, $get_products[0]->warehouse_list, '')['value'][0]['price'];
                    }
                }
            }
    }
    public function getCBProduct($product_id){
        $st = $this->conn->prepare("SELECT * FROM oc_china_product WHERE goods_sn = :product_id LIMIT 1");
        $st->bindValue(':product_id', $product_id);
        $st->execute();
        return $st->fetch();
    }
}
$chinabrands = new ChinaBrands();