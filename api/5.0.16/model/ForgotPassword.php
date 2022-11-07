<?php
require_once '../init.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class ForgotPassword {
    function __construct(){
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    public function count_token($token){
        $data=array();
        $stmt = $this->conn->prepare("SELECT count(customer_id) as count FROM oc_customer where change_pass_token=:change_pass_token");
        $stmt->bindValue(':change_pass_token', $token);
        $stmt->execute();
        $data=$stmt->fetch(PDO::FETCH_ASSOC);
        return $data['count'];
    } 
    public function update_password($customer_id,$data){
        $password =  $data['txtpassword'];
        $confirmpassword =  $data['txtconfirmpassword'];
        $used_symbols = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        $salt= substr(str_shuffle($used_symbols), 0, 9);
    
        $stmt = $this->conn->prepare("UPDATE oc_customer set password=:password,salt=:salt,change_pass_token=:change_pass_token  where customer_id=:customer_id");
        $stmt->bindValue(':customer_id', $customer_id);
        $stmt->bindValue(':salt', $salt);
        $stmt->bindValue(':password',sha1($salt . sha1($salt . sha1($password))) );
        $stmt->bindValue(':change_pass_token',"");
        $stmt->execute();
        return "200";
    }
    public function customer_data_bytoken($token){
        $data=array();
        $stmt = $this->conn->prepare("SELECT * FROM oc_customer where change_pass_token=:change_pass_token");
        $stmt->bindValue(':change_pass_token', $token);
        $stmt->execute();
        $data=$stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }
    public function customer_data($username){
        $data=array();
        $stmt = $this->conn->prepare("SELECT * FROM oc_customer where username=:username");
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        $data=$stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }
    public function forgotPassword($username){
        $username = trim($username);
        global $email_templates;
        $data="";
        if(preg_match('/^(?:09|\+?63)(?:\d(?:-)?){9,10}$/m', $username)){
            if(strlen($username) == 12){
                $username = substr($username ,-(strlen($username)-2));
            }
            if(strlen($username) == 11){
                $username = substr($username ,-(strlen($username)-1));
            }
        }
        $stmt = $this->conn->prepare("SELECT * FROM oc_customer where username=:username OR email = :username OR telephone = :username ORDER BY customer_id DESC LIMIT 1");
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if($stmt->rowCount() > 0){
          $change_pass_token=uniqid();
          $update = $this->conn->prepare("UPDATE oc_customer set change_pass_token=:change_pass_token where username=:username");
          $update->bindValue(':username', $username);
          $update->bindValue(':change_pass_token', $change_pass_token);
          $update->execute();
          $headeremail=$email_templates->GetHeader();
          $footeremail=$email_templates->getFooter();
          //$customer_data=$this->customer_data($username);
          
    
          $link="https://pesoapp.ph/change_password.php?Y2F0X2lk=".$change_pass_token."&5aP7W1h9K=5217c1cbaaf7c1a7627b87760fe6efa9f704008a";
          $smsmessage=" Hi ". $row['firstname'].",Please click the link to change your password https://pesoapp.ph/change_password.php?Y2F0X2lk=".$change_pass_token;
          $wholebody="<h3>Hi ". $row['firstname'].", </h3><h4 style:'width='70%'>Please click the link to change your password </h4>";
          $wholebody.="<h3><a ng-if='showField('website')' href='$link' target='_blank' rel='noopener' style='text-decoration:none;' class='ng-scope'><span style='font-size:9pt; font-family:Arial, sans-serif; color:#008cba;'><span style='font-family:Arial, sans-serif; color:#008cba;' class='ng-binding'>Change Password</span></span></a><!-- end ngIf: showField('website') --><h3>";
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
          $mail->setFrom('customer@pinoyelectronicstore.com', 'PESO');
          $mail->addAddress($row['email'], $row['firstname']);
          $mail->isHTML(true);
          $mail->Subject ="PESO Change Password";
          $mail->Body    = "<html><head><style>".
                             "table { border-collapse: collapse; } td,th { padding:7px;} th { text-align:left; }".
                             "</style></head><body><div style='margin:auto'>".
                             $headeremail."<br>".$wholebody."<br><br>".
                          "<div style='width:95%'>".$footeremail."</div></div></body></html>";
        
          $mail->send();
          $smsin = $this->conn->prepare("INSERT INTO sms set MobileNumberList =:mobile, Message =:Message,status=:status");
          $smsin->bindValue(':mobile', "0".$row['telephone']);
          $smsin->bindValue(':Message', $smsmessage);
          $smsin->bindValue(':status', 0);
          $smsin->execute();
          $data = array('header' => 'Successfully Send', 'message' => 'Please Check your Email (<b>'.$row['email'].'</b>) to changing your password');
        }else{
          $data= array('header' => 'Failed Send', 'message' => 'Username is not exists');
        }   
        return $data;
      }
}
$forgotPassword = new ForgotPassword();