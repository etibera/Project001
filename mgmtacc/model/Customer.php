<?php
require_once "../include/database.php";
class Customer {
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
        public  function customer_list($customer){              
                
                $limit = $customer !== '' ? "" : "LIMIT 200";

                $stmt = $this->conn->prepare("SELECT cgd.name as customer_group , CONCAT(c.firstname, ' ', c.lastname) as customer, c.* from oc_customer c LEFT JOIN oc_customer_group_description cgd ON c.customer_group_id = cgd.customer_group_id where CONCAT(firstname, ' ', lastname) LIKE :customer order by date_added desc ".$limit);
                $stmt->bindValue(':customer', '%'.$customer.'%');
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $row;
        }
        public  function CustomerListVerifiedReg($customer){             
                
                $limit = $customer !== '' ? "" : " ";

                $stmt = $this->conn->prepare("SELECT cgd.name as customer_group , CONCAT(c.firstname, ' ', c.lastname) as customer, c.* from oc_customer c LEFT JOIN oc_customer_group_description cgd ON c.customer_group_id = cgd.customer_group_id where (nexmo_status=1 AND type is  NULL ) and CONCAT(firstname, ' ', lastname) LIKE :customer order by date_added desc ".$limit);
                $stmt->bindValue(':customer', '%'.$customer.'%');
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $row;
        }
        public  function CustomerListVerifiedGAF($customer){             
                
                $limit = $customer !== '' ? "" : "LIMIT 500";

                $stmt = $this->conn->prepare("SELECT cgd.name as customer_group , CONCAT(c.firstname, ' ', c.lastname) as customer, c.* from oc_customer c LEFT JOIN oc_customer_group_description cgd ON c.customer_group_id = cgd.customer_group_id where (type is not NULL AND c.customer_id not in (SELECT g.customer_id FROM oc_customer g where g.type=:type )) and CONCAT(firstname, ' ', lastname) LIKE :customer order by date_added desc ".$limit);
                $stmt->bindValue(':customer', '%'.$customer.'%');
                $stmt->bindValue(':type', 'guest');
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $row;
        }
        public  function CustomerListUnVerified($customer){              
                
                $limit = $customer !== '' ? "" : "LIMIT 500 ";

                $stmt = $this->conn->prepare("SELECT * FROM (
                                                    SELECT cgd.name as customer_group , CONCAT(c.firstname, ' ', c.lastname) as customer, c.* from oc_customer c LEFT JOIN oc_customer_group_description cgd ON c.customer_group_id = cgd.customer_group_id where nexmo_status=0 and type is  NULL and CONCAT(firstname, ' ', lastname) LIKE :customer 
                                                    UNION ALL
                                                    SELECT cgd.name as customer_group , c.username as customer, c.* from oc_customer c LEFT JOIN oc_customer_group_description cgd ON c.customer_group_id = cgd.customer_group_id where type=:type and CONCAT(firstname, ' ', lastname) LIKE :customer
                                                ) AS maintbl
                                            order by maintbl.date_added desc ".$limit);
                $stmt->bindValue(':customer', '%'.$customer.'%');
                $stmt->bindValue(':type', 'guest');
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $row;
        }

         public  function customer_details($customer_id){              
        
                $stmt = $this->conn->prepare("SELECT cgd.name as customer_group , CONCAT(c.firstname, ' ', c.lastname) as customer, c.* from oc_customer c LEFT JOIN oc_customer_group_description cgd ON c.customer_group_id = cgd.customer_group_id where customer_id = :customer_id order by date_added desc");
                $stmt->bindValue(':customer_id', $customer_id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return $row;
        }

        public  function customer_group(){              
                
                $stmt = $this->conn->prepare("SELECT c.* from oc_customer_group_description c order by customer_group_id");
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $row;
        }

        public  function customer_update($customer_id, $customer_group_id, $firstname, $lastname, $email, 
                                        $telephone, $fax, $password, $confirmpassword, $newsletter, $status, $approved, $safe){
                session_start();
                if($password !== $confirmpassword && $password !== '') {
                        $_SESSION['message'] = $this->messageBox("danger","Password Unmatched! Please try again.");
                        $_SESSION['customer_group_id'] = $customer_group_id;    
                        $_SESSION['firstname'] = $firstname;    
                        $_SESSION['lastname'] = $lastname;    
                        $_SESSION['email'] = $email;
                        $_SESSION['telephone'] = $telephone;
                        $_SESSION['fax'] = $fax;
                        $_SESSION['password'] = $password;
                        $_SESSION['confirmpassword'] = $confirmpassword;
                        $_SESSION['newsletter'] = $newsletter;
                        $_SESSION['status'] = $status;
                        $_SESSION['approved'] = $approved;
                        $_SESSION['safe'] = $safe;
                        header("location: customer_update.php?cid=".$customer_id); 

                }else if(strlen($password) < 6 && $password !== '') {
                        $_SESSION['message'] = $this->messageBox("danger","Password must be 6 characters or more ! ");
                        $_SESSION['customer_group_id'] = $customer_group_id;    
                        $_SESSION['firstname'] = $firstname;    
                        $_SESSION['lastname'] = $lastname;    
                        $_SESSION['email'] = $email;
                        $_SESSION['telephone'] = $telephone;
                        $_SESSION['fax'] = $fax;
                        $_SESSION['password'] = $password;
                        $_SESSION['confirmpassword'] = $confirmpassword;
                        $_SESSION['newsletter'] = $newsletter;
                        $_SESSION['status'] = $status;
                        $_SESSION['approved'] = $approved;
                        $_SESSION['safe'] = $safe; 
                        header("location: customer_update.php?cid=".$customer_id); 

                }else if(strlen($telephone) != 12) {
            
                        $_SESSION['message'] = $this->messageBox("danger","Invalid Mobile Number !"); 
                        $_SESSION['customer_group_id'] = $customer_group_id;    
                        $_SESSION['firstname'] = $firstname;    
                        $_SESSION['lastname'] = $lastname;    
                        $_SESSION['email'] = $email;
                        $_SESSION['telephone'] = $telephone;
                        $_SESSION['fax'] = $fax;
                        $_SESSION['password'] = $password;
                        $_SESSION['confirmpassword'] = $confirmpassword;
                        $_SESSION['newsletter'] = $newsletter;
                        $_SESSION['status'] = $status;
                        $_SESSION['approved'] = $approved;
                        $_SESSION['safe'] = $safe; 
                        header("location: customer_update.php?cid=".$customer_id);     

                }else if($telephone[0] != '6' || $telephone[1] != '3') {
                
                        $_SESSION['message'] = $this->messageBox("danger","Invalid Mobile Number !!"); 
                        $_SESSION['customer_group_id'] = $customer_group_id;    
                        $_SESSION['firstname'] = $firstname;    
                        $_SESSION['lastname'] = $lastname;    
                        $_SESSION['email'] = $email;
                        $_SESSION['telephone'] = $telephone;
                        $_SESSION['fax'] = $fax;
                        $_SESSION['password'] = $password;
                        $_SESSION['confirmpassword'] = $confirmpassword;
                        $_SESSION['newsletter'] = $newsletter;
                        $_SESSION['status'] = $status;
                        $_SESSION['approved'] = $approved;
                        $_SESSION['safe'] = $safe; 
                        header("location: customer_update.php?cid=".$customer_id);
                                     
                }else{
                        $query = "";
                        if($password !== ''){
                                $query = "`salt` = :salt, password = :password,";
                        }

                        $used_symbols = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
                        $salt= substr(str_shuffle($used_symbols), 0, 9);
                        $stmt = $this->conn->prepare("UPDATE oc_customer SET customer_group_id = :customer_group_id , firstname = :firstname, lastname = :lastname, email = :email, telephone = :telephone, fax= :fax, ".$query." newsletter = :newsletter, `status` = :status, approved = :approved, safe = :safe where customer_id = :customer_id");
                        $stmt->bindValue(':customer_id', $customer_id);
                        $stmt->bindValue(':customer_group_id', $customer_group_id);
                        $stmt->bindValue(':firstname', $firstname);
                        $stmt->bindValue(':lastname', $lastname);
                        $stmt->bindValue(':email', $email);
                        $stmt->bindValue(':telephone', $telephone);
                        $stmt->bindValue(':fax', $fax);
                        if($password !== ''){
                                $stmt->bindValue(':password',sha1($salt . sha1($salt . sha1($password))) );
                                $stmt->bindValue(':salt', $salt);
                        }                   
                        $stmt->bindValue(':newsletter', $newsletter);
                        $stmt->bindValue(':status', $status);
                        $stmt->bindValue(':approved', $approved);
                        $stmt->bindValue(':safe', $safe);
                        $stmt->execute();

                        $_SESSION['message'] = $this->messageBox("success","Customer Successfully Updated !"); 

                        unset($_SESSION['customer_id']);
                        unset($_SESSION['customer_group_id']);
                        unset($_SESSION['firstname']);
                        unset($_SESSION['lastname']);
                        unset($_SESSION['email']);
                        unset($_SESSION['telephone']);
                        unset($_SESSION['fax']);
                        unset($_SESSION['oldpassword']);
                        unset($_SESSION['password']);
                        unset($_SESSION['newsletter']);
                        unset($_SESSION['status']);
                        unset($_SESSION['approved']);
                        unset($_SESSION['safe']);
                        unset($_SESSION['confirmpassword']);

                        header("location: customer_update.php?cid=".$customer_id); 
                }                        
        }
}

?>