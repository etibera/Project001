<?php
require_once '../init.php';
class Checkout {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    function __destruct(){
        $this->conn = null;
    }
    public function sendOrderEmail($order_status_id, $order_id){
        global $mail;
        $mail->SendEmailallstoreAdmin($order_status_id, $order_id, '');
        $mail->SendEmailallstoreCustomer($order_status_id, $order_id, '');
    }
    public function checkoutDetails($product_ids, $customer_id, $total_price){
        global $address;
        $product_ids = implode(",", json_decode(htmlspecialchars_decode($product_ids), true));
        $data = array();
        $data['store'] = $this->get_cart_local_perstore($customer_id, $product_ids);
        
        $flatShippingPerStore= array_sum(array_column($data['store'], 'flatRate'));

        $data['shippingRate'] = $this->shippingRateData($product_ids, $customer_id, $total_price, $flatShippingPerStore);
        $data['text_payment'] = 'Your order will not ship until we receive payment.';
        $data['bank'] = "account number: 7-590-68106-8 <br> account name: PC VILL, INC<br>Bank Name : RCBC<br>";
        return $data;
    }
    public function get_cart_local_perstore($customer_id,$cart_idstr){
        global $image;
        $stores = array();
        //$cart_idstr = implode(",", $cart_ids);
        $select_stmt = $this->conn->prepare("SELECT os.shop_name,oc.cart_id,oc.seller_id,
                                                concat('company/',os.image) as image 
                                            FROM oc_cart oc
                                            INNER JOIN oc_seller os ON oc.seller_id = os.seller_id 
                                            WHERE oc.customer_id=:customer_id and oc.p_type=:p_type and oc.cart_id  IN (".$cart_idstr.")");
        $select_stmt->bindValue(':customer_id', $customer_id);
        $select_stmt->bindValue(':p_type',0);
        $select_stmt->execute();
        $storeData = $select_stmt->fetchAll();
        foreach ($storeData as $row) {
            $total = $this->get_cart_store_subtotal($customer_id,$cart_idstr,$row['seller_id']);
            $insurance_fee=0;

            if($total>500){  
                $insurance_fee=$total*0.01;              
            }
            $stores[] = array(
                'sellerId' => $row['seller_id'],
                'creditCard' => round(($total / .972) - $total, 2),
                'maxxPayment' => round($total * 0.015, 2),
                'flatRate' => $this->flatRatePerStore($customer_id,$cart_idstr,$row['seller_id']),
                'subTotal' => round($total, 2),
                'branches' => $this->storeAddress($row['seller_id'], $customer_id),
                'insuranceFee' => $insurance_fee
                // 'thumb' => $image->resize($row['image'], 70,70),
                // 'details' => $this->get_cart_local_latest($customer_id,$cart_idstr,$row['seller_id']),
            );
        }
        return $stores; 
    }
    public function storeAddress($seller_id, $customer_id){
        global $address;
        $adr = $address->selectedAddress($customer_id);
        $data = array();
        $stm = $this->conn->prepare("SELECT * FROM seller_branch WHERE seller_id=:seller_id");
        $stm->bindValue(':seller_id', $seller_id);
        $stm->execute();
        $addresses = $stm->fetchAll();
        $checked = false;
        $hasCheckedTrue = 0;
        foreach ($addresses as $row) {
                $data[] = array(
                    'sellerId' => $seller_id,
                    'value' => $row['id'],
                    'type'=> 'radio',
                    'checked' => $checked,
                    'branch_id' => $row['id'],
                    'error' => 0,
                    'label' => $row['b_name']
                );
        }
        if(count($data) > 0){
            $data[0]['checked'] = true;
        }
        return $data;
    }
    public function country($country_id){
        $country = $this->conn->prepare("SELECT name FROM oc_country WHERE country_id = :country_id");
        $country->execute([':country_id'=> $country_id]);
        $c = $country->fetch();
        return $c['name'];
    }
    public function get_cart_store_subtotal($customer_id,$cart_idstr,$seller_id){
        $subtotal = array();
        $select_stmt = $this->conn->prepare("SELECT sum((oc.price * oc.quantity)) as subtotal
                                            FROM oc_cart oc
                                            LEFT JOIN oc_product p ON oc.product_id = p.product_id 
                                            LEFT JOIN oc_product_description pd ON (p.product_id = pd.product_id) 
                                            INNER JOIN oc_seller os ON (oc.seller_id = os.seller_id)
                                            WHERE oc.customer_id=:customer_id 
                                                AND p.status = '1' and oc.p_type=:p_type 
                                                AND oc.seller_id=:seller_id AND oc.cart_id  IN (".$cart_idstr.") ");
        $select_stmt->bindValue(':customer_id', $customer_id);
        $select_stmt->bindValue(':p_type',0);
        $select_stmt->bindValue(':seller_id',$seller_id);
        $select_stmt->execute();
        $subtotal = $select_stmt->fetch();
        return $subtotal['subtotal']; 
    }
    public function flatRatePerStore($customer_id,$chk_cart_id,$seller_id){       
        $shipping_cost=0;
        $cart_idstr2 = $chk_cart_id;
        $select_procuct = $this->conn->prepare("SELECT SUM(OC.quantity) quantity,OC.product_id,OC.cart_id as cid 
                                                    FROM oc_cart OC 
                                                    INNER JOIN oc_product_delivery_charge PDC 
                                                    ON OC.product_id=PDC.product_id  
                                                    WHERE OC.customer_id = :customer_id 
                                                        AND OC.p_type=0 
                                                        AND OC.seller_id=:seller_id
                                                        AND OC.cart_id  IN (".$cart_idstr2.")
                                                    group by PDC.delivery_charge_id,OC.quantity,OC.product_id,OC.cart_id");
        $select_procuct->bindValue(':customer_id', $customer_id);
        $select_procuct->bindValue(':seller_id', $seller_id);
        $select_procuct->execute();       
        $products = $select_procuct->fetchAll();
        foreach ($products as $product) {
            $product_total = $product['quantity'];
            $product_total_id= $product['product_id'];
            $selec_amount = $this->conn->prepare("SELECT DC.* from oc_product_delivery_charge PDC INNER JOIN oc_delivery_charge DC ON PDC.delivery_charge_id=DC.id where PDC.product_id=:product_total_id");
             $selec_amount->bindValue(':product_total_id',  $product_total_id);
             $selec_amount->execute();
             $queryamount=$selec_amount->fetch(PDO::FETCH_ASSOC);

            $shipping_cost+=$queryamount['provincial_amount'];
        }
        return $shipping_cost;
    }
    public function get_cart_product_installment($customer_id,$cart_ids){
        
        $cart_idstr = $cart_ids;
        $data = array();
        $select_stmt = $this->conn->prepare(" SELECT count(oc.cart_id) as count from oc_cart oc
                                            INNER JOIN seller_product_selected sps 
                                                ON sps.product_id=oc.product_id AND sps.seller_id=oc.seller_id
                                            WHERE oc.customer_id=:customer_id AND sps.card_installment=:card_installment 
                                                AND oc.cart_id  IN (".$cart_idstr.") ");
        $select_stmt->bindValue(':customer_id', $customer_id);
        $select_stmt->bindValue(':card_installment',0);
        $select_stmt->execute();
        $data = $select_stmt->fetch();
        return $data['count'];
     }
    public function get_cart_product_cod($customer_id,$cart_ids){
        $cart_idstr = $cart_ids;
        $data = array();
        $select_stmt = $this->conn->prepare(" SELECT count(oc.cart_id) as count from oc_cart oc
                                            INNER JOIN seller_product_selected sps 
                                                ON sps.product_id=oc.product_id AND sps.seller_id=oc.seller_id
                                            WHERE oc.customer_id=:customer_id AND sps.cod=:cod 
                                                AND oc.cart_id  IN (".$cart_idstr.") ");
        $select_stmt->bindValue(':customer_id', $customer_id);
        $select_stmt->bindValue(':cod',0);
        $select_stmt->execute();
        $data = $select_stmt->fetch();
        return $data['count'];
    }
    public function shippingRateData($product_ids, $customer_id, $total_price, $flatShippingPerStore){
        $codAmountValidate = $this->validateAmount($total_price);
        $installment = $this->get_cart_product_installment($customer_id, $product_ids);
        $codSellerValidate = $this->get_cart_product_cod($customer_id,$product_ids);
        $disabledCod = true;
        if($codAmountValidate == ""){
            if($codSellerValidate == 0){
                $disabledCod = false;
            }else{
                $disabledCod = true;
            }
        }
		$sr = array();
		$sr[] = array(
			'name' => 'Flat Shipping Rate',
			'total' => $flatShippingPerStore,
			'value' => 'flat.flat',
			'disable' => false,
			// 'message' => 'Not available during ECQ'
		);
		$sr[] = array(
			'name' => 'Pickup From Store',
			'total' => 0,
			'value' => 'pickup.pickup',
			'disable' => true
		);
		$sr[] = array(
			'name' => 'Customer handles shipping upon arrival',
			'total' => 0,
			'value' => 'shipping_fee',
			'disable' => false
		);
		$pm = array();
		$pm[] = array(
			'name' => 'Bank Transfer',
			'value' => 'bank_transfer',
			'disable' => false
		);
		$pm[] = array(
			'name' => 'Cash on Delivery',
			'value' => 'cod',
			'disable' => $disabledCod,
			'message' => $codAmountValidate
		);
// 		$pm[] = array(
// 			'name' => 'Cash Payment Upon Pickup',
// 			'value' => 'cpup',
// 			'disable' => false
// 		);
		$pm[] = array(
			'name' => 'Cards and Other Payment Method',
			'value' => 'credit_card',
			'disable' => false
		);
		$pm[] = array(
			'name' => 'BDO Card Installment',
			'value' => 'maxx_payment',
			'disable' => $installment == 0 ? false : true
		);
		return array('shipRate' => $sr, 'paymentMethod' => $pm );
    }
    public function validateAmount($total_price){
        $total = (float) $total_price;
        return $total > 50000.00 ? "It can't be used when your purchase is more than 50,000 or product is not available for COD by the store" : "";
    }
    public function flat($product_ids, $customer_id){
        
        $select_procuct = $this->conn->prepare("SELECT SUM(OC.quantity) quantity,OC.product_id FROM oc_cart OC INNER JOIN oc_product_delivery_charge PDC ON OC.product_id=PDC.product_id  WHERE OC.customer_id = :u AND OC.cart_id IN (".$product_ids.") group by PDC.delivery_charge_id ");
        $select_procuct->execute([':u'=> $customer_id]);
        $products = $select_procuct->fetchAll();
      //   $pm = array();
        $shipping_cost=0;
        foreach ($products as $product) {
            $product_total = $product['quantity'];
            $product_total_id= $product['product_id'];
               

            $selec_amount = $this->conn->prepare("SELECT DC.* from oc_product_delivery_charge PDC INNER JOIN oc_delivery_charge DC ON PDC.delivery_charge_id=DC.id where PDC.product_id=:product_total_id");
             $selec_amount->bindValue(':product_total_id',  $product_total_id);
             $selec_amount->execute();
             $queryamount=$selec_amount->fetch(PDO::FETCH_ASSOC);

            $queryamountval=$queryamount['amount'];
            $querymax_qty=$queryamount['max_quantity'];
            $querymax_qty_con=$queryamount['convert_quantity'];
            
             if($product_total<=$querymax_qty){
                $shipping_cost+=$queryamountval;
            }else{
                $modulo=$product_total%$querymax_qty;
                if($modulo==0){
                    $convert_id=$queryamount['convert_id'];
                    $boxQTY=$product_total/$querymax_qty;
                    if($boxQTY<$querymax_qty_con){
                        $querymax_qty_bybox=$boxQTY*$queryamount['amount'];
                        $shipping_cost+=$querymax_qty_bybox;
                    }else if($convert_id==0){
                        $querymax_qty_bybox=$boxQTY*$queryamount['amount'];
                        $shipping_cost+=$querymax_qty_bybox;
                    }else{
                        $select_convert = $this->conn->prepare("SELECT * from oc_delivery_charge PDC  where id=:convert_id");
                        $select_convert->bindValue(':convert_id',  $convert_id);
                        $select_convert->execute();
                        $queryconvert=$select_convert->fetch(PDO::FETCH_ASSOC);
                        $convert_amount=$queryconvert['amount'];

                        $modulo_convert=$boxQTY%$querymax_qty_con;
                        if($modulo_convert==0){
                            $box_convert_QTY=$boxQTY/$querymax_qty_con;
                            $box_convert_amount=$box_convert_QTY*$convert_amount;
                            $shipping_cost+=$box_convert_amount;
                        }else{
                            $box_convert_QTY=intdiv($boxQTY, $querymax_qty_con);
                            $box_convert_amount=$box_convert_QTY*$convert_amount;

                            $box_remain=$modulo_convert;
                            $box_convert_amount_remain=$box_remain*$queryamount['amount'];
                            $box_total_con_amount=$box_convert_amount+$box_convert_amount_remain;
                            $shipping_cost+=$box_total_con_amount;
                        }
                        
                    }
                
                }else{
                    $boxQTYw=intdiv($product_total, $querymax_qty);
                    $boxQTY=$boxQTYw+1;
                    $convert_id=$queryamount['convert_id'];
                    if($boxQTY<$querymax_qty_con){
                        $querymax_qty_bybox=$boxQTY*$queryamount['amount'];
                        $shipping_cost+=$querymax_qty_bybox;
                    }else if($convert_id==0){
                        $querymax_qty_bybox=$boxQTY*$queryamount['amount'];
                        $shipping_cost+=$querymax_qty_bybox;
                    }else{
                        

                        $select_convert = $this->conn->prepare("SELECT * from oc_delivery_charge PDC  where id=:convert_id");
                        $select_convert->bindValue(':convert_id',  $convert_id);
                        $select_convert->execute();
                        $queryconvert=$select_convert->fetch(PDO::FETCH_ASSOC);
                        $convert_amount=$queryconvert['amount'];

                        $modulo_convert=$boxQTY%$querymax_qty_con;
                        if($modulo_convert==0){
                            $box_convert_QTY=$boxQTY/$querymax_qty_con;
                            $box_convert_amount=$box_convert_QTY*$convert_amount;
                            $shipping_cost+=$box_convert_amount;
                        }else{
                            $box_convert_QTY=intdiv($boxQTY, $querymax_qty_con);
                            $box_convert_amount=$box_convert_QTY*$convert_amount;

                            $box_remain=$modulo_convert;
                            $box_convert_amount_remain=$box_remain*$queryamount['amount'];
                            $box_total_con_amount=$box_convert_amount+$box_convert_amount_remain;
                            $shipping_cost+=$box_total_con_amount;
                        }
                        
                    }

                }
                
            }
        }
        return $shipping_cost;
    }
    public function checkRegion($address_id){
        $s = $this->conn->prepare('SELECT * FROM oc_address WHERE address_id = :address_id');
        $s->bindValue(':address_id', $address_id);
        $s->execute();
        $region = $s->fetch()['region'];
        if($region == null || $region == '' || $region == 'null'){
            throw new Exception('Please update your app to proceed this order');
        }
    }
    public function addOrder($token){
        global $auth;
        global $address;
        global $banggood;
        $data = array();
        $customer_id = (int) trim($_POST['customer_id']);
        $totals = json_decode(htmlspecialchars_decode($_POST['totals']), true);
        $payment_method = json_decode(htmlspecialchars_decode($_POST['payment_method']), true);
        $customer = $auth->customer_info($customer_id);
        $order_status_id = (int)trim($_POST['order_status_id']);
        $productIds = implode(",", json_decode(htmlspecialchars_decode($_POST['product_ids']), true));
        $totalsPerStore = json_decode(htmlspecialchars_decode($_POST['totals_per_store']), true);
        
        try{
            $this->checkRegion($_POST['shipping_address_id']);

            $stm = $this->conn->prepare("INSERT INTO oc_order SET 
            invoice_prefix = 'PCV-2019-00000',
            store_name = 'Pinoy Electronics Store Online',
            store_url = 'https://pesoapp.ph/',
            custom_field = '',
            payment_address_format = '',
            payment_custom_field = '[]',
            shipping_address_format = '',
            shipping_custom_field = '[]',
            comment = '',
            affiliate_id= 0,
            commission= 0.0000,
            marketing_id= 0,
            tracking= '',
            language_id= 1,
            currency_id= 4,   
            currency_code = 'PHP', 
            forwarded_ip = '', 
            accept_language = 'en-US,en;q=0.9', 
             date_added = convert_tz(utc_timestamp(),'-08:00','+0:00'), 
             date_modified = convert_tz(utc_timestamp(),'-08:00','+0:00'),
            customer_id = :customer_id, 
            firstname = :firstname, 
            lastname = :lastname, 
            email = :email, 
            telephone = :telephone, 
            fax = :fax, 
            payment_firstname = :payment_firstname, 
            payment_lastname = :payment_lastname,
            payment_company = :payment_company,
            payment_address_1 = :payment_address_1,
            payment_address_2 = :payment_address_2, 
            payment_city = :payment_city, 
            payment_postcode = :payment_postcode, 
            payment_country = :payment_country, 
            payment_country_id = :payment_country_id, 
            payment_zone = :payment_zone, 
            payment_zone_id = :payment_zone_id, 
            payment_method = :payment_method, 
            payment_code = :payment_code, 
            shipping_firstname = :shipping_firstname, 
            shipping_lastname = :shipping_lastname, 
            shipping_company = :shipping_company, 
            shipping_address_1 = :shipping_address_1, 
            shipping_address_2 = :shipping_address_2, 
            shipping_city = :shipping_city, 
            shipping_postcode = :shipping_postcode, 
            shipping_country = :shipping_country, 
            shipping_country_id = :shipping_country_id, 
            shipping_zone = :shipping_zone, 
            shipping_zone_id = :shipping_zone_id, 
            shipping_method = :shipping_method, 
            shipping_code = :shipping_code,
            total = :total, 
            ip = :ip, 
            user_agent = :user_agent,
            order_status_id = :order_status_id,
            payment_district = :payment_district,
            payment_region = :payment_region,
            shipping_district = :shipping_district,
            shipping_region = :shipping_region
            ");
            $b_address = $address->getAddress($_POST['payment_address_id'], $_POST['customer_id']);
            $d_address = $address->getAddress($_POST['shipping_address_id'], $_POST['customer_id']);
             $stm->bindValue(':customer_id', $customer_id);
             $stm->bindValue(':firstname', $customer['firstname']);
             $stm->bindValue(':lastname', $customer['lastname']);
             $stm->bindValue(':email', $customer['email']);
             $stm->bindValue(':telephone', $customer['telephone']);
             $stm->bindValue(':fax', $customer['fax']);
             $stm->bindValue(':payment_firstname', utf8_encode($b_address['firstname']));
             $stm->bindValue(':payment_lastname', utf8_encode($b_address['lastname']));
             $stm->bindValue(':payment_company', utf8_encode($b_address['company']));
             $stm->bindValue(':payment_address_1', utf8_encode($b_address['address_1']));
             $stm->bindValue(':payment_address_2', utf8_encode($b_address['address_2']));
             $stm->bindValue(':payment_city', utf8_encode($b_address['city']));
             $stm->bindValue(':payment_postcode', $b_address['postcode']);
             $stm->bindValue(':payment_country', $b_address['country']);
             $stm->bindValue(':payment_country_id', $b_address['country_id']);
             $stm->bindValue(':payment_zone', $b_address['zone']);
             $stm->bindValue(':payment_zone_id', $b_address['zone_id']);
             $stm->bindValue(':payment_district', $b_address['district']);
             $stm->bindValue(':payment_region', $b_address['region']);
             $stm->bindValue(':payment_method', $payment_method['name']);
             $stm->bindValue(':payment_code', $payment_method['value']);
             $stm->bindValue(':shipping_firstname', $d_address['firstname']);
             $stm->bindValue(':shipping_lastname', $d_address['lastname']);
             $stm->bindValue(':shipping_company', $d_address['company']);
             $stm->bindValue(':shipping_address_1', $d_address['address_1']);
             $stm->bindValue(':shipping_address_2', $d_address['address_2']);
             $stm->bindValue(':shipping_city', $d_address['city']);
             $stm->bindValue(':shipping_postcode', $d_address['postcode']);
             $stm->bindValue(':shipping_country', $d_address['country']);
             $stm->bindValue(':shipping_country_id', $d_address['country_id']);
             $stm->bindValue(':shipping_zone', $d_address['zone']);
             $stm->bindValue(':shipping_zone_id', $d_address['zone_id']);
             $stm->bindValue(':shipping_method', $totals[1]['title']);
             $stm->bindValue(':shipping_code', $totals[1]['code']);
             $stm->bindValue(':shipping_district', $d_address['district']);
             $stm->bindValue(':shipping_region', $d_address['region']);
             $stm->bindValue(':total', end($totals)['value']);
             $stm->bindValue(':ip', $_SERVER['REMOTE_ADDR']);
             $stm->bindValue(':user_agent', $_SERVER['HTTP_USER_AGENT']);
             $stm->bindValue(':order_status_id', $order_status_id);
             $stm->execute();
             $lastId = $this->conn->lastInsertId();
            $comment = '';
    
            if($payment_method['value'] == 'bank_transfer'){
                $comment  = "account number : 7-590-68106-8\r\account name: PC VILL, INC\r\nBank Name : RCBC\r\n";
            }
            $this->insertOrderProduct($productIds, $customer_id, $lastId, $token);
            $this->insertOrderTotal($totals, $lastId);
            // if(count($totalsPerStore) > 0){
                $this->SaveTotalsPerStore($totalsPerStore, $lastId);
                $this->SaveShippingPerStore($totalsPerStore, $lastId, $order_status_id);
            // }
            if($order_status_id !== 0){
                if($this->bgProductCount($lastId, 2) > 0){
                  $res = $banggood->importBGProducts($lastId, $payment_method['value']);
                  if($res == '200'){
                    $this->deleteFromCart($productIds, $customer_id);
                    $this->addOrderHistory($lastId,  $order_status_id, $comment);
                    foreach($totals as $key => $value){
                        if($value['title'] == 'Cash Wallet'){
                            $this->insertCashWallet($lastId, $customer_id, $value['value']);
                        }
                        if($value['title'] == 'Discount Wallet'){
                            $this->insertDiscountWallet($lastId, $customer_id, $value['value']);
                        }
                    }
                  }else{
                      $data['error'] = $res;
                  }
                }else{
                    $this->deleteFromCart($productIds, $customer_id);
                    $this->addOrderHistory($lastId,  $order_status_id, $comment);
                    foreach($totals as $key => $value){
                        if($value['title'] == 'Cash Wallet'){
                            $this->insertCashWallet($lastId, $customer_id, $value['value']);
                        }
                        if($value['title'] == 'Discount Wallet'){
                            $this->insertDiscountWallet($lastId, $customer_id, $value['value']);
                        }
                        if($value['title'] == 'Shipping Wallet'){
                            $this->insertShippingWallet($lastId, $customer_id, $value['value']);
                        }
                    }
                }
                
            }
            $data['orderId'] = $lastId;

            
            
           
            return $data;

        }catch(Exception $e){
            $error_message = $e->getMessage();
            echo $error_message;
            exit();
        }
   
    }
    public function SaveShippingPerStore($shipping_per_store,$order_id, $order_status_id){
        $shippingValue = array('flat_rate','special_del', 'store_pickup', 'customer_handel');
        foreach($shipping_per_store as $t){
            if(in_array($t['code'], $shippingValue)){
                $add_sps= $this->conn->prepare("INSERT INTO order_shipping_per_sotre  SET order_id = :order_id, title = :title, value = :value, seller_id = :seller_id");
                $add_sps->bindValue(':order_id', $order_id);
                $add_sps->bindValue(':title', $t['title']);
                $add_sps->bindValue(':value', $t['value']);                                
                $add_sps->bindValue(':seller_id', $t['sellerId']);
                $add_sps->execute(); 

                $addStoreOrders= $this->conn->prepare("INSERT INTO store_orders  SET order_id = :order_id, seller_id = :seller_id, branch_id = :branch_id");
                $addStoreOrders->bindValue(':order_id', $order_id);                            
                $addStoreOrders->bindValue(':seller_id', $t['sellerId']);
                $addStoreOrders->bindValue(':branch_id', $t['branchId']);
                $addStoreOrders->execute(); 

                $updatesps = $this->conn->prepare("INSERT INTO order_status_per_store 
                SET order_id =:order_id,seller_id=:seller_id,
                                order_status_id = :order_status_id");
                $updatesps->bindValue(':order_id', $order_id);
                $updatesps->bindValue(':order_status_id', $order_status_id);
                $updatesps->bindValue(':seller_id', $t['sellerId']);
                $updatesps->execute();
            }
        }   
    }
    public function SaveTotalsPerStore($total_per_store,$order_id){
        foreach($total_per_store as $totals){
                $sub_total = $this->conn->prepare("INSERT INTO order_total_per_store SET order_id = :order_id,seller_id=:seller_id, code = :code, title = :title, value = :value, sort_order = :sort_order");
                $sub_total->bindValue(':order_id', $order_id);
                $sub_total->bindValue(':seller_id', $totals['sellerId']);
                $sub_total->bindValue(':code', $totals['code']);
                $sub_total->bindValue(':title', $totals['title']);
                $sub_total->bindValue(':value', $totals['value']);                 
                $sub_total->bindValue(':sort_order', $totals['sortOrder']);
                $sub_total->execute();
        }
        // $sub_total = $this->conn->prepare("INSERT INTO order_total_per_store SET order_id = :order_id,seller_id=:seller_id, code = :code, title = :title, value = :value, sort_order = :sort_order");
        // $sub_total->bindValue(':order_id', $order_id);
        // $sub_total->bindValue(':seller_id', $tps['sellerId']);
        // $sub_total->bindValue(':code', $tps['code']);
        // $sub_total->bindValue(':title', $tps['title']);
        // $sub_total->bindValue(':value', $tps['value']);                 
        // $sub_total->bindValue(':sort_order', $tp['sortOrder']);
        // $sub_total->execute();
    }
    public function insertAliexpressPendingProduct($order_id, $p_type){
        $st = $this->conn->prepare("SELECT * FROM oc_order_product where order_id = :order_id AND p_type = :p_type");
        $st->bindValue(':order_id', $order_id, PDO::PARAM_INT);
        $st->bindValue(':p_type', $p_type, PDO::PARAM_INT);
        $st->execute();
        if($st->rowCount() > 0){
            foreach($st->fetchAll(PDO::FETCH_ASSOC) as $product){
                $s = $this->conn->prepare("INSERT INTO aliexpress_pending_order SET 
                order_id = :order_id, product_id = :product_id, `status` = 0");
                $s->bindValue(':order_id', $order_id, PDO::PARAM_INT);
                $s->bindValue(':product_id', $product['product_id'], PDO::PARAM_INT);
                $s->execute();
            }
        }
    }
    public function insertChinaPendingOrder($order_id, $p_type){
        $st = $this->conn->prepare("SELECT p_type FROM oc_order_product where order_id = :order_id AND p_type = :p_type");
        $st->bindValue(':order_id', $order_id, PDO::PARAM_INT);
        $st->bindValue(':p_type', $p_type, PDO::PARAM_INT);
        $st->execute();
        if($st->rowCount() > 0){
            $s = $this->conn->prepare("INSERT INTO china_pending_order SET 
            order_id = :order_id, `status` = 0");
            $s->bindValue(':order_id', $order_id, PDO::PARAM_INT);
            $s->execute();
        }
    }
    public function insertOrderTotal($totals, $lastId){
        foreach($totals as $total){
            $s = $this->conn->prepare('INSERT INTO oc_order_total SET 
            order_id = :order_id, code = :code, title = :title, value = :value, sort_order = :sort_order');
            $s->bindValue(':order_id', (int) $lastId, PDO::PARAM_INT);
            $s->bindValue(':code', $total['code']);
            $s->bindValue(':title', $total['title']);
            $s->bindValue(':value', (float) $total['value']);
            $s->bindValue(':sort_order', (int) $total['sort_order'], PDO::PARAM_INT);
            $s->execute();
        }
    }
    public function bgProductCount($order_id, $p_type){
        $st = $this->conn->prepare("SELECT * FROM oc_order_product where order_id = :order_id AND p_type = :p_type");
        $st->bindValue(':order_id', $order_id, PDO::PARAM_INT);
        $st->bindValue(':p_type', $p_type, PDO::PARAM_INT);
        $st->execute();
        return $st->rowCount();
        
    }
    public function insertOrderProduct($product_ids, $customer_id, $order_id, $token){
        global $cart;
        global $banggood;
        foreach($this->getSelectedCartProduct($customer_id, $product_ids) as $product){
            if($product['p_type'] == 0){
                $order_product = $this->conn->prepare("INSERT INTO oc_order_product SET order_id = :order_id, 
                product_id = :product_id, name = :name, model = :model, quantity = :quantity, price = :price, 
                total = :total, tax = 0.0000, reward = 0, p_type = :p_type, seller_id = :seller_id, discount_details = :discount_details, freebies = :freebies");
                $order_product->bindValue(':order_id', (int) $order_id, PDO::PARAM_INT);
                $order_product->bindValue(':product_id', $product['product_id'], PDO::PARAM_INT);
                $order_product->bindValue(':name', $product['name']);
                $order_product->bindValue(':model', $product['model']);
                $order_product->bindValue(':quantity', $product['quantity']);
                $order_product->bindValue(':price', $product['price']);
                $order_product->bindValue(':total', $product['total']);
                $order_product->bindValue(':p_type', $product['p_type']);
                $order_product->bindValue(':seller_id', $product['seller_id']);
                $order_product->bindValue(':discount_details', $product['discount_details']);
                $order_product->bindValue(':freebies', $product['freebies']);
                $order_product->execute();
            }else if($product['p_type'] == 2){
                $ship = $banggood->getShipping($product['product_id'], $cart->get_bg_warehouse($product['product_id']), (int)$product['quantity']);
                $poa_list = $this->getBGPoaList($product['cart_id'], $product['product_id']);
                $order_product = $this->conn->prepare("INSERT INTO oc_order_product SET order_id = :order_id, 
                product_id = :product_id, name = :name, model = :model, quantity = :quantity, price = :price, 
                total = :total, tax = 0.0000, reward = 0, p_type = :p_type, shipping_fee=:shipping_fee, 
                shipping_name=:shipping_name, shipping_code=:shipping_code,
                poa_name=:poa_name,poa_ids=:poa_ids");
                $order_product->bindValue(':order_id', (int) $order_id, PDO::PARAM_INT);
                $order_product->bindValue(':product_id', $product['product_id'], PDO::PARAM_INT);
                $order_product->bindValue(':name', $product['name']);
                $order_product->bindValue(':model', $product['model']);
                $order_product->bindValue(':quantity', $product['quantity']);
                $order_product->bindValue(':price', $product['price']);
                $order_product->bindValue(':total', $product['total']);
                $order_product->bindValue(':p_type', $product['p_type']);
                $order_product->bindValue(':shipping_fee', floatval($ship['shipfee']));
                $order_product->bindValue(':shipping_name', $ship['shipmethod_name']);
                $order_product->bindValue(':shipping_code', $ship['shipmethod_code']);
                $order_product->bindValue(':poa_name', $poa_list['poa_name']);
                $order_product->bindValue(':poa_ids', $poa_list['poa_ids']);
                $order_product->execute();
            }
          
        }
        // $this->insertChinaPendingOrder($order_id, 1);
        // $this->insertAliexpressPendingProduct($order_id, 3);
    }
    public function deleteFromCart($product_ids, $customer_id){
        $delete = $this->conn->prepare("DELETE from oc_cart where customer_id = :customer_id AND cart_id IN (".$product_ids.")");
        $delete->bindValue(':customer_id', $customer_id, PDO::PARAM_INT);
        // $delete->bindValue(':product_ids', $product_ids);
        $delete->execute();
    }
    public function addOrderHistory($order_id, $order_status_id, $comment){
        $order_history = $this->conn->prepare("INSERT INTO oc_order_history 
        SET order_id = :order_id, order_status_id = :order_status_id, notify = '0', comment = :comment, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00')");
        $order_history->bindValue(':order_id', $order_id, PDO::PARAM_INT);
        $order_history->bindValue(':order_status_id', $order_status_id, PDO::PARAM_INT);
        $order_history->bindValue(':comment', $comment);
        $order_history->execute();

        $order_product = $this->conn->prepare('SELECT * FROM oc_order_product WHERE order_id = :order_id');
        $order_product->bindValue(':order_id', $order_id);
        $order_product->execute();
        foreach($order_product->fetchAll() as $product){
            // $order_history = $this->conn->prepare("UPDATE oc_product SET quantity = (quantity - :quantity) WHERE product_id = :product_id AND subtract = '1'");
            // $order_history->bindValue(':product_id', (int) $product['product_id'], PDO::PARAM_INT);
            // $order_history->bindValue(':quantity', (int) $product['quantity'], PDO::PARAM_INT);
            // $order_history->execute();
            $update_product_store = $this->conn->prepare("UPDATE seller_product_selected SET quantity = quantity -:quantity  WHERE product_id = :product_id AND seller_id =:seller_id");
            $update_product_store->bindValue(':product_id', $product['product_id']);
            $update_product_store->bindValue(':quantity', $product['quantity']);
            $update_product_store->bindValue(':seller_id', $product['seller_id']);
            $update_product_store->execute();
        }
    }
    public function insertCashWallet($order_id, $customer_id, $value){
        $wallet = $this->conn->prepare("INSERT INTO oc_affiliate_wallet 
        SET seller_id = :seller_id,  product_name =:particulars, amount = :amount, date = convert_tz(utc_timestamp(),'-08:00','+0:00')");
        $wallet->bindValue(':seller_id', $customer_id, PDO::PARAM_INT);
        $wallet->bindValue(':particulars', 'Successfully redeemed (Order Id :#'.$order_id.')', PDO::PARAM_STR);
        $wallet->bindValue(':amount', $value, PDO::PARAM_INT);
        $wallet->execute();
    }
    public function insertDiscountWallet($order_id, $customer_id, $value){
        $wallet = $this->conn->prepare("INSERT INTO oc_customer_wallet SET 
        customer_id = :customer_id,  particulars = :particulars, amount =:amount, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00')");
        $wallet->bindValue(':customer_id', $customer_id, PDO::PARAM_INT);
        $wallet->bindValue(':particulars', 'Successfully redeemed (Order Id :#'.$order_id.')', PDO::PARAM_STR);
        $wallet->bindValue(':amount', $value, PDO::PARAM_INT);
        $wallet->execute();
    }
    public function insertShippingWallet($order_id, $customer_id, $value){
        $wallet = $this->conn->prepare("INSERT INTO shipping_wallet SET 
        customer_id = :customer_id,  particulars = :particulars, amount =:amount, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00'), `status` = 0");
        $wallet->bindValue(':customer_id', $customer_id, PDO::PARAM_INT);
        $wallet->bindValue(':particulars', 'Successfully redeemed (Order Id :#'.$order_id.')', PDO::PARAM_STR);
        $wallet->bindValue(':amount', $value, PDO::PARAM_INT);
        $wallet->execute();
        
    }
    public function getSelectedCartProduct($customer_id, $product_ids){
        global $cart;
        global $product;
        global $banggood;
        $data = array();
        $st = $this->conn->prepare('SELECT * FROM oc_cart c WHERE c.customer_id = :customer_id AND c.cart_id IN ('.$product_ids.')');
        // $st->bindValue(':product_ids', $product_ids);
        $st->bindValue(':customer_id', $customer_id);
        $st->execute();
        foreach($st->fetchAll() as $c){
            $type = $cart->product_type($c['p_type']);
            $product_info = $product->get_single_product($c['product_id'], $type);
            if($product_info){
                switch($product_info['type']){
                    case 'reg':
                        $special = false;
                        // if($product_info['special']){
                        //     $special = true;
                        //     $price = $product_info['special'];
                        // }else{
                        //     $price = $product_info['price'];
                        // }
                        $product_quantity = $cart->product_quantity($c['seller_id'], $product_info['product_id']);
                        $data[] = array(
                            'cart_id' => (int)$c['cart_id'],
                            'product_id' => $product_info['product_id'],
                            'name' => utf8_encode($product_info['name']),
                            'model' => utf8_encode($product_info['model']),
                            'quantity' => $product_quantity > 0 ? (int)$c['quantity'] : 0,
                            'productQuantity' => $product_quantity,
                            'price' => (float) $product_info['price'],
                            'total' => ($product_info['price'] * $c['quantity']),
                            'thumb' => $product_info['thumb'],
                            // 'special' => $special,
                            'discount' => $cart->cart_discounts($c['product_id']),
                            'p_type' => $c['p_type'],
                            'typeCode' => $product_info['type'],
                            'warehouse' => [],
                            'seller_id' => $c['seller_id'],
                            'discount_details' => $c['discount_details'],
                            'freebies' => $c['freebies']
                        );
                    break;
                    case 'bg':
                        $cb_product = $banggood->getProduct($c['product_id']);
                        $warehouse = $cart->bg_selected_poa($c['cart_id'],$cb_product['warehouse']);
                        $productQuantity = 0;
                        $price = 0;
                        if(count($warehouse) == 1){
                            foreach($warehouse[0]['value'] as $wh){
                                if($wh['selected'] == true){
                                    $productQuantity = $wh['stock'];
                                    $price = $wh['price'];
                                }
                            }
                        }else if(count($warehouse) == 2){
                            foreach($warehouse[1]['value'] as $wh){
                                if($wh['selected'] == true){
                                    $productQuantity = $wh['stock'];
                                    $price = $wh['price'];
                                }
                            }
                        }else if(count($warehouse) == 0){
                            $productQuantity = $cb_product['quantity'];
                            $price = $cb_product['price'];
                        }

                        // $warehouse = $this->getCBWarehouse($cb_product->warehouse_list, $cart['warehouse_code'], $token, $cb_product->sku, $cb_product->original_img[0]);
                        // if(is_object($cb_product)){
                            $data[] = array(
                                'cart_id' => (int)$c['cart_id'],
                                'product_id' => $c['product_id'],
                                'name' => utf8_encode($cb_product['name']),
                                'model' => $c['product_id'],
                                'quantity' => (int)$c['quantity'],
                                'productQuantity' => (int)$productQuantity,
                                'price' => (float) $price,
                                'total' => ((float)$price * $c['quantity']),
                                'thumb' => $product_info['thumb'],
                                'special' => false,
                                'discount' => [],
                                'p_type' => $c['p_type'],
                                'typeCode' => $product_info['type'],
                                'warehouse' => $warehouse,
                                'ship' => $banggood->getShipping($c['product_id'], $cart->get_bg_warehouse($c['product_id']), (int)$c['quantity'])
                            );
                    break;
                    default:
                    break;
                }
            }
        }
        return $data;
    }
    public function getBGPoaList($cart_id, $product_id){
        $poa_name = array();
        $poa_ids = array();
        $st = $this->conn->prepare('SELECT * from bg_cart_poa_list cpl WHERE cart_id = :cart_id AND product_id = :product_id');
        $st->bindValue(':cart_id', $cart_id);
        $st->bindValue(':product_id', $product_id);
        $st->execute();
        $res = $st->fetchAll(PDO::FETCH_ASSOC);
        foreach($res as $value){
            $poa_name[] = $value['option_name']. ':' . $value['poa_name'];
            $poa_ids[] = $value['poa_id'];
        }
        return array(
            'poa_name' => implode(",",$poa_name),
            'poa_ids' => implode(",",$poa_ids)
        );
    }
    public function countPickupFromStore($customer_id,$cart_ids){
        $cart_idstr = implode(",", $cart_ids);
        $count_cpfs=$this->conn->prepare("SELECT count(seller_id) as count FROM oc_cart WHERE customer_id=:customer_id and cart_id in (".$cart_idstr.") AND seller_id not in (19,20,21)");
        $count_cpfs->bindValue(':customer_id', $customer_id);
        $count_cpfs->execute();
        $row_cpfs = $count_cpfs->fetch(PDO::FETCH_ASSOC);
        return $row_cpfs['count'];
    }
    public function deliveryStatus($address_id){
        $data = array();
        $stm = $this->conn->prepare("SELECT addt.delivery,addt.pickup FROM oc_address oa
                                    INNER JOIN  address_tracker addt ON oa.tracking_id=addt.tracking_id
                                    WHERE oa.address_id=:address_id");
        $stm->bindValue(':address_id', $address_id);
        $stm->execute();
        $data = $stm->fetch(PDO::FETCH_ASSOC);         
        return $data;
    }
    public function pickupStatus($branch_id){
        $data = array();
        $stm = $this->conn->prepare("SELECT addt.delivery,addt.pickup FROM seller_branch sb
                                    INNER JOIN  address_tracker addt ON sb.tracking_id=addt.tracking_id
                                    WHERE sb.id=:branch_id");
        $stm->bindValue(':branch_id', $branch_id);
        $stm->execute();
        $data = $stm->fetch(PDO::FETCH_ASSOC);
        if($stm->rowCount() > 0){
            return $data;
        }else{
            return false;
        }
    }
    public function shipMethodQuadx($seller_id){    
        $sr = array();
        $sr[] = array(
            'name' => 'Standard Shipping Rate',
            'total' => 0,
            'value' => 'flat.flat',
            'code' => 'flat_rate',
            'disable' => false,
            'note' => '(Approximately 3-7 Days delivery.)'
        );
        $sr[] = array(
            'name' => 'Special Delivery',
            'code' => 'special_del',
            'total' => 0,
            'value' => 'special_del',
            'disable' => false,
            'note' => '(Approximately Same day or next day delivery With higher rate.)'
        ); 
        // $sr[] = array(
        //     'name' => 'Customer handles shipping upon arrival',
        //     'total' => 0,
        //     'value' => 'cutomerhandle.flat',
        //     'code' => 'customer_handel',
        //     'disable' => false
        // );
        $store_ids = array("21", "20", "19"); 
        if(in_array($seller_id, $store_ids)){
            $sr[] = array(
                'name' => 'Pickup From Store',
                'code' => 'store_pickup',
                'total' => 0,
                'value' => 'pickup.pickup',
                'disable' => false
            );
        }
       return $sr;
    }
    public function shipMethspecialdel($seller_id){         
        $sr = array();
        $sr[] = array(
            'name' => 'Special Delivery',
            'code' => 'special_del',
            'total' => 0,
            'value' => 'special_del',
            'disable' => false
        ); 
        $store_ids = array("21", "20", "19"); 
        if(in_array($seller_id, $store_ids)){
            $sr[] = array(
                'name' => 'Pickup From Store',
                'code' => 'store_pickup',
                'total' => 0,
                'value' => 'pickup.pickup',
                'disable' => false
            );
        }      
        return $sr;
    }
    public function shippingMethodList($address_id, $cart_ids, $customer_id, $branch_ids, $seller_ids, $total_price){
        $cart_ids = json_decode(htmlspecialchars_decode($cart_ids), true);
        $branch_ids = json_decode(htmlspecialchars_decode($branch_ids), true);
        $seller_ids = json_decode(htmlspecialchars_decode($seller_ids), true);
        $shipMethoData = array();    			      		
        $address = new Address();
        $delstats = $this->deliveryStatus($address_id);			
        $seller_id=0;
        $countcpfs = $this->countPickupFromStore($customer_id,$cart_ids);
        
        if($countcpfs == "0"){
            $seller_id=20;
        }
        $showSSR = false;
        if($address_id){
            if($delstats['delivery']=="Yes"){
                foreach($branch_ids as $ids){
                    $pickUpStats = $this->pickupStatus($ids);
                    $array[] = $pickUpStats;
                    if(is_bool($pickUpStats)){
                        $showSSR = false;
                        break;
                    }else{
                        if($pickUpStats['pickup'] == 'No'){
                            $showSSR = false;
                            break;
                        }else{
                            $showSSR = true;
                        }
                    }
                }
            }else{
                $showSSR = false;
            }
        }else{
            $showSSR = false;
        }
        $flatRate = array();
        $shippingValue = 0;
        foreach($seller_ids as $key => $value){
            $result = $this->getFlatShippingRateByStore($address_id, $branch_ids[$key], $customer_id, $cart_ids, $value);
            $shippingValue += $result['flatRate'];
            $flatRate[] = $result;
        }
        if($showSSR){
            $shipMethoData['option'][] = array(
                'name' => 'Standard Shipping Rate',
                'total' => $shippingValue,
                'value' => 'flat.flat',
                'code' => 'flat_rate',
                'disable' => false,
                'note' => '(Approximately 3-7 Days delivery.)'
            );
        }
      
        $shipMethoData['option'][] = array(
            'name' => 'Special Delivery',
            'code' => 'special_del',
            'total' => 0,
            'value' => 'special_del',
            'disable' => false
        );
        $showPickUp = false;
        foreach($seller_ids as $ids){
            $store_ids = array("21", "20", "19"); 
            if(in_array($ids, $store_ids)){
                $showPickUp = true;
            }else{
                $showPickUp = false;
                break;
            }
        }
        if($showPickUp){
            $shipMethoData['option'][] = array(
                'name' => 'Pickup From Store',
                'code' => 'store_pickup',
                'total' => 0,
                'value' => 'pickup.pickup',
                'disable' => false
            );
        }


        $disabled = false;
        $codMsg = '';
        $codAmountValidate = $this->validateAmount($total_price);
        $productIds = implode(",", $cart_ids);
        $codSellerValidate = $this->get_cart_product_cod($customer_id, $productIds);
        $disabledCod = true;
        if($codAmountValidate == ""){
            if($codSellerValidate == 0){
                $disabledCod = false;
            }else{
                $disabledCod = true;
            }
        }else{
            $codMsg = $codAmountValidate;
        }
        if($disabledCod == true && $showSSR == true){
            $disabled = true;
            $codMsg = "It can't be used when your purchase is more than 50,000 or product is not available for COD by the store";
        }else if($disabledCod == false && $showSSR == true){
            $disabled = false;
            $codMsg = "";
        }else if($disabledCod == true && $showSSR == false){
            $disabled = true;
        }else if($disabledCod == false && $showSSR == false){
            $disabled = true;
            $codMsg = "Your selected product is not available for Cash on delivery";
        }


        $shipMethoData['sellerFlatRate'] = $flatRate;

        $shipMethoData['cod'] = array(
			'name' => 'Cash on Delivery',
			'value' => 'cod',
			'disable' => $disabled,
			'message' => $codMsg
		);


        return $shipMethoData;
    }
    public function getFlatShippingRateByStore($address_id, $branch_id, $customer_id, $cart_ids, $seller_id){
        $pickUpAddress = $this->getPickupAddress($branch_id);
        $shipAddress = $this->getShipAddress($address_id);
        $json = array();
        if($address_id){
            if($shipAddress['region']==$pickUpAddress['region']){
                $json['success'] ="Not provincial rate";
                $value = $this->getFlatrateSamePRV($customer_id,$cart_ids,$seller_id);
                $json['flatRate'] =$value;
                $json['sellerId'] = $seller_id;
            }else{
                $json['success'] ="Provincial rate";
                $value = $this->FlatrateDiffPRV($customer_id,$cart_ids,$seller_id);
                $json['flatRate'] = $value;
                $json['sellerId'] = $seller_id;
            }
        }else{
            $json['success'] = '';
            $json['flatRate'] = 0;
            $json['sellerId'] = $seller_id;
        }
        return $json;		
    }
    public function getPickupAddress($branch_id){
        $data = array();
        $stm = $this->conn->prepare("SELECT * FROM seller_branch where id=:branch_id");
        $stm->bindValue(':branch_id', $branch_id);
        $stm->execute();
        $data = $stm->fetch(PDO::FETCH_ASSOC);         
        return $data;
    }
    public function getShipAddress($address_id){
        $data = array();
        $stm = $this->conn->prepare("SELECT * FROM oc_address where address_id=:address_id");
        $stm->bindValue(':address_id', $address_id);
        $stm->execute();
        $data = $stm->fetch(PDO::FETCH_ASSOC);         
        return $data;
    }
    public function getFlatrateSamePRV($customer_id,$chk_cart_id,$seller_id){       
        $shipping_cost=0;
        $cart_idstr2 = implode(",", $chk_cart_id);
        $select_procuct = $this->conn->prepare("SELECT SUM(OC.quantity) quantity,OC.product_id,OC.cart_id as cid 
                                                    FROM oc_cart OC 
                                                    INNER JOIN oc_product_delivery_charge PDC 
                                                    ON OC.product_id=PDC.product_id  
                                                    WHERE OC.customer_id = :customer_id 
                                                        AND OC.p_type=0 
                                                        AND OC.seller_id=:seller_id
                                                        AND OC.cart_id  IN (".$cart_idstr2.")
                                                    group by PDC.delivery_charge_id,OC.quantity,OC.product_id,OC.cart_id");
        $select_procuct->bindValue(':customer_id', $customer_id);
        $select_procuct->bindValue(':seller_id', $seller_id);
        $select_procuct->execute();       
        $products = $select_procuct->fetchAll();
        foreach ($products as $product) {
            $product_total = $product['quantity'];
            $product_total_id= $product['product_id'];
            $selec_amount = $this->conn->prepare("SELECT DC.* from oc_product_delivery_charge PDC INNER JOIN oc_delivery_charge DC ON PDC.delivery_charge_id=DC.id where PDC.product_id=:product_total_id");
             $selec_amount->bindValue(':product_total_id',  $product_total_id);
             $selec_amount->execute();
             $queryamount=$selec_amount->fetch(PDO::FETCH_ASSOC);

            $shipping_cost+=$queryamount['amount'];
        }
        return $shipping_cost;
    }
     public function FlatrateDiffPRV($customer_id,$chk_cart_id,$seller_id){       
        $shipping_cost=0;
        $cart_idstr2 = implode(",", $chk_cart_id);
        $select_procuct = $this->conn->prepare("SELECT SUM(OC.quantity) quantity,OC.product_id,OC.cart_id as cid 
                                                    FROM oc_cart OC 
                                                    INNER JOIN oc_product_delivery_charge PDC 
                                                    ON OC.product_id=PDC.product_id  
                                                    WHERE OC.customer_id = :customer_id 
                                                        AND OC.p_type=0 
                                                        AND OC.seller_id=:seller_id
                                                        AND OC.cart_id  IN (".$cart_idstr2.")
                                                    group by PDC.delivery_charge_id,OC.quantity,OC.product_id,OC.cart_id");
        $select_procuct->bindValue(':customer_id', $customer_id);
        $select_procuct->bindValue(':seller_id', $seller_id);
        $select_procuct->execute();       
        $products = $select_procuct->fetchAll();
        foreach ($products as $product) {
            $product_total = $product['quantity'];
            $product_total_id= $product['product_id'];
            $selec_amount = $this->conn->prepare("SELECT DC.* from oc_product_delivery_charge PDC INNER JOIN oc_delivery_charge DC ON PDC.delivery_charge_id=DC.id where PDC.product_id=:product_total_id");
             $selec_amount->bindValue(':product_total_id',  $product_total_id);
             $selec_amount->execute();
             $queryamount=$selec_amount->fetch(PDO::FETCH_ASSOC);

            $shipping_cost+=$queryamount['provincial_amount'];
        }
        return $shipping_cost;
    }
}
$checkout = new Checkout();