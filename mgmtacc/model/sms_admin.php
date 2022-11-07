<?php

require_once "../include/database.php";   
require_once '../include/email_template.php';
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/Exception.php';
require_once '../PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
class sms_admin { 
  private $conn;  
  public function __construct(){
    $this->conn = new Database();
    $this->conn = $this->conn->getmyDB(); 
  }  
  
   public function GetCustomerList($type){
    switch($type){
      case '0':           
        $stmt = $this->conn->prepare("SELECT customer_id,lastname,firstname,telephone FROM oc_customer where nexmo_status=1 and telephone!='' and telephone like '9%' and LENGTH(telephone)=10 and landbankacc=0 and fgivesacc=0 order by firstname ASC");
  
      break;
      case '1':           
        $stmt = $this->conn->prepare("SELECT customer_id,lastname,firstname,telephone FROM oc_customer where nexmo_status=1 and telephone!='' and telephone like '9%' and LENGTH(telephone)=10 and landbankacc=1 and fgivesacc=0 order by firstname ASC");
      break;
      case '2':           
        $stmt = $this->conn->prepare("SELECT customer_id,lastname,firstname,telephone FROM oc_customer where nexmo_status=1 and telephone!='' and telephone like '9%' and LENGTH(telephone)=10 and landbankacc=0 and fgivesacc=1 order by firstname ASC");
      break;
      case 'All':           
        $stmt = $this->conn->prepare("SELECT customer_id,lastname,firstname,telephone FROM oc_customer where nexmo_status=1 and telephone!='' and telephone like '9%' and LENGTH(telephone)=10  order by firstname ASC");
      break;
    }
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $row;
   }
    public function GetCustomerListEmail(){
    $stmt = $this->conn->prepare("SELECT * FROM oc_customer WHERE email like'%@%'");
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $row;
   }
  public function InsertSMS($number,$message) {
    try {
      $stmt =$this->conn->prepare("INSERT INTO sms SET MobileNumberList = :mobnumber, Message = :message, `status` = 0;");
      $stmt->bindValue(':mobnumber', $number);
      $stmt->bindValue(':message', $message);
      $stmt->execute();
      $status = "200";
    }catch(Exception $e){
          $status=$e;
    } 
        return $status;
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

  public function sendSMS($data,$message) {
    try {
      foreach ($data  as $value) {        
        $stmt =$this->conn->prepare("INSERT INTO sms SET MobileNumberList = :mobnumber, Message = :message, `status` = 0;");
        $stmt->bindValue(':mobnumber', $value);
        $stmt->bindValue(':message', $message);
        $stmt->execute();
      }
      $status = "Successfully Sent";
    }catch(Exception $e){
          $status=$e;
    } 
        return $status;
  } 
  public function sendSMSPesoapp($data,$message) {
    try {
      foreach ($data  as $value) {   
        require_once "../include/M360Api.php";
        $M360Api=new M360Api;
        $credentials =$M360Api->M360Credetial();
        $M360Domain =$M360Api->M360Domain();
        $M360Url = $M360Domain['production'];
        
        $M360RequestData = array(
            'username' => $credentials['username'],
            'password' => $credentials['password'],
            'msisdn' =>  $value,
            'content' =>  $message,
            'shortcode_mask' => $credentials['shortcode_mask'],
        );
        $M360sms = curl_init($M360Url);
        curl_setopt($M360sms, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($M360sms, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'     
        ));
        curl_setopt($M360sms, CURLOPT_POST, 1);
        curl_setopt($M360sms, CURLOPT_POSTFIELDS,json_encode($M360RequestData));
        curl_setopt($M360sms, CURLOPT_FOLLOWLOCATION, 1);
        $dataM360sms = curl_exec($M360sms);
        curl_close($M360sms);
        $ResponsedataM360 = json_decode($dataM360sms);
       
      }
      $status = "Successfully Sent";
    }catch(Exception $e){
          $status=$e;
    } 
        return $status;
  } 

}