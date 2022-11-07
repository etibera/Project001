<?php
require_once '../init.php';
class Cart {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    public function get_bg_warehouse($product_id){
        $st = $this->conn->prepare("SELECT warehouse FROM bg_product WHERE product_id = :product_id");
        $st->bindValue(':product_id', $product_id);
        $st->execute();
        return $st->fetch()['warehouse'];
    }
    public function bg_stock_price($cart_id, $product){
        $st = $this->conn->prepare("SELECT * FROM bg_cart_poa_list WHERE cart_id = :cart_id");
        $st->bindValue(':cart_id', $cart_id);
        $st->execute();
        $count = $st->rowCount();
        $poa_list = $st->fetchAll(PDO::FETCH_ASSOC);
        $quantity = 0;
        $price = 0;
        switch($count){
            case 0:
                $quantity = $product['quantity'];
                $price = $product['price'];
            break;
            case 1:
                 foreach($product['warehouse'][0]['value'] as $value){
                    if($value['id'] == $poa_list[0]['poa_id']){
                        $quantity = $value['stock'];
                        $price = $value['price'];
                    }
                }
            break;
            case 2: 
                foreach($product['warehouse'][1]['value'] as $value){
                    if($value['id'] == $poa_list[1]['poa_id'] && $value['parent_id'] == $poa_list[0]['poa_id']){
                        $quantity = $value['stock'];
                        $price = $value['price'];
                    }
                }
            break;
            default:
                $quantity = 0;
                $price = 0;
            break;
        };
        return array('quantity' => $quantity, 'price' => $price);
    }
    public function bg_selected_poa($cart_id, $warehouse){
        $st = $this->conn->prepare("SELECT * FROM bg_cart_poa_list WHERE cart_id = :cart_id");
        $st->bindValue(':cart_id', $cart_id);
        $st->execute();
        $poa_list = $st->fetchAll(PDO::FETCH_ASSOC);
        
        for($i = 0; $i < count($warehouse); $i++){
            for($j = 0; $j < count($warehouse[$i]['value']); $j++){
                if($warehouse[$i]['value'][$j]['id'] == $poa_list[$i]['poa_id']){
                    $warehouse[$i]['value'][$j]['selected'] = true;
                }
            }
        }
        return $warehouse;
    }
    public function get_product_cb($product_id, $token){
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
            return $prod= $res_gpd->msg[0];
    }
    public function product_type($type){
        switch($type){
            case 0:
            return 'reg';
            break;
            case 1:
            return 'cb';
            break;
            case 2:
            return 'bg';
            break;
            case 3:
            return 'ae';
            break;
        }
    }
    public function get_product($customer_id, $product_id){
        global $product;
        global $image;
        global $chinabrands;
        global $banggood;
        global $store;
        $st = $this->conn->prepare("SELECT c.* FROM oc_cart c WHERE c.product_id = :product_id AND c.customer_id = :customer_id  LIMIT 1");
        $st->bindValue(':product_id', $product_id);
        $st->bindValue(':customer_id', $customer_id);
        $st->execute();
        $cart = $st->fetch(PDO::FETCH_ASSOC);
        $type = $this->product_type($cart['p_type']);
        $product_info = $product->get_single_product($cart['product_id'], $type);
        if($product_info){
            switch($product_info['type']){
                case 'reg':
                    $special = false;
                    if($cart['price'] != 0 || $cart['price'] != null){
                        if($cart['price'] != $product_info['price']){
                            $price = $cart['price'];
                            $oldPrice = $product_info['price'];
                        }else{
                            $price = $product_info['price'];
                            $oldPrice = 0;
                        }

                    }else{
                        $price = $product_info['price'];
                        $oldPrice = 0;
                    }
                    $product_quantity = $this->product_quantity($cart['seller_id'], $product_info['product_id']);
                    $quantity = $product_quantity > 0 ? (int)$cart['quantity'] : 0;
                    $data = array(
                        'id' => (int)$cart['cart_id'],
                        'productId' => $product_info['product_id'],
                        'name' => utf8_encode($product_info['name']),
                        'quantity' => $quantity,
                        'productQuantity' => $product_quantity,
                        'price' => (float) $price,
                        'oldPrice' => (float) $oldPrice,
                        'total' => ($price * $quantity),
                        'thumb' => $product_info['thumb'],
                        'special' => $special,
                        'discount' => $this->cart_discounts($cart['product_id']),
                        'type' => $cart['p_type'],
                        'typeCode' => $product_info['type'],
                        'warehouse' => [],
                        'freebies' => $cart['freebies'],
                        'seller' => $store->get_seller($cart['seller_id']),
                        'selected' => false
                    );
                break;
                case 'bg':
                    $cb_product = $banggood->getProduct($cart['product_id']);
                    $warehouse = $this->bg_selected_poa($cart['cart_id'],$cb_product['warehouse']);
                    $stockPrice = $this->bg_stock_price($cart['cart_id'],$cb_product);
                    $price = 0;
                        $data = array(
                            'id' => (int)$cart['cart_id'],
                            'productId' => $cart['product_id'],
                            'name' => utf8_encode($cb_product['name']),
                            'model' => $cart['product_id'],
                            'quantity' => (int)$cart['quantity'],
                            'productQuantity' => $stockPrice['quantity'],
                            'price' => (float) $stockPrice['price'],
                            'oldPrice' => 0,
                            'total' => (float)$stockPrice['price'] * $cart['quantity'],
                            'thumb' => $product_info['thumb'],
                            'special' => false,
                            'discount' => [],
                            'type' => $cart['p_type'],
                            'typeCode' => $product_info['type'],
                            'warehouse' => $warehouse,
                            'ship' => $banggood->getShipping($cart['product_id'], $this->get_bg_warehouse($cart['product_id']), (int)$cart['quantity']),
                            'freebies' => '',
                            'seller' => array()
                        );
                break;
                default:
                break;
            }
        }
        return $data;
    }
    public function get_products($customer_id, $token){
        global $product;
        global $image;
        global $chinabrands;
        global $banggood;
        global $store;
        $data = array();
        $st = $this->conn->prepare("SELECT c.* FROM oc_cart c LEFT JOIN oc_seller s ON s.seller_id = c.seller_id WHERE c.customer_id = :customer_id AND c.seller_id IS NOT NULL ORDER BY c.cart_id DESC");
        $st->bindValue(':customer_id', $customer_id);
        $st->execute();
        foreach($st->fetchAll() as $cart){
            if($cart['quantity'] < 0 ){
                $st1 = $this->conn->prepare("UPDATE oc_cart SET quantity = 1 WHERE customer_id = :customer_id AND cart_id = :cart_id ");
                $st1->bindValue(':customer_id', $customer_id);
                $st1->bindValue(':cart_id', $cart['cart_id']);
                $st1->execute();
            }
            $type = $this->product_type($cart['p_type']);
            $product_info = $product->get_single_product($cart['product_id'], $type);
            if($product_info){
                switch($product_info['type']){
                    case 'reg':
                        $special = false;
                        if($cart['price'] != 0 || $cart['price'] != null){
                            if($cart['price'] != $product_info['price']){
                                $price = $cart['price'];
                                $oldPrice = $product_info['price'];
                            }else{
                                $price = $product_info['price'];
                                $oldPrice = 0;
                            }

                        }else{
                            $price = $product_info['price'];
                            $oldPrice = 0;
                        }
                        $product_quantity = $this->product_quantity($cart['seller_id'], $product_info['product_id']);
                        $quantity = $product_quantity > 0 ? (int)$cart['quantity'] : 0;
                        $data[] = array(
                            'id' => (int)$cart['cart_id'],
                            'productId' => $product_info['product_id'],
                            'name' => utf8_encode($product_info['name']),
                            'quantity' => $quantity,
                            'productQuantity' => $product_quantity,
                            'price' => (float) $price,
                            'oldPrice' => (float) $oldPrice,
                            'total' => ($price * $quantity),
                            'thumb' => $product_info['thumb'],
                            'special' => $special,
                            'discount' => $this->cart_discounts($cart['product_id']),
                            'type' => $cart['p_type'],
                            'typeCode' => $product_info['type'],
                            'warehouse' => [],
                            'freebies' => $cart['freebies'],
                            'seller' => $store->get_seller($cart['seller_id']),
                            'test' => $this->product_quantity($cart['seller_id'], $product_info['product_id'])
                        );
                    break;
                      case 'bg':
                        $cb_product = $banggood->getProduct($cart['product_id']);
                        $warehouse = $this->bg_selected_poa($cart['cart_id'],$cb_product['warehouse']);
                        $stockPrice = $this->bg_stock_price($cart['cart_id'],$cb_product);
                        $price = 0;
                        // if(count($warehouse) == 1){
                        //     foreach($warehouse[0]['value'] as $wh){
                        //         if($wh['selected'] == true){
                        //             $productQuantity = $wh['stock'];
                        //             $price = $wh['price'];
                        //         }
                        //     }
                        // }else if(count($warehouse) == 2){
                        //     foreach($warehouse[1]['value'] as $wh){
                        //         if($wh['selected'] == true){
                        //             $productQuantity = $wh['stock'];
                        //             $price = $wh['price'];
                        //         }
                        //     }
                        // }else if(count($warehouse) == 0){
                        //     $productQuantity = $cb_product['quantity'];
                        //     $price = $cb_product['price'];
                        // }

                        // $warehouse = $this->getCBWarehouse($cb_product->warehouse_list, $cart['warehouse_code'], $token, $cb_product->sku, $cb_product->original_img[0]);
                        // if(is_object($cb_product)){
                            $data[] = array(
                                'id' => (int)$cart['cart_id'],
                                'productId' => $cart['product_id'],
                                'name' => utf8_encode($cb_product['name']),
                                'model' => $cart['product_id'],
                                'quantity' => (int)$cart['quantity'],
                                'productQuantity' => $stockPrice['quantity'],
                                'price' => (float) $stockPrice['price'],
                                'oldPrice' => 0,
                                'total' => ((float)$stockPrice['price'] * $cart['quantity']),
                                'thumb' => $product_info['thumb'],
                                'special' => false,
                                'discount' => [],
                                'type' => $cart['p_type'],
                                'typeCode' => $product_info['type'],
                                'warehouse' => $warehouse,
                                'ship' => $banggood->getShipping($cart['product_id'], $this->get_bg_warehouse($cart['product_id']), (int)$cart['quantity']),
                                'freebies' => '',
                                'seller' => array()
                            );
                        // }
                    break;
                    // case 'cb':
                    //     $cb_product = $this->get_product_cb($cart['product_id'], $token);
                    //     $warehouse = $this->getCBWarehouse($cb_product->warehouse_list, $cart['warehouse_code'], $token, $cb_product->sku, $cb_product->original_img[0]);
                    //     if(is_object($cb_product)){
                    //         $data[] = array(
                    //             'id' => (int)$cart['cart_id'],
                    //             'productId' => $cb_product->sku,
                    //             'name' => utf8_encode($cb_product->title),
                    //             'model' => $cb_product->sku,
                    //             'quantity' => (int)$cart['quantity'],
                    //             'productQuantity' => $chinabrands->getStockByWarehouse($warehouse),
                    //             'price' => (float) $product_info['price'],
                    //             'total' => ($product_info['price'] * $cart['quantity']),
                    //             'thumb' => $cb_product->original_img[0],
                    //             'special' => false,
                    //             'discount' => [],
                    //             'type' => $cart['p_type'],
                    //             'typeCode' => $product_info['type'],
                    //             'warehouse' => array($warehouse)
                    //         );
                    //     }
                    // break;
                    // case 'ae':
                    //     $special = false;
                    //     if($product_info['special']){
                    //         $special = true;
                    //         $price = $product_info['special'];
                    //     }else{
                    //         $price = $product_info['price'];
                    //     }
                    //     $data[] = array(
                    //         'id' => (int)$cart['cart_id'],
                    //         'productId' => $product_info['product_id'],
                    //         'name' => utf8_encode($product_info['name']),
                    //         'model' => utf8_encode($product_info['model']),
                    //         'quantity' => (int)$cart['quantity'],
                    //         'productQuantity' => $this->ae_product_quantity($product_info['product_id']),
                    //         'price' => (float) $price,
                    //         'total' => ($price * $cart['quantity']),
                    //         'thumb' => $product_info['image'],
                    //         'special' => $special,
                    //         'discount' => [],
                    //         'type' => $cart['p_type']
                    //     );
                    // break;
                    default:
                    break;
                }
            }
        }
        return $data;
    }
    public function getCBWarehouse($lists, $warehouse_code, $token, $product_id, $image){
        global $currency;
        global $chinabrands;
        $data = array();
        $lists = json_decode(json_encode($lists), true);
        $res_stock = $chinabrands->getStockChinaApi($token, $product_id);
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
                                // 'price' => 12.3,
                                'price' => round($currency->setPriceForCB($lists[$data_stock->warehouse]['price']), 2),
                                'selected' => $data_stock->warehouse === $warehouse_code ? true : false
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
    public function productTypeNumber($type_value){
        switch($type_value){  
            case 'reg':
                return 0;
            case 'cb':
                return 1;
            case 'bg':
                return 2;
            break;
            case 'ae':
                return 3;
            default: return 0;
        }
    }
    public function add(){
        global $product;
        $data = array();
        $p_type = 0;
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $customer_id = isset($_POST['customer_id']) ? (int) trim($_POST['customer_id']) : 0;
            $product_id = isset($_POST['product_id']) ? (int) trim($_POST['product_id']) : 0;
            //$quantity = isset($_POST['quantity']) ? (int) trim($_POST['quantity']) : 0;
            $selectedProduct = json_decode(htmlspecialchars_decode($_POST['selectedProduct']));
            if(isset($_POST['type'])){
                $p_type = $this->productTypeNumber(trim($_POST['type']));
            }
            $product_info = $product->get_product($product_id);
            $sql = '';
            // if($customer_id != 0){
                $cart = $this->check_product_from_cart($customer_id, $product_id, $p_type);
                // echo print_r($p_type . ' type');
                try{
                    if($cart == 0){
                        $sql .= "INSERT oc_cart SET seller_id = :seller_id, price = :price, discount_details = :discount_details, freebies = :freebies,
                        customer_id = :customer_id, product_id = :product_id, session_id = '', recurring_id= '0', `option`='[]', quantity = :quantity, date_added = NOW(), p_type = :p_type";
                    }else{
                        $sql .= "UPDATE oc_cart SET quantity = quantity + :quantity, seller_id = :seller_id, price = :price, discount_details = :discount_details, freebies = :freebies
                        WHERE product_id = :product_id AND customer_id = :customer_id AND p_type = :p_type";
                    }
                switch($p_type){
                    case 0 :
                        $s = $this->conn->prepare($sql);
                        $s->bindValue(':customer_id', $customer_id);
                        $s->bindValue(':seller_id', $selectedProduct->sellerId);
                        $s->bindValue(':freebies', $selectedProduct->freebies);
                        $s->bindValue(':price', $selectedProduct->price);
                        $s->bindValue(':discount_details', $selectedProduct->discountDetails);
                        $s->bindValue(':product_id', $product_id);
                        $s->bindValue(':quantity', $selectedProduct->quantity);
                        $s->bindValue(':p_type', $p_type);
                        $s->execute();
                        $cart_id = $this->conn->lastInsertId();
                    break;
                    case 1:
                        if($cart == 0){
                            $sql .= ", warehouse_code = :warehouse_code";
                        }else{
                            $sql .= " AND warehouse_code = :warehouse_code";
                        }
                        $s = $this->conn->prepare($sql);
                        $s->bindValue(':customer_id', $customer_id);
                        $s->bindValue(':product_id', $product_id);
                        $s->bindValue(':quantity', $selectedProduct['quantity']);
                        $s->bindValue(':p_type', $p_type);
                        $s->bindValue(':warehouse_code', $warehouse_code);
                        $s->bindValue(':seller_id', $selectedProduct['sellerId']);
                        $s->bindValue(':freebies', $selectedProduct['freebies']);
                        $s->bindValue(':discount_details', $selectedProduct['discountDetails']);
                        $s->execute();
                    break;
                    case 2:
                        $warehouse = json_decode(htmlspecialchars_decode($_POST['warehouse']));
                        $s = $this->conn->prepare($sql);
                        $s->bindValue(':customer_id', $customer_id);
                        $s->bindValue(':product_id', $product_id);
                        $s->bindValue(':quantity', $selectedProduct->quantity);
                        $s->bindValue(':p_type', $p_type);
                        $s->bindValue(':seller_id', 0);
                        $s->bindValue(':price', $selectedProduct->price);
                        $s->bindValue(':freebies', $selectedProduct->freebies);
                        $s->bindValue(':discount_details', $selectedProduct->discountDetails);
                        $s->execute();
                        $cart_id = $this->conn->lastInsertId();
                        if($cart == 0){
                            $this->insertPoaList($product_id, $cart_id, $customer_id, $warehouse);
                        }else{
                            $cart_id = $this->getCartId($customer_id, $product_id, $p_type);
                            $this->updatePoaList($product_id, $cart_id, $customer_id, $warehouse);
                        }
                    break;
                    default:
                    break;
                }
                $data = $this->get_product($customer_id, $product_id);
                }catch(PDOexception $e){
                    echo $e;
                }
        }
        return $data;
    }
    public function updatePoaList($product_id, $cart_id, $customer_id, $warehouse){
        $st = $this->conn->prepare('DELETE FROM bg_cart_poa_list WHERE product_id = :product_id AND customer_id = :customer_id');
        $st->bindValue(":product_id", $product_id);
        $st->bindValue(":customer_id", $customer_id);
        $st->execute();
        foreach($warehouse as $value){
            $s = $this->conn->prepare("INSERT bg_cart_poa_list SET 
            option_id = :option_id, option_name = :option_name, poa_id = :poa_id, poa_name = :poa_name, product_id = :product_id, customer_id = :customer_id, cart_id = :cart_id");
            $s->bindValue(":option_id", $value->option_id);
            $s->bindValue(":option_name", $value->option_name);
            $s->bindValue(":poa_id", $value->poa_id);
            $s->bindValue(":poa_name", $value->poa_name);
            $s->bindValue(":product_id", $product_id);
            $s->bindValue(":customer_id", $customer_id);
            $s->bindValue(":cart_id", $cart_id);
            $s->execute();
        }
    }
    public function insertPoaList($product_id, $cart_id, $customer_id, $warehouse){
        // echo var_dump($warehouse);
        foreach($warehouse as $value){
            // echo $value->option_id;
            // echo var_dump($value['option_id']);
            $s = $this->conn->prepare("INSERT bg_cart_poa_list SET 
            option_id = :option_id, option_name = :option_name, poa_id = :poa_id, poa_name = :poa_name, product_id = :product_id, customer_id = :customer_id, cart_id = :cart_id");
            $s->bindValue(":option_id", $value->option_id);
            $s->bindValue(":option_name", $value->option_name);
            $s->bindValue(":poa_id", $value->poa_id);
            $s->bindValue(":poa_name", $value->poa_name);
            $s->bindValue(":product_id", $product_id);
            $s->bindValue(":customer_id", $customer_id);
            $s->bindValue(":cart_id", $cart_id);
            $s->execute();
        }
    }
    public function getCartId($customer_id, $product_id, $p_type){
        $st = $this->conn->prepare("SELECT cart_id FROM oc_cart WHERE customer_id = :customer_id AND product_id = :product_id AND p_type = :p_type");
        $st->bindValue(":product_id", $product_id);
        $st->bindValue(":customer_id", $customer_id);
        $st->bindValue(":p_type", $p_type);
        $st->execute();
        return $st->fetch()['cart_id'];
    }
    public function check_product_from_cart($customer_id, $product_id, $p_type){
        $st = $this->conn->prepare("SELECT * FROM oc_cart WHERE customer_id = :customer_id AND product_id = :product_id AND p_type = :p_type");
        $st->bindValue(":product_id", $product_id);
        $st->bindValue(":customer_id", $customer_id);
        $st->bindValue(":p_type", $p_type);
        $st->execute();
        return $st->rowCount();
    }
    public function product_quantity($seller_id, $product_id){
        // if($seller_id == null || $seller_id == 0){
        //     $s = $this->conn->prepare("SELECT quantity from oc_product where product_id = :product_id");
        //     $s->bindValue(":product_id", $product_id);
        // }else{
            $s = $this->conn->prepare("SELECT * FROM seller_product_selected where product_id = :product_id AND seller_id = :seller_id");
            $s->bindValue(":product_id", $product_id);
            $s->bindValue(":seller_id", $seller_id);
        // }
        $s->execute();
        // if($s->rowCount() > 0){
            return $s->fetch()['quantity'];
        // }else{
        //     return 0;
        // }
    }
    public function ae_product_quantity($product_id){
        $s = $this->conn->prepare("SELECT quantity from aliexpress_products where id = :product_id");
        $s->bindValue(":product_id", $product_id);
        $s->execute();
        return intval($s->fetch()['quantity']);
    }
    public function cart_discounts($product_id){
        global $product;
        $data = array();
        $product_discount = $product->getProductDiscount($product_id);
        foreach($product_discount as $discount){
            $data[] = array(
                'quantity' => intval($discount['quantity']),
                'price' => intval($discount['price'])
            );
        }
        return $data;
    }
    public function countProducts($customer_id){
        $product_total = 0;
        $s = $this->conn->prepare("select SUM(quantity) as total from oc_cart where customer_id = :customer_id");
        $s->bindValue(":customer_id", $customer_id);
        $s->execute();
        return intval($s->fetch()['total']);
    }
    public function getTotalQuantity($customer_id){
        $s = $this->conn->prepare("SELECT SUM(quantity) as total from oc_cart where customer_id = :customer_id");
        $s->bindValue(':customer_id', $customer_id);
        $s->execute();
        return intval($s->fetch()['total']);
    }
    public function multipleDeleteCart($product_ids, $customer_id){
        $data = array();
        foreach(json_decode($product_ids) as $id){
            $st = $this->conn->prepare('DELETE FROM oc_cart WHERE cart_id = :cart_id AND customer_id = :customer_id');
            $st->bindValue(':cart_id', (int) trim($id));
            $st->bindValue(':customer_id', (int) trim($customer_id), PDO::PARAM_INT);
            $st->execute();
        }
        $data['getTotalCart'] = $this->getTotalQuantity($customer_id);
        return $data;
    }
    public function changeQuantity(){
        $s = $this->conn->prepare("UPDATE oc_cart SET quantity = :quantity WHERE customer_id = :customer_id AND cart_id = :cart_id");
        $s->bindValue(':quantity', (int) trim($_GET['set_quantity']), PDO::PARAM_INT);
        $s->bindValue(':customer_id', (int) trim($_GET['customer_id']), PDO::PARAM_INT);
        $s->bindValue(':cart_id', (int) trim($_GET['cart_id']), PDO::PARAM_INT);
        $s->execute();
        $data['getTotalCart'] = $this->getTotalQuantity($_GET['customer_id']);
        return $data;
    }
    public function totalCart($customer_id){
        $s = $this->conn->prepare('SELECT SUM(c.quantity) as total from oc_cart c LEFT JOIN (SELECT p.product_id, p.status FROM oc_product p where p.status = 1 UNION ALL SELECT bg.product_id, bg.status FROM bg_product bg where bg.status = 1 ) pp ON pp.product_id = c.product_id WHERE c.seller_id is not null AND pp.status = 1 AND customer_id = :customer_id');
        $s->bindValue(':customer_id', (int) trim($customer_id), PDO::PARAM_INT);
        $s->execute();
        return $s->fetch()['total']; 
    }
}
$cart = new Cart();