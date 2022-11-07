<?php
require_once '../init.php';
class OrderHistory {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    public function getOrderHistoryDetail($customer_id){
        $s = $this->conn->prepare('SELECT min(date_added) minDate, max(date_added) maxDate
        FROM oc_order o LEFT JOIN oc_order_status os ON (o.order_status_id = os.order_status_id) 
        WHERE o.customer_id = :customer_id AND o.order_status_id > 0');
        $s->bindValue(':customer_id', (int) trim($customer_id));
        $s->execute();
        $res = $s->fetch();
        return array(
            'minDate' => date('Y-m-d', strtotime($res['minDate'])),
            'maxDate' => date('Y-m-d')
        );
    }
    public function getPendingOrderTotal($customer_id){
        $s = $this->conn->prepare('SELECT COUNT(*) as total from oc_order WHERE customer_id = :customer_id AND order_status_id IN (17,18,1,19,23,28,25,30,32,33,34,35,36)');
        $s->bindValue(':customer_id', (int) trim($customer_id));
        $s->execute();
        return intval($s->fetch()['total']);
    }
    public function getOrders($customer_id, $start_date, $end_date){
        $data = array();
        $start = 0;
        $limit = 20;
        if(($start_date == null && $end_date == null) || ($start_date == 'undefined' && $end_date == 'undefined')){
            $s = $this->conn->prepare('SELECT o.order_id, o.firstname, o.lastname,os.name as status, o.date_added, o.total, o.currency_code, o.currency_value, os.order_status_id status_id,o.payment_code,o.payment_method 
            FROM oc_order o LEFT JOIN oc_order_status os ON (o.order_status_id = os.order_status_id) 
            WHERE o.customer_id = :customer_id AND o.order_status_id > 0  ORDER BY o.order_id DESC LIMIT :start , :limit');
            $s->bindValue(':customer_id', (int) trim($customer_id));
            $s->bindValue(':start', (int) $start, PDO::PARAM_INT);
            $s->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
            $s->execute();
        }else{
            $s = $this->conn->prepare('SELECT o.order_id, o.firstname, o.lastname,os.name as status, o.date_added, o.total, o.currency_code, o.currency_value, os.order_status_id status_id,o.payment_code,o.payment_method 
            FROM oc_order o LEFT JOIN oc_order_status os ON (o.order_status_id = os.order_status_id) 
            WHERE o.customer_id = :customer_id AND o.order_status_id > 0 AND DATE(o.date_added) BETWEEN :start_date AND :end_date ORDER BY o.order_id DESC LIMIT :start , :limit');
            $s->bindValue(':customer_id', (int) trim($customer_id));
            $s->bindValue(':start', (int) $start, PDO::PARAM_INT);
            $s->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
            $s->bindValue(':start_date', $start_date);
            $s->bindValue(':end_date', $end_date);
            $s->execute();
        }
        if($s->rowCount() > 0){
            foreach($s->fetchAll() as $oh){
                $date = date_create($oh['date_added']);
                $data[] = array(
                    'orderId'    => $oh['order_id'],
                    'status'           => $oh['status'],
                    'dateAdded'           => date_format($date,"F d, Y H:i A"),
                    'total'           => intval($oh['total']),
                    'statusId'           => $oh['status_id']
                );
            }
        }
        return $data;
    }
    public function getOrderInfo($customer_id, $order_id){
        $data = array();
        $s = $this->conn->prepare('SELECT * FROM oc_order WHERE order_id = :order_id AND customer_id = :customer_id AND order_status_id > 0');
        $s->bindValue(':customer_id', (int) $customer_id);
        $s->bindValue(':order_id', (int) $order_id);
        $s->execute();
        $result = $s->fetch();
        if($s->rowCount() > 0){
               $data = array(
                'orderId' => intval($result['order_id']),
                'dateAdded' => date('F d, Y', strtotime($result['date_added'])),
                'orderStatusId' => intval($result['order_status_id']),
                'paymentMethod' => $result['payment_method'],
                'paymentCompany' => $result['payment_company'],
                'paymentAddress1' => $result['payment_address_1'],
                'paymentAddress2' => $result['payment_address_2'],
                'paymentCity' => $result['payment_city'],
                'paymentPostCode' => $result['payment_postcode'],
                'shippingAddress1' => $result['shipping_address_1'],
                'shippingMethod' => $result['shipping_method'],
                'shippingCompany' => $result['shipping_company'],
                'shippingAddress2' => $result['shipping_address_2'],
                'shippingCity' => $result['shipping_city'],
                'shippingPostCode' => $result['shipping_postcode'],
                'shippingRegion' => $result['shipping_region'],
                'shippingDistrict' => $result['shipping_district'],
                'paymentCountry' => $result['payment_country'],
                'paymentZone' => $result['payment_zone'],
                'paymentDistrict' => $result['payment_district'],
                'paymentRegion' => $result['payment_region'],
                'shippingCountry' => $result['shipping_country'],
                'shippingZone' => $result['shipping_zone'],
                'products' => $this->getOrderProducts($result['order_id']),
                'totals' => $this->getTotals($result['order_id']),
                'orderHistoryStatus' => $this->getOrderHistory($result['order_id']),
                'disableCancelOrder' => $this->checkCancelProduct($result['order_id'])
               );
        }
        return $data;
    }
    public function getOrderProducts($order_id){
        global $banggood;
        $data = array();
        $s = $this->conn->prepare('SELECT ops.serial,ops.id as serialid, oop.* 
        FROM oc_order_product as oop 
        left join oc_product_serial as ops on oop.order_id=ops.order_id 
        and oop.order_product_id=ops.order_product_id 
        WHERE oop.order_id = :order_id order by oop.order_product_id asc');
        $s->bindValue(':order_id', $order_id);
        $s->execute();
        foreach($s->fetchAll() as $product){
            $bg = $banggood->trackAndOrderInfo($order_id);
            $shipping = "";
            if($product['p_type'] == 2){
                $shipping = "Free Shippping";
            }
            $data[] = array(
                'name' => $product['name'],
                'model' => $product['model'],
                'serial' => $product['serial'],
                'quantity' => $product['quantity'],
                'price' => number_format((float)$product['price'], 2, '.', ''),
                'total' => number_format((float)$product['total'], 2, '.', ''),
                'productId' => $product['product_id'],
                'options' => $product['poa_name'],
                'type' => $product['p_type'],
                'orderStatus' => $bg['bg_order_status'],
                'trackInfo' => $bg['bg_track_info'],
                'trackNumber' => $bg['bg_track_number'],
                'shipping' => $shipping,
                'sellerId' => $product['seller_id']
                // 'image' =>  $image->resize($product['image'], 200, 200)
                );
        }
        return $data;
    }
    public function getSellerDetails($order_id){
        global $image;
        $data = array();
        $s = $this->conn->prepare('SELECT s.shop_name, s.seller_id, concat("company/", s.image) as image, os.name `status`, osps.order_status_id FROM order_status_per_store osps 
        LEFT JOIN oc_seller s ON s.seller_id = osps.seller_id
        LEFT JOIN oc_order_status os ON os.order_status_id = osps.order_status_id 
        WHERE osps.order_id = :order_id');
        $s->bindValue(':order_id', $order_id);
        $s->execute();
        foreach($s->fetchAll(PDO::FETCH_ASSOC) as $seller){
            $data[] = array(
                'name' => $seller['shop_name'],
                'sellerId' => intval($seller['seller_id']),
                'image' => $image->resize($seller['image'], 50,50),
                'status' => $seller['status'],
                'statusId' => intval($seller['order_status_id']),
                'totals' => $this->orderTotalPerStore($order_id, $seller['seller_id'])
            );
        }
        return $data;
    }
    public function orderTotalPerStore($order_id, $seller_id){
        $s = $this->conn->prepare('SELECT * FROM order_total_per_store WHERE order_id = :order_id AND seller_id = :seller_id ORDER BY sort_order');
        $s->bindValue(':order_id', $order_id);
        $s->bindValue(':seller_id', $seller_id);
        $s->execute();
        return $s->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTotals($order_id){
        $data = array();
        $s = $this->conn->prepare('SELECT * FROM oc_order_total WHERE order_id = :order_id ORDER BY sort_order');
        $s->bindValue(':order_id', $order_id);
        $s->execute();

        $bgAmount = 0;
        $st = $this->conn->prepare('SELECT SUM(total) total FROM oc_order_product where order_id = :order_id and p_type = 2');
        $st->bindValue(':order_id', $order_id);
        $st->execute();
        foreach($s->fetchAll() as $total){
            $data[] = array(
                'title' => $total['title'],
                'value' => (float)$total['value'],
                );
            }
        return $data;
    }
    public function getOrderHistory($order_id){
        $data = array();
        $s = $this->conn->prepare("SELECT date_added, os.name AS status, oh.comment, oh.notify 
        FROM oc_order_history oh LEFT JOIN oc_order_status os ON oh.order_status_id = os.order_status_id 
        WHERE oh.order_id = :order_id ORDER BY oh.date_added");
        $s->bindValue(':order_id', (int) $order_id);
        $s->execute();
        foreach($s->fetchAll() as $h){
            $data[] =  array(  
                'dateAdded' => date_format(date_create($h['date_added']),"F d, Y"),
                'status'  => $h['status'],
                'comment' => $h['comment'],
            );
        }
        return $data;
    }
    public function checkCancelProduct($order_id){
        $s = $this->conn->prepare("SELECT * FROM oc_order_product where order_id = :order_id AND no_serial_added is not null");
        $s->bindValue(':order_id', (int) $order_id);
        $s->execute();
        $order_products_count = $s->rowCount();

        $st = $this->conn->prepare("SELECT * FROM china_pending_order where order_id = :order_id AND `status` = 1");
        $st->bindValue(':order_id', (int) $order_id);
        $st->execute();
        $china_pending_order_count = $st->rowCount();
        if($order_products_count > 0 || $china_pending_order_count > 0){
            return true;
        }else{
            return false;
        }
    }
    public function checkChinaPendingOrder($order_id){
        $s = $this->conn->prepare("SELECT * FROM china_pending_order where order_id = :order_id AND `status` = 0");
        $s->bindValue(':order_id', (int) $order_id);
        $s->execute();
        if($s->rowCount() > 0){
            $delete = $this->conn->prepare("DELETE from china_pending_order where order_id = :order_id");
            $delete->bindValue(':order_id', $order_id, PDO::PARAM_INT);
            $delete->execute();
        }
    }
    public function checkAliexpressPendingOrder($order_id){
        $s = $this->conn->prepare("SELECT * FROM aliexpress_pending_order where order_id = :order_id AND `status` = 0");
        $s->bindValue(':order_id', (int) $order_id);
        $s->execute();
        if($s->rowCount() > 0){
            $delete = $this->conn->prepare("DELETE from aliexpress_pending_order where order_id = :order_id");
            $delete->bindValue(':order_id', $order_id, PDO::PARAM_INT);
            $delete->execute();
        }
    }
    public function cancelOrder($order_id){
      try{
        $this->checkChinaPendingOrder($order_id);
        // $this->checkAliexpressPendingOrder($order_id);
        $s = $this->conn->prepare('UPDATE  oc_order set order_status_id = 31  where order_id=:order_id');
        $s->bindValue(':order_id', $order_id);
        $s->execute();

        $select_stmt = $this->conn->prepare("SELECT product_id, quantity, seller_id FROM oc_order_product WHERE order_id=:order_id");
        $select_stmt->execute([':order_id'=> $order_id]);
        $products = $select_stmt->fetchAll();
        foreach ($products as $row) {
            $update_product = $this->conn->prepare("UPDATE seller_product_selected SET quantity = quantity +:quantity  WHERE product_id = :product_id AND seller_id =:seller_id");
            $update_product->bindValue(':product_id', $row['product_id']);
            $update_product->bindValue(':quantity', $row['quantity']);
            $update_product->bindValue(':seller_id', $row['seller_id']);
            $update_product->execute();
        } 

        $seller = $this->conn->prepare("UPDATE  order_status_per_store set order_status_id=31  where order_id=:order_id ");
        $seller->bindValue(':order_id', $order_id);
        $seller->execute();

        
        $s1 = $this->conn->prepare("UPDATE  oc_customer_wallet set status='1' where SUBSTRING_INDEX(REPLACE(particulars,')',''), '#', - 1) = :order_id");
        $s1->bindValue(':order_id', $order_id);
        $s1->execute();
        $s2 = $this->conn->prepare("SELECT REPLACE(amount,'-','') amount,customer_id FROM oc_customer_wallet where SUBSTRING_INDEX(REPLACE(particulars,')',''), '#', - 1) = :order_id");
            $s2->bindValue(':order_id', $order_id);
            $s2->execute();
            $getwalletinfo = $s2->fetchAll(PDO::FETCH_ASSOC);
            if($s2->rowCount() > 0){
                foreach($getwalletinfo as $wallet){
                    $s3 = $this->conn->prepare("INSERT INTO oc_customer_wallet SET customer_id = :customer_id, particulars =:particulars, amount = :amount, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00'),status='1'");
                    $s3->bindValue(':particulars','Reversal For cancelled (Order Id:'.$order_id.')');
                    $s3->bindValue(':customer_id', $wallet['customer_id']);
                    $s3->bindValue(':amount', $wallet['amount']);
                    $s3->execute();                   
                } 
            }
      }catch(Exception $e){
        echo $e->getMessage();
      }
    }
    public function sendMailForCancel($order_id){
        global $mail;
        foreach($mail->get_store_orders($order_id) as $so){
            $orderData=$mail->order_details_seller($so['order_id'], $so['seller_id']);		  	
		  	$mail->SendEmailSeller(31,$orderData,'Customer Cancelled.');
        }
        $mail->SendEmailallstoreCustomer(31,$order_id,"Customer Cancelled.");
        $mail->SendEmailallstoreAdmin(31,$order_id,"Customer Cancelled.");
    }
    
    public  function getpayment_method($order_id){
        $total = array();
        $t = $this->conn->prepare("SELECT payment_code  FROM oc_order WHERE order_id=:order_id");
        $t->bindValue(':order_id', $order_id);
        $t->execute();
        $total = $t->fetch(PDO::FETCH_ASSOC);
        return $total['payment_code'];
    }
    public  function getbdotermsconfirm($order_id){
        $total = array();
        $t = $this->conn->prepare("SELECT SC_PAYTERM FROM oc_maxxpayment_response where SC_PAYMODE='0% Interest' AND SC_REF=:order_id");
        $t->bindValue(':order_id', $order_id);
        $t->execute();
        $total = $t->fetch(PDO::FETCH_ASSOC);
        return $total['SC_PAYTERM'];
    }
    public  function getbank_charge($terms){
        $total = array();
        $t = $this->conn->prepare("SELECT rate/100 as rates FROM bank_charge where terms=:terms");
        $t->bindValue(':terms', $terms);
        $t->execute();
        $total = $t->fetch(PDO::FETCH_ASSOC);
        return $total['rates'];
    }
    public function sendMailForReceived($orderId, $sellerId){
        global $mail;
        $orderData=$mail->order_details_seller($orderId, $sellerId);	
        $mail->SendEmailSeller(20,$orderData,'');
        $mail->SendEmailAdmin(20,$orderData,'');
        $mail->SendEmailcustomer(20,$orderData,'');
    }
    public function receive($order_id, $seller_id){
        if($order_id != null || $seller_id != null){

     
        $h = $this->conn->prepare("INSERT INTO oc_order_history SET order_id = :order_id, order_status_id = :order_status_id, notify = 0, comment=:comment,seller_id=:seller_id, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00')");
        $h->bindValue(':order_id', $order_id);
        $h->bindValue(':order_status_id', 20);
        $h->bindValue(':comment', '');
        $h->bindValue(':seller_id', 0);
        $h->execute();

        $updatesps = $this->conn->prepare("UPDATE order_status_per_store 
                                        SET order_status_id = '20' 
                                        WHERE order_id =:order_id AND seller_id=:seller_id");
        $updatesps->bindValue(':order_id', $order_id);
        $updatesps->bindValue(':seller_id', $seller_id);
        $updatesps->execute();


        $s = $this->conn->prepare("SELECT value FROM order_total_per_store WHERE order_id=:order_id AND seller_id=:seller_id AND title=:title");
        $s->bindValue(':order_id', $order_id);
        $s->bindValue(':seller_id', $seller_id);
        $s->bindValue(':title', 'Sub-Total');
        $s->execute();
        $res = $s->fetch(PDO::FETCH_ASSOC);

        $getpayment_method=$this->getpayment_method($order_id);
        if($res){
            try{
                $total_amount = (float) $res['value'];
                $deduction = $total_amount * 0.035;
                $totaStoreWallet = $total_amount - $deduction;
                if($getpayment_method=="maxx_payment"){
                    $getbdotermsconfirm=$this->getbdotermsconfirm($order_id);
					if($getbdotermsconfirm){
						$getbank_charge=$this->getbank_charge($getbdotermsconfirm);						
						$bankCdeduct=$total_amount*$getbank_charge;
						$totaldeduc=$deduction+$bankCdeduct;
						$totaStoreWalletBC=$total_amount-$totaldeduc;
						$this->AddSellerWallet($order_id,$seller_id,$totaStoreWalletBC);
					}else{
						$this->AddSellerWallet($order_id,$seller_id,$totaStoreWallet);
					}
                }else{
                    $this->AddSellerWallet($order_id,$seller_id,$totaStoreWallet);
                }
                
            }catch(Exception $e){
                return $e;
            }
        }
        



        }
    }
    public function addSellerWallet($order_id, $seller_id, $totalStoreWallet ){
        $sw = $this->conn->prepare("INSERT INTO seller_wallet SET `desc` = :orderdesc, amount = :amount, seller_id=:seller_id, `date` = convert_tz(utc_timestamp(),'-08:00','+0:00')");
        $sw->bindValue(':orderdesc', 'Order Id: '.$order_id.' Recieved by Customer');
        $sw->bindValue(':amount', $totalStoreWallet);
        $sw->bindValue(':seller_id', $seller_id);
        $sw->execute();

        $sp = $this->conn->prepare("INSERT INTO store_payables SET seller_id = :seller_id, amount = :amount, order_id=:order_id,status=:status, `date` = convert_tz(utc_timestamp(),'-08:00','+0:00')");
        $sp->bindValue(':seller_id', $seller_id);            
        $sp->bindValue(':amount', $totalStoreWallet);
        $sp->bindValue(':order_id', $order_id);
        $sp->bindValue(':status', 0);
        $sp->execute();
    }
}
$orderhistory = new OrderHistory();