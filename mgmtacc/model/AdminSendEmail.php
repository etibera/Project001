<?php

require_once "../include/database.php";   
require_once '../include/email_template.php';
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/Exception.php';
require_once '../PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
class SendEmail { 
  private $conn;  
  public function __construct(){
    $this->conn = new Database();
    $this->conn = $this->conn->getmyDB(); 
  }  
  

//   public function GetCustomerListEmail($pageNum){
//     $no_per_page = 100;
//     $offset = ($pageNum-1) * $no_per_page;
//     $stmt = $this->conn->prepare("SELECT * FROM oc_customer WHERE email like'%@%' AND firstname!=''  order by firstname ASC LIMIT :offset , :no_per_page");
//     $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
//     $stmt->bindValue(':no_per_page', $no_per_page, PDO::PARAM_INT);
//     $stmt->execute();
//     $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//     return $row;
//   }
  public function GetCustomerListEmail($type){
    switch($type){
      case '0':           
        $stmt = $this->conn->prepare("SELECT * FROM oc_customer WHERE email like'%@%' AND landbankacc=0 and fgivesacc=0 ORDER BY firstname");
  
      break;
      case '1':   
        $stmt = $this->conn->prepare("SELECT * FROM oc_customer WHERE email like'%@%' AND landbankacc=1 and fgivesacc=0 ORDER BY firstname");   
      break;
      case '2':  
        $stmt = $this->conn->prepare("SELECT * FROM oc_customer WHERE email like'%@%' AND landbankacc=0 and fgivesacc=1 ORDER BY firstname");      
      break;
      case 'All':           
        $stmt = $this->conn->prepare("SELECT * FROM oc_customer WHERE email like'%@%' ORDER BY firstname");
      break;
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public function GetCustomerListEmailByNAME($name){    
    $stmt = $this->conn->prepare("SELECT * FROM oc_customer WHERE firstname LIKE :name OR lastname LIKE :name  order by firstname ASC");
    $stmt->bindValue(':name','%'.$name.'%');
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $row;
  }
  public function getcustomerPageNumber(){   
    $stmt = $this->conn->prepare("SELECT ROUND(COUNT(customer_id)/100,0) as pagenumber FROM oc_customer WHERE email like'%@%' AND firstname!='' ;");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['pagenumber'];
   }
 
  public function sendCustomerEmail($data,$message,$subject) {
      global $email_templates;
      $headeremail=$email_templates->GetHeader();
      $footeremail=$email_templates->getFooter();
      foreach ($data  as $value) {   
        $data="";         
        $stmtcust = $this->conn->prepare("SELECT * FROM oc_customer where customer_id=:customer_id");
        $stmtcust->bindValue(':customer_id', $value);
        $stmtcust->execute();
        $customer_data=$stmtcust->fetch(PDO::FETCH_ASSOC);

        $wholebody="<h3>Hi ". $customer_data['firstname'].", </h3></br>";
        $wholebody.="<h5>".$message."<h5>";
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
        $mail->setFrom('support@pesoapp.ph', 'PESO');   /*            
        $mail->addAddress('edmaribera.2425@gmail.com', 'Admin');*/
        $mail->addAddress($customer_data['email'], $customer_data['firstname']);
        $mail->isHTML(true);
        $mail->Subject =$subject;
                  // $mail->AddEmbeddedImage('https://pesoapp.ph/assets/PESO trans2.png', 'PESO');
        $mail->Body    = "<html><head><style>".
                           "table { border-collapse: collapse; } td,th { padding:7px;} th { text-align:left; }".
                           "</style></head><body><div style='margin:auto'>".
                           $headeremail."<br>".$wholebody."<br><br>".
                        "<div style='width:95%'>".$footeremail."</div></div></body></html>";
      
        $mail->send();
      }
    $status = "Successfully Sent";
    return $status;
  } 
  public function sendemailTest() {
      global $email_templates;
      $headeremail=$email_templates->GetHeader();
      $footeremail=$email_templates->getFooter();
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
      $mail->setFrom('support@pesoapp.ph', 'PESO');               
      $mail->addAddress('edmaribera.2425@gmail.com', 'Admin');
      
      $mail->isHTML(true);
      $mail->Subject ="PESO Change Password";
                // $mail->AddEmbeddedImage('https://pesoapp.ph/assets/PESO trans2.png', 'PESO');
      $mail->Body    = "<html><head><style>".
                         "table { border-collapse: collapse; } td,th { padding:7px;} th { text-align:left; }".
                         "</style></head><body><div style='margin:auto'>".
                         $headeremail."<br>Test<br><br>".
                      "<div style='width:95%'>".$footeremail."</div></div></body></html>";
    
      $mail->send();
      $status = "Eamil Successfully Sent";
      return $status;
  }



}