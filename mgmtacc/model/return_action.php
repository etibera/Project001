<?php
require_once "../include/database.php";
class returnaction{
        private $conn;   
        public function __construct()
        {
                $this->conn = new Database();
                $this->conn = $this->conn->getmyDB();
        }

	public function getstatus() {
        
        $s = $this->conn->prepare("SELECT * FROM oc_return_action order by name ");
        $s->execute();
        $status = $s->fetchAll(PDO::FETCH_ASSOC);
        return $status;
   
    }

   

    public function savestatus($name)
        {
        
     $stmt3=$this->conn->prepare("INSERT INTO oc_return_action (language_id,name) values ('1',:name)");
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
        
        $stmt3=$this->conn->prepare("UPDATE oc_return_action set name=:name where return_action_id=:id");
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
        
     $stmt3=$this->conn->prepare("DELETE from oc_return_action where return_action_id=:id");
         
        $stmt3->bindValue(':id', $id);     
         if($stmt3->execute()){
            $fetch="200";
           
        }else{
            $fetch="Error Occured";
        }
        return     $fetch;
        }

}