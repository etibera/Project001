<?php
require_once "../include/database.php";
class productbrand{
    private $conn;   
    public function __construct(){
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
	public function getbrand(){
        $s = $this->conn->prepare("SELECT * FROM oc_product_brand order by sort_order asc,name asc ");
        $s->execute();
        $status = $s->fetchAll(PDO::FETCH_ASSOC);
        return $status;
    }
    public function search_brand($sval){
        $stmt = $this->conn->prepare("SELECT * FROM oc_product_brand  where name like :searchval ");
        $stmt->bindValue(':searchval','%'.$sval.'%');
        $stmt->execute();
        $status = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $status;
    }
    public function savebrand($name, $description,$sort_order){
        $stmt3=$this->conn->prepare("INSERT INTO oc_product_brand (name,description,sort_order) values (:name,:description,:sort_order)");
        $stmt3->bindValue(':name', $name);     
        $stmt3->bindValue(':description', $description);
        $stmt3->bindValue(':sort_order', $sort_order);
        if($stmt3->execute()){
            $fetch="200";
        }else{
            $fetch="Error Occured";
        }
        return     $fetch;
    }
    public function brand_enable($id) {
        try {
            $stmt =$this->conn->prepare("UPDATE  oc_product_brand set status=:status WHERE id=:id ");
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':status', 1);
            $stmt->execute();
            return "200";
        }catch(Exception $e){
             return $e;
        } 
    } 
    public function brand_disable($id) {
            try {
                $stmt =$this->conn->prepare("UPDATE  oc_product_brand set status=:status WHERE id=:id ");
                $stmt->bindValue(':id', $id);
                $stmt->bindValue(':status', 0);
                $stmt->execute();
                return "200";
            }catch(Exception $e){
                 return $e;
            } 
        }
    public function UpdateImageBrand($data) {
        try {
            $insert =$this->conn->prepare("UPDATE oc_product_brand SET image=:image WHERE id=:edit_id");
            $insert->bindValue(':image',$data[0]['image']);
            $insert->bindValue(':edit_id',$data[0]['edit_id']);
            $insert->execute();
            $status = "200";

        }catch(Exception $e){
              $status=$e;
        } 
        return $status;
    } 
    public function UpdateImagebanner($data) {
        try {
            $insert =$this->conn->prepare("UPDATE oc_product_brand SET banner_img=:image,banner_moblie_img=:image_mobile WHERE id=:edit_id");
            $insert->bindValue(':image',$data[0]['image']);            
            $insert->bindValue(':image_mobile',$data[0]['image_mobile']);
            $insert->bindValue(':edit_id',$data[0]['edit_id']);
            $insert->execute();
            $status = "200";

        }catch(Exception $e){
              $status=$e;
        } 
        return $status;
    } 
    public  function getbg_brand_image($id){   
        $stmt = $this->conn->prepare("SELECT * FROM oc_product_brand  where id=:id ");
         $stmt->bindValue(':id',$id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
    public function updatebrand($id,$name, $description,$sort_order){
        $stmt3=$this->conn->prepare("UPDATE oc_product_brand set name=:name,description=:description,sort_order=:sort_order where id=:id");
        $stmt3->bindValue(':name', $name);     
        $stmt3->bindValue(':description', $description);          
        $stmt3->bindValue(':sort_order', $sort_order);          
        $stmt3->bindValue(':id', $id);     
        if($stmt3->execute()){
            $fetch="200";
        }else{
            $fetch="Error Occured";
        }
        return     $fetch;
    }
    public function deletebrand($id){
        $stmt3=$this->conn->prepare("DELETE from oc_product_brand where id=:id");
        $stmt3->bindValue(':id', $id);     
        if($stmt3->execute()){
            $fetch="200";
        }else{
            $fetch="Error Occured";
        }
        return     $fetch;
    }
}