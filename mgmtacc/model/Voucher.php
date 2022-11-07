<?php
require_once "../include/database.php";
class Voucher {
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
        public  function voucher_list(){              
                

                $stmt = $this->conn->prepare("SELECT v.*,vtd.name as theme, CASE WHEN v.status = 1 THEN 'Enabled' ELSE 'Disabled' END as stats  from oc_voucher v LEFT JOIN oc_voucher_theme_description vtd ON v.voucher_theme_id = vtd.voucher_theme_id  order by v.voucher_id");
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $row;
        }

        public  function voucher_theme_list(){              
                

                $stmt = $this->conn->prepare("SELECT v.*, v.voucher_theme_id, vd.name as name from oc_voucher_theme v LEFT JOIN oc_voucher_theme_description vd ON v.voucher_theme_id = vd.voucher_theme_id order by v.voucher_theme_id");
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $row;
        }

        public  function voucher_details($voucher_id){              
                
                $stmt = $this->conn->prepare("SELECT v.* from oc_voucher v where v.voucher_id = :voucher_id and v.voucher_id IS NOT NULL");
                $stmt->bindValue(':voucher_id', $voucher_id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return $row;
        }

        public  function voucher_theme_details($voucher_theme_id){              
                
                $stmt = $this->conn->prepare("SELECT v.*,vd.* from oc_voucher_theme v LEFT JOIN oc_voucher_theme_description vd ON 
                v.voucher_theme_id = vd.voucher_theme_id  where v.voucher_theme_id = :voucher_theme_id and v.voucher_theme_id IS NOT NULL");
                $stmt->bindValue(':voucher_theme_id', $voucher_theme_id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return $row;
        }

        public  function voucher_history($voucher_id){              
                

                $stmt = $this->conn->prepare("SELECT v.* from oc_voucher_history v where v.voucher_id = :voucher_id and v.voucher_id IS NOT NULL");
                $stmt->bindValue(':voucher_id', $voucher_id);
                $stmt->execute();
                $row = $stmt->fetchall(PDO::FETCH_ASSOC);

                return $row;
        }

        public  function voucher_update($data){ 
                try{
                                           
                    $stmt = $this->conn->prepare("UPDATE oc_voucher SET code = :code, from_name = :from_name, from_email = :from_email, to_name = :to_name, to_email = :to_email, voucher_theme_id = :voucher_theme_id, message = 
                    :message, amount = :amount, status = :status WHERE voucher_id = :voucher_id");
                    $stmt->bindValue(':voucher_id', $data['voucher_id']);
                    $stmt->bindValue(':code', $data['code']);
                    $stmt->bindValue(':from_name', $data['from_name']);
                    $stmt->bindValue(':from_email', $data['from_email']);
                    $stmt->bindValue(':to_name', $data['to_name']);
                    $stmt->bindValue(':to_email', $data['to_email']);
                    $stmt->bindValue(':voucher_theme_id', $data['voucher_theme_id']);
                    $stmt->bindValue(':message', $data['message']);
                    $stmt->bindValue(':amount', $data['amount']);
                    $stmt->bindValue(':status', $data['status']);
                    $stmt->execute();
                    unset($_POST);
                    $_SESSION['message'] = $this->messageBox("success","Voucher Successfully Updated !"); 
                }catch(PDOexception $e){
                    echo "<p>Database Error: $e </p>";
                }
        }

        public  function voucher_theme_update($data,$img){ 
                try{
                                           
                    $stmt = $this->conn->prepare("UPDATE oc_voucher_theme SET image = :img 
                        WHERE voucher_theme_id = :voucher_theme_id");
                    $stmt->bindValue(':voucher_theme_id', $data['voucher_theme_id']);
                    $stmt->bindValue(':img', $img);
                    $stmt->execute();

                    $stmt = $this->conn->prepare("UPDATE oc_voucher_theme_description SET name = :name 
                        WHERE voucher_theme_id = :voucher_theme_id");
                    $stmt->bindValue(':voucher_theme_id', $data['voucher_theme_id']);
                    $stmt->bindValue(':name', $data['name']);
                    $stmt->execute();

                    unset($_POST);

                    $_SESSION['message'] = $this->messageBox("success","Voucher Theme Successfully Updated !"); 

                }catch(PDOexception $e){
                    echo "<p>Database Error: $e </p>";
                }
        }

         public  function voucher_add($data){ 
                try{
                   
                    $stmt = $this->conn->prepare("INSERT INTO oc_voucher SET code = :code, from_name = :from_name, from_email = :from_email, to_name = :to_name, to_email = :to_email, voucher_theme_id = :voucher_theme_id, message = 
                    :message, amount = :amount, status = :status , date_added = NOW(), order_id = 0");
                    $stmt->bindValue(':code', $data['code']);
                    $stmt->bindValue(':from_name', $data['from_name']);
                    $stmt->bindValue(':from_email', $data['from_email']);
                    $stmt->bindValue(':to_name', $data['to_name']);
                    $stmt->bindValue(':to_email', $data['to_email']);
                    $stmt->bindValue(':voucher_theme_id', $data['voucher_theme_id']);
                    $stmt->bindValue(':message', $data['message']);
                    $stmt->bindValue(':amount', $data['amount']);
                    $stmt->bindValue(':status', $data['status']);
                    $stmt->execute();
                    unset($_POST);
                    $_SESSION['message'] = $this->messageBox("success","Voucher Successfully Added !"); 
                }catch(PDOexception $e){
                    echo "<p>Database Error: $e </p>";
                }
        }

        public  function voucher_theme_add($data,$img){ 
                try{
                                           
                    $stmt = $this->conn->prepare("INSERT INTO oc_voucher_theme SET image = :img");
                    $stmt->bindValue(':img', $img);
                    $stmt->execute();
                    $lastId = $this->conn->lastInsertId();

                    $stmt = $this->conn->prepare("INSERT INTO oc_voucher_theme_description SET name = :name, voucher_theme_id = :voucher_theme_id, language_id = '1'");
                    $stmt->bindValue(':voucher_theme_id', $lastId);
                    $stmt->bindValue(':name', $data['name']);
                    $stmt->execute();

                    unset($_POST);

                    $_SESSION['message'] = $this->messageBox("success","Voucher Theme Successfully Added !"); 
                    
                }catch(PDOexception $e){
                    echo "<p>Database Error: $e </p>";
                }
        }

        public  function voucher_delete($voucher_id){ 
                try{
                                         
                    $stmt = $this->conn->prepare("DELETE FROM oc_voucher where voucher_id = :voucher_id");
                    $stmt->bindValue(':voucher_id', $voucher_id);
                    $stmt->execute();

                    $stmt = $this->conn->prepare("DELETE FROM oc_voucher_history where voucher_id = :voucher_id");
                    $stmt->bindValue(':voucher_id', $voucher_id);
                    $stmt->execute();

                    $stats = 'Successfully Deleted!';

                }catch(PDOexception $e){

                    $error_message = $e->getMessage();
                    $stats = $error_message;           
                }

                return $stats;
        }

         public  function voucher_theme_delete($voucher_theme_id){ 
                try{
                                         
                    $stmt = $this->conn->prepare("DELETE FROM oc_voucher_theme where voucher_theme_id = :voucher_theme_id");
                    $stmt->bindValue(':voucher_theme_id', $voucher_theme_id);
                    $stmt->execute();

                    $stmt = $this->conn->prepare("DELETE FROM oc_voucher_theme_description where voucher_theme_id = :voucher_theme_id");
                    $stmt->bindValue(':voucher_theme_id', $voucher_theme_id);
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