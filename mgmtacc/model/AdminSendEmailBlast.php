<?php

require_once "../include/database.php";   
require_once '../include/email_template.php';
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/Exception.php';
require_once '../PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
class SendEmailBlast { 
  private $conn;  
  public function __construct(){
    $this->conn = new Database();
    $this->conn = $this->conn->getmyDB(); 
  }  
 public function GetCustomerListEmail($pageNum){
    $no_per_page = 100;
    $offset = ($pageNum-1) * $no_per_page;
    $stmt = $this->conn->prepare("SELECT * FROM oc_customer WHERE email like'%@%' AND firstname!=''  order by firstname ASC LIMIT :offset , :no_per_page");
    $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    $stmt->bindValue(':no_per_page', $no_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $row;
  }
  public function sendEmailBlast($message,$subject) {
      global $email_templates;
      $headeremail=$email_templates->GetHeader();
      $footeremail=$email_templates->getFooter();     

      $stmtcust = $this->conn->prepare("SELECT *,REPLACE(email, ' ', '') as emailadd FROM oc_customer WHERE email like'%@%.%' AND firstname!='' order by firstname ASC");
      $stmtcust->execute();
      $customer_data=$stmtcust->fetchAll(PDO::FETCH_ASSOC);
      $countdata=0;
      if(count($customer_data)!=0){
        foreach ($customer_data as $custData) {
          if (filter_var($custData['emailadd'], FILTER_VALIDATE_EMAIL)) {
            $countdata++;
            $wholebody="<h3>Hi ". $custData['firstname'].", </h3></br>";
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
            $mail->addAddress($custData['emailadd'], $custData['firstname']);
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
            
        }
      }
    $status = $countdata." Email Successfully Sent";
    return $status;
  } 
}