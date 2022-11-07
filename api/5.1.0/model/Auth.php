<?php
require_once '../init.php';
class Auth {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    function __destruct(){
        $this->conn = null;
    }
    public function customer($data, $eventName = ''){
        global $affiliate;
        return array(
            'id' => $data['customer_id'],
            'firstName' => utf8_encode($data['firstname']),
            'lastName' => utf8_encode($data['lastname']),
            'email' => $data['email'],
            'telephone' => $data['telephone'] == 'null' ? '' : $data['telephone'],
            'cart' => $data['cart'],
            'addressId' => $data['address_id'],
            'username' => $data['username'],
            'birthday' => $data['b_day'],
            'nexmoStatus' => $data['nexmo_status'],
            'nexmoCode' => $data['nexmo_code'],
            'dateAdded' => $data['date_added'],
            'isFb' => false,
            'affiliateProgram' => $affiliate->check_affiliate($data['customer_id']),
            'type' => $data['type'],
            'eventName' => $eventName,
            'isPickedCategory' => $this->guestCategoryLength($data['customer_id'])
        );
    }
    public function guestCategoryLength($customer_id){
        $stmt1 = $this->conn->prepare("SELECT * FROM customer_guest_category WHERE customer_id = :customer_id LIMIT 1");
        $stmt1->bindValue(':customer_id', $customer_id);
        $stmt1->execute();
        return $stmt1->rowCount() > 0 ? true : false;

    }
    public function login(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['username'], $_POST['password'])){
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            $newpass=md5($password);
            $strtelephone = '0';
            if(preg_match('/^(?:09|\+?63)(?:\d(?:-)?){9,10}$/m', $username)){
                if(strlen($username) == 12){
                    $strtelephone = substr($username ,-(strlen($username)-2));
                }
                if(strlen($username) == 11){
                    $strtelephone = substr($username ,-(strlen($username)-1));
                }
            }
            
            $stmt = $this->conn->prepare("SELECT * FROM oc_customer WHERE (LOWER(username) =:username  or telephone=:strtelephone or email= :username ) AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1(:password))))) OR password = :newpass) AND status = '1' AND approved = '1' ORDER BY customer_id DESC LIMIT 1");
            $stmt->bindValue(':username', $username);
            $stmt->bindValue(':password', $password);
            $stmt->bindValue(':newpass',  $newpass);
            $stmt->bindValue(':strtelephone',  $strtelephone);
            $stmt->execute();
            $row = $stmt->fetch();
            return !empty($row) ? $this->customer($row): false;
        }else{
            exit();
        }
    }else{
        exit();
    }

    }
    public function register(){
        $data = array();
        global $affiliate;
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            try{
                $confirmcode=rand(100000,999999);
                $used_symbols = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
                $salt= substr(str_shuffle($used_symbols), 0, 9);
                // if($_POST['bday'] != ''){
                //     $b_date=date('Y-m-d',strtotime($_POST['bday']));
                // }else{
                //     $b_date = null;
                // }
                // $telephone = $_POST['telephone'] != "" ? $_POST['telephone'] : "";
                $username = trim($_POST['username']);
                $password = trim($_POST['password']);
                $s = $this->conn->prepare("INSERT INTO oc_customer SET 
                customer_group_id = 1, 
                store_id = 0, 
                firstname = '', lastname = '',
                b_day= null, email = :email, telephone = '', 
                fax = '', custom_field = '', salt = :salt, password = :password, 
                newsletter = 0, ip = :ip, status = 1, approved = 1, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00'),
                username=:username, nexmo_code=:nexmo_code,nexmo_status=0, safe=0, token='', a2sh_status='', fb_app_id=''");
                // $s->bindValue(':firstname', utf8_decode($_POST['firstname']));
                // $s->bindValue(':lastname', utf8_decode($_POST['lastname']));
                // $s->bindValue(':b_day', $b_date);
                $s->bindValue(':email', $_POST['email']);
                // $s->bindValue(':telephone', $telephone);
                $s->bindValue(':salt', $salt);
                $s->bindValue(':password',sha1($salt . sha1($salt . sha1($password))));
                $s->bindValue(':ip',$_SERVER['REMOTE_ADDR']);
                $s->bindValue(':username', $username);
                $s->bindValue(':nexmo_code', $confirmcode);
                $s->execute();
                $lastId = $this->conn->lastInsertId();
                $affiliate->add_shipping_wallet($lastId);
                // $s1 = $this->conn->prepare("INSERT INTO sms SET 
                // MobileNumberList = :telephone, Message = :message, status = 0");
                // $s1->bindValue(':telephone', '0'.$telephone);
                // $s1->bindValue(':message', 'Your PESO Verification Code Is '. $confirmcode);
                // $s1->execute();
                
                $data = $this->login($username, $password);
            }catch(PDOexception $e){
                echo "<p>Database Error: $e </p>";
                exit();
            }
            return $data;
        }else{
            exit();
        }

    }
    public function resend_code($customer_id, $telephone){            
                $confirmcode=rand(100000,999999);
                $stmt = $this->conn->prepare("UPDATE oc_customer SET nexmo_code = :code WHERE customer_id = :customer_id LIMIT 1");
                $stmt->bindValue(':customer_id', $customer_id);
                $stmt->bindValue(':code', $confirmcode);
                $stmt->execute();

                $M360Api=new M360Api;
                $credentials =$M360Api->M360Credetial();
                $M360Domain =$M360Api->M360Domain();

                $M360Url = $M360Domain['production'];
                $messageval='Your PESO Verification Code Is '.$confirmcode;
                $mobileNumber='0'.$telephone;
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
                    $s1->bindValue(':telephone', '0'.$telephone);
                    $s1->bindValue(':message', 'Your PESO Verification Code Is '. $confirmcode);
                    $s1->execute();
                }   
                return true;
    }
    public function update_contact_number($customer_id, $telephone){
        if($customer_id != null || $customer_id != 'undefined'){
            $stmt = $this->conn->prepare("UPDATE oc_customer SET telephone = :telephone WHERE customer_id = :customer_id");
            $stmt->bindValue(':telephone', $telephone);
            $stmt->bindValue(':customer_id', $customer_id);
            $stmt->execute();
            
            $stmt1 = $this->conn->prepare("SELECT * FROM oc_customer WHERE customer_id = :customer_id LIMIT 1");
            $stmt1->bindValue(':customer_id', $customer_id);
            $stmt1->execute();
                    
            $data = array(
                    'msg' => 'Successfully updated',
                    'error' => false,
                    'data' => $this->customer($stmt1->fetch())
                );
            return $data;
            
        }
    }
    public function verify_code($customer_id, $code){
            global $affiliate;
            $data = array();
            if($customer_id != null || $customer_id != 'undefined'){
                $stmt = $this->conn->prepare("SELECT * FROM oc_customer WHERE customer_id = :customer_id LIMIT 1");
                $stmt->bindValue(':customer_id', $customer_id);
                $stmt->execute();
                $result = $stmt->fetch();
                if($result['nexmo_code'] == $code){
                    $stmt = $this->conn->prepare("UPDATE oc_customer set nexmo_status = 1 WHERE customer_id = :customer_id");
                    $stmt->bindValue(':customer_id', $customer_id);
                    $stmt->execute();
                    
                    $stmt1 = $this->conn->prepare("SELECT * FROM oc_customer WHERE customer_id = :customer_id LIMIT 1");
                    $stmt1->bindValue(':customer_id', $customer_id);
                    $stmt1->execute();
                    $affiliate->add_shipping_wallet($customer_id, 500);
                    

                    $data = array(
                        'msg' => 'Successfully verified',
                        'error' => false,
                        'data' => $this->customer($stmt1->fetch())
                        );
                    return $data;
                }else{
                    $data = array(
                        'msg' => 'Your code is invalid',
                        'error' => true
                        );
                    return $data;
                }
            }
           
    }
    public function check_username($username){
            $stmt = $this->conn->prepare("SELECT * FROM oc_customer WHERE LOWER(username) = :username");
            $stmt->bindValue(':username', $username);
            $stmt->execute();
            return $stmt->rowCount() > 0 ? true : false;
    }
    public function check_email($email){
            $stmt = $this->conn->prepare("SELECT * FROM oc_customer WHERE LOWER(email) = :email");
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            return $stmt->rowCount() > 0 ? true: false;
    }
    public function checkNumber($number){
            $stmt = $this->conn->prepare("SELECT * FROM oc_customer WHERE telephone = :telephone");
            $stmt->bindValue(':telephone', $number);
            $stmt->execute();
            return $stmt->rowCount() > 0 ? true: false;
    }
    public function checkNumberByCustomer($number, $customer_id){
            $validate = false;
            $stmt = $this->conn->prepare("SELECT * FROM oc_customer WHERE telephone = :telephone AND customer_id = :customer_id");
            $stmt->bindValue(':telephone', $number);
            $stmt->bindValue(':customer_id', $customer_id);
            $stmt->execute();
            if($stmt->rowCount() == 0){
                $stmt1 = $this->conn->prepare("SELECT * FROM oc_customer WHERE telephone = :telephone");
                $stmt1->bindValue(':telephone', $number);
                $stmt1->execute();
                $validate = $stmt1->rowCount() > 0 ? true : false;
            }
            return $validate;
    }
    public function checkEmailByCustomer($email, $customer_id){
        $validate = false;
        $stmt = $this->conn->prepare("SELECT * FROM oc_customer WHERE LOWER(email) = :email AND customer_id = :customer_id");
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':customer_id', $customer_id);
        $stmt->execute();
        if($stmt->rowCount() == 0){
            $stmt1 = $this->conn->prepare("SELECT * FROM oc_customer WHERE email = :email");
            $stmt1->bindValue(':email', $email);
            $stmt1->execute();
            $validate = $stmt1->rowCount() > 0 ? true : false;
        }
        return $validate;
    }
    public function customer_info($customer_id){
        $s = $this->conn->prepare('SELECT * FROM oc_customer where customer_id = :customer_id');
        $s->bindValue(':customer_id', $customer_id);
        $s->execute();
        return $s->fetch();
    }
    public function insert_customer_view($platform){
        $s = $this->conn->prepare("SELECT * FROM customer_views where ip = :ip 
        AND platform = :platform AND DATE(date_viewed) = convert_tz(utc_timestamp(),'-08:00','+0:00')");
        $s->bindValue(':ip', $_SERVER['REMOTE_ADDR']);
        $s->bindValue(':platform', $platform);
        $s->execute();
        if($s->rowCount() == 0){
            $stmt = $this->conn->prepare("INSERT INTO customer_views SET 
            platform = :platform, ip = :ip, date_viewed=convert_tz(utc_timestamp(),'-08:00','+0:00')");
            $stmt->bindValue(':platform', $platform);
            $stmt->bindValue(':ip', $_SERVER['REMOTE_ADDR']);
            $stmt->execute();
        }
    
     $s = $this->conn->prepare("SELECT * FROM customer_views_hourly where ip = :ip 
        AND platform = :platform AND HOUR(date_viewed) = HOUR(convert_tz(utc_timestamp(),'-08:00','+0:00'))");
        $s->bindValue(':ip', $_SERVER['REMOTE_ADDR']);
        $s->bindValue(':platform', $platform);
        $s->execute();
        if($s->rowCount() == 0){
            $stmt = $this->conn->prepare("INSERT INTO customer_views_hourly SET 
            platform = :platform, ip = :ip, date_viewed=convert_tz(utc_timestamp(),'-08:00','+0:00')");
            $stmt->bindValue(':platform', $platform);
            $stmt->bindValue(':ip', $_SERVER['REMOTE_ADDR']);
            $stmt->execute();
        }
    return false;
    }
    public function chinaBrandLogin(){
        $client_secret = '7b0e788f86fb7b5b5a41c1a5674ceedf';
		$api_url = 'https://gloapi.chinabrands.com/v2/user/login';
		$data_auth = array(
		    'email' => 'chinabrands_api@pinoyelectronicstore.com',
		    'password' => 'kerberos@ddssi01',
		    'client_id' =>  '3502591804'
		);
		$json_data = json_encode($data_auth);
		$signature_string = md5($json_data.$client_secret);
		$post_data = 'signature='.$signature_string.'&data='.urlencode($json_data);
		$curl = curl_init($api_url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
		$result_auth = curl_exec($curl);
		curl_close($curl);
		$res_auth= json_decode($result_auth);
		return $token=$res_auth;
    }
    public function googleLogin($googleData){
        global $affiliate;
        $data = array();
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $googleData = json_decode(htmlspecialchars_decode($googleData), true);
            $_POST['username'] = $googleData['email'];
            $_POST['password'] = 'abc123';

            $username = trim($_POST['username']);
            $password = 'abc123';
            $eventName = 'login';
            try{
                if(!$this->check_email($googleData['email'])){
                    $eventName = 'sign_up';
                     $confirmcode=rand(100000,999999);
                    $used_symbols = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
                    $salt= substr(str_shuffle($used_symbols), 0, 9);
                    // if($_POST['bday'] != ''){
                    //     $b_date=date('Y-m-d',strtotime($_POST['bday']));
                    // }else{
                    //     $b_date = null;
                    // }
                    $s = $this->conn->prepare("INSERT INTO oc_customer SET 
                    customer_group_id = 1, 
                    store_id = 0, 
                    firstname = :firstname, lastname = :lastname,
                    b_day=:b_day, email = :username, telephone = '', 
                    fax = '', custom_field = '', salt = :salt, password = :password, 
                    newsletter = 0, ip = :ip, status = 1, approved = 1, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00'),
                    username=:username, nexmo_code=:nexmo_code,nexmo_status=0, safe=0, token='', a2sh_status='', fb_app_id='', type='google'");
                    $s->bindValue(':firstname', $googleData['givenName']);
                    $s->bindValue(':lastname', $googleData['familyName']);
                    $s->bindValue(':b_day', null);
                    $s->bindValue(':email', $googleData['email']);
                    $s->bindValue(':salt', $salt);
                    $s->bindValue(':password',sha1($salt . sha1($salt . sha1($password))));
                    $s->bindValue(':ip',$_SERVER['REMOTE_ADDR']);
                    $s->bindValue(':username', $googleData['email']);
                    $s->bindValue(':nexmo_code', $confirmcode);
                    $s->execute();
                    $lastId = $this->conn->lastInsertId();
                    $affiliate->add_shipping_wallet($lastId, 300);
                }
                $data = $this->googleLoginData($googleData['email'], $eventName);
            }catch(PDOexception $e){
                echo "<p>Database Error: $e </p>";
                exit();
            }
            return $data;
        }else{
            exit();
        }
    }
    public function googleLoginData($email, $eventName){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $sql = "SELECT * FROM oc_customer WHERE (LOWER(username) =:username or email= :username ) AND status = '1' AND approved = '1' ORDER BY customer_id DESC LIMIT 1";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindValue(':username', $email);
                    $stmt->execute();
                    $row = $stmt->fetch();
                    return !empty($row) ? $this->customer($row, $eventName): false;
            }else{
                exit();
            }
    }
        public function appleLogin($googleData){
            // throw new Exception($googleData);
        global $affiliate;
        $data = array();
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $googleData = json_decode(htmlspecialchars_decode($googleData), true);
            $_POST['username'] = $googleData['email'];
            $_POST['password'] = 'abc123';

            $username = trim($_POST['username']);
            $password = 'abc123';
            $eventName = 'login';
            try{
                if(!$this->check_email($googleData['email'])){
                    $eventName = 'sign_up';
                     $confirmcode=rand(100000,999999);
                    $used_symbols = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
                    $salt= substr(str_shuffle($used_symbols), 0, 9);
                    $s = $this->conn->prepare("INSERT INTO oc_customer SET 
                    customer_group_id = 1, 
                    store_id = 0, 
                    firstname = :firstname, lastname = :lastname,
                    b_day=:b_day, email = :email, telephone = '', 
                    fax = '', custom_field = '', salt = :salt, password = :password, 
                    newsletter = 0, ip = :ip, status = 1, approved = 1, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00'),
                    username=:username, nexmo_code=:nexmo_code,nexmo_status=0, safe=0, token='', a2sh_status='', fb_app_id='', type='apple'");
                    $s->bindValue(':firstname', $googleData['givenName'] && '');
                    $s->bindValue(':lastname', $googleData['familyName'] && '');
                    $s->bindValue(':b_day', null);
                    $s->bindValue(':email', $googleData['email']);
                    $s->bindValue(':salt', $salt);
                    $s->bindValue(':password',sha1($salt . sha1($salt . sha1($password))));
                    $s->bindValue(':ip',$_SERVER['REMOTE_ADDR']);
                    $s->bindValue(':username', $googleData['email']);
                    $s->bindValue(':nexmo_code', $confirmcode);
                    $s->execute();
                    $lastId = $this->conn->lastInsertId();
                    $affiliate->add_shipping_wallet($lastId, 300);
                }
                $data = $this->googleLoginData($googleData['email'], $eventName);
            }catch(PDOexception $e){
                echo "<p>Database Error: $e </p>";
                exit();
            }
            return $data;
        }else{
            exit();
        }
    }
    public function facebookLogin($googleData){
        global $affiliate;
        $data = array();
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $googleData = json_decode(htmlspecialchars_decode($googleData), true);
            $_POST['username'] = $googleData['email'];
            $_POST['password'] = 'abc123';

            $username = trim($_POST['username']);
            $password = 'abc123';
            $eventName = 'login';
            try{
                if(!$this->check_email($googleData['email'])){
                    $eventName = 'sign_up';
                     $confirmcode=rand(100000,999999);
                    $used_symbols = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
                    $salt= substr(str_shuffle($used_symbols), 0, 9);
                    // if($_POST['bday'] != ''){
                    //     $b_date=date('Y-m-d',strtotime($_POST['bday']));
                    // }else{
                    //     $b_date = null;
                    // }
                    $s = $this->conn->prepare("INSERT INTO oc_customer SET 
                    customer_group_id = 1, 
                    store_id = 0, 
                    firstname = :firstname, lastname = :lastname,
                    b_day=:b_day, email = :email, telephone = '', 
                    fax = '', custom_field = '', salt = :salt, password = :password, 
                    newsletter = 0, ip = :ip, status = 1, approved = 1, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00'),
                    username=:username, nexmo_code=:nexmo_code,nexmo_status=0, safe=0, token='', a2sh_status='', fb_app_id='', type='facebook'");
                    $s->bindValue(':firstname', $googleData['first_name']);
                    $s->bindValue(':lastname', $googleData['last_name']);
                    $s->bindValue(':b_day', null);
                    $s->bindValue(':email', $googleData['email']);
                    $s->bindValue(':salt', $salt);
                    $s->bindValue(':password',sha1($salt . sha1($salt . sha1($password))));
                    $s->bindValue(':ip',$_SERVER['REMOTE_ADDR']);
                    $s->bindValue(':username', $googleData['email']);
                    $s->bindValue(':nexmo_code', $confirmcode);
                    $s->execute();
                    $lastId = $this->conn->lastInsertId();
                    $affiliate->add_shipping_wallet($lastId, 300);
                }
                $data = $this->googleLoginData($googleData['email'], $eventName);
            }catch(PDOexception $e){
                echo "<p>Database Error: $e </p>";
                exit();
            }
            return $data;
        }else{
            exit();
        }
    }
        public function guestSignup(){
        $data = array();
        global $affiliate;
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            try{
                $confirmcode=rand(100000,999999);
                $used_symbols = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
                $salt= substr(str_shuffle($used_symbols), 0, 9);
                // if($_POST['bday'] != ''){
                //     $b_date=date('Y-m-d',strtotime($_POST['bday']));
                // }else{
                //     $b_date = null;
                // }
                // $telephone = $_POST['telephone'] != "" ? $_POST['telephone'] : "";
                $username = trim($_POST['username']);
                $password = trim($_POST['password']);
                $s = $this->conn->prepare("INSERT INTO oc_customer SET 
                customer_group_id = 1, 
                store_id = 0, 
                firstname = '', lastname = '',
                b_day= null, email = :email, telephone = '', 
                fax = '', custom_field = '', salt = :salt, password = :password, 
                newsletter = 0, ip = :ip, status = 1, approved = 1, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00'),
                username=:username, nexmo_code=:nexmo_code,nexmo_status=0, safe=0, token='', a2sh_status='', fb_app_id='', type='guest'");
                $s->bindValue(':email', $_POST['email']);
                $s->bindValue(':salt', $salt);
                $s->bindValue(':password',sha1($salt . sha1($salt . sha1($password))));
                $s->bindValue(':ip',$_SERVER['REMOTE_ADDR']);
                $s->bindValue(':username', $username);
                $s->bindValue(':nexmo_code', $confirmcode);
                $s->execute();
                $lastId = $this->conn->lastInsertId();
                
                $data = $this->login($username, $password);
            }catch(PDOexception $e){
                echo "<p>Database Error: $e </p>";
                exit();
            }
            return $data;
        }else{
            exit();
        }
    }
    public function landbankRegister($data){
        global $affiliate;
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST['username'] = $data->email;
            $_POST['password'] = 'abc123';

            $username = trim($data->email);
            $password = 'abc123';
            $eventName = 'login';
            try{
                
                    $eventName = 'sign_up';
                     $confirmcode=rand(100000,999999);
                    $used_symbols = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
                    $salt= substr(str_shuffle($used_symbols), 0, 9);
                    if($data->dateOfBirth != ''){
                        $b_date=date('Y-m-d',strtotime($data->dateOfBirth));
                    }else{
                        $b_date = null;
                    }
                    $s = $this->conn->prepare("INSERT INTO oc_customer SET 
                    customer_group_id = 1, 
                    store_id = 0, 
                    firstname = :firstname, lastname = :lastname,
                    b_day=:b_day, email = :email, telephone = :telephone, 
                    fax = '', custom_field = '', salt = :salt, password = :password, 
                    newsletter = 0, ip = :ip, status = 1, approved = 1, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00'),
                    username=:username, nexmo_code=:nexmo_code,nexmo_status=1, landbankacc = 1, safe=0, token='', a2sh_status='', fb_app_id=''");
                    $s->bindValue(':firstname', $data->firstName);
                    $s->bindValue(':telephone', $data->mobileNumber);
                    $s->bindValue(':lastname', $data->lastName);
                    $s->bindValue(':b_day', $b_date);
                    $s->bindValue(':email', $data->email);
                    $s->bindValue(':salt', $salt);
                    $s->bindValue(':password',sha1($salt . sha1($salt . sha1($password))));
                    $s->bindValue(':ip',$_SERVER['REMOTE_ADDR']);
                    $s->bindValue(':username', $data->email);
                    $s->bindValue(':nexmo_code', $confirmcode);
                    $s->execute();
                    $lastId = $this->conn->lastInsertId();
                
                $data = $this->googleLoginData($data->email, $eventName);
            }catch(PDOexception $e){
                echo "<p>Database Error: $e </p>";
                exit();
            }
            return $data;
        }else{
            exit();
        }
    }
    public function fourgivesLogin($mobileNumber){
            $stmt = $this->conn->prepare("SELECT * FROM oc_customer WHERE telephone=:telephone AND fgivesacc = 1 ORDER BY customer_id DESC LIMIT 1");
            $stmt->bindValue(':telephone', $mobileNumber);
            $stmt->execute();
            $row = $stmt->fetch();
            return !empty($row) ? $this->customer($row): false;
    }
    public function landbankLogin($mobileNumber){
            $stmt = $this->conn->prepare("SELECT * FROM oc_customer WHERE telephone=:telephone AND landbankacc = 1 ORDER BY customer_id DESC LIMIT 1");
            $stmt->bindValue(':telephone', $mobileNumber);
            $stmt->execute();
            $row = $stmt->fetch();
            if($stmt->rowCount() > 0){
               $stmt = $this->conn->prepare("UPDATE oc_customer SET landbankacc = 1 WHERE customer_id = :customer_id LIMIT 1");
                $stmt->bindValue(':customer_id', $row['customer_id']);
                $stmt->execute();
               return !empty($row) ? $this->customer($row): false;
            }else{
                $LandBank=new LandBank;
                $credentials =$LandBank->getLandBankUAT();
                $LanBankDomain =$LandBank->LanBankDomain();
                $LandBankUrl = $LanBankDomain['sandbox'];
                $token="";
                $tokenRequestData = array(
                	'clientId' => $credentials['ClientID'],
                	'secret' => $credentials['Secret'],
                );
                $lburltoken = curl_init($LandBankUrl.'/api/Ecommerce/Token');
                curl_setopt($lburltoken, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($lburltoken, CURLOPT_HTTPHEADER, array(
                	'Content-Type: application/json',
                	'Identity: 6d5a74c940694d668aaaae6b402b4ee71cca906bc6ce48c39002fae9d72ae384-IDTK45E291-112701'
                ));
                curl_setopt($lburltoken, CURLOPT_POST, 1);
                curl_setopt($lburltoken, CURLOPT_POSTFIELDS,json_encode($tokenRequestData));
                curl_setopt($lburltoken, CURLOPT_FOLLOWLOCATION, 1);
                $datalburltoken = curl_exec($lburltoken);
                curl_close($lburltoken);
                $Responsedatatoken = json_decode($datalburltoken);
                
                
                $token=$Responsedatatoken->body->token;
                $MobileNumber2=$mobileNumber;
                $MobileRequestData = array(
                	'clientId' => $credentials['ClientID'],
                	'secret' => $credentials['Secret'],
                	'mobileNumber' => $MobileNumber2,	
                );
                $lburl2 = curl_init($LandBankUrl.'/api/Customer/Get');
                curl_setopt($lburl2, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($lburl2, CURLOPT_HTTPHEADER, array(
                	'Content-Type: application/json',
                	'Authorization: Bearer ' . $token
                ));
                curl_setopt($lburl2, CURLOPT_POST, 1);
                curl_setopt($lburl2, CURLOPT_POSTFIELDS,json_encode($MobileRequestData));
                curl_setopt($lburl2, CURLOPT_FOLLOWLOCATION, 1);
                $datalburl2 = curl_exec($lburl2);
                curl_close($lburl2);
                $Responsedata2 = json_decode($datalburl2);
                $data = $Responsedata2->body;
                return $this->landbankRegister($data);
            }
    }
    public function landbakPayRegisterOnExistingNumber($lbpMobileNo,$lbpPassword){
            $status= array();
    $LandBank=new LandBank;
    $credentials =$LandBank->getLandBankUAT();
    $LanBankDomain =$LandBank->LanBankDomain();
    $LandBankUrl = $LanBankDomain['sandbox'];
    //api get lanbank token
    $token="";
    $tokenRequestData = array(
        'clientId' => $credentials['ClientID'],
        'secret' => $credentials['Secret'],
    );
    $lburltoken = curl_init($LandBankUrl.'/api/Ecommerce/Token');
    curl_setopt($lburltoken, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($lburltoken, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Identity: 6d5a74c940694d668aaaae6b402b4ee71cca906bc6ce48c39002fae9d72ae384-IDTK45E291-112701'
    ));
    curl_setopt($lburltoken, CURLOPT_POST, 1);
    curl_setopt($lburltoken, CURLOPT_POSTFIELDS,json_encode($tokenRequestData));
    curl_setopt($lburltoken, CURLOPT_FOLLOWLOCATION, 1);
    $datalburltoken = curl_exec($lburltoken);
    curl_close($lburltoken);
    $Responsedatatoken = json_decode($datalburltoken);
    $token=$Responsedatatoken->body->token;
    //api get lanbank customer
    $MobileNumber2=$lbpMobileNo;
    $MobileRequestData = array(
      'clientId' => $credentials['ClientID'],
      'secret' => $credentials['Secret'],
      'mobileNumber' => $MobileNumber2, 
    );
    $lburl2 = curl_init($LandBankUrl.'/api/Customer/Get');
    curl_setopt($lburl2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($lburl2, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Authorization: Bearer ' . $token
    ));
    curl_setopt($lburl2, CURLOPT_POST, 1);
    curl_setopt($lburl2, CURLOPT_POSTFIELDS,json_encode($MobileRequestData));
    curl_setopt($lburl2, CURLOPT_FOLLOWLOCATION, 1);
    $datalburl2 = curl_exec($lburl2);
    curl_close($lburl2);
    $Responsedata2 = json_decode($datalburl2);
    $customer = array();
    if($Responsedata2->body->isValid){
        $eventName = 'login';    
      $customer_id="";
      $stmt = $this->conn->prepare("SELECT * FROM oc_customer WHERE telephone=:telephone AND landbankacc = 1 ORDER BY customer_id DESC LIMIT 1");
      $stmt->bindValue(':telephone', $lbpMobileNo);
      $stmt->execute();
      $row = $stmt->fetch();
      if($stmt->rowCount() > 0){
          $eventName = 'login';
        //mobile already exist
         $stmt = $this->conn->prepare("UPDATE oc_customer SET landbankacc = 1 WHERE customer_id = :customer_id LIMIT 1");
        $stmt->bindValue(':customer_id', $row['customer_id']);
        $stmt->execute(); 
        $customer_id= $row['customer_id'];  
        $customer =  $this->googleLoginData($row['email'], $eventName);
      }else{
          $eventName='sign_up';
        $confirmcode=rand(100000,999999);
        $used_symbols = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        $salt= substr(str_shuffle($used_symbols), 0, 9);
        if($Responsedata2->body->dateOfBirth != ''){
            $b_date=date('Y-m-d',strtotime($Responsedata2->body->dateOfBirth));
        }else{
            $b_date = null;
        }
        $s = $this->conn->prepare("INSERT INTO oc_customer SET 
        customer_group_id = 1, 
        store_id = 0, 
        firstname = :firstname, lastname = :lastname,
        b_day=:b_day, email = :email, telephone = :telephone, 
        fax = '', custom_field = '', salt = :salt, password = :password, 
        newsletter = 0, ip = :ip, status = 1, approved = 1, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00'),
        username=:username, nexmo_code=:nexmo_code,nexmo_status=1, landbankacc = 1, safe=0, token='', a2sh_status='', fb_app_id=''");
        $s->bindValue(':firstname', $Responsedata2->body->firstName);
        $s->bindValue(':telephone',$Responsedata2->body->mobileNumber);
        $s->bindValue(':lastname',$Responsedata2->body->lastName);
        $s->bindValue(':b_day', $b_date);
        $s->bindValue(':email', $Responsedata2->body->email);
        $s->bindValue(':salt', $salt);
        $s->bindValue(':password',sha1($salt . sha1($salt . sha1($lbpPassword))));
        $s->bindValue(':ip',$_SERVER['REMOTE_ADDR']);
        $s->bindValue(':username', $Responsedata2->body->email);
        $s->bindValue(':nexmo_code', $confirmcode);
        $s->execute();
        $lastId = $this->conn->lastInsertId();
        $customer_id=$lastId ;
        $customer =  $this->googleLoginData($Responsedata2->body->email, $eventName);
      }
      $status= array(
        'error' => false,
        'message' => 'Successfully logged in',
        'data' =>$customer
      );
      $data;
    }else{
      $status= array(
        'error' => true,
        'message' => 'LandbankPay mobile number is not valid please register first to LandbakPay app',
        'data' => array()
      );
    }
    return $status;
    }
    public function fgivesRegister($fgivesMobileNo,$fgivesPassword){
    $customer = array();
    $status= array();
    $stmt = $this->conn->prepare("SELECT * FROM 4gives_customer WHERE telephone=:telephone ORDER BY f_customer_id DESC LIMIT 1");
    $stmt->bindValue(':telephone', $fgivesMobileNo);
    $stmt->execute();
    $row = $stmt->fetch();
    $eventName = 'login';
    if($stmt->rowCount() > 0){
      $customer_id="";
      $confirmcode = 0;
      
      $stmt1 = $this->conn->prepare("UPDATE 4gives_customer SET status=1 WHERE telephone=:telephone ");
      $stmt1->bindValue(':telephone', $fgivesMobileNo);
      $stmt1->execute();

      $stmt2 = $this->conn->prepare("SELECT * FROM oc_customer WHERE telephone=:telephone AND fgivesacc = 1 ORDER BY customer_id DESC LIMIT 1");
      $stmt2->bindValue(':telephone',$fgivesMobileNo);
      $stmt2->execute();
      $row2 = $stmt2->fetch();
      if($stmt2->rowCount() > 0){
        //mobile already exist
        $confirmcode=rand(100000,999999);
        $used_symbols = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        $salt= substr(str_shuffle($used_symbols), 0, 9);
        $stmt3 = $this->conn->prepare("UPDATE oc_customer SET fgivesacc = 1,nexmo_status=0,nexmo_code=:nexmo_code,salt = :salt, password = :password,username=:username  WHERE customer_id = :customer_id LIMIT 1");
        $stmt3->bindValue(':customer_id', $row2['customer_id']);
        $stmt3->bindValue(':salt', $salt);
        $stmt3->bindValue(':password',sha1($salt . sha1($salt . sha1($fgivesPassword))));
        $stmt3->bindValue(':username', $fgivesMobileNo);
        $stmt3->bindValue(':nexmo_code', $confirmcode);
        $stmt3->execute(); 
        $customer_id= $row2['customer_id'];  
        $customer =  $this->googleLoginData($row2['email'], $eventName);
      }else{
        $confirmcode=rand(100000,999999);
        $used_symbols = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        $salt= substr(str_shuffle($used_symbols), 0, 9);
        if($row['b_date'] != ''){
            $b_date=date('Y-m-d',strtotime($row['b_date']));
        }else{
            $b_date = null;
        }
        $eventName = 'sign_up';
        $s = $this->conn->prepare("INSERT INTO oc_customer SET 
        customer_group_id = 1, 
        store_id = 0, 
        firstname = :firstname, lastname = :lastname,
        b_day=:b_day, email = :email, telephone = :telephone, 
        fax = '', custom_field = '', salt = :salt, password = :password, 
        newsletter = 0, ip = :ip, status = 1, approved = 1, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00'),
        username=:username, nexmo_code=:nexmo_code,nexmo_status=0, fgivesacc = 1, safe=0, token='', a2sh_status='', fb_app_id=''");
        $s->bindValue(':firstname', $row['firstname']);
        $s->bindValue(':telephone',$row['telephone']);
        $s->bindValue(':lastname',$row['lastname']);
        $s->bindValue(':b_day', $b_date);
        $s->bindValue(':email', $row['email']);
        $s->bindValue(':salt', $salt);
        $s->bindValue(':password',sha1($salt . sha1($salt . sha1($fgivesPassword))));
        $s->bindValue(':ip',$_SERVER['REMOTE_ADDR']);
        $s->bindValue(':username', $fgivesMobileNo);
        $s->bindValue(':nexmo_code', $confirmcode);
        $s->execute();
        $lastId = $this->conn->lastInsertId();
        $customer_id=$lastId ;
         $customer =  $this->googleLoginData($row['email'], $eventName);
        $stmt3 = $this->conn->prepare("SELECT * FROM 4gives_address WHERE customer_id=:customer_id AND tracking_id!=0");
        $stmt3->bindValue(':customer_id',$row['f_customer_id']);
        $stmt3->execute();
        $row3 = $stmt3->fetch();
        if($stmt3->rowCount() > 0){
           $stmt4=$this->conn->prepare("INSERT INTO oc_address 
                                        set customer_id=:customer_id,firstname=:firstname,
                                            lastname=:lastname,company=:company,address_1=:address_1,
                                            address_2=:address_2,city=:city,region=:region,district=:district,
                                            postcode=:postcode,tracking_id=:tracking_id,country_id=:country_id,custom_field=''");
            $stmt4->bindValue(':customer_id',$customer_id);
            $stmt4->bindValue(':firstname', $row3['firstname']);
            $stmt4->bindValue(':lastname', $row3['lastname']);
            $stmt4->bindValue(':company', $row3['company']);
            $stmt4->bindValue(':address_1', $row3['address_1']);
            $stmt4->bindValue(':address_2', $row3['address_2']);
            $stmt4->bindValue(':city', $row3['city']);
            $stmt4->bindValue(':region', $row3['region']);
            $stmt4->bindValue(':district', $row3['district']);
            $stmt4->bindValue(':postcode', $row3['postcode']);
            $stmt4->bindValue(':tracking_id', $row3['tracking_id']);
            $stmt4->bindValue(':country_id', 168);
            $stmt4->execute();
            $lastId2 = $this->conn->lastInsertId();
            $stmt5=$this->conn->prepare("UPDATE oc_customer set address_id=:address_id where customer_id=:customer_id");
            $stmt5->bindValue(':customer_id',$customer_id);
            $stmt5->bindValue(':address_id', $lastId2);
            $stmt5->execute();
            
        }
        
      }
      $this->resend_code($customer_id, $fgivesMobileNo);
      $status= array(
        'error' => false,
        'message' => 'Successfully logged in',
        'data' =>$customer
      );
      


    }else{
       $status= array(
        'error' => true,
        'message' => '4gives mobile number is invalid',
        'data' =>$customer
      );

    }
    return $status;


  }
}
$auth = new Auth();