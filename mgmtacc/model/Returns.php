  <?php
require_once "../include/database.php";
class Returns {

        private $conn;
        public function __construct()
        {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
        }

        public function return_historyA(){              
              
                $stmt = $this->conn->prepare("SELECT r.return_id,  r.order_id , CONCAT(r.firstname,' ',r.lastname) as customer,
                r.product, r.model, r.serial, rs.name as return_status, DATE_FORMAT(r.date_added,'%Y-%m-%d') as date_added,
                DATE_FORMAT(r.date_modified,'%Y-%m-%d') as date_modified  from oc_return r LEFT JOIN oc_return_status rs ON r.return_status_id = rs.return_status_id order by date_added desc;");
                // $stmt->bindValue(':customer_id', $customer_id);
                $stmt->execute(); 
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $row;
        } 

        public function return_details($return_id){              
              
                $stmt = $this->conn->prepare("SELECT r.customer_id,r.firstname, r.lastname, r.telephone,r.email, r.return_id, rs.name as return_status, DATE_FORMAT(r.date_added,'%Y-%m-%d') as date_added, r.order_id, CONCAT(r.firstname,' ',r.lastname) as customer, ra.return_action_id as return_action, rr.return_reason_id as return_reason, r.date_ordered, r.product, r.model, r.quantity, r.serial, r.opened, r.comment
                    from oc_return r LEFT JOIN oc_return_status rs ON r.return_status_id = rs.return_status_id LEFT JOIN oc_return_action ra ON r.return_action_id = ra.return_action_id LEFT JOIN oc_return_reason rr ON r.return_reason_id = rr.return_reason_id where r.return_id = :return_id order by date_added desc;");
                $stmt->bindValue(':return_id', $return_id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return $row;
        }

         public function return_history_details($return_id){              
              
                $stmt = $this->conn->prepare("SELECT r.*,rs.name as return_status, DATE_FORMAT(r.date_added,'%Y-%m-%d') as added_date from oc_return_history r LEFT JOIN oc_return_status rs ON r.return_status_id = rs.return_status_id where r.return_id = :return_id order by r.date_added desc;");
                $stmt->bindValue(':return_id', $return_id);
                $stmt->execute(); 
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $row;
        }  


        public function return_reason(){              
              
                $stmt = $this->conn->prepare("SELECT * from oc_return_reason");
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $row;
        }    

        public function return_action(){              
              
                $stmt = $this->conn->prepare("SELECT * from oc_return_action");
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $row;
        }   

        public function return_status(){              
              
                $stmt = $this->conn->prepare("SELECT * from oc_return_status order by name");
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $row;
        }    

        public function return_update($order_id,$return_id,$firstname,$lastname,$email,$telephone,$product,
              $model,$quantity,$opened,$return_reason_id,$comment,$date_ordered,$return_action_id,$return_status_id,$notify,$comment_a){              

                $stmt = $this->conn->prepare("UPDATE oc_return SET order_id =  :order_id, firstname = :firstname, lastname = :lastname, email = :email, telephone = :telephone, product = :product, model = :model, quantity = :quantity, opened = :opened, return_reason_id = :return_reason_id, return_action_id = :return_action_id, return_status_id = :return_status_id , comment = :comment, date_ordered = :date_ordered , date_modified = NOW() where return_id = :return_id");
                $stmt->bindValue(':order_id', $order_id);
                $stmt->bindValue(':return_id', $return_id);
                $stmt->bindValue(':firstname', $firstname);
                $stmt->bindValue(':lastname', $lastname);
                $stmt->bindValue(':email', $email);
                $stmt->bindValue(':telephone', $telephone);
                $stmt->bindValue(':product', $product);
                $stmt->bindValue(':model', $model);
                $stmt->bindValue(':quantity', $quantity);
                $stmt->bindValue(':opened', $opened);
                $stmt->bindValue(':return_reason_id', $return_reason_id);
                $stmt->bindValue(':return_action_id', $return_action_id);
                $stmt->bindValue(':return_status_id', $return_status_id);
                $stmt->bindValue(':comment', $comment);
                $stmt->bindValue(':date_ordered', $date_ordered);
                $stmt->execute();

                $stmt2 = $this->conn->prepare("INSERT INTO oc_return_history (return_id,return_status_id,notify,comment,date_added) VALUES (:return_id, :return_status_id, :notify, :comment_a, NOW())");
                $stmt2->bindValue(':return_id', $return_id);
                $stmt2->bindValue(':return_status_id', $return_status_id);
                $stmt2->bindValue(':notify', $notify);
                $stmt2->bindValue(':comment_a', $comment_a);
                $stmt2->execute();

                if($notify == '1'){
                	//Send Email Hold;
                }

                $_SESSION['message'] = '<div class="alert alert-success">Return ID: '.$return_id.' Successfully Updated ! </div>';
                header('location: return_list.php');  

                exit();
        }    
}

?>