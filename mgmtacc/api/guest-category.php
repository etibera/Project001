<?php
require_once "../../include/database.php";
require_once "../../model/ImageResizer.php";
require_once "../../model/Image.php";
class GuestCategory {
    function __construct()
    {
        $this->conn = (new Database())->getmyDB();
    }
    public function url(){
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){
            return 'https://pesoapp.ph/img';
        }else{
            return 'http://localhost/peso-web-new/img';
        }
    }
    public function delete($id){
        global $image;
        $stmt = $this->conn->prepare('SELECT * FROM guest_category_list WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $img = $stmt->fetch()['image'];

        $target_path = $image->dir_image() .'guest-category/'. $img;
        if(!empty($img)){
            if(file_exists($target_path)){
                unlink($target_path);
            }
        }

        $stmt1 = $this->conn->prepare("SELECT * FROM customer_guest_category WHERE guest_category_id = :id");
        $stmt1->bindValue(':id', $id);
        $stmt1->execute();
        if($stmt1->rowCount() > 0){
            return array('message' => 'You cannot delete this category because it\'s in used ', 'error' => 1);
        }else{
            $stmt = $this->conn->prepare("DELETE FROM guest_category_list WHERE id = :id");
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return array('message' => 'Successfully Deleted', 'error' => 0);
        }
    }
    public function setStatus($status, $id){
        $stmt = $this->conn->prepare("UPDATE guest_category_list SET `status` = :status WHERE id = :id");
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
    public function all(){
        global $image;
        $data = array();
        $stmt = $this->conn->prepare('SELECT * FROM guest_category_list ORDER BY id DESC');
        $stmt->execute();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $value){
            //$img = $image->resize($value['image'], 200, 200, 'popup_ads');
            $img = $this->url() . '/guest-category/' .$value['image'];
            $data[] = array(
                'id' => $value['id'],
                'image' => $img,
                'name' => $value['name'],
                'status' => $value['status']
            );
        }
        return $data;
    }
    public function edit($input, $file){
        global $image;
        $stmt = $this->conn->prepare('SELECT * FROM guest_category_list where id = :id');
        $stmt->bindValue(':id', $input['id']);
        $stmt->execute();
        $fetchImage = $stmt->fetch()['image'];
        $selectedImage = '';
        $input['image'] = '';
        if($file['image_file']['size'] > 0){
            $ext = pathinfo($file['image_file']['name'], PATHINFO_EXTENSION);
            $imageNameEncypted = $this->token(32) . "." .$ext;
            $targetFile = $image->dir_image() . "guest-category/" . $imageNameEncypted;
            $lastId = 0;
            $imageUrl = '';
            $oldImage = $image->dir_image() . 'guest-category/'. $fetchImage;
            if(file_exists($oldImage)){
                unlink($oldImage);
            }
            if(move_uploaded_file($_FILES['image_file']['tmp_name'], $targetFile)){
                $input['image'] = $imageNameEncypted;
            }
        }else{
            $input['image'] = $fetchImage;
        }

        $stmt = $this->conn->prepare("UPDATE guest_category_list SET `image` = :image, name = :name WHERE id = :id");
        $stmt->bindValue(':image', $input['image']);
        $stmt->bindValue(':name', $input['name']);
        $stmt->bindValue(':id', $input['id']);
        $stmt->execute();
    }
    public function add($input, $file){
        global $image;
        $ext = pathinfo($file['image_file']['name'], PATHINFO_EXTENSION);
        $imageNameEncypted = $this->token(32) . "." .$ext;
        $targetFile = $image->dir_image() . "guest-category/" . $imageNameEncypted;
        $lastId = 0;
        $imageUrl = '';
        if(move_uploaded_file($_FILES['image_file']['tmp_name'], $targetFile)){
            $stmt = $this->conn->prepare("INSERT INTO guest_category_list SET `image` = :image, name = :name, `status` = 1, date_added = NOW() ");
            $stmt->bindValue(':image', $imageNameEncypted);
            $stmt->bindValue(':name', $input['name']);
            $stmt->execute();
            $lastId = $this->conn->lastInsertId();
        }
        return array(
                    'id' => $lastId, 
                    'name' => $input['name'],
                    'image' => $image->url('guest-category') . $imageNameEncypted,
                    'status' => 1
                );
    }
    public function token($length = 32) {
        // Create token to login with
        $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        
        $token = '';
        
        for ($i = 0; $i < $length; $i++) {
            $token .= $string[mt_rand(0, strlen($string) - 1)];
        }	
        
        return $token;
    }
}
$gc = new GuestCategory();
if(isset($_GET['action'])){
    $source = $_GET['action'];
}else{
    $source = "";
}
$data = array();
switch($source){
    case 'add':
        if($_POST['id'] != 0){
            $data = $gc->edit($_POST, $_FILES);
        }else{
            $data = $gc->add($_POST, $_FILES);
        }
        echo json_encode($data);
    break;
    case 'all':
        $data = $gc->all();
        echo json_encode($data);
    break;
    case 'setStatus':
        $gc->setStatus($_POST['status'], $_POST['id']); 
    break;
    case 'delete':
        echo json_encode($gc->delete($_POST['id']));
    break;
    default:
    break;
}