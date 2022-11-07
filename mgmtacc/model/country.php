<?php
require_once "../include/database.php";
class country{
        private $conn;   
        public function __construct()
        {
                $this->conn = new Database();
                $this->conn = $this->conn->getmyDB();
        }

	public function getcountry() {
        
        $s = $this->conn->prepare("SELECT * FROM oc_country order by name ");
        $s->execute();
        $status = $s->fetchAll(PDO::FETCH_ASSOC);
        return $status;
   
    }

   

    public function savecountry($name, $iso2,$iso3)
        {
        
     $stmt3=$this->conn->prepare("INSERT INTO oc_country (name,iso_code_2,iso_code_3,address_format,postcode_required,status) values (:name,:iso2,:iso3,'','0','1')");
        $stmt3->bindValue(':name', $name);     
        $stmt3->bindValue(':iso2', $iso2);     
        $stmt3->bindValue(':iso3', $iso3);     
         
         if($stmt3->execute()){
            $fetch="200";
           
        }else{
            $fetch="Error Occured";
        }
        return     $fetch;
        }

    public function updatecountry($id,$name,$iso2, $iso3)
        {
        
        $stmt3=$this->conn->prepare("UPDATE oc_country set name=:name,iso_code_2=:iso2,iso_code_3=:iso3 where country_id=:id");
        $stmt3->bindValue(':name', $name);     
        $stmt3->bindValue(':iso2', $iso2);          
        $stmt3->bindValue(':iso3', $iso3);          
        $stmt3->bindValue(':id', $id);     
         if($stmt3->execute()){
            $fetch="200";
           
        }else{
            $fetch="Error Occured";
        }
        return     $fetch;
        }

    public function deletecountry($id)
        {
        
     $stmt3=$this->conn->prepare("DELETE from oc_country where country_id=:id");
         
        $stmt3->bindValue(':id', $id);     
         if($stmt3->execute()){
            $fetch="200";
           
        }else{
            $fetch="Error Occured";
        }
        return     $fetch;
        }

}