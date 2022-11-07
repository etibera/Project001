<?php
require_once '../init.php';
class Message {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    function __destruct(){
        $this->conn = null;
    }
    public function getNotification($customer_id){
        $data = array();
        $stmt = $this->conn->prepare("SELECT SUM(CASE WHEN mi.read is null THEN 1 ELSE 0 END) as unread FROM oc_message_inbox mi where receiver = :id and customer_id = :id");
        $stmt->bindValue(':id', (int) $customer_id);
        $stmt->execute();
        return intval($stmt->fetch()['unread']);
    }
    public function getAdminMessage($customer_id){
        $data = array();
        $stmt = $this->conn->prepare("SELECT IFNULL(sm.read,'') as `read`, sm.admin_id, CONCAT('PESO ADMIN') as fullname,
                                                    sm.sender, sm.message,
                                                    DATE_FORMAT(sm.timestamp,'%b %d %Y %l:%i %p') as timestamp ,
                                                    sm.id as message_id from oc_message_inbox_ca sm 
                                            where sm.customer_id = :customer_id
                                      order by sm.timestamp desc
                                      LIMIT 1");
        $stmt->bindValue(':customer_id', (int) $customer_id);
        $stmt->execute();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $m){
            $data[] = array(
				'sellerId' => 0,
				'date' => $m['timestamp'],
				'message' => mb_strimwidth($m['message'], 0, 40, "..."),
				'name' => $m['fullname'],
                'unread' => 0
			);
        }
        return $data;
    }
    public function getSendBox($customer_id, $page_number){
        $no_per_page = 12;
        $offset = ($page_number-1) * $no_per_page;
        $data = array();
        $stmt = $this->conn->prepare("SELECT DISTINCT(mi.receiver), c.customer_id, mi.sender, mi.id,
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
				'unread' => 0
			);
        }
        return $data;
    }
    public function getInbox($customer_id, $page_number, $search = ''){
        $no_per_page = 12;
        $offset = ($page_number-1) * $no_per_page;
        $data = array();
        $stmt = $this->conn->prepare("SELECT mi.seller_id 
        FROM oc_message_inbox mi LEFT JOIN oc_seller s ON s.seller_id = mi.seller_id  
        WHERE mi.customer_id  = :customer_id AND shop_name LIKE :search 
        GROUP BY mi.seller_id LIMIT :offset , :no_per_page"); 
        $stmt->bindValue(':customer_id', $customer_id);
        $stmt->bindValue(':search', '%' . $search . '%');
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->bindValue(':no_per_page', $no_per_page, PDO::PARAM_INT);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC)  as  $value) {
            $stmt2 = $this->conn->prepare("SELECT IFNULL(sm.read,'') as `read`, sm.seller_id, c.shop_name, sm.sender, sm.message,
                            DATE_FORMAT(sm.timestamp,'%b %d %Y %l:%i %p') as timestamp ,
                            sm.id as message_id 
                        FROM oc_message_inbox sm 
                        INNER JOIN oc_seller c ON sm.seller_id = c.seller_id 
                        where sm.customer_id = :customer_id AND sm.seller_id=:seller_id
                        order by sm.timestamp desc
                        LIMIT 1"); 
            $stmt2->bindValue(':seller_id', $value['seller_id']);
            $stmt2->bindValue(':customer_id', $customer_id);
            $stmt2->execute();
            $m = $stmt2->fetch(PDO::FETCH_ASSOC);
            $data[] = array(
				'sellerId' => $m['seller_id'],
				'date' => $m['timestamp'],
				'message' => mb_strimwidth($m['message'], 0, 40, "..."),
				'name' => $m['shop_name'],
				'unread' => $this->getUnread($m['seller_id'],$customer_id)
			);
        }
        return $data;
        // $stmt = $this->conn->prepare("SELECT DISTINCT(mi.sender), mi.seller_id, c.customer_id, mi.receiver, 
		// s.shop_name as name,mi.id,
        // (SELECT message from oc_message_inbox WHERE sender = mi.sender AND receiver = mi.receiver ORDER by id desc LIMIT 1) as message, 
        // (SELECT timestamp from oc_message_inbox WHERE sender = mi.sender AND receiver = mi.receiver ORDER by id desc LIMIT 1) as `date`
        // from oc_message_inbox mi JOIN oc_customer c ON c.customer_id = mi.customer_id JOIN oc_seller s ON s.seller_id = mi.seller_id where mi.receiver = :id AND mi.customer_id = :id AND s.shop_name LIKE :search order by mi.id desc LIMIT :offset , :no_per_page");
        // $stmt->bindValue(':id', (int) $customer_id);
        // $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        // $stmt->bindValue(':no_per_page', $no_per_page, PDO::PARAM_INT);
        // $stmt->bindValue(':search', '%'. $search. '%');
        // $stmt->execute();
        // // $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $m){
        //     $data[] = array(
		// 		'sellerId' => $m['seller_id'],
		// 		'date' => $m['date'],
		// 		'message' => mb_strimwidth($m['message'], 0, 40, "..."),
		// 		'name' => $m['name'],
		// 		'unread' => $this->getUnread($customer_id, $m['seller_id'])
		// 	);
        // }
        // return $data;
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
				'unread' => $this->getUnread($m['seller_id'], $customer_id)
			);
        }
        return $data;
    }
    public function getUnread($sender_id, $customer_id){
        $data = array();
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM oc_message_inbox where customer_id = :customer_id AND ISNULL(`read`) and sender != :customer_id AND seller_id = :sender_id");
        $stmt->bindValue(':sender_id', (int) $sender_id);
        $stmt->bindValue(':customer_id', (int) $customer_id);
        $stmt->execute();
        return intval($stmt->fetch()['count']);
    }
    public function getMessagesThread($sender, $receiver, $read){
        $data = array();
        if($sender == 0){
            $stmt = $this->conn->prepare("select sm.receiver,sm.customer_id, sm.admin_id, CONCAT('PESO ADMIN') as fullname, sm.sender, sm.message, DATE_FORMAT(sm.timestamp,'%b %d %Y %l:%i %p') as timestamp, sm.id as message_id from oc_message_inbox_ca sm where sm.admin_id  = :admin_id and  sm.customer_id = :customer_id order by timestamp asc;");
            $stmt->bindValue(':admin_id',0);
            $stmt->bindValue(':customer_id', $receiver);
            $stmt->execute();
            $row=$stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($row as $m){
                $data[] = array(
                    'id' => $m['message_id'],
                    'date' => $m['timestamp'],
                    'sender' => $m['sender'],
                    'receiver' => $m['receiver'],
                    'message' => $m['message'],
                    'productId' => 0,
                    'product' => null
                );
            }
        }else{
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
            $stmt = $this->conn->prepare("select * from oc_message_inbox where seller_id = :sender AND customer_id = :receiver ORDER BY id");
            $stmt->bindValue(':receiver', (int) $receiver);
            $stmt->bindValue(':sender', (int) $sender);
            // $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
            // $stmt->bindValue(':no_per_page', $no_per_page, PDO::PARAM_INT);
            $stmt->execute();
            foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $m){
                $product_id = (int) $m['product_id'];
                $data[] = array(
                    'id' => $m['id'],
                    'date' => $m['timestamp'],
                    'sender' => $m['sender'],
                    'receiver' => $m['receiver'],
                    'message' => $m['message'],
                    'productId' => $product_id,
                    'product' => $this->getProduct($product_id, $sender)
                );
            }  
        }
        
        return $data;
    }
    public function reply($sender_id, $customer_id, $msg, $product_id){
        if($sender_id == 0 ){
            $stmt =$this->conn->prepare("INSERT INTO oc_message_inbox_ca SET sender = :customer_id, receiver = :admin_id, admin_id = :admin_id, customer_id = :customer_id, message = :message, timestamp = convert_tz(utc_timestamp(),'-08:00','+0:00'), `read` = NULL, `void` = NULL, `status` = 0;");
            $stmt->bindValue(':admin_id', $sender_id);
            $stmt->bindValue(':customer_id', $customer_id);
            $stmt->bindValue(':message', trim($msg));
            $stmt->execute();
            return array(
                'message' => $msg,
                'id' => $this->conn->lastInsertId(),
                'date' => date("Y-m-d H:i:s"),
                'sender' => $customer_id,
                'receiver' => $sender_id,
                'productId' => 0,
                'product' => null
            );
        }else{
            $product_id = (int) $product_id;
            $data = array();
            $stmt = $this->conn->prepare("INSERT INTO oc_message_inbox SET sender = :customer_id, receiver =:sender_id, seller_id = :sender_id, customer_id = :customer_id, message = :msg, status = 0 ,timestamp = convert_tz(utc_timestamp(),'-08:00','+0:00'), product_id = :product_id");
            $stmt->bindValue(':sender_id', (int) trim($sender_id));
            $stmt->bindValue(':customer_id', (int) trim($customer_id));
            $stmt->bindValue(':customer_id', (int) trim($customer_id));
            $stmt->bindValue(':product_id', $product_id);
            $stmt->bindValue(':msg', trim($msg));
            $stmt->execute();
            $this->sendSMSToSeller($sender_id);
            return array(
                'message' => $msg,
                'id' => $this->conn->lastInsertId(),
                'date' => date("Y-m-d H:i:s"),
                'sender' => $customer_id,
                'receiver' => $sender_id,
                'productId' => $product_id,
                'product' => $this->getProduct($product_id, $sender_id)
            );
        }
      
    }
    public function multiReply($sender_id, $customer_id, $msg){
        //return json_decode($msg, true);
        $data = array();
        foreach(json_decode($msg) as $message){
            $stmt = $this->conn->prepare("INSERT INTO oc_message_inbox SET sender = :customer_id, receiver =:sender_id, seller_id = :sender_id, customer_id = :customer_id, message = :msg, status = 0 ,timestamp = convert_tz(utc_timestamp(),'-08:00','+0:00')");
            $stmt->bindValue(':sender_id', (int) trim($sender_id));
            $stmt->bindValue(':customer_id', (int) trim($customer_id));
            $stmt->bindValue(':msg', trim($message));
            $stmt->execute();
            $data[] = array(
                'message' => $message,
                'id' => $this->conn->lastInsertId(),
                'date' => date("Y-m-d H:i:s"),
                'sender' => $customer_id,
                'receiver' => $sender_id,
                'product' => $this->isValidURL($message)
            );
        }
        return $data;
    }
    public function messageDetail($sellerId){
        if($sellerId == 0){
            return array(
                'name' => 'Peso Admin'
            );
        }else{
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
    public function getNewMessage($lastId, $customer_id, $seller_id){
        $data = array();
        $stmt = $this->conn->prepare("SELECT id, timestamp, sender, receiver, message from oc_message_inbox WHERE id > :lastId AND seller_id = :seller_id AND customer_id = :customer_id order by id desc");
        $stmt->bindValue(':lastId', (int) $lastId);
        $stmt->bindValue(':customer_id', (int) $customer_id);
        $stmt->bindValue(':seller_id', (int) $seller_id);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $m){
                $data[] = array(
                    'id' => $m['id'],
                    'date' => $m['timestamp'],
                    'sender' => $m['sender'],
                    'receiver' => $m['receiver'],
                    'message' => $m['message'],
                );
            }
        }
        return $data;
    }
    public function sendSMSToSeller($seller_id){
        $s = $this->conn->prepare("SELECT * FROM seller_branch WHERE seller_id = :seller_id");
        $s->bindValue(':seller_id', $seller_id);
        $s->execute();
        if($s->rowCount() > 0){
            foreach($s->fetchAll(PDO::FETCH_ASSOC) as $m){
                if($m['telephone'] != null){
                    $smsin = $this->conn->prepare("INSERT INTO sms set MobileNumberList =:mobile, Message =:Message,status=:status");
                    $smsin->bindValue(':mobile', "0".$m['telephone']);
                    $smsin->bindValue(':Message', "Hello seller you have a new message in PESO");
                    $smsin->bindValue(':status', 0);
                    $smsin->execute();
                }
            }
        }
    }
    public function getProduct($product_id, $seller_id){
        global $image;
       if($product_id !== 0){
            $p = $this->conn->prepare("SELECT p.product_id as productId, p.image, pd.name, p.price FROM oc_product p LEFT JOIN oc_product_description pd ON pd.product_id = p.product_id WHERE p.product_id = :product_id LIMIT 1");
            $p->bindValue(':product_id', $product_id);
            $p->execute();
            $product = $p->fetch(PDO::FETCH_ASSOC);
            return array(
                'productId' => $product['productId'],
                'image' => $image->resize($product['image'], 200, 200),
                'path' => "/product/product/$product_id/reg/$seller_id",
                'name' => $product['name'],
                'price' => $product['price']
            );
       }else{
           return null;
       }
    }
    public function isValidURL($url)
    {
        global $image;
        $parse_url = parse_url($url);
        if($parse_url['path'] == '/product.php'){
            $str = $parse_url['query'];
            $exploded = array();
            parse_str($str, $exploded);
            $p = $this->conn->prepare("SELECT p.product_id as productId, p.image, pd.name, p.price FROM oc_product p LEFT JOIN oc_product_description pd ON pd.product_id = p.product_id WHERE p.product_id = :product_id LIMIT 1");
            $p->bindValue(':product_id', $exploded['product_id']);
            $p->execute();
            $product = $p->fetch(PDO::FETCH_ASSOC);
            return array(
                'productId' => $product['productId'],
                'image' => $image->resize($product['image'], 200, 200),
                'name' => $product['name'],
                'price' => $product['price']
            );
        } else {
            return null;
        }
    }
}
$message = new Message();