<?php
require_once "../include/database.php";
class Banner {
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
        public  function banner_list(){              
                

                $stmt = $this->conn->prepare("SELECT *, CASE WHEN status = 1 THEN 'Enabled' ELSE 'Disabled' END as stats from oc_banner");
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $row;
        }

        public  function banner_details($banner_id){              
                
                $stmt = $this->conn->prepare("SELECT b.* from oc_banner b where b.banner_id = :banner_id");
                $stmt->bindValue(':banner_id', $banner_id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return $row;
        }

        public  function banner_images($banner_id){              
                
                $stmt = $this->conn->prepare("SELECT bi.*, bid.* , b.banner_id as b_id, bi.banner_image_id as bi_id from oc_banner b INNER JOIN oc_banner_image bi ON b.banner_id = bi.banner_id INNER JOIN oc_banner_image_description bid ON bi.banner_image_id = bid.banner_image_id where b.banner_id = :banner_id order by bi.banner_image_id");
                $stmt->bindValue(':banner_id', $banner_id);
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $row;
        }

        public  function banner_update($data){ 
                try{            
                    $stmt = $this->conn->prepare("DELETE from oc_banner_image where  banner_id = :banner_id");
                    $stmt->bindValue(':banner_id', $data['banner_id']);
                    $stmt->execute();

                    $stmt = $this->conn->prepare("DELETE from oc_banner_image_description where   banner_id = :banner_id");
                    $stmt->bindValue(':banner_id', $data['banner_id']);
                    $stmt->execute();

                    $stmt = $this->conn->prepare("UPDATE oc_banner set name = :name, status = :status where   banner_id = :banner_id");
                    $stmt->bindValue(':banner_id', $data['banner_id']);
                    $stmt->bindValue(':name', $data['name']);
                    $stmt->bindValue(':status', $data['status']);
                    $stmt->execute();

                    for($key = 0 ; $key < count($data['title']); $key++) {

                        $stmt = $this->conn->prepare("INSERT INTO oc_banner_image set banner_id = :banner_id, image = :image, `link` = :link, sort_order = :sort_order");
                        $stmt->bindValue(':banner_id', $data['banner_id']);
                        $stmt->bindValue(':image', $data['image_path'][$key]);
                        $stmt->bindValue(':link', $data['link'][$key]);
                        $stmt->bindValue(':sort_order', $data['sort_order'][$key]);
                        $stmt->execute();
                        $banner_image_id = $this->conn->lastInsertId();                  

                        $stmt = $this->conn->prepare("INSERT INTO oc_banner_image_description set banner_image_id = :banner_image_id, banner_id = :banner_id, title= :title, language_id= 1");
                        $stmt->bindValue(':banner_image_id', $banner_image_id);
                        $stmt->bindValue(':banner_id', $data['banner_id']);
                        $stmt->bindValue(':title', $data['title'][$key]);
                        $stmt->execute();
                    
                    }
                        

                    unset($_SESSION['name']);
                    unset($_SESSION['status']);
                    $_SESSION['message'] = $this->messageBox("success","Banner Successfully Updated !"); 
                   
                }catch(PDOexception $e){
                    echo "<p>Database Error: $e </p>";
                }
        }

         public  function banner_add($data){ 
                try{            
                    $stmt = $this->conn->prepare("DELETE from oc_banner_image where  banner_id = :banner_id");
                    $stmt->bindValue(':banner_id', $data['banner_id']);
                    $stmt->execute();

                    $stmt = $this->conn->prepare("DELETE from oc_banner_image_description where   banner_id = :banner_id");
                    $stmt->bindValue(':banner_id', $data['banner_id']);
                    $stmt->execute();

                    $stmt = $this->conn->prepare("INSERT INTO oc_banner set name = :name, status = :status");
                    $stmt->bindValue(':name', $data['name']);
                    $stmt->bindValue(':status', $data['status']);
                    $stmt->execute();
                    $banner_id = $this->conn->lastInsertId();

                    for($key = 0 ; $key < count($data['title']); $key++) {

                        $stmt = $this->conn->prepare("INSERT INTO oc_banner_image set banner_id = :banner_id, image = :image, `link` = :link, sort_order = :sort_order");
                        $stmt->bindValue(':banner_id', $banner_id);
                        $stmt->bindValue(':image', $data['image_path'][$key]);
                        $stmt->bindValue(':link', $data['link'][$key]);
                        $stmt->bindValue(':sort_order', $data['sort_order'][$key]);
                        $stmt->execute();
                        $banner_image_id = $this->conn->lastInsertId();                  

                        $stmt = $this->conn->prepare("INSERT INTO oc_banner_image_description set banner_image_id = :banner_image_id, banner_id = :banner_id, title= :title, language_id= 1");
                        $stmt->bindValue(':banner_image_id', $banner_image_id);
                        $stmt->bindValue(':banner_id', $banner_id);
                        $stmt->bindValue(':title', $data['title'][$key]);
                        $stmt->execute();
                    
                    }
                        

                    unset($_SESSION['name']);
                    unset($_SESSION['status']);
                    $_SESSION['message'] = $this->messageBox("success","Banner Successfully Updated !"); 
                   
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

        public  function banner_delete($banner_id){ 
                try{
                                         
                    $stmt = $this->conn->prepare("DELETE FROM oc_banner where banner_id = :banner_id");
                    $stmt->bindValue(':banner_id', $banner_id);
                    $stmt->execute();

                    $stmt = $this->conn->prepare("DELETE FROM oc_banner_image_description where banner_id = :banner_id");
                    $stmt->bindValue(':banner_id', $banner_id);
                    $stmt->execute();

                    $stmt = $this->conn->prepare("DELETE FROM oc_banner_image where banner_id = :banner_id");
                    $stmt->bindValue(':banner_id', $banner_id);
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