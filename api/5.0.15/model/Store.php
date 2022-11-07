<?php
require_once '../init.php';
class Store {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    public function get_seller($seller_id){
        global $image;
        $data = array();
        $stmt = $this->conn->prepare("SELECT seller_id, shop_name, concat('company/', image) as image FROM oc_seller WHERE seller_id = :seller_id LIMIT 1");
        $stmt->bindValue(':seller_id', $seller_id);
        $stmt->execute();
        $res = $stmt->fetch();
        if($res){
            $data = array(
                'sellerId' => $res['seller_id'],
                'image' => $image->resize($res['image'], 40,40),
                'shopName'=> $res['shop_name']
            );
        }
        return $data;
        
        // return array(
        //     'sellerId' => $res->seller_id,
        //     'image' => $image->resize($res->image, 40,40),
        //     'shopName'=> $res->shop_name
        // );
    }
    public function seller_total_products($seller_id){
        $sql = "SELECT SUM(quantity) as total FROM oc_product WHERE ";
        if($seller_id == null){
            $sql .= "seller_id is null ";
            $sql .= "AND status = 1";
            $s = $this->conn->prepare($sql);
        }else{
            $sql .= "seller_id = :seller_id ";
            $sql .= "AND status = 1";
            $s = $this->conn->prepare($sql);
            $s->bindValue(":seller_id", $seller_id);
        }
       
        $s->execute();
        return $s->fetch()['total'];
    }
    public function seller_total_orders($seller_id){
        $sql = "SELECT COUNT(op.order_product_id) as total 
        FROM oc_order_product op 
        LEFT JOIN oc_order o ON o.order_id = op.order_id 
        LEFT JOIN oc_product p ON op.product_id = p.product_id 
        WHERE ";
        if($seller_id == null){
            $sql .= "p.seller_id is null ";
            $sql .= "AND o.order_status_id > 0";
            $s = $this->conn->prepare($sql);
        } else{
            $sql .= "p.seller_id = :seller_id ";
            $sql .= "AND o.order_status_id > 0";
            $s = $this->conn->prepare($sql);
            $s->bindValue(":seller_id", $seller_id);
        }
      
        $s->execute();
        return $s->fetch()['total'];
    }
}
$store = new Store();