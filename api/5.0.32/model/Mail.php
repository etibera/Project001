<?php
require_once '../init.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Mail {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    function __destruct(){
        $this->conn = null;
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
                $mail->Host       = MAIL_HOST;
                $mail->SMTPAuth   = true;                                   
                $mail->Username   = MAIL_USERNAME;
                $mail->Password   = MAIL_PASSWORD;
                $mail->SMTPAutoTLS = false;
                $mail->SMTPSecure = false;
                $mail->Port       = MAIL_PORT;
                $mail->setFrom(MAIL_USERNAME, 'PESO');
               
                $mail->addAddress('admin@pinoyelectronicstore.com', 'Admin');
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
            $mail->Host       = MAIL_HOST;
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = MAIL_USERNAME;
            $mail->Password   = MAIL_PASSWORD;
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->SMTPAutoTLS = false;
            $mail->SMTPSecure = false;
            $mail->Port       = MAIL_PORT;

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
           $mail->setFrom(MAIL_USERNAME, 'PESO');/*
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
            $mail->Host       = MAIL_HOST;
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = MAIL_USERNAME;
            $mail->Password   = MAIL_PASSWORD;
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->SMTPAutoTLS = false;
            $mail->SMTPSecure = false;
            $mail->Port       = MAIL_PORT;

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
           $mail->setFrom(MAIL_USERNAME, 'PESO');
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
              $mail->Host       = MAIL_HOST;
                $mail->SMTPAuth   = true;                                   
                $mail->Username   = MAIL_USERNAME;
                $mail->Password   = MAIL_PASSWORD;
                $mail->SMTPAutoTLS = false;
                $mail->SMTPSecure = false;
                $mail->Port       = MAIL_PORT;
                $mail->setFrom(MAIL_USERNAME, 'PESO');
                $mail->addAddress($emailcx,$cxname);
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
   public  function order_status_name($status_id){
        
    $s = $this->conn->prepare("SELECT * FROM oc_order_status where order_status_id = :status_id ");
    $s->bindValue(':status_id', $status_id);
    $s->execute();
    $status = $s->fetch(PDO::FETCH_ASSOC);
    return $status['name'];
}
public  function order_status_type($status_id){        
    $s = $this->conn->prepare("SELECT * FROM oc_order_status where order_status_id = :status_id ");
    $s->bindValue(':status_id', $status_id);
    $s->execute();
    $status = $s->fetch(PDO::FETCH_ASSOC);
    return $status['type'];

}
public  function get_store_orders($order_id){
    $data=array();
    $s = $this->conn->prepare("SELECT * FROM store_orders where order_id=:order_id");
    $s->bindValue(':order_id', $order_id);
    $s->execute();
    $data = $s->fetchAll(PDO::FETCH_ASSOC);
    return $data; 
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
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPAutoTLS = false;
        $mail->SMTPSecure = false;
        $mail->Port       = MAIL_PORT;

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
        $mail->setFrom(MAIL_USERNAME, 'PESO');
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
}

$mail = new Mail();