<?php
require_once '../init.php';
// require_once '../../composer/vendor/autoload.php';
// require_once '../../composer/vendor/luokuncool/taobao-sdk-php/Autoloader.php';
class AliExpress {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    function __destruct(){
        $this->conn = null;
    }
    public function getProduct($product_id){
        global $product;
        $data = array();
        $c = new TopClient;
        $c->appkey = '32053793';
        $c->secretKey = '6c8efcd09baefea3518f95a1b94f3735';
        $req = new AliexpressAffiliateProductdetailGetRequest;
        $req->setAppSignature("aaaaa");
        $req->setFields("commission_rate,sale_price");
        $req->setProductIds($this->getProductId($product_id));
        $req->setTargetCurrency("USD");
        $req->setTargetLanguage("EN");
        $resp = $c->execute($req);
        $json = json_decode(json_encode($resp->resp_result), TRUE);
        $prod = $json['result']['products']['product'];
        // foreach((array) $json['result']['products']['product'] as $prod){
            $data = array(
                'productId' => $product_id,
                'name' => $prod['product_title'],
                'price' => $prod['original_price'],
                'oldPrice' => false,
                'thumb' => $prod['product_main_image_url'],
                'image' => $prod['product_small_image_urls']['string'],
                'description' => $prod['product_title'],
                'rating' => 0,
                'quantity' => $this->getQuantity($product_id),
                'tag' => 'test',
                'viewed' => 0,
                'totalSold'=> '10 sold',
                'store' => $product->store_by_id(null),
                'freebie'=> [],
                'discount' => [],
                'reviews' => [],
                'detail'  => array(
                    array(
                        'label' => 'stocks',
                        'value' => 1
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
        // }
        return $data;
    }
    public function getQuantity($product_id){
        $stmt = $this->conn->prepare("SELECT quantity from aliexpress_products WHERE product_id = :product_id");
        $stmt->bindValue(':product_id', $product_id);
        $stmt->execute();
        return $stmt->fetch()['quantity'];
    }
    public function getId($product_id){
        $stmt = $this->conn->prepare("SELECT id from aliexpress_products WHERE product_id = :product_id");
        $stmt->bindValue(':product_id', $product_id);
        $stmt->execute();
        return $stmt->fetch()['id'];
    }
    public function getProductId($id){
        $stmt = $this->conn->prepare("SELECT product_id from aliexpress_products WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch()['product_id'];
    }
}
$aliexpress = new AliExpress();