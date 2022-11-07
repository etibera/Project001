<?php
require_once "../include/database.php";
class Coupon {
        private $conn;
        public function __construct()
        {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
        }

        public function messageBox($stats,$msg)
        {
                $message = '<div class="alert alert-'.$stats.' alert-dismissable" style="margin:0px 0px 10px 0px">
                <span id="msg-error">'.$msg.'</span>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button></div>'; 
                return $message;   
        }
        
        public  function coupon_list(){                              

                $stmt = $this->conn->prepare("SELECT *, CASE WHEN status = 1 THEN 'Enabled' ELSE 'Disabled' END as stats from oc_coupon");
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $row;
        }

        public  function coupon_details($coupon_id){              
                
                $stmt = $this->conn->prepare("SELECT v.* from oc_coupon v where v.coupon_id = :coupon_id and v.coupon_id IS NOT NULL");
                $stmt->bindValue(':coupon_id', $coupon_id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return $row;
        }

        public  function coupon_history($coupon_id){              
                
                $stmt = $this->conn->prepare("SELECT v.*,CONCAT(c.firstname,' ',c.lastname) as customer from oc_coupon_history v LEFT JOIN oc_customer c ON c.customer_id = v.customer_id where v.coupon_id = :coupon_id and v.coupon_id IS NOT NULL");
                $stmt->bindValue(':coupon_id', $coupon_id);
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return $row;
        }

        public  function coupon_category($coupon_id){              
                
                $stmt = $this->conn->prepare("SELECT v.*, c.name as category_name from oc_coupon_category v LEFT JOIN oc_category_description c ON c.category_id = v.category_id where v.coupon_id = :coupon_id and v.coupon_id IS NOT NULL order by c.name");
                $stmt->bindValue(':coupon_id', $coupon_id);
                $stmt->execute();
                $row = $stmt->fetchall(PDO::FETCH_ASSOC);

                return $row;
        }

        public  function category(){              
                
                $stmt = $this->conn->prepare("SELECT v.* from oc_category_description v order by name");
                $stmt->execute();
                $row = $stmt->fetchall(PDO::FETCH_ASSOC);

                return $row;
        }

        public  function coupon_product($coupon_id){              
                
                $stmt = $this->conn->prepare("SELECT v.*, p.name as product_name from oc_coupon_product v LEFT JOIN oc_product_description p ON p.product_id = v.product_id where v.coupon_id = :coupon_id and v.coupon_id IS NOT NULL order by p.name");
                $stmt->bindValue(':coupon_id', $coupon_id);
                $stmt->execute();
                $row = $stmt->fetchall(PDO::FETCH_ASSOC);

                return $row;
        }

        public  function product(){              
                
                $stmt = $this->conn->prepare("SELECT product_id,name from oc_product_description order by name");
                $stmt->execute();
                $row = $stmt->fetchall(PDO::FETCH_ASSOC);
                return $row;
        }

        public  function coupon_update($data){ 
                try{

                    $stmt = $this->conn->prepare("DELETE FROM oc_coupon_category WHERE coupon_id = :coupon_id");
                    $stmt->bindValue(':coupon_id', $data['coupon_id']);
                    $stmt->execute();

                    $stmt = $this->conn->prepare("DELETE FROM oc_coupon_product WHERE coupon_id = :coupon_id");
                    $stmt->bindValue(':coupon_id', $data['coupon_id']);
                    $stmt->execute();
                                           
                    $stmt = $this->conn->prepare("UPDATE oc_coupon SET name = :name, code = :code, type = :type, discount = :discount, total = :total, logged= :logged, shipping = :shipping, date_start = :date_start, date_end = :date_end, uses_total = :uses_total, uses_customer = :uses_customer, status = :status WHERE coupon_id = :coupon_id");
                    $stmt->bindValue(':coupon_id', $data['coupon_id']);
                    $stmt->bindValue(':name', $data['name']);
                    $stmt->bindValue(':code', $data['code']);
                    $stmt->bindValue(':type', $data['type']);
                    $stmt->bindValue(':discount', $data['discount']);
                    $stmt->bindValue(':total', $data['total']);
                    $stmt->bindValue(':logged', $data['logged']);
                    $stmt->bindValue(':shipping', $data['shipping']);
                    $stmt->bindValue(':date_start', $data['date_start']);
                    $stmt->bindValue(':date_end', $data['date_end']);
                    $stmt->bindValue(':uses_total', $data['uses_total']);
                    $stmt->bindValue(':uses_customer', $data['uses_customer']);
                    $stmt->bindValue(':status', $data['status']);
                    $stmt->execute();

                    for($key = 0 ; $key < count($data['product_id']); $key++) {

                        $stmt = $this->conn->prepare("INSERT INTO oc_coupon_product set coupon_id = :coupon_id, 
                            product_id = :product_id");
                        $stmt->bindValue(':coupon_id', $data['coupon_id']);
                        $stmt->bindValue(':product_id', $data['product_id'][$key]);
                        $stmt->execute();                    
                    }

                    for($key = 0 ; $key < count($data['category_id']); $key++) {

                       $stmt = $this->conn->prepare("INSERT INTO oc_coupon_category set coupon_id = :coupon_id, 
                            category_id = :category_id");
                        $stmt->bindValue(':coupon_id', $data['coupon_id']);
                        $stmt->bindValue(':category_id', $data['category_id'][$key]);
                        $stmt->execute();                    
                    }

                    unset($_POST);
                    $_SESSION['message'] = $this->messageBox("success","Coupon Successfully Updated !"); 
                }catch(PDOexception $e){
                    echo "<p>Database Error: $e </p>";
                }
        }

        public  function coupon_add($data){ 
                try{                   
                     $stmt = $this->conn->prepare("INSERT INTO oc_coupon SET name = :name, code = :code, type = :type, discount = :discount, total = :total, logged= :logged, shipping = :shipping, date_start = :date_start, date_end = :date_end, uses_total = :uses_total, uses_customer = :uses_customer, status = :status, 
                        date_added = NOW()");
                    $stmt->bindValue(':name', $data['name']);
                    $stmt->bindValue(':code', $data['code']);
                    $stmt->bindValue(':type', $data['type']);
                    $stmt->bindValue(':discount', $data['discount']);
                    $stmt->bindValue(':total', $data['total']);
                    $stmt->bindValue(':logged', $data['logged']);
                    $stmt->bindValue(':shipping', $data['shipping']);
                    $stmt->bindValue(':date_start', $data['date_start']);
                    $stmt->bindValue(':date_end', $data['date_end']);
                    $stmt->bindValue(':uses_total', $data['uses_total']);
                    $stmt->bindValue(':uses_customer', $data['uses_customer']);
                    $stmt->bindValue(':status', $data['status']);
                    $stmt->execute();
                    $coupon_id = $this->conn->lastInsertId();

                    for($key = 0 ; $key < count($data['product_id']); $key++) {

                        $stmt = $this->conn->prepare("INSERT INTO oc_coupon_product set coupon_id = :coupon_id, 
                            product_id = :product_id");
                        $stmt->bindValue(':coupon_id', $coupon_id);
                        $stmt->bindValue(':product_id', $data['product_id'][$key]);
                        $stmt->execute();                    
                    }

                    for($key = 0 ; $key < count($data['category_id']); $key++) {

                       $stmt = $this->conn->prepare("INSERT INTO oc_coupon_category set coupon_id = :coupon_id, 
                            category_id = :category_id");
                        $stmt->bindValue(':coupon_id', $coupon_id);
                        $stmt->bindValue(':category_id', $data['category_id'][$key]);
                        $stmt->execute();                    
                    }

                    unset($_POST);
                    $_SESSION['message'] = $this->messageBox("success","Coupon Successfully Added !"); 
                }catch(PDOexception $e){
                    echo "<p>Database Error: $e </p>";
                }
        }

        public  function coupon_delete($coupon_id){ 
                try{
                                         
                    $stmt = $this->conn->prepare("DELETE FROM oc_coupon where coupon_id = :coupon_id");
                    $stmt->bindValue(':coupon_id', $coupon_id);
                    $stmt->execute();

                    $stmt = $this->conn->prepare("DELETE FROM oc_coupon_history where coupon_id = :coupon_id");
                    $stmt->bindValue(':coupon_id', $coupon_id);
                    $stmt->execute();

                    $stmt = $this->conn->prepare("DELETE FROM oc_coupon_category where coupon_id = :coupon_id");
                    $stmt->bindValue(':coupon_id', $coupon_id);
                    $stmt->execute();

                    $stmt = $this->conn->prepare("DELETE FROM oc_coupon_product where coupon_id = :coupon_id");
                    $stmt->bindValue(':coupon_id', $coupon_id);
                    $stmt->execute();

                    $stats = 'Successfully Deleted!';

                }catch(PDOexception $e){

                    $error_message = $e->getMessage();
                    $stats = $error_message;           
                }

                return $stats;
        }
         
}

?>