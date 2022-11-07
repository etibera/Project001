<?php
require_once "../include/database.php";
class customeractivity{
        private $conn;   
        public function __construct()
        {
                $this->conn = new Database();
                $this->conn = $this->conn->getmyDB();
        }

	public  function activity_list($datefrom,$dateto,$customer,$ip) {
        $sql="";
        $sql1="";
        $sql2="";
        $sql3="";
		if($customer != ''){
            $sql=" and (c.firstname LIKE :customer or c.lastname LIKE :customer)";
        }
        if($ip != ''){
            $sql1=" and a.ip LIKE :ip";
        }
         if($datefrom != '' && $dateto != ''){
            $sql3=" and a.date_added between :date and :date1";
        }
                
		$stmt = $this->conn->prepare("SELECT a.*,c.firstname,c.lastname,c.email,c.type from oc_customer_activity a inner join oc_customer c on a.customer_id = c.customer_id where a.activity_id is not null ".$sql.$sql1.$sql2.$sql3." order by a.date_added desc LIMIT 200");

                 if(!empty($customer)) {
                   $stmt->bindValue(':customer', '%'.$customer.'%');
                }
                 if(!empty($ip)) {
                   $stmt->bindValue(':ip', '%'.$ip.'%');
                }
                 if(!empty($datefrom) && !empty($dateto)) {
                   $stmt->bindValue(':date', $datefrom.' 00:00:00');
                   $stmt->bindValue(':date1', $dateto.' 23:59:59');
                }
		              
              
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);       
                return $row;
	}

    public  function getactivity() {
       
        $stmt = $this->conn->prepare("SELECT a.*,c.firstname,c.lastname from oc_customer_activity a inner join oc_customer c on a.customer_id = c.customer_id order by a.date_added");

                   
                      
              
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);       
                return $row;
    }



     public function savereview($author, $product, $status,$ratings,$customerid,$desc)
        {
        
     $stmt3=$this->conn->prepare("INSERT INTO oc_review (product_id,customer_id,
        author,text,rating,status,date_added,date_modified) values (:product_id,:customer_id,:author,:text,:rating,:status,convert_tz(utc_timestamp(),'-08:00','+0:00'),convert_tz(utc_timestamp(),'-08:00','+0:00'))");
        $stmt3->bindValue(':product_id', $product);     
        $stmt3->bindValue(':customer_id', $customerid);     
        $stmt3->bindValue(':author', $author);     
        $stmt3->bindValue(':text', $desc);     
        $stmt3->bindValue(':rating', $ratings);     
        $stmt3->bindValue(':status', $status);     
             
         if($stmt3->execute()){
            $fetch="200";
           
        }else{
            $fetch="Error Occured";
        }
        return     $fetch;
        }

         public function updatereview($author, $product, $status,$ratings,$customerid,$desc,$reviewid)
        {
        
     $stmt3=$this->conn->prepare("UPDATE oc_review set product_id=:product_id,customer_id=:customer_id,
        author=:author,text=:text,rating=:rating,status=:status,date_modified=convert_tz(utc_timestamp(),'-08:00','+0:00') where review_id=:reviewid");
        $stmt3->bindValue(':product_id', $product);     
        $stmt3->bindValue(':customer_id', $customerid);     
        $stmt3->bindValue(':author', $author);     
        $stmt3->bindValue(':text', $desc);     
        $stmt3->bindValue(':rating', $ratings);     
        $stmt3->bindValue(':status', $status);     
        $stmt3->bindValue(':reviewid', $reviewid);     
             
         if($stmt3->execute()){
            $fetch="200";
           
        }else{
            $fetch="Error Occured";
        }
        return     $fetch;
        }


        public function getreviews() {
        
        $s = $this->conn->prepare("SELECT * from oc_product order by model");
        $s->execute();
        
        $c = $s->fetchAll();
         foreach ($c as $row) {
            $data[] = array(
                'model' => $row['model'],
                'product_id' => $row['product_id'],
            );
         }
         return $data;
        
   
    }
   
}