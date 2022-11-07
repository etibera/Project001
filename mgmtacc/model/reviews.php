<?php
require_once "../include/database.php";
class Reviews{
        private $conn;   
        public function __construct()
        {
                $this->conn = new Database();
                $this->conn = $this->conn->getmyDB();
        }

	public  function review_list($product_name,$status,$author,$date) {
        $sql="";
        $sql1="";
        $sql2="";
        $sql3="";
		if($product_name != ''){
            $sql=" and p.model LIKE :product";
        }
        if($status != '2'){
            $sql1=" and r.status LIKE :status";
        }
        if($author != ''){
            $sql2=" and r.author LIKE :author";
        }
         if($date != ''){
            $sql3=" and r.date_added between :date and :date1";
        }
                
		$stmt = $this->conn->prepare("SELECT r.*,p.model FROM pcvill_oc1.oc_review r inner join oc_product p on r.product_id = p.product_id where r.review_id is not null ".$sql.$sql1.$sql2.$sql3." order by date_added desc LIMIT 200");

                 if(!empty($product_name)) {
                   $stmt->bindValue(':product', '%'.$product_name.'%');
                }
                if($status != '2') {
                   $stmt->bindValue(':status',  $status);
                }
                 if(!empty($author)) {
                   $stmt->bindValue(':author', '%'.$author.'%');
                }
                 if(!empty($date)) {
                   $stmt->bindValue(':date', $date.' 00:00:00');
                   $stmt->bindValue(':date1', $date.' 23:59:59');
                }
		              
              
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);       
                return $row;
	}

    public  function getreview_list($id) {
       
        $stmt = $this->conn->prepare("SELECT r.*,p.model FROM pcvill_oc1.oc_review r inner join oc_product p on r.product_id = p.product_id where r.review_id = :id");

                   $stmt->bindValue(':id', $id);
                      
              
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