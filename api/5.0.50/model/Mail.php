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
        $ordercart= $this->getStoreOrders($order_id);
        $order ="";
        $wholebody="";
        foreach($ordercart as $cart){

                $seller_id=$cart['seller_id'];
                $order_number=$cart['order_number'];
                $order = $this->order_details_seller($order_id, $seller_id);
                $GetOrderPrd = $this->GetOrderPrd($order_id,$cart['seller_id'],$cart['order_number']);
                $GetOrderPrdTotals=$this->GetOrderPrdTotals($order_id,$cart['seller_id'],$cart['order_number']);
                $shopname=$cart['shop_name'];
                $shopimg=str_replace(" ","%20",$cart['image']);
                $status_name = $this->order_status_name($status_id);
                $status_type = $this->order_status_type($status_id);
                
                $tbody_val = '';
                $tbody_val=$email_templates->Gettbody_val($GetOrderPrd,"seller",$GetOrderPrdTotals);
                $grandtottal="";
                foreach ($order['total'] as $totals) {
                    $grandtottal .=
                      '<tr>'.
                      '<td colspan ="6" style="text-align:right">'.$totals['title'].' : </td>'.
                      '<td style="text-align:right"><b>'.number_format($totals['value'],2).'</b></td></tr>';
                }
                $ifemail=0;
                 $wholebody=$wholebody."<H3> Order#".$cart['order_number']."</H3><br><img style='width:20px;height:20px;' src='".$shopimg."'".">"." ".$shopname.
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
        $wholebody.="<div style='width:95%;'><hr></div>";
        $wholebody.="<table style='border-collapse: collapse;'>";
        $wholebody.= '<tr><td colspan="8"></td></tr>';
        $wholebody.= '<tr><td colspan="3"></td><td colspan="5"style="text-align:center">Grand Totals</td></tr>';
        $wholebody.=$grandtottal;
        $wholebody.="</table>";
        $wholebody.="<div style='width:95%;'><hr></div>";
        $wholebody.="<div style='width:95%;'><hr></div>";

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
    
               /* $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;                                   
                $mail->Username   = 'reizondev0001@gmail.com';
                $mail->Password   = '@abc1234';
                // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;
    */
                //Recipients
                $mail->setFrom('support@pesoapp.ph', 'PESO');
               
                $mail->addAddress('admin@pinoyelectronicstore.com', 'Admin');/*
                $mail->addAddress('edmaribera.2425@gmail.com', 'Edmar');*/
                
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
        $ordercart= $this->getStoreOrders($order_id);
        $order ="";
        $wholebody="";
        foreach($ordercart as $cart){
                $seller_id=$cart['seller_id'];
                $order_number=$cart['order_number'];
                $order = $this->order_details_seller($order_id, $seller_id);
                $GetOrderPrd = $this->GetOrderPrd($order_id,$cart['seller_id'],$cart['order_number']);
                $GetOrderPrdTotals=$this->GetOrderPrdTotals($order_id,$cart['seller_id'],$cart['order_number']);
                $shopname=$cart['shop_name'];
                $shopimg=str_replace(" ","%20",$cart['image']);
                $status_name = $this->order_status_name($status_id);
                $status_type = $this->order_status_type($status_id);
                $grandtottal="";
                foreach ($order['total'] as $totals) {
                    $grandtottal .=
                      '<tr>'.
                      '<td colspan ="6" style="text-align:right">'.$totals['title'].' : </td>'.
                      '<td style="text-align:right"><b>'.number_format($totals['value'],2).'</b></td></tr>';
                }
                $tbody_val = '';
                $tbody_val=$email_templates->Gettbody_val2($GetOrderPrd,"seller",$GetOrderPrdTotals);
                $ifemail=0;
                 $wholebody=$wholebody."<H3> Order#".$cart['order_number']."</H3><br><img style='width:20px;height:20px;' src='".$shopimg."'".">"." ".$shopname.
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
        $wholebody.="<div style='width:95%;'><hr></div>";
        $wholebody.="<table style='border-collapse: collapse;'>";
        $wholebody.= '<tr><td colspan="8"></td></tr>';
        $wholebody.= '<tr><td colspan="3"></td><td colspan="5"style="text-align:center">Grand Totals</td></tr>';
        $wholebody.=$grandtottal;
        $wholebody.="</table>";
        $wholebody.="<div style='width:95%;'><hr></div>";
        $wholebody.="<div style='width:95%;'><hr></div>";
        
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
    
               /* $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;                                   
                $mail->Username   = 'reizondev0001@gmail.com';
                $mail->Password   = '@abc1234';
                // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;*/
    
                //Recipients
                $mail->setFrom('support@pesoapp.ph', 'PESO');   /*            
                $mail->addAddress('edmaribera.2425@gmail.com', 'edmar');*/
                $mail->addAddress($emailcx,$cxname);
                
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
    public  function getStoreOrders($order_id){
        $data=array();
        $s = $this->conn->prepare("SELECT sb.b_name as shop_name,so.order_id,so.seller_id,
                                         concat('https://pesoapp.ph/img/',sb.branch_logo) as image,so.order_number 
                                            FROM store_orders so 
                                            INNER JOIN oc_seller os ON so.seller_id = os.seller_id
                                            INNER JOIN seller_branch sb ON sb.id=so.branch_id
                                            where so.order_id=:order_id");
        $s->bindValue(':order_id', $order_id);
        $s->execute();
        $data = $s->fetchAll(PDO::FETCH_ASSOC);
        return $data; 
    }
    public  function order_details_seller($order_id,$seller_id){        
        $stmt = $this->conn->prepare("SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS customer 
            FROM oc_order o 
            INNER JOIN oc_customer c ON c.customer_id = o.customer_id
            WHERE o.order_id=:order_id");
        $stmt->bindValue(':order_id', $order_id);
        $stmt->execute();
        $row = $stmt->fetch();
        $row['total'] = array();
        $t = $this->conn->prepare("SELECT * from oc_order_total where order_id=:order_id  order by sort_order asc;");
        $t->bindValue(':order_id', $order_id);
        $t->execute();
        $total = $t->fetchAll(PDO::FETCH_ASSOC);
        foreach($total as $totals){
        $row['total'][] = $totals;
        }
        return $row;
   }
   public function GetOrderPrd($order_id,$seller_id,$order_number){
    $products = array();
    $s = $this->conn->prepare("SELECT ops.serial,ops.id as serialid, oop.*,ifnull(oop.no_serial_added,0)as NOA,concat('https://pesoapp.ph/img/',op.image)as img 
                               FROM oc_order_product as oop 
                               inner join oc_product op
                                    on op.product_id=oop.product_id
                               left join oc_product_serial as ops on oop.order_id=ops.order_id and oop.order_product_id=ops.order_product_id
                               WHERE oop.order_id = :order_id AND oop.seller_id=:seller_id AND oop.order_number=:order_number order by oop.order_product_id asc");
    $s->bindValue(':order_id', $order_id);
    $s->bindValue(':seller_id', $seller_id);
    $s->bindValue(':order_number', $order_number);
    $s->execute();
    $products = $s->fetchAll(PDO::FETCH_ASSOC);
    return $products;
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