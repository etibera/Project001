<?php
require_once "../include/database.php";
class returnstatus{
        private $conn;   
        public function __construct()
        {
                $this->conn = new Database();
                $this->conn = $this->conn->getmyDB();
        }

	public function getstatus() {
        
        $s = $this->conn->prepare("SELECT * FROM oc_return_status order by name ");
        $s->execute();
        $status = $s->fetchAll(PDO::FETCH_ASSOC);
        return $status;
   
    }

   

    public function savestatus($name)
        {
        
     $stmt3=$this->conn->prepare("INSERT INTO oc_return_status (language_id,name) values ('1',:name)");
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
        
        $stmt3=$this->conn->prepare("UPDATE oc_return_status set name=:name where return_status_id=:id");
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
        
     $stmt3=$this->conn->prepare("DELETE from oc_return_status where return_status_id=:id");
         
        $stmt3->bindValue(':id', $id);     
         if($stmt3->execute()){
            $fetch="200";
           
        }else{
            $fetch="Error Occured";
        }
        return     $fetch;
        }

}