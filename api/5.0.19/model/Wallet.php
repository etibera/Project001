<?php
require_once '../init.php';
class Wallet {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    public function getShippingWalletTotal($customer_id){
        $st = $this->conn->prepare('SELECT sum(amount) as total FROM shipping_wallet WHERE  customer_id = :customer_id');
        $st->bindValue(':customer_id', $customer_id);
        $st->execute();
        return intval($st->fetch()['total']); 
    }
    public function getCashWalletTotal($customer_id){
        $st = $this->conn->prepare('SELECT sum(amount) as total FROM oc_affiliate_wallet WHERE  seller_id = :seller_id');
        $st->bindValue(':seller_id', $customer_id);
        $st->execute();
        return intval($st->fetch()['total']);
    }
    public function getDiscountWaletTotal($customer_id){
                $st = $this->conn->prepare('SELECT SUM(amount) as total FROM oc_customer_wallet WHERE status is null and customer_id = :customer_id');
                $st->bindValue(':customer_id', $customer_id);
                $st->execute();
                return intval($st->fetch()['total']);

    }
    public function getShippingWallet($customer_id){
        $data = array();
        $total = 0;
        if($customer_id != 'null'){
            $st = $this->conn->prepare('SELECT * FROM shipping_wallet WHERE customer_id = :customer_id ORDER BY id desc');
            $st->bindValue(':customer_id', (int) trim($customer_id));
            $st->execute();
            foreach($st->fetchAll() as $wallet){
                $data[] = array(
                    'particulars' => utf8_encode($wallet['particulars']),
                    'amount' => $wallet['amount'],
                    'dateAdded' => $wallet['date_added']
                );
            }
        }
        return $data;
    }
    public function getDiscountWalletDetails($customer_id){
        $data = array();
        $total = 0;
        if($customer_id != 'null'){
            $st = $this->conn->prepare('SELECT * FROM oc_customer_wallet WHERE customer_id = :customer_id ORDER BY id desc');
            $st->bindValue(':customer_id', (int) trim($customer_id));
            $st->execute();
            foreach($st->fetchAll() as $wallet){
                $data['details'][] = array(
                    'particulars' => utf8_encode($wallet['particulars']),
                    'amount' => $wallet['amount'],
                    'dateAdded' => $wallet['date_added']
                );
                $total += $wallet['amount'];
            }
        }
        $data['total'] = $total;
        return $data;
    }
    public function getCashWalletDetails($customer_id){
        $data = array();
        $total = 0;
        $st = $this->conn->prepare('SELECT * FROM oc_affiliate_wallet WHERE seller_id = :customer_id ORDER BY id desc');
        $st->bindValue(':customer_id', (int) trim($customer_id));
        $st->execute();
        foreach($st->fetchAll() as $w){
            $data['details'][] = array(
                'particulars' => $w['product_id'] == null ? $w['product_name'] :
                'Incentive for selling product ('. $w['product_name'].')',
                'amount' => $w['amount'],
                'dateAdded' => $w['date']
                );
            $total += $w['amount'];
        }
        $data['total'] = $total;
        return $data;
    }
    public function cashOut($data = array()){
        $json = array();
        $total = $this->getCashWalletTotal($data['userId']);
        if($total >= 499){
            try {
                $s = $this->conn->prepare("INSERT INTO oc_cash_out_request SET 
                customer_id = :customer_id, cash_out_type = :cash_out_type, 
                amount = :amount, date = convert_tz(utc_timestamp(),'-08:00','+0:00'),
                status=0, account_name=:account_name, account_number=:account_number");
                $s->bindValue(':customer_id', $data['userId']);
                $s->bindValue(':amount', $data['amount']);
                $s->bindValue(':account_number', $data['account_number']);
                $s->bindValue(':account_name', $data['account_name']);
                $s->bindValue(':cash_out_type', $data['cash_out_type']);
                $s->execute();
                $lastId = $this->conn->lastInsertId();
                $s = $this->conn->prepare("INSERT INTO oc_affiliate_wallet 
                SET cash_out_id=:lastId, seller_id = :customer_id, 
                product_name = :product_name, amount = :amount, 
                date = convert_tz(utc_timestamp(),'-08:00','+0:00')");
                $s->bindValue(':lastId',(int) $lastId);
                $s->bindValue(':customer_id', (int) $data['userId']);
                $s->bindValue(':amount', -$data['amount'], PDO::PARAM_INT);
                $s->bindValue(':product_name', "Pending cash out using ". $data['cash_out_type']);
                $s->execute();
                $json[] = array(
                    'success' => "Request Successfully Forwarded",
                    'details' => "Success"
                    );  
            }catch(Exception $e){
                $json[] = array(
                    'success' => $e->getMessage(),
                    'details' => "Server Error"
                );  
            }
        }elseif($data['amount'] < 499){
            $json[] = array(
                'success' => "Minimum Cash Out should be 500 cash wallet.",
                'details' => "Request Failed"
            );  
        }else{
            $json[] = array(
                'success' => "Invalid Amount Please try Again",
                'details' => "Request Failed"
                );  
        }
        return $json;

    }
}
$wallet = new Wallet();