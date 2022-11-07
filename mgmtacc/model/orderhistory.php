<?php
require_once "include/database.php";
require_once 'model/ImageResizer.php';
require_once 'model/Image.php';
require_once 'include/email_template.php';

require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class OrderHistory{

    private $conn;   
    public function __construct()
    {
            $this->conn = new Database();
            $this->conn = $this->conn->getmyDB();
    }
    public  function order_status_type($status_id){        
        $s = $this->conn->prepare("SELECT * FROM oc_order_status where order_status_id = :status_id ");
        $s->bindValue(':status_id', $status_id);
        $s->execute();
        $status = $s->fetch(PDO::FETCH_ASSOC);
        return $status['type'];

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
   
    public  function order_details_seller($order_id,$seller_id){        
         $stmt = $this->conn->prepare("SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS customer 
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
            $t = $this->conn->prepare("SELECT * from order_total_per_store where order_id=:order_id and seller_id=:seller_id  order by sort_order asc;");
            $t->bindValue(':order_id', $order_id);
            $t->bindValue(':seller_id', $seller_id);
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

    public  function get_store_orders($order_id){
        $data=array();
        $s = $this->conn->prepare("SELECT * FROM store_orders where order_id=:order_id");
        $s->bindValue(':order_id', $order_id);
        $s->execute();
        $data = $s->fetchAll(PDO::FETCH_ASSOC);
        return $data; 
    }
    public  function order_history($customer_id,$status){
         $s_status = "";
        if($status == "0") {

            $s_status = "AND o.order_status_id > 0";
        }else {
            $s_status = "AND o.order_status_id = :status";
        }
        $stmt = $this->conn->prepare("SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, os.name AS status,
         o.order_status_id, o.shipping_code, (SELECT oot.value from oc_order_total oot where oot.order_id= o.order_id and title='Total') AS total, o.currency_code, o.currency_value, o.date_added, o.date_modified, o.payment_code FROM oc_order o LEFT JOIN oc_order_status os ON  os.order_status_id = o.order_status_id 

            WHERE o.customer_id = :customer_id ".$s_status."
            ORDER BY o.date_added DESC");
        $stmt->bindValue(':customer_id', $customer_id);
        if($status != "0") {

            $stmt->bindValue(':status', $status);
        }
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    public function SendEmailallstoreCustomer($status_id,$order_id,$comment){
        global $email_templates;
        $trackingnumber="";
        $ordercart= $this->get_store_orders($order_id);
        $order ="";
        $wholebody="";
        foreach($ordercart as $cart){
                $seller_id=$cart['seller_id'];
                $order = $this->order_details_seller( $order_id, $seller_id);
                $shopname=$order['store_details']['shop_name'];
                $shopimg=str_replace(" ","%20",$order['store_details']['img']);
                $status_name = $this->order_status_name($status_id);
                $status_type = $this->order_status_type($status_id);
                
                $tbody_val = '';
                $tbody_val=$email_templates->Gettbody_val($order['products'],"seller",$order['total']);
                $ifemail=0;
                 $wholebody=$wholebody."<img style='width:20px;height:20px;' src='".$shopimg."'".">"." ".$shopname.
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
            "</table>";
        }
        $emailcx=$order['email'];
        $cxname=$order['customer'];
        $headeremail=$email_templates->GetHeader();
        $footeremail=$email_templates->getFooter();
        $bodySellerdata=$email_templates->GetbodySellerdata($status_id,$trackingnumber,$shopname,$order['order_id'],$comment,$order['customer'],"customer");
        $bodyCustomer=$bodySellerdata['bodySeller'];
        $ifemail=$bodySellerdata['ifemail'];    
        $deliveryDetails=$email_templates->GetdeliveryDetails($order);  
        $GetSubjectdet=$email_templates->GetSubjectdet($order['order_id'],$status_name,"customer");   
            
         
                $mail = new PHPMailer(true);
                $mail->isSMTP(); 
                $mail->SMTPOptions = array(
                    'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                    )
                );                                               
                $mail->Host       = 'mail.pinoyelectronicstore.com';
                $mail->SMTPAuth   = true;                                   
                $mail->Username   = 'customer@pinoyelectronicstore.com';
                $mail->Password   = 'change55me';
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
                
               //$mail->addAddress('lerjun99@gmail.com', 'Lerjun');
               //-------------------use below if live and delete address above-------------//
                $mail->setFrom('customer@pinoyelectronicstore.com', 'PESO');
                $mail->addAddress($emailcx,$cxname); 
              /*  $mail->addAddress('edmaribera.2425@gmail.com', 'edmar');*/
                
                $mail->isHTML(true);
                $mail->Subject = $GetSubjectdet;
                $mail->Body    = "<html><head><style>".
                                 "table { border-collapse: collapse; } td,th { padding:7px;} th { text-align:left; }".
                                 "</style></head><body><div style='margin:auto'>".
                                 $headeremail.$bodyCustomer.
                                "<div style='width:95%;'><hr></div>".
                                $deliveryDetails."<br>".
                                "<div style='width:95%;'><hr></div>".
                               "<span><b>Sold By</span><br>".$wholebody.
                              "<div style='width:95%'>".$footeremail."</div></div></body></html>";
    
                $mail->send();
                //return $status_name;
    
    }
     public function SendEmailallstoreAdmin($status_id,$order_id,$comment){
        global $email_templates;
        $trackingnumber="";
        $ordercart= $this->get_store_orders($order_id);
        $order ="";
        $wholebody="";
        foreach($ordercart as $cart){
                $seller_id=$cart['seller_id'];
                $order = $this->order_details_seller( $order_id, $seller_id);
                $shopname=$order['store_details']['shop_name'];
                $shopimg=str_replace(" ","%20",$order['store_details']['img']);
                $status_name = $this->order_status_name($status_id);
                $status_type = $this->order_status_type($status_id);
                
                $tbody_val = '';
                $tbody_val=$email_templates->Gettbody_val($order['products'],"seller",$order['total']);
                $ifemail=0;
                 $wholebody=$wholebody."<img style='width:20px;height:20px;' src='".$shopimg."'".">"." ".$shopname.
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
            "</table>";
        }
        $emailcx=$order['email'];
        $cxname=$order['customer'];
        $headeremail=$email_templates->GetHeader();
        $footeremail=$email_templates->getFooter();
        $bodySellerdata=$email_templates->GetbodySellerdata($status_id,$trackingnumber,$shopname,$order['order_id'],$comment,$order['customer'],"admin");
        $bodyCustomer=$bodySellerdata['bodySeller'];
        $ifemail=$bodySellerdata['ifemail'];    
        $deliveryDetails=$email_templates->GetdeliveryDetails($order);  
        $GetSubjectdet=$email_templates->GetSubjectdet($order['order_id'],$status_name,"admin");   
            
         
                $mail = new PHPMailer(true);
                $mail->isSMTP(); 
                $mail->SMTPOptions = array(
                    'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                    )
                );                                               
                $mail->Host       = 'mail.pinoyelectronicstore.com';
                $mail->SMTPAuth   = true;                                   
                $mail->Username   = 'customer@pinoyelectronicstore.com';
                $mail->Password   = 'change55me';
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
                $mail->setFrom('customer@pinoyelectronicstore.com', 'PESO');               
                $mail->addAddress('admin@pinoyelectronicstore.com', 'Admin');
               /* $mail->addAddress('edmaribera.2425@gmail.com', 'edmar');*/
                
               //$mail->addAddress('lerjun99@gmail.com', 'Lerjun');
               //-------------------use below if live and delete address above-------------//
                //$mail->addAddress($emailcx,$cxname); 
                
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
                               "<span><b>Sold By</span><br>".$wholebody.
                              "<div style='width:95%'>".$footeremail."</div></div></body></html>";
    
                $mail->send();
                //return $status_name;
    
    }
	public  function order_details($order_id){
		
		$stmt = $this->conn->prepare("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM oc_customer c WHERE c.customer_id = o.customer_id) AS customer FROM oc_order o WHERE o.order_id = :order_id");
		$stmt->bindValue(':order_id', $order_id);
        $stmt->execute();
        $row = $stmt->fetch();
        $row['products'] = array();
        $row['history'] = array();
        $row['total'] = array();
        if($stmt->rowCount()){
        	$s = $this->conn->prepare("SELECT ops.serial,ops.id as serialid, oop.*,ifnull(oop.no_serial_added,0)as NOA FROM oc_order_product as oop left join oc_product_serial as ops on oop.order_id=ops.order_id and oop.order_product_id=ops.order_product_id WHERE oop.order_id = :order_id order by oop.order_product_id asc");
        	$s->bindValue(':order_id', $order_id);
        	$s->execute();
        	$products = $s->fetchAll(PDO::FETCH_ASSOC);
        	foreach($products as $product){
        		$row['products'][] = $product;
        	}

        	$h = $this->conn->prepare("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM oc_order_history oh LEFT JOIN oc_order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = :order_id ORDER BY oh.date_added DESC");
        	$h->bindValue(':order_id', $order_id);
        	$h->execute();
        	$histories = $h->fetchAll(PDO::FETCH_ASSOC);
        	foreach($histories as $history){
        		$row['history'][] = $history;
        	}
            $hn = $this->conn->prepare("SELECT oh.date_added, os.name AS status,
                                            concat('(',st.shop_name,') ',oh.comment) as comment,
                                            oh.notify FROM oc_order_history oh 
                                        LEFT JOIN oc_order_status os 
                                            ON oh.order_status_id = os.order_status_id
                                        LEFT JOIN oc_seller st  
                                            ON st.seller_id = oh.seller_id
                                        WHERE oh.order_id = :order_id ORDER BY oh.date_added DESC");
            $hn->bindValue(':order_id', $order_id);
            $hn->execute();
            $historiesn = $hn->fetchAll(PDO::FETCH_ASSOC);
            foreach($historiesn as $historyn){
                $row['historyNew'][] = $historyn;
            }
            $t = $this->conn->prepare("SELECT * from oc_order_total where order_id=:order_id  order by sort_order asc;");
            $t->bindValue(':order_id', $order_id);
            $t->execute();
            $total = $t->fetchAll(PDO::FETCH_ASSOC);
            foreach($total as $totals){
                    $row['total'][] = $totals;
            }
        }
        return $row;
	}
	public  function order_status(){
		
		$s = $this->conn->prepare("SELECT * FROM oc_order_status");
        $s->execute();
        $status = $s->fetchAll(PDO::FETCH_ASSOC);
        return $status; 
	}
    public  function count_serial($order_id){
        
        $stmt = $this->conn->prepare("SELECT count(id) as countid from  oc_product_serial  where order_id=:order_id");
        $stmt->bindValue(':order_id', $order_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['countid'];
    }
    public  function count_bgproduct($order_id){
        
        $stmt = $this->conn->prepare("SELECT count(order_product_id) as countid from  oc_order_product  where order_id=:order_id and p_type='2'");
        $stmt->bindValue(':order_id', $order_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['countid'];
    }
    public  function ContOrderNotCancel($order_id){        
        $stmt = $this->conn->prepare("SELECT count(id) as countid FROM order_status_per_store 
                                    WHERE order_id=:order_id AND order_status_id in (44,45,46,48,27,20,31)");
        $stmt->bindValue(':order_id', $order_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['countid'];
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
            if($payment === "BDO Card Installment" || $payment === "Cards and Other Payment Method")
            {   
             
              // if($payment === "maxx_payment" ) $payment_method= "BDO Card Installment";
              // if($payment === "credit_card" ) $payment_method= "Cards and Other Payment Method";

              $stm = $this->conn->prepare("SELECT * FROM oc_customer WHERE customer_id=:u");
              $stm->execute([':u'=> $customer_id]);
              $customer = $stm->fetchAll(PDO::FETCH_ASSOC);        
              foreach($customer as $row){
                $c = $row['firstname'].' '.$row['lastname'];                
              }

              $stm2 = $this->conn->prepare("SELECT * FROM oc_email_address WHERE type= 'cancel_order'");
              $stm2->execute();
              $email = $stm2->fetchAll(PDO::FETCH_ASSOC);          
              foreach($email as $e){

                    $email_address = $e['email'];
                    
                    $message = "Hi Good Day". "\n\n";
                    $message .= " Order id#: $order_id Cancelled By Customer $c"."\n\n";
                    $message .= " Payment Method is ".$payment."\n\n";
                    try 
                    { 
                        //$mail = new PHPMailer(true);                            
                        //$mail->isSMTP();     
                        //$mail->SMTPDebug = 2;     
                        // $mail->SMTPOptions = array(
                        // 'ssl' => array(
                        // 'verify_peer' => false,
                        // 'verify_peer_name' => false,
                        // 'allow_self_signed' => true ));                    

                        //$mail->Host = "sg3plcpnl0228.prod.sin3.secureserver.net";

                        //$mail->Host = "localhost"; // if host is on local server

                        // $mail->SMTPAuth = true;                          
                        // $mail->Username = "ruazares@digitaldoorssoftware.com";                 
                        // $mail->Password = "xxx1234xxx";                           
                        // $mail->SMTPSecure = "tls";                           
                        // $mail->Port = 465;                                   

                        /*$mail->setFrom("ruazares@digitaldoorssoftware.com","Reizon");
                        $mail->addAddress("ruazares@digitaldoorssoftware.com", "Reizon");
                        $mail->addAddress("reizontemp01@gmail.com", "Reizon");
                        $mail->addAddress("marckevinflores@gmail.com", "Kevin");
                        //$mail->addAddress("rmbautista@digitaldoorssoftware.com", "Ronald");
                        $mail->addAddress("etibera@digitaldoorssoftware.com", "Edmar");
                        $mail->addAddress("mpflores@digitaldoorssoftware.com", "Marc Kevin");
                        $mail->addAddress("jdmosqueda@digitaldoorssoftware.com", "John Mark");
                        $mail->isHTML(true);
                        $mail->Subject = "PESO - Cancelled Order";
                        $mail->Body = $message;
                        $mail->Send();
                        unset($mail);*/
                        
                      
                    } catch(Exception $e){
                         $_SESSION['message'] = "SMTP Failed: " .$e;//$mail->ErrorInfo;
                    }
              }
            }

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

    public  function email_test($email) {

        try 
        { 
            $mail = new PHPMailer(true);                            
            $mail->isSMTP();     
            $mail->SMTPDebug = 2;     
            $mail->SMTPOptions = array(
            'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true ));                       
            $mail->Host = "sg3plcpnl0228.prod.sin3.secureserver.net";
            //$mail->Host = "localhost";
            $mail->SMTPAuth = true;                          
            $mail->Username = "ruazares@digitaldoorssoftware.com";                 
            $mail->Password = "xxx1234xxx";                           
            $mail->SMTPSecure = "tls";                           
            $mail->Port = 465;                              

            $mail->setFrom("ruazares@digitaldoorssoftware.com","Reizon");
            //$mail->addAddress("ruazares@digitaldoorssoftware.com", "Reizon");
            $mail->addAddress("reizontemp01@gmail.com", "Reizon");
            $mail->addAddress("marckevinflores@gmail.com", "Kevin");
            //$mail->addAddress("rmbautista@digitaldoorssoftware.com", "Ronald");
            $mail->addAddress("etibera@digitaldoorssoftware.com", "Edmar");
            $mail->addAddress("mpflores@digitaldoorssoftware.com", "Marc Kevin");
            $mail->addAddress("jdmosqueda@digitaldoorssoftware.com", "John Mark");
            $mail->isHTML(true);
            $mail->Subject = "PESO - Cancelled Order";
            $mail->Body = $email;
            $mail->Send();
            unset($mail);
            $_SESSION['message'] = 'Message: '.$email;  
          
        } catch(Exception $e){
             $_SESSION['message'] = "SMTP Failed: " .$e;//$mail->ErrorInfo;
        }
    }

    public  function OrderPrdDetails($order_id){
        global $image;
        $stores = array();
        $select_stmt = $this->conn->prepare("SELECT os.shop_name,so.order_id,so.seller_id,
                                                concat('company/',os.image) as image 
                                            FROM store_orders so 
                                            INNER JOIN oc_seller os ON so.seller_id = os.seller_id 
                                            where so.order_id=:order_id");
        $select_stmt->bindValue(':order_id', $order_id);
        $select_stmt->execute();
        $storeData = $select_stmt->fetchAll();
        foreach ($storeData as $row) {
            $stores[] = array(
                'shop_name' => $row['shop_name'],
                'seller_id' => $row['seller_id'],                
                'order_status' => $this->GetOrderStatusPerStore($order_id,$row['seller_id']),                
                'thumb' => $image->resize($row['image'], 70,70),
                'details' => $this->GetOrderPrd($order_id,$row['seller_id']),
                'totals' => $this->GetOrderPrdTotals($order_id,$row['seller_id']),
            );
        }
        return $stores; 
    } 
    public  function GetOrderStatusPerStore($order_id,$seller_id){
        $status = array();
        $s = $this->conn->prepare("SELECT oos.name AS status,osps.order_status_id FROM order_status_per_store osps
                                    INNER JOIN oc_order_status oos 
                                        ON oos.order_status_id = osps.order_status_id
                                    WHERE  osps.order_id = :order_id AND osps.seller_id=:seller_id ");
        $s->bindValue(':order_id', $order_id);
        $s->bindValue(':seller_id', $seller_id);
        $s->execute();
        $status = $s->fetchAll(PDO::FETCH_ASSOC);
        return $status;
    }
    public  function GetOrderPrd($order_id,$seller_id){
        $products = array();
        $s = $this->conn->prepare("SELECT ops.serial,ops.id as serialid, oop.*,ifnull(oop.no_serial_added,0)as NOA 
                                   FROM oc_order_product as oop 
                                   left join oc_product_serial as ops on oop.order_id=ops.order_id and oop.order_product_id=ops.order_product_id 
                                   WHERE oop.order_id = :order_id AND seller_id=:seller_id order by oop.order_product_id asc");
        $s->bindValue(':order_id', $order_id);
        $s->bindValue(':seller_id', $seller_id);
        $s->execute();
        $products = $s->fetchAll(PDO::FETCH_ASSOC);
        return $products;
    }
    public  function GetOrderPrdTotals($order_id,$seller_id){
        $total = array();
        $t = $this->conn->prepare("SELECT * from order_total_per_store where order_id=:order_id and seller_id=:seller_id  order by sort_order asc;");
        $t->bindValue(':order_id', $order_id);
        $t->bindValue(':seller_id', $seller_id);
        $t->execute();
        $total = $t->fetchAll(PDO::FETCH_ASSOC);
        return $total;
    } 
    public  function count_OlddataOrder($order_id){
        $total = array();
        $t = $this->conn->prepare("SELECT count(id) as total FROM store_orders WHERE order_id=:order_id");
        $t->bindValue(':order_id', $order_id);
        $t->execute();
        $total = $t->fetch(PDO::FETCH_ASSOC);
        return $total['total'];
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
    public  function ReciveOrder($order_id,$seller_id,$comment){
        try { 
            $h = $this->conn->prepare("INSERT INTO oc_order_history SET order_id = :order_id, order_status_id = :order_status_id, notify = 0, comment=:comment,seller_id=:seller_id, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00')");
            $h->bindValue(':order_id', $order_id);
            $h->bindValue(':order_status_id', 49);
            $h->bindValue(':comment', '');
            $h->bindValue(':seller_id', 0);
            $h->execute();

            $updatesps = $this->conn->prepare("UPDATE  order_status_per_store 
                                            SET order_status_id = '49' 
                                            WHERE order_id =:order_id AND seller_id=:seller_id");
            $updatesps->bindValue(':order_id', $order_id);
            $updatesps->bindValue(':seller_id', $seller_id);
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
    public  function AddSellerWallet($order_id,$seller_id,$walletAmount){
        try { 
            $sw = $this->conn->prepare("INSERT INTO seller_wallet SET `desc` = :orderdesc, amount = :amount, seller_id=:seller_id, `date` = convert_tz(utc_timestamp(),'-08:00','+0:00')");
            $sw->bindValue(':orderdesc', 'Order Id: '.$order_id.' Recieved by Customer');
            $sw->bindValue(':amount', $walletAmount);
            $sw->bindValue(':seller_id', $seller_id);
            $sw->execute();

             $sp = $this->conn->prepare("INSERT INTO store_payables SET seller_id = :seller_id, amount = :amount, order_id=:order_id,status=:status, `date` = convert_tz(utc_timestamp(),'-08:00','+0:00')");
            $sp->bindValue(':seller_id', $seller_id);            
            $sp->bindValue(':amount', $walletAmount);
            $sp->bindValue(':order_id', $order_id);
            $sp->bindValue(':status', 0);
            $sp->execute();
           return "200";
        } catch(Exception $e){
            return $e;
        }
    }
    public  function OrderPrdDetailsGlobal($order_id){
        $productsGlobal = array();
        $s = $this->conn->prepare("SELECT ops.serial,ops.id as serialid, oop.*,ifnull(oop.no_serial_added,0)as NOA FROM oc_order_product as oop left join oc_product_serial as ops on oop.order_id=ops.order_id and oop.order_product_id=ops.order_product_id WHERE oop.order_id = :order_id AND oop.p_type!=0 order by oop.order_product_id asc");
        $s->bindValue(':order_id', $order_id);
        $s->execute();
        $productsGlobal = $s->fetchAll(PDO::FETCH_ASSOC);
         return $productsGlobal;

    }
    public  function TotalAmountPerSellerOrder($order_id,$seller_id){
        $totaAmount = array();
        $s = $this->conn->prepare("SELECT value FROM order_total_per_store WHERE order_id=:order_id AND seller_id=:seller_id AND title=:title");
        $s->bindValue(':order_id', $order_id);
        $s->bindValue(':seller_id', $seller_id);
        $s->bindValue(':title', 'Sub-Total');
        $s->execute();
        $totaAmount = $s->fetch(PDO::FETCH_ASSOC);
        return $totaAmount['value'];
    }
}