<?php
require_once '../init.php';
class Currency {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    public function setPriceForCB($price){
        $date = new DateTime("now", new DateTimeZone('Asia/Manila'));
        $date_now=$date->format('Y-m-d');

        $exchange_currency_RATE=0;
        $exchange_currency_RATE= $this->getRate($date_now);
        $othercharges=1.3;
        return ($price * $exchange_currency_RATE) * $othercharges;
    }
    public function getRate($date_added) {
        $stmt = $this->conn->prepare("SELECT rate FROM foreign_exchange_rates order by id desc limit 1");
        // $stmt->bindValue(':s', $date_added);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['rate'];
    }
}
$currency = new Currency();