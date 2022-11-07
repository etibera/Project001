<?php
require_once '../init.php';
class Message {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    public function getNotification($customer_id){
        $data = array();
        $stmt = $this->conn->prepare("SELECT SUM(CASE WHEN mi.read is null THEN 1 ELSE 0 END) as unread FROM oc_message_inbox mi where receiver = :id");
        $stmt->bindValue(':id', (int) $customer_id);
        $stmt->execute();
        return intval($stmt->fetch()['unread']);
    }
    public function getMessages($customer_id){
        $data = array();
        $stmt = $this->conn->prepare("SELECT c.customer_id, concat(c.firstname,' ',c.lastname) as name, 
        (SELECT message from oc_message_inbox where (sender = :id OR receiver = :id) AND (sender = c.customer_id OR receiver = c.customer_id) ORDER BY timestamp desc LIMIT 1) as message,
        (SELECT timestamp from oc_message_inbox where (sender = :id OR receiver = :id) AND (sender = c.customer_id OR receiver = c.customer_id) ORDER BY timestamp desc LIMIT 1) as date
        FROM oc_customer c
        JOIN oc_message_inbox mi ON mi.sender = c.customer_id OR mi.receiver = c.customer_id
        WHERE (mi.receiver = :id OR mi.sender = :id) AND c.customer_id != :id GROUP BY c.customer_id ORDER BY date DESC LIMIT 5");
        $stmt->bindValue(':id', (int) $customer_id);
        $stmt->execute();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $m){
            $data[] = array(
				'customer_id' => $m['customer_id'],
				'date' => $m['date'],
				'message' => mb_strimwidth($m['message'], 0, 40, "..."),
				'name' => $m['name'],
				'unread' => $this->getUnread($customer_id, $m['customer_id'])
			);
        }
        return $data;
    }
    public function getUnread($sender_id, $customer_id){
        $data = array();
        $stmt = $this->conn->prepare("SELECT IF(SUM(mi.read is NULL) >= 0,SUM(mi.read is NULL), 0) unread FROM oc_message_inbox mi where receiver = :sender_id AND sender = :customer_id");
        $stmt->bindValue(':sender_id', (int) $sender_id);
        $stmt->bindValue(':customer_id', (int) $customer_id);
        $stmt->execute();
        return intval($stmt->fetch()['unread']);
    }
    public function getMessagesThread($sender, $receiver, $read){
        if($read){
            $date = date("Y-m-d H:i:s");
            $r = $this->conn->prepare("UPDATE oc_message_inbox mi SET mi.read=:date WHERE mi.receiver = :sender AND mi.sender= :receiver AND mi.read is null");
            $r->bindValue(':receiver', (int) $receiver);
            $r->bindValue(':sender', (int) $sender);
            $r->bindValue(':date', $date);
            $r->execute();
        }
        $data = array();
        $stmt = $this->conn->prepare("select * from oc_message_inbox where (sender = :sender and receiver = :receiver) OR (sender = :receiver and receiver = :sender) ORDER BY timestamp DESC LIMIT 10");
        $stmt->bindValue(':receiver', (int) $receiver);
        $stmt->bindValue(':sender', (int) $sender);
        $stmt->execute();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $m){
            $data[] = array(
				'id' => $m['id'],
				'date' => $m['timestamp'],
				'sender' => $m['sender'],
				'receiver' => $m['receiver'],
				'message' => $m['message'],
			);
        }
        return array_reverse($data);
    }
    public function reply($sender_id, $customer_id, $msg){
        $data = array();
        $stmt = $this->conn->prepare("INSERT INTO oc_message_inbox SET sender = :customer_id, receiver =:sender_id, message = :msg, status = 0 ,timestamp = convert_tz(utc_timestamp(),'-08:00','+0:00')");
        $stmt->bindValue(':sender_id', (int) trim($sender_id));
        $stmt->bindValue(':customer_id', (int) trim($customer_id));
        $stmt->bindValue(':msg', trim($msg));
        $stmt->execute();
        return array('id' => $this->conn->lastInsertId(), 'date' => date("Y-m-d H:i:s"));
    }
}
$message = new Message();