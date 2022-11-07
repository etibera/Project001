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
    public function edit_customer($data = array()){
        $b_date=date('Y-m-d',strtotime($data['bday']));
        
        $s = $this->conn->prepare("UPDATE oc_customer SET firstname = :firstname, lastname = :lastname, b_day= :b_day, 
        email = :email, telephone = :telephone, fax = '', custom_field = '' WHERE customer_id = :customer_id");
        $s->bindValue(':firstname',  utf8_decode($data['firstname']));
        $s->bindValue(':lastname',  utf8_decode($data['lastname']));
        $s->bindValue(':b_day',  $b_date);
        $s->bindValue(':email',  trim($data['email']));
        $s->bindValue(':telephone', $data['telephone']);
        $s->bindValue(':customer_id', (int) trim($data['customer_id']));
        $s->execute();
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
}
$customer = new Customer();