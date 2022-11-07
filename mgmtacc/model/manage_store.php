<?php
require_once "../include/database.php";
class manage_store{
    private $conn;   
    public function __construct(){
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
	public function GetAllStore(){
        $s = $this->conn->prepare("SELECT *,concat('../img/company/',image) as thumb FROM oc_seller order by shop_name ASC ");
        $s->execute();
        $status = $s->fetchAll(PDO::FETCH_ASSOC);
        return $status;
    } 
    public function UpdateStore($seller_id, $status){
       try{           
            $stmt_items = $this->conn->prepare("UPDATE  oc_seller SET status=:status where seller_id = :seller_id");
            $stmt_items->bindValue(':status',$status);
            $stmt_items->bindValue(':seller_id',$seller_id);
            $stmt_items->execute();
            $status=true;
        }catch(PDOexception $e){
            echo "<p>Database Error: $e </p>";
            $status = false;
        }
        return $status;
    } 
     public  function getSellerUnreadMessage($seller_id){ 
            $stmt = $this->conn->prepare("SELECT COUNT(id) as total FROM `oc_message_inbox` WHERE  seller_id=:seller_id AND sender=customer_id AND `read` is null");
            $stmt->bindValue(':seller_id', $seller_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'];
    }

}