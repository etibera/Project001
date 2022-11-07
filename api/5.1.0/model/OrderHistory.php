<?php
require_once '../init.php';
class OrderHistory {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    function __destruct(){
        $this->conn = null;
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
                    'total'           => (float) $oh['total'],
                    'statusId'           => $oh['status_id']
                );
            }
        }
        return $data;
    }
    public function getOrderInfo($customer_id, $order_id){
        global $image;
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

                'orderHistoryStatus' => $this->getOrderHistory($result['order_id']),
                'disableCancelOrder' => $this->checkCancelProduct($result['order_id'])
                );
                $countOldData = $this->count_OlddataOrder($order_id);
                $countOrderNumber = $this->countOrderNumber($order_id);
                if($countOldData == 0){
                    $data['oldData'] = true;
                    $data['products'] = $this->getOrderProducts($result['order_id']);
                }else{
                    if($countOrderNumber != 0){
                        $data['oldData'] = false;
                        $data['storeProducts'] = $this->storeOrderProducts($order_id);
                        $data['totals'] = $this->getTotals($result['order_id']);
                    }else{
                        $data['oldData'] = true;
                        $data['products'] = $this->getOrderProducts($result['order_id']);
                    }
                }
        }

        return $data;
    }
    public function storeOrderProducts($order_id){
        global $image;
        $stores = array();
        $select_stmt = $this->conn->prepare("SELECT sb.b_name as shop_name,so.order_id,so.seller_id,
        sb.branch_logo as image,so.order_number 
                FROM store_orders so 
                INNER JOIN oc_seller os ON so.seller_id = os.seller_id
                INNER JOIN seller_branch sb ON sb.id=so.branch_id
                where so.order_id=:order_id");
        $select_stmt->bindValue(':order_id', $order_id);
        $select_stmt->execute();
        $storeData = $select_stmt->fetchAll();
        foreach ($storeData as $row) {
        $stores[] = array(
        'oldData' => false,
        'shop_name' => $row['shop_name'],
        'order_number' => $row['order_number'],
        'seller_id' => $row['seller_id'],                
        'order_status' => $this->GetOrderStatusPerStore($order_id,$row['seller_id'],$row['order_number']),                
        'thumb' => $image->resize($row['image'], 70,70),
        'details' => $this->GetOrderPrd($order_id,$row['seller_id'],$row['order_number']),
        'totals' => $this->GetOrderPrdTotals($order_id,$row['seller_id'],$row['order_number']),
        );
        }
        return $stores;
    }
    public  function count_OlddataOrder($order_id){
        $total = array();
        $t = $this->conn->prepare("SELECT count(id) as total FROM store_orders WHERE order_id=:order_id");
        $t->bindValue(':order_id', $order_id);
        $t->execute();
        $total = $t->fetch(PDO::FETCH_ASSOC);
        return $total['total'];
    }
    public  function countOrderNumber($order_id){
        $total = array();
        $t = $this->conn->prepare("SELECT count(id) as total FROM store_orders where order_id=:order_id AND order_number is not null ");
        $t->bindValue(':order_id', $order_id);
        $t->execute();
        $total = $t->fetch(PDO::FETCH_ASSOC);
        return $total['total'];
    }
    public  function GetOrderStatusPerStore($order_id,$seller_id,$order_number){
        $status = array();
        $s = $this->conn->prepare("SELECT oos.name AS status,osps.order_status_id FROM order_status_per_store osps
                                    INNER JOIN oc_order_status oos 
                                        ON oos.order_status_id = osps.order_status_id
                                    WHERE  osps.order_id = :order_id AND osps.seller_id=:seller_id AND osps.order_number=:order_number");
        $s->bindValue(':order_id', $order_id);
        $s->bindValue(':seller_id', $seller_id);
        $s->bindValue(':order_number', $order_number);
        $s->execute();
        $status = $s->fetchAll(PDO::FETCH_ASSOC);
        return $status;
    }

    public  function GetOrderPrd($order_id,$seller_id,$order_number){
        $products = array();
        // $s = $this->conn->prepare("SELECT ops.serial,ops.id as serialid, oop.*,ifnull(oop.no_serial_added,0)as NOA 
        //                           FROM oc_order_product as oop 
        //                           left join oc_product_serial as ops on oop.order_id=ops.order_id and oop.order_product_id=ops.order_product_id 
        //                           WHERE oop.order_id = :order_id AND oop.seller_id=:seller_id AND oop.order_number=:order_number order by oop.order_product_id asc");
        // $s->bindValue(':order_id', $order_id);
        // $s->bindValue(':seller_id', $seller_id);
        // $s->bindValue(':order_number', $order_number);
        // $s->execute();
        // return $products = $s->fetchAll(PDO::FETCH_ASSOC);
        $stmt = $this->conn->prepare("SELECT op.*, p.sku, p.weight, p.location from oc_order_product op LEFT JOIN oc_product p ON op.product_id = p.product_id where op.order_number = :order_id ");
        $stmt->execute([':order_id' => $order_number]);
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $key => $value){
            $products[$key] = $value;
        }
        // foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
        //     $products[] = array(
        //             'branchId' => $row['branch_id'],
        //             'productId' => $row['product_id'],
        //             'name' => $row['name'],
        //             'orderId' => $row['order_id'],
        //             'orderNumber' => $row['order_numnber'],
        //             'sellerId' => $row['seller_id']
        //         );
        // }
        return $products;
        
    }
    public  function GetOrderPrdTotals($order_id,$seller_id,$order_number){
        $total = array();
        $t = $this->conn->prepare("SELECT * from order_total_per_store where order_id=:order_id and seller_id=:seller_id and order_number=:order_number  order by sort_order asc;");
        $t->bindValue(':order_id', $order_id);
        $t->bindValue(':seller_id', $seller_id);
        $t->bindValue(':order_number', $order_number);
        $t->execute();
        $total = $t->fetchAll(PDO::FETCH_ASSOC);
        return $total;
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
    public function cancelOrder($order_number){
        global $mail;
        // $order_number = $_POST['orderNumber'];
    try {
      $stmt = $this->conn->prepare("SELECT order_id FROM store_orders WHERE order_number = :order_number ORDER BY id DESC LIMIT 1");
      $stmt->execute([':order_number' => $order_number]);
      $order_id = $stmt->fetchObject()->order_id ?? null;

      if (!empty($order_id)) {
        $this->conn->prepare("INSERT INTO oc_order_history SET order_id = :order_id, order_status_id = 31, notify = 0, comment = '', seller_id = 0, order_number = :order_number, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00')")->execute([':order_id' => $order_id, ':order_number' => $order_number]);

        $this->conn->prepare("UPDATE order_status_per_store SET order_status_id = 31 WHERE order_id = :order_id AND order_number = :order_number")->execute([':order_id' => $order_id, ':order_number' => $order_number]);
        $stmt = $this->conn->prepare("SELECT id FROM store_orders WHERE order_id = :order_id");
        $stmt->execute([':order_id' => $order_id]);
        $orderIdCount = $stmt->rowCount();

        $stmt = $this->conn->prepare("SELECT product_id, quantity, seller_id, branch_id FROM oc_order_product WHERE order_number = :order_number");
        $stmt->execute([':order_number' => $order_number]);
        $products = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($products as $row) {
          $this->conn->prepare("UPDATE seller_branch_selected_products SET quantity = quantity +:quantity WHERE product_id = :product_id AND seller_id = :seller_id AND branch_id = :branch_id")->execute([':product_id' => $row->product_id, ':quantity' => $row->quantity, ':seller_id' => $row->seller_id, ':branch_id' => $row->branch_id]);
        }

        if ($orderIdCount == 1) {
          $this->conn->prepare("UPDATE oc_order SET order_status_id = 31, date_modified = convert_tz(utc_timestamp(), '-08:00', '+0:00') WHERE order_id = :order_id")->execute([':order_id' => $order_id]);

          $get_store_orders =  $mail->get_store_orders($order_id);
          foreach ($get_store_orders as $so) {
            $orderData =  $mail->order_details_seller($so['order_id'], $so['seller_id']);
            $mail->SendEmailSeller(31, $orderData, 'Customer Cancelled.');
          }

          $this->order_cancel($order_id, $_POST['customer_id']);
          $mail->SendEmailallstoreCustomer(31, $order_id, "Customer Cancelled.");
          $mail->SendEmailallstoreAdmin(31, $order_id, "Customer Cancelled.");
        } else {
          $stmt = $this->conn->prepare("SELECT id FROM order_status_per_store WHERE order_id = :order_id AND order_status_id = 31");
          $stmt->execute([':order_id' => $order_id]);
          $orderNumberCount = $stmt->rowCount();

          if ($orderIdCount === $orderNumberCount) {
            $this->conn->prepare("UPDATE oc_order SET order_status_id = 31, date_modified = convert_tz(utc_timestamp(), '-08:00', '+0:00') WHERE order_id = :order_id")->execute([':order_id' => $order_id]);

            $get_store_orders =  $mail->get_store_orders($order_id);
            foreach ($get_store_orders as $so) {
              $orderData =  $mail->order_details_seller($so['order_id'], $so['seller_id']);
              $mail->SendEmailSeller(31, $orderData, 'Customer Cancelled.');
            }

            $this->order_cancel($order_id, $_POST['customer_id']);
            $mail->SendEmailallstoreCustomer(31, $order_id, "Customer Cancelled.");
            $mail->SendEmailallstoreAdmin(31, $order_id, "Customer Cancelled.");
          }
        }
        return array('type' => 'success', 'message' => 'Order Cancelled');
      }
    } catch (PDOException $e) {
      return array('type' => 'error', 'message' => $e->getMessage());
    }
    //   try{
    //     $this->checkChinaPendingOrder($order_id);
    //     // $this->checkAliexpressPendingOrder($order_id);
    //     $s = $this->conn->prepare('UPDATE  oc_order set order_status_id = 31  where order_id=:order_id');
    //     $s->bindValue(':order_id', $order_id);
    //     $s->execute();

    //     $select_stmt = $this->conn->prepare("SELECT product_id, quantity, seller_id FROM oc_order_product WHERE order_id=:order_id");
    //     $select_stmt->execute([':order_id'=> $order_id]);
    //     $products = $select_stmt->fetchAll();
    //     foreach ($products as $row) {
    //         $update_product = $this->conn->prepare("UPDATE seller_product_selected SET quantity = quantity +:quantity  WHERE product_id = :product_id AND seller_id =:seller_id");
    //         $update_product->bindValue(':product_id', $row['product_id']);
    //         $update_product->bindValue(':quantity', $row['quantity']);
    //         $update_product->bindValue(':seller_id', $row['seller_id']);
    //         $update_product->execute();
    //     } 

    //     $seller = $this->conn->prepare("UPDATE  order_status_per_store set order_status_id=31  where order_id=:order_id ");
    //     $seller->bindValue(':order_id', $order_id);
    //     $seller->execute();

        
    //     $s1 = $this->conn->prepare("UPDATE  oc_customer_wallet set status='1' where SUBSTRING_INDEX(REPLACE(particulars,')',''), '#', - 1) = :order_id");
    //     $s1->bindValue(':order_id', $order_id);
    //     $s1->execute();
    //     $s2 = $this->conn->prepare("SELECT REPLACE(amount,'-','') amount,customer_id FROM oc_customer_wallet where SUBSTRING_INDEX(REPLACE(particulars,')',''), '#', - 1) = :order_id");
    //         $s2->bindValue(':order_id', $order_id);
    //         $s2->execute();
    //         $getwalletinfo = $s2->fetchAll(PDO::FETCH_ASSOC);
    //         if($s2->rowCount() > 0){
    //             foreach($getwalletinfo as $wallet){
    //                 $s3 = $this->conn->prepare("INSERT INTO oc_customer_wallet SET customer_id = :customer_id, particulars =:particulars, amount = :amount, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00'),status='1'");
    //                 $s3->bindValue(':particulars','Reversal For cancelled (Order Id:'.$order_id.')');
    //                 $s3->bindValue(':customer_id', $wallet['customer_id']);
    //                 $s3->bindValue(':amount', $wallet['amount']);
    //                 $s3->execute();                   
    //             } 
    //         }
    //   }catch(Exception $e){
    //     echo $e->getMessage();
    //   }
    }
        public  function order_cancel($order_id, $customer_id){
        $msg = "";
        $s = $this->conn->prepare("SELECT * FROM oc_product_serial where order_id= :order_id");
        $s->bindValue(':order_id', $order_id);
        $s->execute();
        $status = $s->fetchAll(PDO::FETCH_ASSOC);
        if(count($status) > 0) {
             $_SESSION['message'] = "Order #".$order_id." has already serial.";
        }
        else
        {

          $stmt = $this->conn->prepare("SELECT payment_method FROM oc_order WHERE order_id=:order_id");
          $stmt->execute([':order_id'=> $order_id]);
          $pymnt = $stmt->fetch(PDO::FETCH_ASSOC);        
          $payment = $pymnt['payment_method'];

            $s = $this->conn->prepare("UPDATE  oc_order set order_status_id=31  where order_id=:order_id ");
            $s->bindValue(':order_id', $order_id);
            $s->execute();

            $seller = $this->conn->prepare("UPDATE  order_status_per_store set order_status_id=31  where order_id=:order_id ");
            $seller->bindValue(':order_id', $order_id);
            $seller->execute();

            $this->updateorder_product($order_id);

            $s1 = $this->conn->prepare("UPDATE  oc_customer_wallet set status='1' where SUBSTRING_INDEX(REPLACE(particulars,')',''), '#', - 1) = :order_id");
            $s1->bindValue(':order_id', $order_id);
            $s1->execute();

            $s2 = $this->conn->prepare("SELECT REPLACE(amount,'-','') amount,customer_id FROM oc_customer_wallet where SUBSTRING_INDEX(REPLACE(particulars,')',''), '#', - 1) = :order_id");
            $s2->bindValue(':order_id', $order_id);
            $s2->execute();
            $getwalletinfo = $s2->fetchAll(PDO::FETCH_ASSOC);
            foreach($getwalletinfo as $wallet){

                $s3 = $this->conn->prepare("INSERT INTO oc_customer_wallet SET customer_id = :customer_id, particulars =:particulars, amount = :amount, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00'),status='1'");
                $s3->bindValue(':particulars','Reversal For cancelled (Order Id:'.$order_id.')');
                $s3->bindValue(':customer_id', $wallet['customer_id']);
                $s3->bindValue(':amount', $wallet['amount']);
                $s3->execute();                   
            } 

            //$payment_method = "";
            $c = "";
            $se = "";
            $email_address = "";
            

            $_SESSION['message'] = 'Order # '.$order_id.' has been cancelled.';  
        }
    }
    public function updateorder_product($lastId)
    {
        $select_stmt = $this->conn->prepare("SELECT product_id,quantity,seller_id FROM oc_order_product WHERE order_id=:order_id");
        $select_stmt->execute([':order_id'=> $lastId]);
        $products = $select_stmt->fetchAll();
         foreach ($products as $row) {
            
             $update_product = $this->conn->prepare("UPDATE seller_product_selected SET quantity = quantity +:quantity  WHERE product_id = :product_id AND seller_id =:seller_id");
             $update_product->bindValue(':product_id', $row['product_id']);
             $update_product->bindValue(':quantity', $row['quantity']);
             $update_product->bindValue(':seller_id', $row['seller_id']);
             $update_product->execute();
                
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
    public  function getbank_charge($terms){
        $total = array();
        $t = $this->conn->prepare("SELECT rate/100 as rates FROM bank_charge where terms=:terms");
        $t->bindValue(':terms', $terms);
        $t->execute();
        $total = $t->fetch(PDO::FETCH_ASSOC);
        return $total['rates'];
    }
    public function sendMailForReceived($orderId, $sellerId, $order_number){
        global $mail;
        $orderData=$mail->order_details_seller($orderId,$sellerId,$order_number);		  	
        $mail->SendEmailSeller(49,$orderData,'');
        $mail->SendEmailAdmin(49,$orderData,'');
        $mail->SendEmailcustomer(49,$orderData,'');
    }
    public function receiveOrder($order_id,$seller_id,$comment,$order_number){
                try { 
            $h = $this->conn->prepare("INSERT INTO oc_order_history SET order_id = :order_id, order_status_id = :order_status_id, notify = 0, comment=:comment,seller_id=:seller_id,order_number=:order_number, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00')");
            $h->bindValue(':order_number', $order_number);
            $h->bindValue(':order_id', $order_id);
            $h->bindValue(':order_status_id', 49);
            $h->bindValue(':comment', '');
            $h->bindValue(':seller_id', 0);
            $h->execute();

            $updatesps = $this->conn->prepare("UPDATE  order_status_per_store 
                                            SET order_status_id = '49' 
                                            WHERE order_id =:order_id AND seller_id=:seller_id AND order_number=:order_number");
            $updatesps->bindValue(':order_id', $order_id);
            $updatesps->bindValue(':seller_id', $seller_id);
            $updatesps->bindValue(':order_number', $order_number);
            $updatesps->execute();

            $rowd= array();
            $count=$this->conn->prepare("SELECT count(id) as countid  FROM store_orders WHERE order_id=:order_id ");
            $count->bindValue(':order_id', $order_id);
            $count->execute();
            $rowd = $count->fetch();

            if($rowd['countid']==1){
                $h_update = $this->conn->prepare("UPDATE oc_order SET order_status_id = :status_id,date_modified = convert_tz(utc_timestamp(),'-08:00','+0:00'),wr=:wr WHERE order_id = :order_id");
                $h_update->bindValue(':order_id', $order_id);
                $h_update->bindValue(':wr', $comment);
                $h_update->bindValue(':status_id', 49);
                $h_update->execute();
            }else{
                $rowd2= array();
                $count2=$this->conn->prepare("SELECT count(id) as countid2 FROM order_status_per_store  where order_id=:order_id AND order_status_id=:order_status_id ");
                $count2->bindValue(':order_id', $order_id);
                $count2->bindValue(':order_status_id', 49);
                $count2->execute();
                $rowd2 = $count2->fetch();
              
                if($rowd2['countid2']==1){
                    $h_updateWR2 = $this->conn->prepare("UPDATE oc_order SET wr=:comment WHERE order_id = :order_id");
                    $h_updateWR2->bindValue(':order_id', $order_id);
                    $h_updateWR2->bindValue(':comment', $comment.',');
                    $h_updateWR2->execute();
                }else{
                    if($rowd['countid']==$rowd2['countid2']){
                         $commentnew=$comment.' ';
                    }else{
                        $commentnew=$comment.' ,';
                    }
                    $h_updateWR = $this->conn->prepare("UPDATE oc_order SET wr=concat(wr,:comment) WHERE order_id = :order_id");
                    $h_updateWR->bindValue(':order_id', $order_id);
                    $h_updateWR->bindValue(':comment', $commentnew);
                    $h_updateWR->execute();
                   
                }
                if($rowd['countid']==$rowd2['countid2']){
                    $h_update = $this->conn->prepare("UPDATE oc_order SET order_status_id = :status_id,date_modified = convert_tz(utc_timestamp(),'-08:00','+0:00') WHERE order_id = :order_id");
                    $h_update->bindValue(':order_id', $order_id);
                    $h_update->bindValue(':status_id', 49);
                    $h_update->execute();
                }
            }
           return "200";
        } catch(Exception $e){
            return $e;
        }
    }
    public  function TotalAmountPerSellerOrder($order_id,$seller_id,$order_number){
        $totaAmount = array();
        $s = $this->conn->prepare("SELECT value FROM order_total_per_store WHERE order_id=:order_id AND seller_id=:seller_id AND order_number=:order_number AND title=:title");
        $s->bindValue(':order_id', $order_id);
        $s->bindValue(':seller_id', $seller_id);
        $s->bindValue(':order_number', $order_number);
        $s->bindValue(':title', 'Sub-Total');
        $s->execute();
        $totaAmount = $s->fetch(PDO::FETCH_ASSOC);
        return $totaAmount['value'];
    }
    public  function getbdotermsconfirm($order_id){
        $total = array();
        $t = $this->conn->prepare("SELECT SC_PAYTERM FROM oc_maxxpayment_response where SC_PAYMODE='0% Interest' AND SC_REF=:order_id");
        $t->bindValue(':order_id', $order_id);
        $t->execute();
        $total = $t->fetch(PDO::FETCH_ASSOC);
        return $total['SC_PAYTERM'];
    }
    public function receive($order_id, $seller_id, $order_number){
        $list = $this->receiveOrder($order_id,$seller_id,'',$order_number);
        $getpayment_method=$this->getpayment_method($order_id);
        if($list=="200"){	        	
	        	$totalAmount=$this->TotalAmountPerSellerOrder($order_id,$seller_id,$order_number);
	        	$deduction=$totalAmount*0.035;
	        	$totaStoreWallet=$totalAmount-$deduction;
	        	if($getpayment_method=="maxx_payment"){
					$getbdotermsconfirm=$this->getbdotermsconfirm($order_id);
					if($getbdotermsconfirm){
						$getbank_charge=$this->getbank_charge($getbdotermsconfirm);						
						$bankCdeduct=$totalAmount*$getbank_charge;
						$totaldeduc=$deduction+$bankCdeduct;
						$totaStoreWalletBC=$totalAmount-$totaldeduc;
						$this->AddSellerWallet($order_id,$seller_id,$totaStoreWalletBC,$order_number);
					}else{
						$this->AddSellerWallet($order_id,$seller_id,$totaStoreWallet,$order_number);
					}
	        	}else{
	        		$this->AddSellerWallet($order_id,$seller_id,$totaStoreWallet,$order_number);
	        	}
	        	//$model->AddStorePayables($order_id,$seller_id);
	        	// $json['success'] =" Order Successfully Recived.";
	        }
            // else{
	        //   $json['success'] ="Error Occured.";
	        // }
    //     if($res){
    //         try{
    //             $total_amount = (float) $res['value'];
    //             $deduction = $total_amount * 0.035;
    //             $totaStoreWallet = $total_amount - $deduction;
    //             if($getpayment_method=="maxx_payment"){
    //                 $getbdotermsconfirm=$this->getbdotermsconfirm($order_id);
				// 	if($getbdotermsconfirm){
				// 		$getbank_charge=$this->getbank_charge($getbdotermsconfirm);						
				// 		$bankCdeduct=$total_amount*$getbank_charge;
				// 		$totaldeduc=$deduction+$bankCdeduct;
				// 		$totaStoreWalletBC=$total_amount-$totaldeduc;
				// 		$this->AddSellerWallet($order_id,$seller_id,$totaStoreWalletBC);
				// 	}else{
				// 		$this->AddSellerWallet($order_id,$seller_id,$totaStoreWallet);
				// 	}
    //             }else{
    //                 $this->AddSellerWallet($order_id,$seller_id,$totaStoreWallet);
    //             }
                
    //         }catch(Exception $e){
    //             return $e;
    //         }
    //     }
    }
    public  function AddSellerWallet($order_id,$seller_id,$walletAmount,$order_number){
        try { 
            $sw = $this->conn->prepare("INSERT INTO seller_wallet SET `desc` = :orderdesc, amount = :amount, seller_id=:seller_id,order_number=:order_number, `date` = convert_tz(utc_timestamp(),'-08:00','+0:00')");
            $sw->bindValue(':orderdesc', 'Order Id: '.$order_id.' Recieved by Customer');
            $sw->bindValue(':amount', $walletAmount);
            $sw->bindValue(':seller_id', $seller_id);
            $sw->bindValue(':order_number', $order_number);
            $sw->execute();

             $sp = $this->conn->prepare("INSERT INTO store_payables SET seller_id = :seller_id, amount = :amount, order_id=:order_id,status=:status,order_number=:order_number, `date` = convert_tz(utc_timestamp(),'-08:00','+0:00')");
            $sp->bindValue(':seller_id', $seller_id);            
            $sp->bindValue(':amount', $walletAmount);
            $sp->bindValue(':order_id', $order_id);
            $sp->bindValue(':order_number', $order_number);
            $sp->bindValue(':status', 0);
            $sp->execute();
           return "200";
        } catch(Exception $e){
            return $e;
        }
    }
        public function SendEmailSeller($status_id,$order,$comment){
        global $email_templates;
        $shopname=$order['store_details']['shop_name'];
        $shopemail=$order['store_details']['email'];
        $shopimg=str_replace(" ","%20",$order['store_details']['img']);
        $status_name = $this->order_status_name($status_id);
        
        $ifemail=0;
        $trackingnumber="";
        $counttrack=$order['store_tnumber'];
        if($counttrack!=""){
            $trackingnumber=$order['store_tnumber']['tracking_number'];
        }
        $headeremail=$email_templates->GetHeader();
        $footeremail=$email_templates->getFooter();
        $tbody_val=$email_templates->Gettbody_val($order['products'],"seller",$order['total']);
        $bodySellerdata=$email_templates->GetbodySellerdata($status_id,$trackingnumber,$shopname,$order['order_id'],$comment,$order['customer'],"seller");
        $bodySeller=$bodySellerdata['bodySeller'];
        $ifemail=$bodySellerdata['ifemail'];        
        $deliveryDetails=$email_templates->GetdeliveryDetails($order);
        $GetSubjectdet=$email_templates->GetSubjectdet($order['order_id'],$status_name,"seller");
        if($ifemail==1){
            //Send to Email
            $mail = new PHPMailer(true);
            $mail->isSMTP(); 
            $mail->SMTPOptions = array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
                )
            );                                        
              $mail->Host       = 'mail.pesoapp.ph';
              $mail->SMTPAuth   = true;                                   
              $mail->Username   = 'support@pesoapp.ph';
              $mail->Password   = 'Izn8Z~(^$01E';
              // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
              $mail->SMTPAutoTLS = false;
              $mail->SMTPSecure = false;
              $mail->Port       = 587;

           /* $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = 'reizondev0001@gmail.com';
            $mail->Password   = '@abc1234';
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;*/

            //Recipients
           /* $mail->setFrom('customer@pinoyelectronicstore.com', 'PESO'); 
            $mail->addAddress('edmaribera.2425@gmail.com', 'edmar');*/
           //-------------------use below if live and delete address above-------------//
            $mail->setFrom('support@pesoapp.ph', 'PESO');
            $mail->addAddress($shopemail, $shopname);
           /* $mail->addAddress('edmaribera.2425@gmail.com', 'edmar');*/
            $mail->isHTML(true);
            $mail->Subject =$GetSubjectdet; 
            $mail->Body    = "<html><head><style>".
                             "table { border-collapse: collapse; } td,th { padding:7px;} th { text-align:left; }".
                             "</style></head><body><div style='margin:auto'>".
                             $headeremail.
                            $bodySeller.
                            "<div style='width:95%;'><hr></div>".
                            $deliveryDetails."<br>".
                            "<div style='width:95%;'><hr></div>".
                           "<span><b>Items</span><br>".
                            "<img style='width:20px;height:20px;' src='".$shopimg."'".">"." ".$shopname.

                             "<table style='border-collapse: collapse;'>".
                                 "<thead>".
                                     "<th>     </th>".
                                     "<th>Product</th>".
                                     "<th>Model</th>".
                                     //"<th>Discount</th>".
                                     "<th>Freebies</th>".
                                     "<th>Quantity</th>".
                                     "<th>Unit Price</th>".
                                     "<th>Total</th>".
                                 "</thead>".
                                 "<tbody>".
                                    $tbody_val.
                                 "</tbody>"."<br>".
                             "</table><div style='width:95%'>".$footeremail."</div></div></body></html>";

            $mail->send();
        }   
    }
     public function SendEmailAdmin($status_id,$order,$comment){
        global $email_templates;
        $shopname=$order['store_details']['shop_name'];
        $shopemail=$order['store_details']['email'];
        $shopimg=str_replace(" ","%20",$order['store_details']['img']);
        $status_name = $this->order_status_name($status_id);
        
        $ifemail=0;
        $trackingnumber="";
        $counttrack=$order['store_tnumber'];
        if($counttrack!=""){
            $trackingnumber=$order['store_tnumber']['tracking_number'];
        }
        $headeremail=$email_templates->GetHeader();
        $footeremail=$email_templates->getFooter();
        $tbody_val=$email_templates->Gettbody_val($order['products'],"seller",$order['total']);
        $bodySellerdata=$email_templates->GetbodySellerdata($status_id,$trackingnumber,$shopname,$order['order_id'],$comment,$order['customer'],"admin");
        $bodySeller=$bodySellerdata['bodySeller'];
        $ifemail=$bodySellerdata['ifemail'];        
        $deliveryDetails=$email_templates->GetdeliveryDetails($order);
        $GetSubjectdet=$email_templates->GetSubjectdet($order['order_id'],$status_name,"admin");
        if($ifemail==1){
            //Send to Email
            $mail = new PHPMailer(true);
            $mail->isSMTP(); 
            $mail->SMTPOptions = array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
                )
            );                                        
              $mail->Host       = 'mail.pesoapp.ph';
              $mail->SMTPAuth   = true;                                   
              $mail->Username   = 'support@pesoapp.ph';
              $mail->Password   = 'Izn8Z~(^$01E';
              // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
              $mail->SMTPAutoTLS = false;
              $mail->SMTPSecure = false;
              $mail->Port       = 587;

            /*$mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = 'reizondev0001@gmail.com';
            $mail->Password   = '@abc1234';
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;*/

            //Recipients
           /* $mail->setFrom('customer@pinoyelectronicstore.com', 'PESO'); 
            $mail->addAddress('edmaribera.2425@gmail.com', 'edmar');*/
           //-------------------use below if live and delete address above-------------//
           $mail->setFrom('support@pesoapp.ph', 'PESO');/*
           $mail->addAddress('edmaribera.2425@gmail.com', 'edmar');*/
           $mail->addAddress('admin@pinoyelectronicstore.com', 'Admin');
            $mail->isHTML(true);
            $mail->Subject =$GetSubjectdet; 
            $mail->Body    = "<html><head><style>".
                             "table { border-collapse: collapse; } td,th { padding:7px;} th { text-align:left; }".
                             "</style></head><body><div style='margin:auto'>".
                             $headeremail.
                            $bodySeller.
                            "<div style='width:95%;'><hr></div>".
                            $deliveryDetails."<br>".
                            "<div style='width:95%;'><hr></div>".
                           "<span><b>Items</span><br>".
                            "<img style='width:20px;height:20px;' src='".$shopimg."'".">"." ".$shopname.

                             "<table style='border-collapse: collapse;'>".
                                 "<thead>".
                                     "<th>     </th>".
                                     "<th>Product</th>".
                                     "<th>Model</th>".
                                     //"<th>Discount</th>".
                                     "<th>Freebies</th>".
                                     "<th>Quantity</th>".
                                     "<th>Unit Price</th>".
                                     "<th>Total</th>".
                                 "</thead>".
                                 "<tbody>".
                                    $tbody_val.
                                 "</tbody>"."<br>".
                             "</table><div style='width:95%'>".$footeremail."</div></div></body></html>";

            $mail->send();
        }   
    }
    public function SendEmailcustomer($status_id,$order,$comment){
        global $email_templates;
        $shopname=$order['store_details']['shop_name'];
        $custname=$order['customer'];
        $customer_email=$order['email'];
        $shopimg=str_replace(" ","%20",$order['store_details']['img']);
        $status_name = $this->order_status_name($status_id);
        
        $ifemail=0;
        $trackingnumber="";
        $counttrack=$order['store_tnumber'];
        if($counttrack!=""){
            $trackingnumber=$order['store_tnumber']['tracking_number'];
        }
        $headeremail=$email_templates->GetHeader();
        $footeremail=$email_templates->getFooter();
        $tbody_val=$email_templates->Gettbody_val($order['products'],"seller",$order['total']);
        $bodySellerdata=$email_templates->GetbodySellerdata($status_id,$trackingnumber,$shopname,$order['order_id'],$comment,$order['customer'],"customer");
        $bodySeller=$bodySellerdata['bodySeller'];
        $ifemail=$bodySellerdata['ifemail'];        
        $deliveryDetails=$email_templates->GetdeliveryDetails($order);
        $GetSubjectdet=$email_templates->GetSubjectdet($order['order_id'],$status_name,"customer");
        if($ifemail==1){
            //Send to Email
            $mail = new PHPMailer(true);
            $mail->isSMTP(); 
            $mail->SMTPOptions = array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
                )
            );                                        
              $mail->Host       = 'mail.pesoapp.ph';
              $mail->SMTPAuth   = true;                                   
              $mail->Username   = 'support@pesoapp.ph';
              $mail->Password   = 'Izn8Z~(^$01E';
              // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
              $mail->SMTPAutoTLS = false;
              $mail->SMTPSecure = false;
              $mail->Port       = 587;

            /*$mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = 'reizondev0001@gmail.com';
            $mail->Password   = '@abc1234';
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;*/

            //Recipients
          /*  $mail->setFrom('customer@pinoyelectronicstore.com', 'PESO'); 
            $mail->addAddress('edmaribera.2425@gmail.com', 'edmar');*/
           //-------------------use below if live and delete address above-------------//
           $mail->setFrom('support@pesoapp.ph', 'PESO');
          /* $mail->addAddress('edmaribera.2425@gmail.com', 'edmar');*/
           $mail->addAddress($customer_email, $custname);
            $mail->isHTML(true);
            $mail->Subject =$GetSubjectdet; 
            $mail->Body    = "<html><head><style>".
                             "table { border-collapse: collapse; } td,th { padding:7px;} th { text-align:left; }".
                             "</style></head><body><div style='margin:auto'>".
                             $headeremail.
                            $bodySeller.
                            "<div style='width:95%;'><hr></div>".
                            $deliveryDetails."<br>".
                            "<div style='width:95%;'><hr></div>".
                           "<span><b>Items</span><br>".
                            "<img style='width:20px;height:20px;' src='".$shopimg."'".">"." ".$shopname.

                             "<table style='border-collapse: collapse;'>".
                                 "<thead>".
                                     "<th>     </th>".
                                     "<th>Product</th>".
                                     "<th>Model</th>".
                                     //"<th>Discount</th>".
                                     "<th>Freebies</th>".
                                     "<th>Quantity</th>".
                                     "<th>Unit Price</th>".
                                     "<th>Total</th>".
                                 "</thead>".
                                 "<tbody>".
                                    $tbody_val.
                                 "</tbody>"."<br>".
                             "</table><div style='width:95%'>".$footeremail."</div></div></body></html>";

            $mail->send();
        }   
    }
    
    //new order history with filter
    public function getOrderHistoryFilter(){
        global $review;
            $result = array();
            $customer_id = $_GET['customerId'] ?? null;
        
            $result['review'] = $review->getReviewCount("all");
        
            foreach ($this->getOrderNumber($customer_id) as $key => $row) {
              $result['order'][$key] = array(
                'store' => array_merge($this->getOrderNumber($customer_id)[$key], $this->getOrderTotal($row['order_number']), $this->getStatusFilter($row['order_number'])),
                'product' => $this->getOrderProduct($row['order_number']),
                'review' => $review->getReviewCount("orderNumber", $row['order_number']),
                'currentStatus' => $this->getStatus($row['order_number'])
              );
    }

    return $result;
    }
      public function getOrderProduct($order_number)
  {
    $result = array();

    $stmt = $this->conn->prepare("SELECT oop.product_id, oop.name AS product_name, op.image, oop.quantity, oop.total FROM oc_order_product AS oop
    LEFT JOIN oc_product AS op ON op.product_id = oop.product_id
    WHERE oop.order_number = :order_number ORDER BY oop.order_number");
    $stmt->execute([':order_number' => $order_number]);

    $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    foreach ($data as $row) {
      $result[] = array(
        'product_id' => $row->product_id,
        'product_name' => $row->product_name,
        'image' => "img/" . $row->image,
        'quantity' => $row->quantity,
        'total' => utf8_encode('&#8369;') . number_format($row->total, 2),
      );
    }

    return $result;
  }
    
    public function getOrderNumber($customer_id)
    {
    $dataParams = array(':customer_id' => $customer_id);

    $filter = $_GET['filter'] ?? "all";
    $page = intval($_GET['page'] ?? "all");

    $sql = "SELECT *
    FROM(SELECT DISTINCT osps.order_status_id, (SELECT oos.status_type FROM oc_order_status oos WHERE oos.order_status_id=
        (SELECT order_status_id FROM oc_order_history ooh WHERE ooh.order_number = oop.order_number OR ooh.order_id = oop.order_id OR ooh.order_id = oop.order_id ORDER BY ooh.date_added DESC LIMIT 1) )  as status_type,
    oop.order_number, oop.seller_id, oop.branch_id, os.shop_name, CONCAT('img/', sb.branch_logo) AS branch_logo,oc.date_added 
      FROM oc_order_product AS oop
    INNER JOIN oc_order AS oc ON oc.order_id = oop.order_id
    INNER JOIN seller_branch AS sb ON sb.seller_id = oop.seller_id AND sb.id = oop.branch_id
    INNER JOIN oc_seller AS os ON os.seller_id = sb.seller_id
    INNER JOIN order_status_per_store AS osps ON osps.order_id = oop.order_id AND osps.order_number = oop.order_number
    WHERE oc.customer_id = :customer_id) 
    AS main WHERE status_type IS NOT NULL AND status_type != ''
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute($dataParams);
    $noFilter = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];

    if ($filter != "all") {
      $sql .= " AND status_type = :filter AND order_status_id != 31";
      $dataParams = array_merge($dataParams, [':filter' => $filter]);
    }

    $sql .= " ORDER BY date_added DESC";

    if ($page != "all") {
      $sql .= " LIMIT :start, :length";
      $dataParams = array_merge($dataParams, [':start' => ($page - 1) * 5, ':length' => 5]);
    }

    $stmt = $this->conn->prepare($sql);
    foreach ($dataParams as $key => $value) {
      if (is_int($value)) {
        $params = PDO::PARAM_INT;
      } else {
        $params = PDO::PARAM_STR;
      }
      $stmt->bindValue($key, $value, $params);
    }
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];

    if (!empty($noFilter)) {
      foreach ($noFilter as $row) {
        $this->checkIfReceived($row['order_number']);
      }
    }
    return $data;
  }
      public function checkIfReceived($order_number)
  {
    $stmt = $this->conn->prepare("SELECT so.seller_id, ooh.order_id, CASE WHEN ooh.date_added + INTERVAL 1 DAY <= convert_tz(utc_timestamp(),'-08:00','+0:00')
    THEN DATE_FORMAT(ooh.date_added + INTERVAL 1 DAY, '%m/%d/%Y %H:%i') ELSE 'false' END AS date_received
    FROM oc_order_history AS ooh
    INNER JOIN store_orders AS so ON so.order_number = ooh.order_number
    WHERE ooh.order_number = :order_number AND (SELECT COUNT(order_history_id) FROM oc_order_history
    WHERE order_number = :order_number AND order_status_id = 49) = 0 AND ooh.order_status_id = 46 LIMIT 1");
    $stmt->execute([':order_number' => $order_number]);

    $data = $stmt->fetchObject() ?? false;

    if ($data->date_received ?? false) {
      $this->receiveOrders($data->seller_id, $data->order_id, $order_number, $data->date_received);
    }

    return $data;
  }
    public function getOrderTotal($order_number)
  {
    $stmt = $this->conn->prepare("SELECT `value` FROM order_total_per_store WHERE order_number = :order_number AND code = 'total' LIMIT 1");
    $stmt->execute([':order_number' => $order_number]);

    return array('total' => utf8_encode('&#8369;') . number_format($stmt->fetchObject()->value ?? 0, 2));
  }
    public function getStatusFilter($order_number)
  {
    $stmt = $this->conn->prepare("SELECT CASE
		WHEN MAIN.order_status_id IN (31, 27, 48) THEN 'Cancelled'
		ELSE MAIN.status
        END AS status
	  FROM(
		SELECT CASE
			WHEN oos.status_type = 1 THEN 'To Pay'
			WHEN oos.status_type = 2 THEN 'To Ship'
			WHEN oos.status_type = 3 THEN 'To Receive'
			WHEN oos.status_type = 4 THEN CONCAT('Delivered on ', DATE_FORMAT(ooh.date_added, '%m/%d/%Y'))
		END AS status,
			ooh.order_status_id
		FROM oc_order_history AS ooh
		INNER JOIN oc_order_status AS oos ON oos.order_status_id = ooh.order_status_id
		WHERE ooh.order_number = :order_number
		ORDER BY ooh.date_added DESC LIMIT 1) AS MAIN");
    $stmt->execute([':order_number' => $order_number]);

    return array('status' => $stmt->fetchObject()->status ?? null);
  }

  public function getRowCount()
  {
    $customer_id = $_GET['customerId'] ?? null;
    $dataParams = array(':customer_id' => $customer_id);

    $filter = $_GET['filter'] ?? "all";

    $sql = "SELECT * FROM(SELECT DISTINCT oop.order_number, (SELECT oos.status_type FROM oc_order_status oos WHERE oos.order_status_id=
    (SELECT order_status_id FROM oc_order_history ooh WHERE ooh.order_number = oop.order_number ORDER BY ooh.date_added DESC LIMIT 1)) as status_type FROM oc_order AS oc
    INNER JOIN oc_order_product AS oop ON oop.order_id = oc.order_id
    INNER JOIN oc_order_history AS oh ON oh.order_number = oop.order_number
    INNER JOIN oc_order_status AS oos ON oos.order_status_id = oh.order_status_id
    WHERE oc.customer_id = :customer_id) AS main WHERE status_type IS NOT NULL AND status_type != ''";

    if ($filter != "all") {
      $sql .= " AND status_type = :filter";
      $dataParams = array_merge($dataParams, [':filter' => $filter]);
    }

    $stmt = $this->conn->prepare($sql);

    $stmt->execute($dataParams);

    $count = intval($stmt->rowCount() ?? null);

    if ($count > 5) {
      return array('type' => true, 'count' => $count);
    } else {
      return array('type' => false, 'count' => $count);
    }
  }
  
  //new order history details
  
  public function getOrderHistoryStatus()
  {
    $result = array();
    $order_number = $_GET['order'] ?? 0;
    $_SESSION['user_login'] = $_GET['customerId'];

    if ($order_number != 0 && !empty($this->getOrderProduct($order_number))) {
      if (intval($_SESSION['user_login']) == intval($this->getContactInfo($order_number)->customer_id)) {
        $result['contact'] = $this->getContactInfo($order_number);
        $result['status'] = $this->getOrderStatus($order_number);
        $result['product'] = $this->getOrderProduct($order_number);
        $result['total'] = $this->getTotal($order_number);
        $result['store'] = $this->getStore($order_number);
        $result['currentStatus'] = $this->getStatus($order_number);
      } else {
        return array('type' => 'error', 'message' => 'Something went wrong');
      }
    } else {
      return array('type' => 'error', 'message' => 'Something went wrong');
    }

    return $result;
  }
    public function getStatus($order_number)
  {
    $stmt = $this->conn->prepare("SELECT order_status_id FROM order_status_per_store WHERE order_number = :order_number LIMIT 1");
    $stmt->execute([':order_number' => $order_number]);

    $data = intval($stmt->fetchObject()->order_status_id ?? null);

    if ($data === 49 || $data === 50) {
      return 'received';
    } else if ($data === 46) {
      return 'pickup';
    } else {
      return false;
    }
  }
   public function getContactInfo($order_number)
  {
    $stmt = $this->conn->prepare("SELECT DISTINCT oc.customer_id, CONCAT(oc.payment_firstname, ' ', oc.payment_lastname) AS full_name, oc.telephone AS contact,
    CONCAT(
    CASE WHEN oc.payment_address_1 != '' THEN CONCAT(oc.payment_address_1, ', ') ELSE '' END,
    CASE WHEN oc.payment_address_2 != '' THEN CONCAT(oc.payment_address_2, ', ') ELSE '' END,
    oc.payment_city, ' ', payment_postcode, ', ', payment_country
    ) AS `address`,
    oc.payment_method
    FROM oc_order AS oc
    INNER JOIN oc_order_product AS oop ON oop.order_id = oc.order_id AND oop.order_number = :order_number LIMIT 1");
    $stmt->execute([':order_number' => $order_number]);

    return $stmt->fetchObject();
  }

  public function getOrderStatus($order_number)
  {
    $result = array();

    $stmt = $this->conn->prepare("SELECT oos.order_status_id, oos.name, ooh.comment, DATE_FORMAT(ooh.date_added, '%m/%d/%Y %H:%i') as `date`, oos.status_type AS type FROM oc_order_history AS ooh
    INNER JOIN oc_order_status AS oos ON oos.order_status_id = ooh.order_status_id
    WHERE ooh.order_number = :order_number
    ORDER BY ooh.date_added DESC");
    $stmt->execute([':order_number' => $order_number]);

    $data = $stmt->fetchAll(PDO::FETCH_OBJ);


    foreach ($data as $row) {
          $result[] = array(
            'order_status_id' => $row->order_status_id,
            'name' => $row->name,
            'date' => $row->date,
            'type' => $row->type,
            'comment' => $row->comment
          );
      }
    // if ($this->checkReview($order_number) != "false" && $this->checkReview($order_number) != false) {
    //   $result[] = array(
    //     'order_status_id' => 0,
    //     'name' => 'a',
    //     'date' => $this->checkReview($order_number),
    //     'type' => '5',
    //   );
    // }

    return $result;
  }

//   public function getOrderProduct($order_number)
//   {
//     $result = array();

//     $stmt = $this->conn->prepare("SELECT oop.product_id, oop.name AS product_name, op.image, oop.quantity, oop.total FROM oc_order_product AS oop
//     LEFT JOIN oc_product AS op ON op.product_id = oop.product_id
//     WHERE oop.order_number = :order_number");
//     $stmt->execute([':order_number' => $order_number]);

//     $data = $stmt->fetchAll(PDO::FETCH_OBJ);

//     foreach ($data as $row) {
//       $result['data'][] = array(
//         'product_id' => $row->product_id,
//         'product_name' => $row->product_name,
//         'image' => "img/" . $row->image,
//         'quantity' => $row->quantity,
//         'total' => utf8_encode('&#8369;') . number_format($row->total, 2),
//         'count' => $this->getReviewCount("product", $order_number, $row->product_id),
//       );
//     }

//     return $result;
//   }

  public function getTotal($order_number)
  {
    $result = array();

    $stmt = $this->conn->prepare("SELECT title, `value`, code FROM order_total_per_store WHERE order_number = :order_number ORDER BY sort_order");
    $stmt->execute([':order_number' => $order_number]);

    $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    foreach ($data as $row) {
      if ($row->code == "total") $value = utf8_encode('&#8369;') . number_format($row->value, 2);
      else $value = utf8_encode('&#8369;') . number_format($row->value, 2);
      $result[] = array(
        'title' => $row->title,
        'value' => $value
      );
    }

    return $result;
  }

  public function getStore($order_number)
  {
    $stmt = $this->conn->prepare("SELECT oop.order_id, oop.seller_id, oop.branch_id, os.shop_name, CONCAT('img/', sb.branch_logo) AS `image`, oop.order_number FROM oc_order_product AS oop
    INNER JOIN oc_seller AS os ON os.seller_id = oop.seller_id
    INNER JOIN seller_branch AS sb ON sb.id = oop.branch_id
    WHERE oop.order_number = :order_number LIMIT 1");
    $stmt->execute([':order_number' => $order_number]);

    return $stmt->fetchObject();
  }

  public function checkReview($order_number)
  {
    $stmt = $this->conn->prepare("SELECT DATE_FORMAT(ocr.date_added, '%m/%d/%Y %H:%i') AS date_review FROM oc_order_product AS oop
    LEFT JOIN oc_review AS ocr ON ocr.review_id = oop.review_id
    WHERE oop.order_number = :order_number ORDER BY ocr.date_added LIMIT 1");
    $stmt->execute([':order_number' => $order_number]);
    return $stmt->fetchObject()->date_review ?? $this->checkIfCompleted($order_number);
  }

//   public function getStatusOrder($order_number)
//   {
//     $stmt = $this->conn->prepare("SELECT order_status_id FROM order_status_per_store WHERE order_number = :order_number LIMIT 1");
//     $stmt->execute([':order_number' => $order_number]);

//     $data = intval($stmt->fetchObject()->order_status_id ?? null);

//     if ($data === 49 || $data === 50) {
//       return 'received';
//     } else if ($data === 46) {
//       return 'pickup';
//     } else {
//       return false;
//     }
//   }

  public function checkIfCompleted($order_number)
  {
    $stmt = $this->conn->prepare("SELECT CASE WHEN ooh.date_added + INTERVAL 7 DAY <= convert_tz(utc_timestamp(),'-08:00','+0:00')
    THEN DATE_FORMAT(ooh.date_added + INTERVAL 7 DAY, '%m/%d/%Y %H:%i') ELSE 'false' END AS date_review
    FROM oc_order_product AS oop
    INNER JOIN oc_order_history AS ooh ON ooh.order_number = oop.order_number
    WHERE oop.order_number = :order_number AND oop.review_id = 0 AND ooh.order_status_id IN (49, 50) LIMIT 1");
    $stmt->execute([':order_number' => $order_number]);

    return $stmt->fetchObject()->date_review ?? false;
  }
}
$orderhistory = new OrderHistory();