<?php
require_once "../include/database.php";
class cashout{
        private $conn;   
        public function __construct()
        {
                $this->conn = new Database();
                $this->conn = $this->conn->getmyDB();
        }

	public function getcashoutrequest() {
        
        $s = $this->conn->prepare("SELECT cr.*,c.firstname,c.lastname from oc_cash_out_request cr inner join oc_customer c on  cr.customer_id = c.customer_id order by cr.status ");
        $s->execute();
        $status = $s->fetchAll(PDO::FETCH_ASSOC);
        return $status;
   
    }

    public function getcashoutrequestdetails($id) {
        
        $s = $this->conn->prepare("SELECT cr.*,c.firstname,c.lastname from oc_cash_out_request cr inner join oc_customer c on  cr.customer_id = c.customer_id where cr.id=:id order by cr.status ");
        $s->bindValue(':id', $id); 
        $s->execute();
        $status = $s->fetch(PDO::FETCH_ASSOC);
        return $status;
   
    }

  

         public function approvedcash($id)
        {
        
     $stmt3=$this->conn->prepare("UPDATE oc_cash_out_request set status='1' where id=:id");
         
        $stmt3->bindValue(':id', $id);     
         if($stmt3->execute()){
            $fetch="200";
           
        }else{
            $fetch="Error Occured";
        }
        return     $fetch;
        }

           public function disapprovedcash($id,$remarks)
        {
        
     $stmt3=$this->conn->prepare("UPDATE oc_cash_out_request set status='2',remarks=:remarks where id=:id");
         
        $stmt3->bindValue(':id', $id);     
        $stmt3->bindValue(':remarks', $remarks);     
         if($stmt3->execute()){
            $fetch="200";
            $this->removecashwallet($id);
           
        }else{
            $fetch="Error Occured";
        }
        return     $fetch;
        }

         public function removecashwallet($id)
        {
        
     $stmt3=$this->conn->prepare("DELETE from oc_affiliate_wallet where cash_out_id=:id");
         
        $stmt3->bindValue(':id', $id);     
             
         if($stmt3->execute()){
            $fetch="200";
           
        }else{
            $fetch="Error Occured";
        }
        return     $fetch;
        }


          public function updatecashwallet($id,$refnum,$paytype)
        {

            $remarks = "Successfully cash out using ".$paytype. " (Ref. No. ".$refnum.")";
        
     $stmt3=$this->conn->prepare("UPDATE oc_affiliate_wallet set product_name=:remarks where cash_out_id=:id");
         
        $stmt3->bindValue(':id', $id);     
        $stmt3->bindValue(':remarks', $remarks);     
         if($stmt3->execute()){
            $fetch="200";
           
        }else{
            $fetch="Error Occured";
        }
        return     $fetch;
        }


         public function saveref($id,$refnum,$paytype)
        {
        
     $stmt3=$this->conn->prepare("UPDATE oc_cash_out_request set remarks=:remarks,status='3' where id=:id");
         
        $stmt3->bindValue(':id', $id);     
        $stmt3->bindValue(':remarks', $refnum);     
         if($stmt3->execute()){
            $fetch="200";
           $this->updatecashwallet($id,$refnum,$paytype);
        }else{
            $fetch="Error Occured";
        }
        return     $fetch;
        }






}