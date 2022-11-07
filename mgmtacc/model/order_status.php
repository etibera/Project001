<?php
require_once "../include/database.php";
class orderstatus{
        private $conn;   
        public function __construct()
        {
                $this->conn = new Database();
                $this->conn = $this->conn->getmyDB();
        }

    public function getstatus() {
        
        $s = $this->conn->prepare("SELECT * FROM oc_order_status order by name ");
        $s->execute();
        $status = $s->fetchAll(PDO::FETCH_ASSOC);
        return $status;
   
    }

   

    public function savestatus($name)
        {
        
     $stmt3=$this->conn->prepare("INSERT INTO oc_order_status (language_id,name) values ('1',:name)");
        $stmt3->bindValue(':name', $name);     
         
         
         if($stmt3->execute()){
            $fetch="200";
           
        }else{
            $fetch="Error Occured";
        }
        return     $fetch;
        }

    public function updatestatus($id,$name)
        {
        
        $stmt3=$this->conn->prepare("UPDATE oc_order_status set name=:name where order_status_id=:id");
        $stmt3->bindValue(':name', $name);     
        $stmt3->bindValue(':id', $id);     
         if($stmt3->execute()){
            $fetch="200";
           
        }else{
            $fetch="Error Occured";
        }
        return     $fetch;
        }

    public function deletestatus($id)
        {
        
     $stmt3=$this->conn->prepare("DELETE from oc_order_status where order_status_id=:id");
         
        $stmt3->bindValue(':id', $id);     
         if($stmt3->execute()){
            $fetch="200";
           
        }else{
            $fetch="Error Occured";
        }
        return     $fetch;
        }

}