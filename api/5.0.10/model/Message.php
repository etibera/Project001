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
        $stmt = $this->conn->prepare("SELECT SUM(CASE WHEN mi.read is null THEN 1 ELSE 0 END) as unread FROM oc_message_inbox mi where receiver = :id and customer_id = :id");
        $stmt->bindValue(':id', (int) $customer_id);
        $stmt->execute();
        return intval($stmt->fetch()['unread']);
    }
    public function getSendBox($customer_id, $page_number){
        $no_per_page = 12;
        $offset = ($page_number-1) * $no_per_page;
        $data = array();
        $stmt = $this->conn->prepare("SELECT DISTINCT(mi.receiver), c.customer_id, mi.sender, 
		(SELECT shop_name from oc_seller WHERE seller_id = mi.seller_id) name, 
        (SELECT message from oc_message_inbox WHERE sender = mi.sender AND receiver = mi.receiver ORDER by id desc LIMIT 1) as message, 
        (SELECT timestamp from oc_message_inbox WHERE sender = mi.sender AND receiver = mi.receiver ORDER by id desc LIMIT 1) as `date`
        from oc_message_inbox mi JOIN oc_customer c ON c.customer_id = mi.customer_id where mi.sender = :id and mi.customer_id = :id order by mi.id desc LIMIT :offset , :no_per_page");
        $stmt->bindValue(':id', (int) $customer_id);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->bindValue(':no_per_page', $no_per_page, PDO::PARAM_INT);
        $stmt->execute();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $m){
            $data[] = array(
				'customerId' => $m['customer_id'],
				'date' => $m['date'],
				'message' => mb_strimwidth($m['message'], 0, 40, "..."),
				'name' => $m['name'],
				'unread' => $this->getUnread($customer_id, $m['customer_id'])
			);
        }
        return $data;
    }
    public function getInbox($customer_id, $page_number, $search = ''){
        $no_per_page = 12;
        $offset = ($page_number-1) * $no_per_page;
        $data = array();
        $stmt = $this->conn->prepare("SELECT DISTINCT(mi.sender), mi.seller_id, c.customer_id, mi.receiver, 
		s.shop_name as name,
        (SELECT message from oc_message_inbox WHERE sender = mi.sender AND receiver = mi.receiver ORDER by id desc LIMIT 1) as message, 
        (SELECT timestamp from oc_message_inbox WHERE sender = mi.sender AND receiver = mi.receiver ORDER by id desc LIMIT 1) as `date`
        from oc_message_inbox mi JOIN oc_customer c ON c.customer_id = mi.customer_id JOIN oc_seller s ON s.seller_id = mi.seller_id where mi.receiver = :id AND mi.customer_id = :id AND s.shop_name LIKE :search order by mi.id desc LIMIT :offset , :no_per_page");
        $stmt->bindValue(':id', (int) $customer_id);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->bindValue(':no_per_page', $no_per_page, PDO::PARAM_INT);
        $stmt->bindValue(':search', '%'. $search. '%');
        $stmt->execute();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $m){
            $data[] = array(
				'sellerId' => $m['seller_id'],
				'date' => $m['date'],
				'message' => mb_strimwidth($m['message'], 0, 40, "..."),
				'name' => $m['name'],
				'unread' => $this->getUnread($customer_id, $m['seller_id'])
			);
        }
        return $data;
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
        $stmt = $this->conn->prepare("SELECT IF(SUM(mi.read is NULL) >= 0,SUM(mi.read is NULL), 0) unread FROM oc_message_inbox mi where customer_id = :sender_id AND seller_id = :customer_id");
        $stmt->bindValue(':sender_id', (int) $sender_id);
        $stmt->bindValue(':customer_id', (int) $customer_id);
        $stmt->execute();
        return intval($stmt->fetch()['unread']);
    }
    public function getMessagesThread($sender, $receiver, $read){
        
        if($read){
            $date = date("Y-m-d H:i:s");
            $r = $this->conn->prepare("UPDATE oc_message_inbox mi SET mi.read=:date_read WHERE mi.seller_id = :sender AND mi.customer_id= :receiver AND mi.read is null");
            $r->bindValue(':receiver', (int) $receiver);
            $r->bindValue(':sender', (int) $sender);
            $r->bindValue(':date_read', $date);
            $r->execute();
        }
        // $no_per_page = 12;
        // $offset = ($page_number-1) * $no_per_page;
        $data = array();
        $stmt = $this->conn->prepare("select * from oc_message_inbox where (customer_id = :sender and seller_id = :receiver) OR (customer_id = :receiver and seller_id = :sender) ORDER BY timestamp DESC");
        $stmt->bindValue(':receiver', (int) $receiver);
        $stmt->bindValue(':sender', (int) $sender);
        // $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        // $stmt->bindValue(':no_per_page', $no_per_page, PDO::PARAM_INT);
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
        $stmt = $this->conn->prepare("INSERT INTO oc_message_inbox SET sender = :customer_id, receiver =:sender_id, seller_id = :sender_id, customer_id = :customer_id, message = :msg, status = 0 ,timestamp = convert_tz(utc_timestamp(),'-08:00','+0:00')");
        $stmt->bindValue(':sender_id', (int) trim($sender_id));
        $stmt->bindValue(':customer_id', (int) trim($customer_id));
        $stmt->bindValue(':msg', trim($msg));
        $stmt->execute();
        return array(
            'message' => $msg,
            'id' => $this->conn->lastInsertId(),
            'date' => date("Y-m-d H:i:s"),
            'sender' => $customer_id,
            'receiver' => $sender_id
        );
    }
    public function messageDetail($sellerId){
        $data = array();
        $stmt = $this->conn->prepare("SELECT * FROM oc_seller WHERE seller_id = :seller_id");
        $stmt->bindValue(':seller_id', (int) $sellerId);
        $stmt->execute();
        $res = $stmt->fetch();
        return $data = array(
            'name' => trim($res['shop_name'])
        );
    }
}
$message = new Message();