<?php
require_once "../include/database.php";
class delivery_charge{
        private $conn;   
        public function __construct()
        {
                $this->conn = new Database();
                $this->conn = $this->conn->getmyDB();
        }

	public function getdelivery() {        
        $s = $this->conn->prepare("SELECT * FROM oc_delivery_charge ");
        $s->execute();
        $status = $s->fetchAll(PDO::FETCH_ASSOC);
        return $status;
   
    }

    public function getdeliveryname() {
        
        $s = $this->conn->prepare("SELECT d.*,(SELECT name as dname from oc_delivery_charge c where c.id=d.convert_id ) as convert_into FROM oc_delivery_charge d ");
        $s->execute();
        
        $c = $s->fetchAll();
         foreach ($c as $row) {
            $data[] = array(
                'name' => $row['name'],
                'id' => $row['id'],
            );
         }
         return $data;
        
   
    }

    public function savedelivery($name, $max_quantity, $convert_quantity,$amount,$delivery_option,$provincial_amount)
        {
        
     $stmt3=$this->conn->prepare("INSERT INTO oc_delivery_charge (name,amount,
        max_quantity,convert_quantity,convert_id,provincial_amount) values (:name,:amount,:max_quantity,:convert_quantity,:convert_into,:provincial_amount)");
        $stmt3->bindValue(':name', $name);     
        $stmt3->bindValue(':amount', $amount);     
        $stmt3->bindValue(':max_quantity', $max_quantity);     
        $stmt3->bindValue(':convert_quantity', $convert_quantity);     
        $stmt3->bindValue(':convert_into', $delivery_option);     
        $stmt3->bindValue(':provincial_amount', $provincial_amount);     
         if($stmt3->execute()){
            $fetch="200";
           
        }else{
            $fetch="Error Occured";
        }
        return     $fetch;
        }

        public function updatedelivery($id,$name, $max_quantity, $convert_quantity,$amount,$delivery_option,$provincial_amount)
        {
        
     $stmt3=$this->conn->prepare("UPDATE oc_delivery_charge set name=:name,amount=:amount,
        max_quantity=:max_quantity,convert_quantity=:convert_quantity,convert_id=:convert_into,provincial_amount=:provincial_amount where id=:id");
        $stmt3->bindValue(':name', $name);     
        $stmt3->bindValue(':amount', $amount);     
        $stmt3->bindValue(':max_quantity', $max_quantity);     
        $stmt3->bindValue(':convert_quantity', $convert_quantity);     
        $stmt3->bindValue(':convert_into', $delivery_option);     
        $stmt3->bindValue(':provincial_amount', $provincial_amount);     
        $stmt3->bindValue(':id', $id);     
         if($stmt3->execute()){
            $fetch="200";
           
        }else{
            $fetch="Error Occured";
        }
        return     $fetch;
        }

         public function deletedelivery($id)
        {
        
     $stmt3=$this->conn->prepare("DELETE from oc_delivery_charge where id=:id");
         
        $stmt3->bindValue(':id', $id);     
         if($stmt3->execute()){
            $fetch="200";
           
        }else{
            $fetch="Error Occured";
        }
        return     $fetch;
        }

}