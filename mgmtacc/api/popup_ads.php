<?php
require_once "../../include/database.php";
require_once "./ImageResizer.php";
class PopupAds {
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
        $stmt = $this->conn->prepare('SELECT * FROM popup_ads WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $img = $stmt->fetch()['image'];

        $target_path = $image->dir_image() .'popup_ads/'. $img;
        if(file_exists($target_path)){
            unlink($target_path);
        }
        $stmt = $this->conn->prepare("DELETE FROM popup_ads WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
    public function setStatus($status, $id){
        $stmt = $this->conn->prepare("UPDATE popup_ads SET `status` = :status WHERE id = :id");
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
    public function all(){
        global $image;
        $data = array();
        $stmt = $this->conn->prepare('SELECT * FROM popup_ads ORDER BY id DESC');
        $stmt->execute();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $value){
            //$img = $image->resize($value['image'], 200, 200, 'popup_ads');
            $img = $this->url() . '/popup_ads/' .$value['image'];
            $data[] = array(
                'id' => $value['id'],
                'image' => $img,
                'web_url' => $value['web_url'],
                'webmobile_url' => $value['webmobile_url'],
                'mobile_url' => $value['mobile_url'],
                'status' => $value['status'],
                'position' => $value['position']
            );
        }
        return $data;
    }
    public function edit($input, $file){
        global $image;
        $stmt = $this->conn->prepare('SELECT * FROM popup_ads where id = :id');
        $stmt->bindValue(':id', $input['id']);
        $stmt->execute();
        $fetchImage = $stmt->fetch()['image'];
        $selectedImage = '';
        $input['image'] = '';
        if($file['image_file']['size'] > 0){
            $ext = pathinfo($file['image_file']['name'], PATHINFO_EXTENSION);
            $imageNameEncypted = $this->token(32) . "." .$ext;
            $targetFile = $image->dir_image() . "popup_ads/" . $imageNameEncypted;
            $lastId = 0;
            $imageUrl = '';
            $oldImage = $image->dir_image() . 'popup_ads/'. $fetchImage;
            if(file_exists($oldImage)){
                unlink($oldImage);
            }
            if(move_uploaded_file($_FILES['image_file']['tmp_name'], $targetFile)){
                $input['image'] = $imageNameEncypted;
            }
        }else{
            $input['image'] = $fetchImage;
        }

        $stmt = $this->conn->prepare("UPDATE popup_ads SET `image` = :image, web_url = :web_url, webmobile_url = :webmobile_url, mobile_url = :mobile_url, position = :position WHERE id = :id");
        $stmt->bindValue(':image', $input['image']);
        $stmt->bindValue(':web_url', $input['web_url']);
        $stmt->bindValue(':webmobile_url', $input['webmobile_url']);
        $stmt->bindValue(':mobile_url', $input['mobile_url']);
        $stmt->bindValue(':position', $input['position']);
        $stmt->bindValue(':id', $input['id']);
        $stmt->execute();
    }
    public function add($input, $file){
        global $image;
        $ext = pathinfo($file['image_file']['name'], PATHINFO_EXTENSION);
        $imageNameEncypted = $this->token(32) . "." .$ext;
        $targetFile = $image->dir_image() . "popup_ads/" . $imageNameEncypted;
        $lastId = 0;
        $imageUrl = '';
        if(move_uploaded_file($_FILES['image_file']['tmp_name'], $targetFile)){
            $stmt = $this->conn->prepare("INSERT INTO popup_ads SET `image` = :image, web_url = :web_url, webmobile_url = :webmobile_url, mobile_url = :mobile_url, position = :position, `status` = 1, date_added = NOW() ");
            $stmt->bindValue(':image', $imageNameEncypted);
            $stmt->bindValue(':web_url', $input['web_url']);
            $stmt->bindValue(':webmobile_url', $input['webmobile_url']);
            $stmt->bindValue(':mobile_url', $input['mobile_url']);
            $stmt->bindValue(':position', $input['position']);
            $stmt->execute();
            $lastId = $this->conn->lastInsertId();
            //$imageUrl = $image->resize($imageNameEncypted, 400, 400, 'popup_ads');
        }
        // $stmt1 = $this->conn->prepare('SELECT `image` FROM popup_ads WHERE id = :id');
        // $stmt1->bindValue(':id', $lastId);
        // $stmt1->execute();
        // $imageUrl = $stmt1->fetch()['image'];
        // $imageUrl = $image->resize($imageNameEncypted, 400, 400, 'popup_ads');
        return array(
                    'id' => $lastId, 
                    'web_url' => $input['web_url'],
                    'webmobile_url' => $input['webmobile_url'],
                    'mobile_url' => $input['mobile_url'],
                    'image' => $image->url('popup_ads') . $imageNameEncypted
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
$popups = new PopupAds();
if(isset($_GET['action'])){
    $source = $_GET['action'];
}else{
    $source = "";
}
$data = array();
switch($source){
    case 'add_popup':
        if($_POST['id'] != 0){
            $data = $popups->edit($_POST, $_FILES);
        }else{
            $data = $popups->add($_POST, $_FILES);
        }
        echo json_encode($data);
    break;
    case 'popup_ads':
        $data = $popups->all();
        echo json_encode($data);
    break;
    case 'setStatus':
        $popups->setStatus($_POST['status'], $_POST['id']); 
    break;
    case 'delete':
        $popups->delete($_POST['id']);
    break;
    default:
    break;
}