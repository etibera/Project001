<?php
require_once "../include/database.php";
class Category {
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
        public  function category_list(){              
                

                $stmt = $this->conn->prepare("SELECT c.*, cd.name from oc_category c LEFT JOIN oc_category_description cd ON c.category_id = cd.category_id order by cd.name");
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $row;
        }

        public  function category_details($category_id){              
                
                $stmt = $this->conn->prepare("SELECT c.*, cd.name, cd.description, cd.meta_title, cd.meta_description, cd.meta_keyword from oc_category c LEFT JOIN oc_category_description cd ON c.category_id = cd.category_id where c.category_id = :category_id order by cd.name");
                $stmt->bindValue(':category_id', $category_id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return $row;
        }

        public  function category_update($data,$img){ 
                try{
                   
                    $top = $data['top'] === "" ? "0" : $data['top'];                         
                    $stmt = $this->conn->prepare("UPDATE oc_category SET image = :image, top = :top, `column` = :column, 
                        sort_order = :sort_order, `status` = :status, date_modified = NOW() WHERE category_id = :category_id");
                    $stmt->bindValue(':category_id', $data['category_id']);
                    $stmt->bindValue(':image', $img);
                    $stmt->bindValue(':top', $top);
                    $stmt->bindValue(':column', $data['column']);
                    $stmt->bindValue(':sort_order', $data['sort_order']);
                    $stmt->bindValue(':status', $data['status']);
                    $stmt->execute();

                    $stmt = $this->conn->prepare("UPDATE oc_category_description SET name = :name, description = :description, meta_title = :meta_title, meta_description = :meta_description, meta_keyword = :meta_keyword where category_id = :category_id");
                    $stmt->bindValue(':category_id', $data['category_id']);
                    $stmt->bindValue(':name', $data['name']);
                    $stmt->bindValue(':description', $data['description']);
                    $stmt->bindValue(':meta_title', $data['meta_title']);
                    $stmt->bindValue(':meta_description', $data['meta_description']);
                    $stmt->bindValue(':meta_keyword', $data['meta_keyword']);
                    $stmt->execute();
                    $_SESSION['message'] = $this->messageBox("success","Category Successfully Updated !"); 
                }catch(PDOexception $e){
                    echo "<p>Database Error: $e </p>";
                }
        }

         public  function category_add($data,$img){ 
                try{
                   
                    $top = $data['top'] === "" ? "0" : $data['top'];                         
                    $stmt = $this->conn->prepare("INSERT INTO oc_category (image , top , `column` , 
                        sort_order , `status`, date_added, date_modified, parent_id) VALUES (:image , :top , :column , 
                        :sort_order , :status, NOW(), NOW(), '0' ) ");
                    $stmt->bindValue(':image', $img);
                    $stmt->bindValue(':top', $top);
                    $stmt->bindValue(':column', $data['column']);
                    $stmt->bindValue(':sort_order', $data['sort_order']);
                    $stmt->bindValue(':status', $data['status']);
                    $stmt->execute();
                    $lastId = $this->conn->lastInsertId();

                    $stmt = $this->conn->prepare("INSERT INTO oc_category_description (category_id, name, description, meta_title, meta_description, meta_keyword,language_id) VALUES (:category_id, :name, :description, :meta_title, :meta_description, :meta_keyword,'1')");
                    $stmt->bindValue(':category_id', $lastId);
                    $stmt->bindValue(':name', $data['name']);
                    $stmt->bindValue(':description', $data['description']);
                    $stmt->bindValue(':meta_title', $data['meta_title']);
                    $stmt->bindValue(':meta_description', $data['meta_description']);
                    $stmt->bindValue(':meta_keyword', $data['meta_keyword']);
                    $stmt->execute();

                    $stmt = $this->conn->prepare("INSERT INTO oc_category_to_store (category_id, store_id) VALUES (:category_id, 
                        '0')");
                    $stmt->bindValue(':category_id', $lastId);
                    $stmt->execute();

                    $_SESSION['message'] = $this->messageBox("success","Category Successfully Saved !"); 
                }catch(PDOexception $e){
                    echo "<p>Database Error: $e </p>";
                }
        }

        public  function category_delete($category_id){ 
                try{
                                         
                    $stmt = $this->conn->prepare("DELETE FROM oc_category where category_id = :category_id");
                    $stmt->bindValue(':category_id', $category_id);
                    $stmt->execute();

                    $stmt = $this->conn->prepare("DELETE FROM oc_category_description where category_id = :category_id");
                    $stmt->bindValue(':category_id', $category_id);
                    $stmt->execute();

                    $stmt = $this->conn->prepare("DELETE FROM oc_category_to_store where category_id = :category_id");
                    $stmt->bindValue(':category_id', $category_id);
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