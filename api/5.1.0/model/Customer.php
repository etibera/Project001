<?php
require_once '../init.php';
class Customer {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    function __destruct(){
        $this->conn = null;
    }
    public  function account_details($customer_id){              
              
          $stmt = $this->conn->prepare("SELECT * from oc_customer where customer_id = :customer_id");
            $stmt->bindValue(':customer_id', $customer_id);
            $stmt->execute();
            $row = $stmt->fetch();            
            return $row;
    } 
    public function edit_customer($data = array()){
        $customer_data=$this->account_details((int) trim($data['customer_id']));
        $confirmcode=rand(100000,999999);
        $b_date=date('Y-m-d',strtotime($data['bday']));        
        $sql = "UPDATE oc_customer SET firstname = :firstname, lastname = :lastname, b_day= :b_day, 
        email = :email, telephone = :telephone, fax = '', custom_field = '' ";
        
        if(array_key_exists('type', $data)){
            if($data['type'] == 'guest'){
                $sql .= ", `type` = :type";
            }
        }
        $sql .= " WHERE customer_id = :customer_id";

        $s = $this->conn->prepare($sql);
        $s->bindValue(':firstname',  utf8_decode($data['firstname']));
        $s->bindValue(':lastname',  utf8_decode($data['lastname']));
        $s->bindValue(':b_day',  $b_date);
        $s->bindValue(':email',  trim($data['email']));
        $s->bindValue(':telephone', $data['telephone']);

        if(array_key_exists('type', $data)){
            if($data['type'] == 'guest'){
                $s->bindValue(':type', '');
                $data['type'] = '';
            }
        }
        $s->bindValue(':customer_id', (int) trim($data['customer_id']));
        $s->execute();
        //for sending PESO Verification
        if($customer_data['nexmo_status']==0){
            $stmt = $this->conn->prepare("UPDATE oc_customer SET nexmo_code = :code WHERE customer_id = :customer_id LIMIT 1");
            $stmt->bindValue(':customer_id', (int) trim($data['customer_id']));
            $stmt->bindValue(':code', $confirmcode);
            $stmt->execute();
            $M360Api=new M360Api;
            $credentials =$M360Api->M360Credetial();
            $M360Domain =$M360Api->M360Domain();

            $M360Url = $M360Domain['production'];
            $messageval='Your PESO Verification Code Is '.$confirmcode;
            $mobileNumber='0'.$data['telephone'];
            $content=$messageval;
            $M360RequestData = array(
                'username' => $credentials['username'],
                'password' => $credentials['password'],
                'msisdn' =>  $mobileNumber,
                'content' =>  $content,
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
            if($ResponsedataM360->code=="400"){
               $s1 = $this->conn->prepare("INSERT INTO sms SET 
                MobileNumberList = :telephone, Message = :message, status = 0");
                $s1->bindValue(':telephone', '0'.$data['telephone']);
                $s1->bindValue(':message', 'Your PESO Verification Code Is '. $confirmcode);
                $s1->execute();
            }   

        }else if($customer_data['telephone']=!$data['telephone']){
            $stmt = $this->conn->prepare("UPDATE oc_customer SET nexmo_code = :code WHERE customer_id = :customer_id LIMIT 1");
            $stmt->bindValue(':customer_id', (int) trim($data['customer_id']));
            $stmt->bindValue(':code', $confirmcode);
            $stmt->execute();
            $M360Api=new M360Api;
            $credentials =$M360Api->M360Credetial();
            $M360Domain =$M360Api->M360Domain();

            $M360Url = $M360Domain['production'];
            $messageval='Your PESO Verification Code Is '.$confirmcode;
            $mobileNumber='0'.$data['telephone'];
            $content=$messageval;
            $M360RequestData = array(
                'username' => $credentials['username'],
                'password' => $credentials['password'],
                'msisdn' =>  $mobileNumber,
                'content' =>  $content,
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
            if($ResponsedataM360->code=="400"){
               $s1 = $this->conn->prepare("INSERT INTO sms SET 
                MobileNumberList = :telephone, Message = :message, status = 0");
                $s1->bindValue(':telephone', '0'.$data['telephone']);
                $s1->bindValue(':message', 'Your PESO Verification Code Is '. $confirmcode);
                $s1->execute();
            }  

        }  
        return $data;
    }
    public function change_password($data = array()){
        $json = array();
        $message = '';
        $s = $this->conn->prepare("SELECT * FROM oc_customer WHERE customer_id= :customer_id AND 
        (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1(:currentPassword)))))) OR 
        password = md5(:currentPassword) AND status = 1 AND approved = 1");
        $s->bindValue(':customer_id', (int) trim($data['customer_id']));
        $s->bindValue(':currentPassword',  trim($data['currentPassword']));
        $s->execute();
        if($s->rowCount() == 0){
            $message = 'Incorrect Password';
        }else{
            if($data['currentPassword'] == $data['password']){
                $message = "Current Password is not allowed!";
            }else{
                $used_symbols = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
                $salt= substr(str_shuffle($used_symbols), 0, 9);
                $stmt=$this->conn->prepare("UPDATE oc_customer set salt = :salt,password = :password where customer_id = :id");
                $stmt->bindValue(':id', (int) trim($data['customer_id']));
                $stmt->bindValue(':password',sha1($salt . sha1($salt . sha1($data['password']))) );
                $stmt->bindValue(':salt', $salt);
                $stmt->execute();

            }
        }
        $json['error'] = $message;

        return $json;
    }
    public function updateUsernameAndPassword($data){
        global $affiliate;
        $used_symbols = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        $salt= substr(str_shuffle($used_symbols), 0, 9);
        $s = $this->conn->prepare("UPDATE oc_customer SET username = :username, salt=:salt, `password` = :password WHERE customer_id = :customer_id");
        $s->bindValue(':username', $data['username']);
        $s->bindValue(':password',  sha1($salt . sha1($salt . sha1($data['password']))));
        $s->bindValue(':salt', $salt);
        $s->bindValue(':customer_id',  $data['customer_id']);
        $s->execute();
        $affiliate->add_shipping_wallet($data['customer_id']);
        return array('message' => 'Successfully Saved');
    }
}
$customer = new Customer();