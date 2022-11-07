<?php
require_once "../include/database.php";
class FGives_Custumer{
    private $conn;   
    public function __construct(){
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    public function getRegion(){
        $data = array();
        $stm = $this->conn->prepare("SELECT DISTINCT province FROM address_tracker ORDER BY province ASC");
        $stm->execute();
        $c = $stm->fetchAll(PDO::FETCH_ASSOC);
         foreach ($c as $row) {
            $data[] = array(
                'province' => $row['province']
            );
         }
         return $data;
    } 

    public function getAllCustomer() {         
       $stmt = $this->conn->prepare("SELECT fc.*,concat(fc.firstname,' ',fc.lastname) as fullname,
                                        DATE_FORMAT(fc.b_date,'%b %d %Y') as bday ,
                                        concat(IFNULL(fa.address_1, ' '),' ',IFNULL(fa.address_2, ' '),' ',fa.district,' ',fa.city,' , ' ,fa.region) as address
                                    FROM 4gives_customer fc
                                    LEFT JOIN 4gives_address fa on fa.customer_id= fc.f_customer_id");
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    public function save4GivesCustomer($data) { 
        $stats="";
        try { 
            $date=date_create($data['bday']);
            $bday=date_format($date,"Y-m-d");   
            $isert_cust = $this->conn->prepare("INSERT INTO 4gives_customer SET firstname = :firstname, lastname =:lastname, email =:email, status =:status, telephone = :telephone,b_date=:b_date");            
            $isert_cust->bindValue(':firstname', $data['fname']);
            $isert_cust->bindValue(':lastname', $data['lname']);
            $isert_cust->bindValue(':email', $data['txtemail']);
            $isert_cust->bindValue(':telephone', $data['telephone']);
            $isert_cust->bindValue(':status', '0');
            $isert_cust->bindValue(':b_date',$bday );
            $isert_cust->execute();
            $lastId = $this->conn->lastInsertId();  

            $isert_addr =$this->conn->prepare("INSERT INTO 4gives_address SET customer_id=:lastId,firstname = :firstname, lastname =:lastname, address_1 =:address_1, address_2 =:address_2, city = :city,postcode=:postcode,country_id=:country_id,district=:district,region=:region,company=:company,tracking_id=:tracking_id,custom_field=''");  
            $isert_addr->bindValue(':lastId', $lastId);         
            $isert_addr->bindValue(':firstname',$data['fname']);
            $isert_addr->bindValue(':lastname', $data['lname']);
            $isert_addr->bindValue(':address_1', $data['address_1']);
            $isert_addr->bindValue(':address_2', $data['address_2']);
            $isert_addr->bindValue(':city', $data['city']);
            $isert_addr->bindValue(':postcode', $data['postal_code']);
            $isert_addr->bindValue(':country_id', '168');
            $isert_addr->bindValue(':district', $data['district']);
            $isert_addr->bindValue(':region', $data['region']);
            $isert_addr->bindValue(':company', '');
            $isert_addr->bindValue(':tracking_id',$data['tracking_id']);
            $isert_addr->execute();

            $stats="Customer Successfully Added";  
        }catch(PDOexception $e){
          $stats=$e;
          
        }  
        return $stats;
      
    } 
    public function checkcustomer($telephone) {         
        $stmt = $this->conn->prepare("SELECT count(telephone) as count FROM 4gives_customer where telephone=:telephone");
        $stmt->bindValue(':telephone',$telephone);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }
    public function getTrackingId($district,$city,$region) {  

        $stmt = $this->conn->prepare("SELECT * FROM address_tracker where province like :region and city like :city AND district like :district ");
        $stmt->bindValue(':district', '%'.$district.'%');
        $stmt->bindValue(':city',$city);
        $stmt->bindValue(':region',$region);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row){
            return $row['tracking_id'];     
        }else{
            return 0;   
        }
    }
    public function savedata($firstname,$lastname,$email,$telephone,$bday,$address_1,$address_2,$district,$city,$region,$postcode){
        $stats="";
        try { 
            $checkcustomer=$this->checkcustomer($telephone);
            if($checkcustomer==0){
                $isert_cust = $this->conn->prepare("INSERT INTO 4gives_customer SET firstname = :firstname, lastname =:lastname, email =:email, status =:status, telephone = :telephone,b_date=:b_date");            
                $isert_cust->bindValue(':firstname', $firstname);
                $isert_cust->bindValue(':lastname', $lastname);
                $isert_cust->bindValue(':email', $email);
                $isert_cust->bindValue(':telephone', $telephone);
                $isert_cust->bindValue(':status', '0');
                $isert_cust->bindValue(':b_date', $bday);
                $isert_cust->execute();
                $lastId = $this->conn->lastInsertId();

                $tracking_id=0; 
                $tracking_id=$this->getTrackingId($district,$city,$region);

                $isert_addr =$this->conn->prepare("INSERT INTO 4gives_address SET customer_id=:lastId,firstname = :firstname, lastname =:lastname, address_1 =:address_1, address_2 =:address_2, city = :city,postcode=:postcode,country_id=:country_id,district=:district,region=:region,company=:company,tracking_id=:tracking_id,custom_field=''");            
                $isert_addr->bindValue(':firstname', $firstname);
                $isert_addr->bindValue(':lastname', $lastname);
                $isert_addr->bindValue(':lastId', $lastId);
                $isert_addr->bindValue(':address_1', $address_1);
                $isert_addr->bindValue(':address_2', $address_2);
                $isert_addr->bindValue(':city', $city);
                $isert_addr->bindValue(':postcode', $postcode);
                $isert_addr->bindValue(':country_id', '168');
                $isert_addr->bindValue(':district', $district);
                $isert_addr->bindValue(':region', $region);
                $isert_addr->bindValue(':company','');
                $isert_addr->bindValue(':tracking_id',$tracking_id);
                $isert_addr->execute();
            }
            
            $stats="Customer Successfully Uploaded";
        }catch(PDOexception $e){
          $stats=$e;
         
               
        }
         return $stats;
    }
}
?>