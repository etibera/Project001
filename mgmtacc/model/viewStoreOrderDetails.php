<?php

require_once "../include/database.php";
require_once "../include/email_template.php";
require_once '../model/ImageResizer.php';
require_once '../model/Image.php';
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/Exception.php';
require_once '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class StoreOrder{
    private $conn;   
    public function __construct(){
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    public  function StoreOrderDet($order_id,$seller_id,$order_number){        
         $stmt = $this->conn->prepare("SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS customer,o.date_added as order_date 
                                    FROM oc_order o 
                                    INNER JOIN oc_customer c ON c.customer_id = o.customer_id
                                    WHERE o.order_id=:order_id");
        $stmt->bindValue(':order_id', $order_id);
        $stmt->execute();
        $row = $stmt->fetch();
        $row['products'] = array();
        $row['history'] = array();
        $row['total'] = array();
        $row['store_details'] = array();
        $row['store_tnumber'] = array();
        if($stmt->rowCount()){

            $s = $this->conn->prepare("SELECT concat('https://pesoapp.ph/img/',op.image)as img,ops.serial,ops.id as serialid, oop.*,ifnull(oop.no_serial_added,0)as NOA 
            FROM oc_order_product as oop 
            inner join oc_product op
             on op.product_id=oop.product_id
            left join oc_product_serial as ops on oop.order_id=ops.order_id and oop.order_product_id=ops.order_product_id 
            WHERE oop.order_id = :order_id AND oop.seller_id=:seller_id order by oop.order_product_id asc");
            $s->bindValue(':order_id', $order_id);
            $s->bindValue(':seller_id', $seller_id);
            $s->execute();
            $products = $s->fetchAll(PDO::FETCH_ASSOC);
            foreach($products as $product){
                $row['products'][] = $product;
            }
            $notin=$seller_id.",0";
            $h = $this->conn->prepare("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM oc_order_history oh LEFT JOIN oc_order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = :order_id AND seller_id IN (".$notin.")  ORDER BY oh.date_added DESC");
            $h->bindValue(':order_id', $order_id);
            $h->execute();
            $histories = $h->fetchAll(PDO::FETCH_ASSOC);
            foreach($histories as $history){
                $row['history'][] = $history;
            }
            $t = $this->conn->prepare("SELECT * from order_total_per_store where order_id=:order_id and seller_id=:seller_id and order_number=:order_number order by sort_order asc;");
            $t->bindValue(':order_id', $order_id);
            $t->bindValue(':seller_id', $seller_id);
            $t->bindValue(':order_number', $order_number);
            $t->execute();
            $total = $t->fetchAll(PDO::FETCH_ASSOC);
            foreach($total as $totals){
                    $row['total'][] = $totals;
            }
            $sd = $this->conn->prepare("SELECT *,concat('https://pesoapp.ph/img/company/',image) as img FROM oc_seller where seller_id=:seller_id");
            $sd->bindValue(':seller_id', $seller_id);
            $sd->execute();
            $row['store_details'] = $sd->fetch(PDO::FETCH_ASSOC);

            $tn = $this->conn->prepare("SELECT * FROM quadx_orders where order_id=:order_id and seller_id=:seller_id");
            $tn->bindValue(':seller_id', $seller_id);
            $tn->bindValue(':order_id', $order_id);
            $tn->execute();
            $row['store_tnumber'] = $tn->fetch(PDO::FETCH_ASSOC);
            
        }
        return $row;
    } 
    public  function StoreOrderShipment($order_id,$seller_id,$shipment,$order_number){
        $status= array();
        try{ 
             $hupdate = $this->conn->prepare("UPDATE store_orders SET shipment = :shipment WHERE order_id = :order_id AND seller_id=:seller_id AND order_number=:order_number");
            $hupdate->bindValue(':order_id', $order_id);
            $hupdate->bindValue(':shipment', $shipment);
            $hupdate->bindValue(':seller_id', $seller_id);
            $hupdate->bindValue(':order_number', $order_number);
            $hupdate->execute();
            $status['code'] = "200";
            $status['message'] = "Successfully Updated";
             return $status;
        }catch(PDOexception $e){
            $status['code'] = "400";
            $status['message'] = $e;
            return $status;

        }
    }
    public  function saveStoreOrderHistory($status_id, $comment, $order_id,$seller_id,$order_number){
        $status= array();
        try{       
            $h = $this->conn->prepare("INSERT INTO oc_order_history SET order_id = :order_id, order_status_id = :order_status_id, notify = 0, comment=:comment,seller_id=:seller_id,order_number=:order_number, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00')");
            $h->bindValue(':order_id', $order_id);
            $h->bindValue(':order_status_id', $status_id);
            $h->bindValue(':comment', $comment);
            $h->bindValue(':seller_id', $seller_id);
            $h->bindValue(':order_number', $order_number);
            $h->execute();

            $hupdate = $this->conn->prepare("UPDATE order_status_per_store SET order_status_id = :status_id WHERE order_id = :order_id AND seller_id=:seller_id  AND order_number=:order_number");
            $hupdate->bindValue(':order_id', $order_id);
            $hupdate->bindValue(':status_id', $status_id);
            $hupdate->bindValue(':seller_id', $seller_id);
            $hupdate->bindValue(':order_number', $order_number);
            $hupdate->execute();

            $rowd= array();
            $count=$this->conn->prepare("SELECT count(id) as countid  FROM store_orders WHERE order_id=:order_id ");
            $count->bindValue(':order_id', $order_id);
            $count->execute();
            $rowd = $count->fetch();

            if($rowd['countid']==1){
                $orderstats = $this->conn->prepare("UPDATE oc_order SET order_status_id = :status_id WHERE order_id = :order_id");
                $orderstats->bindValue(':order_id', $order_id);
                $orderstats->bindValue(':status_id', $status_id);
                $orderstats->execute();
            }
            /*$order = $this->StoreOrderDet( $order_id, $seller_id,$order_number);
            $status_type = $this->storeStatsType($status_id);
            if(($status_id!=17 && $status_type!=1)){
                   $this->SendEmailSeller($status_id,$order,$comment);
            }
            $this->SendEmail($status_id,$order,$comment);*/
            $status['code'] = "200";
            $status['message'] = "Successfully Added";
            return $status;
        }catch(PDOexception $e){
            $status['code'] = "400";
            $status['message'] = $e;
             return $status;
        }
        

    }
    public  function storeStatsType($status_id){        
        $s = $this->conn->prepare("SELECT * FROM oc_order_status where order_status_id = :status_id ");
        $s->bindValue(':status_id', $status_id);
        $s->execute();
        $status = $s->fetch(PDO::FETCH_ASSOC);
        return $status['type'];

    }
    public  function StoreOrderPrdDetails($order_id){
        global $image;
        $stores = array();
        $select_stmt = $this->conn->prepare("SELECT sb.b_name as shop_name,so.order_id,so.seller_id,
                                                sb.branch_logo as image,so.order_number,so.branch_id 
                                            FROM store_orders so 
                                            INNER JOIN oc_seller os ON so.seller_id = os.seller_id
                                            INNER JOIN seller_branch sb ON sb.id=so.branch_id
                                            where so.order_id=:order_id");
        $select_stmt->bindValue(':order_id', $order_id);
        $select_stmt->execute();
        $storeData = $select_stmt->fetchAll();
        foreach ($storeData as $row) {
            $stores[] = array(
                'shop_name' => $row['shop_name'],
                'seller_id' => $row['seller_id'],                
                'branch_id' => $row['branch_id'],                
                'order_number' => $row['order_number'],                
                'order_status' => $this->GetStoreOrderStatus($order_id,$row['seller_id'],$row['order_number']),                
                'thumb' => $image->resize($row['image'], 70,70),
                'details' => $this->GetStoreOrderPrd($order_id,$row['seller_id'],$row['order_number']),
                'totals' => $this->GetStoreOrderPrdTotals($order_id,$row['seller_id'],$row['order_number']),
            );
        }
        return $stores; 
    }
    public  function GetStoreOrderStatus($order_id,$seller_id,$order_number){
        $status = array();
        $s = $this->conn->prepare("SELECT oos.name AS status,osps.order_status_id FROM order_status_per_store osps
                                    INNER JOIN oc_order_status oos 
                                        ON oos.order_status_id = osps.order_status_id
                                    WHERE  osps.order_id = :order_id AND osps.seller_id=:seller_id AND osps.order_number=:order_number ");
        $s->bindValue(':order_id', $order_id);
        $s->bindValue(':seller_id', $seller_id);
        $s->bindValue(':order_number', $order_number);
        $s->execute();
        $status = $s->fetchAll(PDO::FETCH_ASSOC);
        return $status;
    } 


    public  function GetStoreOrderPrd($order_id,$seller_id,$order_number){
        $products = array();
        $s = $this->conn->prepare("SELECT ops.serial,ops.id as serialid, oop.*,ifnull(oop.no_serial_added,0)as NOA 
                                   FROM oc_order_product as oop 
                                   left join oc_product_serial as ops on oop.order_id=ops.order_id and oop.order_product_id=ops.order_product_id 
                                   WHERE oop.order_id = :order_id AND oop.seller_id=:seller_id AND oop.order_number=:order_number order by oop.order_product_id asc");
        $s->bindValue(':order_id', $order_id);
        $s->bindValue(':seller_id', $seller_id);
        $s->bindValue(':order_number', $order_number);
        $s->execute();
        $products = $s->fetchAll(PDO::FETCH_ASSOC);
        return $products;
    }
    public  function GetStoreOrderPrdTotals($order_id,$seller_id,$order_number){
        $total = array();
        $t = $this->conn->prepare("SELECT * from order_total_per_store where order_id=:order_id and seller_id=:seller_id and order_number=:order_number  order by sort_order asc;");
        $t->bindValue(':order_id', $order_id);
        $t->bindValue(':seller_id', $seller_id);
        $t->bindValue(':order_number', $order_number);
        $t->execute();
        $total = $t->fetchAll(PDO::FETCH_ASSOC);
        return $total;
    }
    public  function order_status_name($status_id){
        
        $s = $this->conn->prepare("SELECT * FROM oc_order_status where order_status_id = :status_id ");
        $s->bindValue(':status_id', $status_id);
        $s->execute();
        $status = $s->fetch(PDO::FETCH_ASSOC);
        return $status['name'];

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

            /*$mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = 'reizondev0001@gmail.com';
            $mail->Password   = '@abc1234';
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;*/

            //Recipients
           /* $mail->setFrom('customer@pinoyelectronicstore.com', 'PESO');
           $mail->addAddress('reizonazaress@gmail.com', 'REIZON');
           $mail->addAddress('macuartero@pinoyelectronicstore.com', 'MAC');
            $mail->addAddress('edmaribera.2425@gmail.com', 'Edmar');
          //  $mail->addAddress('lerjun99@gmail.com', 'Lerjun');*/
           //-------------------use below if live and delete address above-------------//
           $mail->setFrom('support@pesoapp.ph', 'PESO');/*
           $mail->addAddress('edmaribera.2425@gmail.com', 'Edmar');*/
           $mail->addAddress($shopemail, $shopname);
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
    public function SendEmail($status_id,$order,$comment)
    {       
            global $email_templates;   
            $shopname=$order['store_details']['shop_name'];
            $shopimg=str_replace(" ","%20",$order['store_details']['img']);
            $status_name = $this->order_status_name($status_id);
           
            $counttrack=$order['store_tnumber'];
            $trackingnumber="";
            $emailcx=$order['email'];
            $cxname=$order['customer'];
            if($counttrack!="")
            {
            $trackingnumber=$order['store_tnumber']['tracking_number'];
            }
            $headeremail=$email_templates->GetHeader();
            $footeremail=$email_templates->getFooter();
            $tbody_val=$email_templates->Gettbody_val($order['products'],"seller",$order['total']);
            $bodySellerdata=$email_templates->GetbodySellerdata($status_id,$trackingnumber,$shopname,$order['order_id'],$comment,$order['customer'],"customer");
            $bodyCustomer=$bodySellerdata['bodySeller'];
            $ifemail=$bodySellerdata['ifemail'];        
            $deliveryDetails=$email_templates->GetdeliveryDetails($order);
            $GetSubjectdet=$email_templates->GetSubjectdet($order['order_id'],$status_name,"customer");            
            if($ifemail==1)
            {
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
                $mail->addAddress('reizonazaress@gmail.com', 'REIZON');
                $mail->addAddress('macuartero@pinoyelectronicstore.com', 'MAC');
                $mail->addAddress('edmaribera.2425@gmail.com', 'edmar');
               //$mail->addAddress('lerjun99@gmail.com', 'Lerjun');*/
               //-------------------use below if live and delete address above-------------//
                $mail->setFrom('support@pesoapp.ph', 'PESO');
             /*   $mail->addAddress('edmaribera.2425@gmail.com', 'Edmar');*/
                $mail->addAddress($emailcx,$cxname); 
    
                $mail->isHTML(true);
                $mail->Subject = $GetSubjectdet; 
                // $mail->AddEmbeddedImage('https://pesoapp.ph/assets/PESO trans2.png', 'PESO');
                $mail->Body    = "<html><head><style>".
                                 "table { border-collapse: collapse; } td,th { padding:7px;} th { text-align:left; }".
                                 "</style></head><body><div style='margin:auto'>".
                                 $headeremail.$bodyCustomer.
                                "<div style='width:95%;'><hr></div>".
                                $deliveryDetails."<br>".
                                "<div style='width:95%;'><hr></div>".
                               "<span><b>Sold By</span><br>".
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
                //return $status_name;    
               
            } 
    }
     
}