<?php
require_once "../include/database.php";   
class message { 
  private $conn;  
  public function __construct(){
    $this->conn = new Database();
    $this->conn = $this->conn->getmyDB(); 
  }  
  public  function getGetAdminMessagesList($admin_id){ 
      $row=array();    
      $stmt = $this->conn->prepare("SELECT mi.customer_id,
                                  CASE
                                      WHEN oc.type ='guest' THEN oc.username
                                      WHEN oc.firstname ='' and oc.lastname='' THEN oc.email
                                      ELSE CONCAT(oc.firstname,' ',oc.lastname)
                                  END  as fullname                                         
                                  FROM `oc_message_inbox_ca`  mi
                                  INNER JOIN oc_customer oc ON mi.customer_id=oc.customer_id
                                  WHERE  mi.receiver=:admin_id AND mi.admin_id=:admin_id AND mi.sender=mi.customer_id 
                                  GROUP BY mi.customer_id,oc.firstname,oc.lastname");
      $stmt->bindValue(':admin_id', $admin_id);
      $stmt->execute();
      foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
          $lastmessage= $this->getAdminLastMessage($value['customer_id'],$admin_id);
          $row[] = array(
              'customer_id' => $value['customer_id'],
              'fullname' => $value['fullname'],
              'timestamp' => $lastmessage['timestamp'],
              'timestampval' => $lastmessage['timestampval'],
              'message' => $lastmessage['message'],
              'read' => $lastmessage['read'],
              'no_unreadmsg' => $this->getUnreadAdminMessageByCustomer($value['customer_id'],$admin_id),
          );
      }
       return $row;
          
  }
   public  function getAdminLastMessage($customer_id,$admin_id){ 
        $row=array();
        $stmt = $this->conn->prepare("SELECT mi.message,mi.read, CONCAT(oc.firstname,' ',oc.lastname) as fullname ,
                                          DATE_FORMAT(mi.timestamp,'%b %d %Y %l:%i %p') as timestampval,mi.timestamp
                                    FROM `oc_message_inbox_ca`  mi
                                    INNER JOIN oc_customer oc ON mi.customer_id=oc.customer_id
                                    WHERE  mi.receiver=:admin_id AND mi.admin_id=:admin_id AND mi.customer_id=:customer_id AND mi.sender=:customer_id 
                                    ORDER BY timestamp desc LIMIT 1");
        $stmt->bindValue(':admin_id', $admin_id);
        $stmt->bindValue(':customer_id', $customer_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
  public  function getUnreadAdminMessageByCustomer($customer_id,$admin_id){ 
        $row=array();
        $stmt = $this->conn->prepare("SELECT COUNT(id) as total FROM `oc_message_inbox_ca` WHERE  receiver=:admin_id AND admin_id=:admin_id AND sender=:customer_id AND customer_id=:customer_id AND `read` is null");
        $stmt->bindValue(':admin_id', $admin_id);
        $stmt->bindValue(':customer_id', $customer_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
  }

  public function GetAdminMessagesCA($admin_id){
    $row=array();
    $stmt = $this->conn->prepare("SELECT customer_id FROM oc_message_inbox_ca 
                                  WHERE admin_id  = :admin_id  
                                  GROUP BY customer_id    
                                 "); 
    $stmt->bindValue(':admin_id',  $admin_id);
    $stmt->execute();
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC)  as  $value) {
       $stmt2 = $this->conn->prepare(" SELECT IFNULL(sm.read,'') as `read`, sm.customer_id,
                                         CONCAT(c.firstname,' ',c.lastname) as fullname,
                                         sm.sender, sm.message,  DATE_FORMAT(sm.timestamp,'%b %d %Y %l:%i %p') as timestamp ,
                                         sm.id as message_id 
                                      FROM oc_message_inbox_ca sm 
                                      INNER JOIN oc_customer c ON sm.customer_id = c.customer_id 
                                      where sm.admin_id  = :admin_id AND sm.customer_id=:customer_id
                                      order by sm.timestamp desc
                                      LIMIT 1"); 
      $stmt2->bindValue(':customer_id',  $value['customer_id']);
      $stmt2->bindValue(':admin_id',  $admin_id);
      $stmt2->execute();
      $row[]=$stmt2->fetch(PDO::FETCH_ASSOC); 
    }

    $stmt3 = $this->conn->prepare("select admin_id,customer_id,COUNT(id) as unreads
      from oc_message_inbox_ca where admin_id = :admin_id and sender != :admin_id and ISNULL(`read`)
      group by admin_id,customer_id;");
    $stmt3->bindValue(':admin_id', 0);
    $stmt3->execute(); 
    $row2=$stmt3->fetchAll(PDO::FETCH_ASSOC);
    
 
    $results = array(
        'list' =>  $row,
        'unreads' =>  $row2,
    );

    return $results;
  }

  public function GetTotalUnreadsCA($admin_id){
    $stmt = $this->conn->prepare("select COUNT(id) as unreads from oc_message_inbox_ca where admin_id = :admin_id and ISNULL(`read`) and sender != :admin_id");
    $stmt->bindValue(':admin_id', 0);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
  }

   public function GetConversationsCA($admin_id,$customer_id){
    $stmt = $this->conn->prepare("select  sm.receiver,sm.customer_id, sm.admin_id, sm.sender, sm.message, DATE_FORMAT(sm.timestamp,'%b %d %Y %l:%i %p') as timestamp, sm.id as message_id from oc_message_inbox_ca sm where sm.admin_id  = :admin_id and  sm.customer_id = :customer_id order by sm.timestamp ASC;");
    $stmt->bindValue(':admin_id', 0);
    $stmt->bindValue(':customer_id', $customer_id);
    $stmt->execute();
    $row=$stmt->fetchAll(PDO::FETCH_ASSOC);
    return $row;
  }

  public function UpdateToIsReadCA($admin_id,$customer_id) {
    try {
      $stmt =$this->conn->prepare("UPDATE oc_message_inbox_ca SET `read` =  convert_tz(utc_timestamp(),'-08:00','+0:00') where admin_id = :admin_id and customer_id = :customer_id; and sender != :admin_id");
      $stmt->bindValue(':admin_id', 0);
      $stmt->bindValue(':customer_id', $customer_id);
      $stmt->execute(); 
      $status = "200";
    }catch(Exception $e){
          $status=$e;
    } 
        return $status;
  } 

  public function InsertMessageCA($admin_id,$customer_id,$message,$order_id) {
    try {
      
        $stmt =$this->conn->prepare("INSERT INTO oc_message_inbox_ca SET receiver = :customer_id, sender = :admin_id, admin_id = :admin_id, customer_id = :customer_id, message = :message, timestamp = convert_tz(utc_timestamp(),'-08:00','+0:00'), `read` = NULL, `void` = NULL, `status` = 0;");
        $stmt->bindValue(':admin_id', 0);
        $stmt->bindValue(':customer_id', $customer_id);
        $stmt->bindValue(':message', $message);
        $stmt->execute();
        $status = "200";
      if($order_id!=0){ 
          $this->sendSMSToCustomer($customer_id,$message,$order_id);
      }
      
    }catch(Exception $e){
          $status=$e;
    } 
        return $status;
  }
  public function sendSMSToCustomer($customer_id,$message,$order_id){
    $s = $this->conn->prepare("SELECT * FROM oc_customer WHERE customer_id = :customer_id");
    $s->bindValue(':customer_id', $customer_id);
    $s->execute();
    if($s->rowCount() > 0){
      foreach($s->fetchAll(PDO::FETCH_ASSOC) as $m){
        if($m['telephone'] != null){
            $messagedata="Hello ".trim($m['firstname']).",\n\nThis is a message from PESO app.\nRegarding Order#: ".$order_id."\n\n".$message."\n\n"."Click the link for more info.\nhttps://pesoapp.ph/message.php?seller_id=0%26branch_id=0%26alias=messageConversation";
            $smsin = $this->conn->prepare("INSERT INTO sms set MobileNumberList =:mobile, Message =:Message,status=:status");
            $smsin->bindValue(':mobile', "0".$m['telephone']);
            $smsin->bindValue(':Message', utf8_encode($messagedata));
            $smsin->bindValue(':status', 0);
            $smsin->execute();
        }
      }
    }
  } 
}