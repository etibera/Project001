<?php
require_once "../include/database.php";
class Invoice {
        private $conn;
        public function __construct()
        {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
        }
        public  function order_details($order_id){              
              
                $stmt = $this->conn->prepare("SELECT * from oc_order where order_id = :order_id ");
                $stmt->execute([':order_id'=> $order_id]);
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return $row;
        }

        public  function order_product_details($order_id){              
             
                $stmt = $this->conn->prepare("SELECT op.*, p.sku, p.weight, p.location from oc_order_product op LEFT JOIN oc_product p ON op.product_id = p.product_id where op.order_id = :order_id ");
                $stmt->execute([':order_id'=> $order_id]);
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $row;
        }
        
        public  function order_product_serial($order_id,$order_product_id){              
              
                $stmt = $this->conn->prepare("SELECT * from oc_product_serial where order_id = :order_id  and order_product_id = :order_product_id");
                $stmt->bindValue(':order_id', $order_id);
                $stmt->bindValue(':order_product_id', $order_product_id);
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $row;
        }

        public  function order_product_total($order_id){              
             
                $stmt = $this->conn->prepare("SELECT * from oc_order_total  where order_id= :order_id order by sort_order asc");
                $stmt->execute([':order_id'=> $order_id]);
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $row;
        }

}

?>