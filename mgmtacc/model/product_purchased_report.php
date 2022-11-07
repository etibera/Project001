<?php
require_once "../include/database.php";
class product_purhased{
        private $conn;   
        public function __construct()
        {
                $this->conn = new Database();
                $this->conn = $this->conn->getmyDB();
        }

        public  function order_status(){
        
        $s = $this->conn->prepare("SELECT * FROM oc_order_status");
        $s->execute();
        $status = $s->fetchAll(PDO::FETCH_ASSOC);
        return $status;
    }

	public  function productpurchased_list($datefrom,$dateto,$status) {
        $sql="";
        $sql1="";
        $sql2="";
        $sql3="";
		
        if($status != '0'){
            $sql1=" and o.order_status_id = :status";
        }
         if($datefrom != '' && $dateto != ''){
            $sql3=" and o.date_added between :date and :date1";
        }
                
		$stmt = $this->conn->prepare("SELECT p.name,p.model,p.price,sum(p.quantity) as total_quantity,(sum(p.quantity) * p.price) as total,o.order_status_id from oc_order_product p left join oc_order o on p.order_id = o.order_id where p.product_id is not null ".$sql.$sql1.$sql2.$sql3." group by p.name LIMIT 200");

               
                 if(!empty($status)) {
                   $stmt->bindValue(':status', $status);
                }
                 if(!empty($datefrom) && !empty($dateto)) {
                   $stmt->bindValue(':date', $datefrom.' 00:00:00');
                   $stmt->bindValue(':date1', $dateto.' 23:59:59');
                }
		              
              
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);       
                return $row;
	}

   
   
}