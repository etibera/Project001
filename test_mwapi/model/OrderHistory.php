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
            'minDate' => date("Y-m-d", strtotime($res['minDate'])),
            'maxDate' => date("Y-m-d")
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
            WHERE o.customer_id = :customer_id AND o.order_status_id > 0 AND o.date_added BETWEEN :start_date AND :end_date ORDER BY o.date_added DESC LIMIT :start , :limit');
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
                    'order_id'    => $oh['order_id'],
                    'status'           => $oh['status'],
                    'date_added'           => date_format($date,"F d, Y H:i A"),
                    'total'           => $oh['total'],
                    'currency_code'           => $oh['currency_code'],
                    'currency_value'           => $oh['currency_value'],
                    'status_id'           => $oh['status_id'],
                    'payment_method'           => $oh['payment_method'],
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
                'orderId' => $result['order_id'],
                'dateAdded' => date('F d, Y', strtotime($result['date_added'])),
                'orderStatusId' => $result['order_status_id'],
                'paymentMethod' => $result['payment_method'],
                'paymentCompany' => $result['payment_company'],
                'shippingMethod' => $result['shipping_method'],
                'paymentAddress1' => $result['payment_address_1'],
                'paymentAddress2' => $result['payment_address_2'],
                'paymentCity' => $result['payment_city'],
                'paymentPostCode' => $result['payment_postcode'],
                'shippingAddress1' => $result['shipping_address_1'],
                'shippingCompany' => $result['shipping_company'],
                'shippingAddress2' => $result['shipping_address_2'],
                'shippingCity' => $result['shipping_city'],
                'shippingPostCode' => $result['shipping_postcode'],
                'paymentCountry' => $result['payment_country'],
                'paymentZone' => $result['payment_zone'],
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
                'shipping' => $shipping
                // 'image' =>  $image->resize($product['image'], 200, 200)
                );
        }
        return $data;
    }
    public function getTotals($order_id){
        $data = array();
        $s = $this->conn->prepare('SELECT * FROM oc_order_total WHERE order_id = :order_id ORDER BY sort_order');
        $s->bindValue(':order_id', $order_id);
        $s->execute();
        foreach($s->fetchAll() as $total){
            $data[] = array(
                'title' => $total['title'],
                'value' => number_format((float)$total['value'], 2, '.', ''),
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

        $select_stmt = $this->conn->prepare("SELECT product_id,quantity FROM oc_order_product WHERE order_id=:order_id");
        $select_stmt->execute([':order_id'=> $order_id]);
        $products = $select_stmt->fetchAll();
        foreach ($products as $row) {
             $update_product = $this->conn->prepare("UPDATE oc_product SET quantity = quantity +:quantity  WHERE product_id = :product_id AND subtract = '1'");
             $update_product->bindValue(':product_id', $row['product_id']);
             $update_product->bindValue(':quantity', $row['quantity']);
             $update_product->execute();
        } 
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
}
$orderhistory = new OrderHistory();