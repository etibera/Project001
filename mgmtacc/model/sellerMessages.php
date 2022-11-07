<?php
require_once "../include/database.php";
require_once '../model/ImageResizer.php';
require_once '../model/Image.php';
class sellerMessage {
    private $conn;
    public function __construct()
    {
    $this->conn = new Database();
    $this->conn = $this->conn->getmyDB();
    }
    public  function getSellerMessageData(){ 
        global $image;
        $row=array();
        $stmt = $this->conn->prepare("SELECT *,concat('../img/company/',image) as thumb FROM oc_seller WHERE status=1 AND seller_type=0");
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
            $row[] = array(
                'image' => $value['thumb'],
                'shop_name' => $value['shop_name'],
                'seller_id' => $value['seller_id'],
                'unread' => $this->getSellerUnreadMessage($value['seller_id']),
                'thumb' => $image->resize($value['thumb'], 75,75)
            );
        }
    return $row;                
    }
    public  function getSellerUnreadMessage($seller_id){ 
        $row=array();
        $stmt = $this->conn->prepare("SELECT COUNT(id) as total FROM `oc_message_inbox` WHERE  receiver=:seller_id AND seller_id=:seller_id AND sender=customer_id AND `read` is null");
        $stmt->bindValue(':seller_id', $seller_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }  
    public  function getSellerLastMessage($customer_id,$seller_id){ 
        $row=array();
        $stmt = $this->conn->prepare("SELECT mi.branch_id,mi.message,mi.read, CONCAT(oc.firstname,' ',oc.lastname) as fullname ,
                                          DATE_FORMAT(mi.timestamp,'%b %d %Y %l:%i %p') as timestampval,mi.timestamp
                                    FROM `oc_message_inbox`  mi
                                    INNER JOIN oc_customer oc ON mi.customer_id=oc.customer_id
                                    WHERE  mi.receiver=:seller_id AND mi.seller_id=:seller_id AND mi.customer_id=:customer_id AND mi.sender=:customer_id AND mi.branch_id=0 
                                    ORDER BY timestamp DESC LIMIT 1");
        $stmt->bindValue(':seller_id', $seller_id);
        $stmt->bindValue(':customer_id', $customer_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    } 
    public  function getSellerLastMessageNew($customer_id,$branch_id){ 
        $row=array();
        $stmt = $this->conn->prepare("SELECT mi.branch_id,mi.message,mi.read, CONCAT(oc.firstname,' ',oc.lastname) as fullname ,
                                          DATE_FORMAT(mi.timestamp,'%b %d %Y %l:%i %p') as timestampval,mi.timestamp
                                    FROM `oc_message_inbox`  mi
                                    INNER JOIN oc_customer oc ON mi.customer_id=oc.customer_id
                                    WHERE  mi.receiver=:branch_id AND mi.branch_id=:branch_id AND mi.customer_id=:customer_id AND mi.sender=:customer_id
                                    ORDER BY timestamp DESC LIMIT 1");
        $stmt->bindValue(':branch_id', $branch_id);
        $stmt->bindValue(':customer_id', $customer_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    } 
    public function adGetConversations($seller_id,$customer_id,$branch_id){
        $stmt = $this->conn->prepare("SELECT sm.product_id, sm.seller_id, sm.receiver, sm.customer_id,
                                                c.firstname,c.lastname, sm.sender, sm.message,
                                                DATE_FORMAT(sm.timestamp,'%b %d %Y %l:%i %p') as timestamp,
                                                sm.id as message_id 
                                    FROM oc_message_inbox sm 
                                    INNER JOIN oc_customer c ON sm.customer_id = c.customer_id 
                                    WHERE sm.seller_id  = :seller_id and  sm.customer_id = :customer_id and sm.branch_id = :branch_id ORDER BY sm.timestamp ASC");
        $stmt->bindValue(':seller_id', $seller_id);
        $stmt->bindValue(':customer_id', $customer_id);
        $stmt->bindValue(':branch_id', $branch_id);
        $stmt->execute();
        $row=$stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = array();
        foreach($row as $value){
            $data[] = array(
                'product_id' => $value['product_id'],
                'receiver' => $value['receiver'],
                'customer_id' => $value['customer_id'],
                'firstname' => $value['firstname'],
                'lastname' => $value['lastname'],
                'sender' => $value['sender'],
                'message' => $value['message'],
                'timestamp' => $value['timestamp'],
                'message_id' => $value['message_id'],
                'product' => $this->getProduct($value['product_id'], $value['seller_id'])
            );
        }
        return $data;
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
     public  function getSellerDetails($seller_id){ 
        $row=array();
        $stmt = $this->conn->prepare("SELECT * FROM oc_seller where seller_id=:seller_id");
        $stmt->bindValue(':seller_id', $seller_id);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
     }
    public  function getSellerBranchDetails($branch_id){ 
        $row=array();
        $stmt = $this->conn->prepare("SELECT * FROM seller_branch where id=:branch_id");
        $stmt->bindValue(':branch_id', $branch_id);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
     }
    public  function getSellerMessageList($seller_id){         
        $row=array();
        //old massage
        $SellerDetails=$this->getSellerDetails($seller_id);
        $stmt = $this->conn->prepare("SELECT mi.customer_id,
                                    CASE
                                        WHEN oc.type ='guest' THEN oc.username
                                        WHEN oc.firstname ='' and oc.lastname='' THEN oc.email
                                        ELSE CONCAT(oc.firstname,' ',oc.lastname)
                                    END  as fullname                                         
                                    FROM `oc_message_inbox`  mi
                                    INNER JOIN oc_customer oc ON mi.customer_id=oc.customer_id
                                    WHERE  mi.receiver=:seller_id AND mi.seller_id=:seller_id AND mi.sender=mi.customer_id AND mi.branch_id=0  
                                    GROUP BY mi.customer_id,oc.firstname,oc.lastname");
        $stmt->bindValue(':seller_id', $seller_id);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
            $lastmessage= $this->getSellerLastMessage($value['customer_id'],$seller_id);
            $row[] = array(
                'customer_id' => $value['customer_id'],
                'fullname' => $value['fullname'],
                'b_name' => $SellerDetails['shop_name'],
                'timestamp' => $lastmessage['timestamp'],
                'branch_id' => $lastmessage['branch_id'],
                'timestampval' => $lastmessage['timestampval'],
                'message' => $lastmessage['message'],
                'read' => $lastmessage['read'],
                'no_unreadmsg' => $this->getUnreadMessageByCustomer($value['customer_id'],$seller_id),
            );
        }
        //new massages
         $stmt2 = $this->conn->prepare("SELECT mi.customer_id,mi.branch_id,
                                    CASE
                                        WHEN oc.type ='guest' THEN oc.username
                                        WHEN oc.firstname ='' and oc.lastname='' THEN oc.email
                                        ELSE CONCAT(oc.firstname,' ',oc.lastname)
                                    END  as fullname                                         
                                    FROM `oc_message_inbox`  mi
                                    INNER JOIN oc_customer oc ON mi.customer_id=oc.customer_id
                                    WHERE  mi.seller_id=:seller_id AND mi.sender=mi.customer_id AND mi.branch_id!=0  
                                    GROUP BY mi.customer_id,oc.firstname,oc.lastname,mi.branch_id");
        $stmt2->bindValue(':seller_id', $seller_id);
        $stmt2->execute();
        foreach ($stmt2->fetchAll(PDO::FETCH_ASSOC) as $value2) {
            $lastmessage2= $this->getSellerLastMessageNew($value2['customer_id'],$value2['branch_id']);
            $SellerBranchDetails=$this->getSellerBranchDetails($value2['branch_id']);
            $row[] = array(
                'customer_id' => $value2['customer_id'],
                'fullname' => $value2['fullname'],
                'b_name' => $SellerBranchDetails['b_name'],
                'timestamp' => $lastmessage2['timestamp'],
                'branch_id' => $value2['branch_id'],
                'timestampval' => $lastmessage2['timestampval'],
                'message' => $lastmessage2['message'],
                'read' => $lastmessage2['read'],
                'no_unreadmsg' => $this->getUnreadMessageByCustomerNew($value2['customer_id'],$value2['branch_id']),
            );
        }

        return $row;
            
    }
    public  function getUnreadMessageByCustomer($customer_id,$seller_id){ 
        $row=array();
        $stmt = $this->conn->prepare("SELECT COUNT(id) as total FROM `oc_message_inbox` WHERE  receiver=:seller_id AND seller_id=:seller_id AND sender=:customer_id AND customer_id=:customer_id AND branch_id=0  AND `read` is null");
        $stmt->bindValue(':seller_id', $seller_id);
        $stmt->bindValue(':customer_id', $customer_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }  
    public  function getUnreadMessageByCustomerNew($customer_id,$branch_id){ 
        $row=array();
        $stmt = $this->conn->prepare("SELECT COUNT(id) as total FROM `oc_message_inbox` WHERE  receiver=:branch_id AND branch_id=:branch_id AND sender=:customer_id AND customer_id=:customer_id  AND `read` is null");
        $stmt->bindValue(':branch_id', $branch_id);
        $stmt->bindValue(':customer_id', $customer_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }  

}

?>