<?php
require_once '../init.php';
require_once SITE_ROOT.'include/banggoodAPI.php';
class Banggood {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    public function getProduct($product_id){
        $data = array();
        global $banggoodAPI;
        global $product;
        $product_bg = $this->product($product_id);
        $params_gps = array('product_id' => $product_bg['product_id']);
        $banggoodAPI->setParams($params_gps);
        $result_gps = $banggoodAPI->getstocks();   
        if($result_gps['code']==0){
            $stock_gps=$result_gps['stocks'][0]['stock_list'][0]['stock'];
            $stock_msg_gps=$result_gps['stocks'][0]['stock_list'][0]['stock_msg'];
        }else{
            $stock_gps=0;
            $stock_msg_gps=$result_gps['msg'];
        }
        $params_pp = array('product_id' => $product_bg['product_id'],'warehouse' => $product_bg['warehouse'],'currency' => 'PHP');
        $banggoodAPI->setParams($params_pp);
        $result_pp = $banggoodAPI->getproductprice();
        if($result_pp['code']==0){
            $price_pp=$result_pp['productPrice'][0]['price'];   
        }else{
            $price_pp=$product_bg['price'];
        }
        $params_pi = array('product_id' =>$product_bg['product_id']);
        $banggoodAPI->setParams($params_pi);
        $res = $banggoodAPI->getProductInfo();
        if($res['code']==0){
            $product_desc=1;
            $p_imagelist=$res['image_list']['view'];
            $p_description=$res['description'];
        }else{
            $product_desc=0;
        }
        
        $params_gs = array('product_id' =>$product_bg['product_id'],'warehouse' => $product_bg['warehouse'],'quantity' => 1,'currency'=>'PHP','country'=>'Philippines');
        $banggoodAPI->setParams($params_gs);
        $result_gs = $banggoodAPI->getShipments();
        $shipFee = 0;
        $shipday = '';
        if($result_gs['code'] == 0){
            if(isset($result_gs['shipmethod_list'])){
                 $shipFee = (float) $result_gs['shipmethod_list'][0]['shipfee'];
                $shipday = $result_gs['shipmethod_list'][0]['shipday'];
            }
        }
        //echo print_r($res);
        $data = array(
            'hasShipMethod' => isset($result_gs['shipmethod_list']) ? true : false,
            'productId' => $product_id,
            'name' => $product_bg['product_name'],
            'price' => round((float) $price_pp + $shipFee, 2),
            'oldPrice' => false,
            'thumb' => $product_bg['img'],
            'image' => $product_desc == 1 ? $this->images($p_imagelist) : [$product_bg['img']],
            'description' => $product_desc ? html_entity_decode(utf8_encode($res['description'])) : '',
            'rating' => 0,
            'quantity' => $stock_gps,
            'tag' => 'test',
            'viewed' => 0,
            'totalSold'=> '10 sold',
            'warehouse' => isset($res['poa_list']) ? $this->getStock($res['poa_list'], $result_pp, $result_gps, $product_id, $shipFee, $product_bg['img']) : [],
            'store' => $product->store_by_id(null),
            'freebie'=> [],
            'discount' => [],
            'reviews' => [],
            'detail'  => array(
                array(
                    'label' => 'Ship From',
                    'value' => $product_bg['warehouse']
                ), 
                  array(    
                    'label' => 'Shipping',
                    'value' => '₱'.$shipFee
                ), 

            ),
            'attribute' => [],
            'error' => $result_gps['code'] == 0 ? '' :  $result_gps['msg'],
            'storeList' => [
                    array(
                        'image' => 'https://pesoapp.ph/img/mobile/discover/peso-global.webp',
                       'shopName' => 'PESO Global',
                       'sellerId' => null,
                       'deductionData' =>  [],
                       'thumb' => 'https://pesoapp.ph/img/mobile/discover/peso-global.webp',  
                    )
                ],
        );
        if($result_gs['code'] == 0){
            if(isset($result_gs['shipmethod_list'])){
                if($shipday != ''){
                    array_push($data['detail'], array(
                    'label' => 'Shipping Time',
                    'value' => $shipday .' business days'
                ));
                }
                
            }
        }
        return $data;
    }
    public function images($image){
        $data = array();
        foreach($image as $img){
            $data[] = $img;
        }
        return $data;
    }
    public function getStock($poa_list, $price, $stock, $product_id, $shipFee, $defaultImage){
        $data = array();
        $i = 0;
        $j = 0;
        if(count($poa_list) == 1){
                    foreach($poa_list as $key => $value){
                            $data[$key]['label'] = $value['option_name'];
                            $data[$key]['id'] = $value['option_id'];
                            foreach($value['option_values'] as $list){
                                if(isset($list['view_image'])){
                                    $img = $list['view_image'];
                                }else{
                                    $img = $defaultImage;
                                }
                                $data[$key]['value'][] = array(
                                        'id' => $list['poa_id'],
                                        'name' => $list['poa_name'],
                                        'code' => $list['poa'],
                                        'stock' => $stock['stocks'][$i]['stock_list'][$j]['stock'],
                                        'img' => $img ,
                                        'price' => ((float)$price['productPrice'][$j]['price'] + $shipFee),
                                        'add_price' => $list['poa_price'],
                                        'selected' => false,
                                        'display' => true
                                );
                                $j++;
                            }
                            $i++;
                    }
        }
        if(count($poa_list) >= 2){
              foreach($poa_list as $key => $value){
                            $data[$key]['label'] = $value['option_name'];
                            $data[$key]['id'] = $value['option_id'];
                                if($key == 0){
                                    foreach($value['option_values'] as $list){
                                    $data[$key]['value'][] = array(
                                        'id' => $list['poa_id'],
                                        'name' => $list['poa_name'],
                                        'add_price' => $list['poa_price'],
                                        'selected' => false,
                                        'display' => true
                                    );
                                    }
                                }else{
                                    $data[$key]['value'] = $this->stockAndPriceFilter($price, $stock, $value['option_values'], $product_id, $shipFee);
                                    //  $data[$key]['value'][] = array(
                                    //     'id' => $list['poa_id'],
                                    //     'name' => $list['poa_name'],
                                    //     'stock' => $stock['stocks'][$i]['stock_list'][$j]['stock'],
                                    //     'img' => $list['view_image'],
                                    //     'price' => $price,
                                    //     'add_price' => $list['poa_price'],
                                    //     'selected' => false
                                    // );
                                }
               }
        }

        return $data;
    }
    public function stockAndPriceFilter($prices, $stocks, $values, $product_id, $shipFee){
        $data = array();
        $i = 0;
        $price = 0;
        $bg_product = $this->product($product_id);
        foreach($stocks['stocks'][0]['stock_list'] as $stock){
            if($prices['code'] == 0){
                $price = $prices['productPrice'][$i]['price'];
            }else{
                $price = $bg_product['price'];
            }
            $ids = explode(',', $stock['poa_id']);
            $key = array_search($ids[1], array_column($values, 'poa_id'));
            // $key = array_search($ids[1], $values);
            $data[] = array(
                'parent_id' => $ids[0],
                'id' => $ids[1],
                'name' => $values[$key]['poa_name'],
                'add_price' => $values[$key]['poa_price'],
                'price' => (float)$price + $shipFee,
                'img' => $values[$key]['view_image'] !== null ? $values[$key]['view_image'] : $bg_product['img'],
                'stock' => $stock['stock'],
                'msg' => $stock['stock_msg'],
                'selected' => false,
                'key' => $key,
                'display' => true
            );
            $i++;
        }
        return $data;
    }
    public function product($product_id){
        $stmt = $this->conn->prepare("SELECT * from bg_product WHERE product_id = :product_id");
        $stmt->bindValue(':product_id', $product_id);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function getCategoryName($category_id){
        $stmt = $this->conn->prepare("SELECT * from bg_product_category WHERE cat_id = :cat_id");
        $stmt->bindValue(':cat_id', $category_id);
        $stmt->execute();
        return $stmt->fetch()['name'];
    }
    public function getShipping($product_id, $warehouse, $quantity){
        global $banggoodAPI;
        $data = array();
        $params_gs = array('product_id' =>$product_id,'warehouse' => $warehouse,'quantity' => $quantity,'currency'=>'PHP','country'=>'Philippines');
        $banggoodAPI->setParams($params_gs);
        $result_gs = $banggoodAPI->getShipments();
        if(isset($result_gs['shipmethod_list'])){
            $res = $result_gs['shipmethod_list'][0];
        }else{
            $res = null;
        }
        return $res;
    }
    public function importBGProducts($order_id, $method){
        global $banggoodAPI;
        $stats="";
        $sale_record_id=$order_id;
        $currency='PHP';
        $country='Philippines';
        $p_info_arr=array();
        $ptotal=0;
        $d_address=$this->order_address_det($sale_record_id) ;
        foreach($this->order_product_det($sale_record_id) as $opd){
            $ptotal++;
            $p_info_arr[]=array(
                'product_id' => $opd['product_id'],
                'quantity' => $opd['quantity'],
                'warehouse' =>  $opd['warehouse'], 
                'poa_id' =>  $opd['poa_ids'],
                'shipmethod_code' => $opd['shipping_code'],
            );
        }
        $params = array();
        $params['sale_record_id']           = $sale_record_id;  //your db record id
        $params['delivery_country']         =  $country;


        if($method!='cod'){
           $params['delivery_name']            = $d_address['firstname'].' '. $d_address['lastname'];
           $params['delivery_street_address']  = $d_address['shipping_address_1'];
           $params['delivery_street_address2'] = $d_address['shipping_address_2'];
           $params['delivery_state']           = $d_address['shipping_city'];
           $params['delivery_city']            = $d_address['shipping_zone'];
           $params['delivery_postcode']        = $d_address['shipping_postcode'];
           $params['delivery_telephone']       = $d_address['telephone'];
       }else{
           $params['delivery_name']            = 'Orly Cuartero';
           $params['delivery_street_address']  = '4/flr 201 Delmonte Ave Masambong';
           $params['delivery_street_address2'] = '';
           $params['delivery_state']           = 'Quezon City';
           $params['delivery_city']            = 'Metro Manila';
           $params['delivery_postcode']        = '1105';
           $params['delivery_telephone']       = '639171123183';
       }
        $params['currency']                 = $currency;
        $params['remark']                   = 'some remark';
        $params['product_total']            = $ptotal; 
        $params['product_list'] = $p_info_arr;
        $banggoodAPI->setParams($params);
        $result = $banggoodAPI->importOrder();
        if($result['code']==0){
             $stats="200";
        }else if($result['code']==1){
           $stats=$result['failure_list'][0]['error_desc'] ;   
            
        }else{
             $stats= $result['mgs'];    
        }
        return $stats;
    }
    public function order_product_det($order_id){
        $select_bg_o = $this->conn->prepare("SELECT bgp.warehouse,oop.* FROM `oc_order_product` oop inner join bg_product bgp on bgp.product_id=oop.product_id where order_id=:order_id");
        $select_bg_o->bindValue(':order_id', $order_id);
        $select_bg_o->execute();
        $order_products = $select_bg_o->fetchAll();
        return $order_products;
    }
    public function order_address_det($order_id){
        $select_bg_a = $this->conn->prepare("SELECT * FROM oc_order where order_id=:order_id");
        $select_bg_a->bindValue(':order_id', $order_id);
        $select_bg_a->execute();
        $bg_order_a = $select_bg_a->fetch();
        return $bg_order_a;
    } 
    public function trackAndOrderInfo($order_id){
        global $banggoodAPI;
        $bg_order_id=0;
								$bg_order_status="";
								$bg_Track_Info="";
								$track_number_bg="";
        								$params_goi = array('sale_record_id' => $order_id,'lang' => 'en');
										$banggoodAPI->setParams($params_goi);
										$result_goi = $banggoodAPI->getOrderInfo();
										$status_goi=$result_goi['code'];
										
										if($status_goi==0){
										    if(count($result_goi['sale_record_id_list']) > 0){
										        $bg_order_id=$result_goi['sale_record_id_list'][0]['order_list'][0]['order_id'];
											    $bg_order_status=$result_goi['sale_record_id_list'][0]['order_list'][0]['status'];
											    
											    //get GetTrackInfo API
    											$params_gti = array('order_id' =>$bg_order_id,'lang' => 'en');
    											$banggoodAPI->setParams($params_gti);
    											$result_gti = $banggoodAPI->getTrackInfo();
    											if(count($result_gti['track_info']) > 0){
    											    $bg_Track_Info=$result_gti['track_info'][0]['event']. "(".$result_gti['track_info'][0]['time'].")";
    											}
    												//GetOrderHistory API
    											$params_goh = array('sale_record_id' => $_GET['order_id'],'order_id' =>$bg_order_id,'lang' => 'en');
    											$banggoodAPI->setParams($params_goh);
    											$result_goh = $banggoodAPI->getOrderHistory();
    											$track_number_bg=$result_goh['track_number'];
										    }

										}else{
											$bg_order_id=0;
											$bg_order_status=$result_goi['msg'];
											$bg_Track_Info="";
										}
		return array(
		    'bg_order_id' => $bg_order_id,
		    'bg_order_status' => $bg_order_status,
		    'bg_track_info' => $bg_Track_Info,
		    'bg_track_number' =>$track_number_bg
		    );
    }
}
$banggood = new Banggood();