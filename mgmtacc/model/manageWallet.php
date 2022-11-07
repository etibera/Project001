<?php
require_once "../include/database.php";
class ManageWallet{	
    private $conn;
    public function __construct(){
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    public  function getWalletList($type){
        $row=array();
        if($type=="shipWallet"){
            $stmt =  $this->conn->prepare("SELECT sw.*,concat(oc.firstname,' ',oc.lastname) as fullname,oc.email FROM shipping_wallet sw
                                            INNER JOIN oc_customer oc
                                                ON sw.customer_id=oc.customer_id
                                            ORDER BY sw.date_added desc") ;
            $stmt->execute();
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
                $row[] = array(
                    'particulars' => $value['particulars'],
                    'date_added' => $value['date_added'],
                    'amount' => $value['amount'],
                    'fullname' => $value['fullname'],
                    'email' => $value['email']
                );
            }
        }else if($type=="cashWallet"){
            $stmt =  $this->conn->prepare("SELECT sw.*,concat(oc.firstname,' ',oc.lastname) as fullname,oc.email FROM oc_affiliate_wallet sw
                                            INNER JOIN oc_customer oc
                                                ON sw.seller_id=oc.customer_id
                                            ORDER BY sw.date desc") ;
            $stmt->execute();
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
                $row[] = array(
                    'particulars' => $value['product_name'],
                    'date_added' => $value['date'],
                    'amount' => $value['amount'],
                    'fullname' => $value['fullname'],
                    'email' => $value['email']
                );
            }
        }else{
            $stmt =  $this->conn->prepare("SELECT sw.*,concat(oc.firstname,' ',oc.lastname) as fullname,oc.email FROM oc_customer_wallet sw
                                            INNER JOIN oc_customer oc
                                                ON sw.customer_id=oc.customer_id
                                            ORDER BY sw.date_added desc") ;
            $stmt->execute();
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
                $row[] = array(
                    'particulars' => $value['particulars'],
                    'date_added' => $value['date_added'],
                    'amount' => $value['amount'],
                    'fullname' => $value['fullname'],
                    'email' => $value['email']
                );
            }

        }
       
        return $row;
    } 
     public  function getTotalWallet($type){
        $row=array();
        if($type=="shipWallet"){
            $stmt =  $this->conn->prepare("SELECT sum(amount) as total FROM shipping_wallet") ;
            $stmt->execute();
            $row=$stmt->fetch(PDO::FETCH_ASSOC);
        }else if($type=="cashWallet"){
            $stmt =  $this->conn->prepare("SELECT sum(amount) as total FROM oc_affiliate_wallet") ;
            $stmt->execute();
            $row=$stmt->fetch(PDO::FETCH_ASSOC);
        }else{
            $stmt =  $this->conn->prepare("SELECT sum(amount) as total FROM oc_customer_wallet") ;
            $stmt->execute();
            $row=$stmt->fetch(PDO::FETCH_ASSOC);

        }       
        return $row;
    }  
    public  function saveWallet($customer_id,$type,$amount,$particulars){
        $row=array();
        if($type=="shipWallet"){
            $stmt =  $this->conn->prepare("INSERT INTO shipping_wallet 
                                            SET customer_id=:customer_id,particulars=:particulars,amount=:amount,status=0,date_added=convert_tz(utc_timestamp(),'-08:00','+0:00')") ;
            $stmt->bindValue(':customer_id',  $customer_id);
            $stmt->bindValue(':particulars',  $particulars);
            $stmt->bindValue(':amount', $amount);
            $stmt->execute();
            return "200";
        }else if($type=="cashWallet"){
            $stmt =  $this->conn->prepare("INSERT INTO oc_affiliate_wallet 
                                            SET seller_id=:customer_id,product_name=:particulars,amount=:amount,date=convert_tz(utc_timestamp(),'-08:00','+0:00')") ;
            $stmt->bindValue(':customer_id',  $customer_id);
            $stmt->bindValue(':particulars',  $particulars);
            $stmt->bindValue(':amount', $amount);
            $stmt->execute();
            return "200";
        }else{
            $stmt =  $this->conn->prepare("INSERT INTO oc_customer_wallet 
                                            SET customer_id=:customer_id,particulars=:particulars,amount=:amount,status=0,date_added=convert_tz(utc_timestamp(),'-08:00','+0:00')") ;
            $stmt->bindValue(':customer_id',  $customer_id);
            $stmt->bindValue(':particulars',  $particulars);
            $stmt->bindValue(':amount', $amount);
            $stmt->execute();
            return "200";
        }       
       
    }  
    public  function GetCustomer_list(){
        $row=array();
        $stmt =  $this->conn->prepare("SELECT concat(firstname,' ',lastname) as fullname,customer_id,email FROM oc_customer where nexmo_status=1 or type is not null order by customer_id desc") ;
        $stmt->execute();
        $row=$stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    } 
}
?>