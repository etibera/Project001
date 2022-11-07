<?php
require_once "../include/database.php";
class User{
	
        private $conn;
        public function __construct()
        {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
        }
    public  function verify_user($username, $password){
        
        $stmt =  $this->conn->prepare("SELECT * FROM  oc_user WHERE username = :username AND password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1(:password))))) OR password = MD5(:password) LIMIT 1");
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':password', $password);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return !empty($row) ? $row: false;
    }

    public function getperm($id) {
        
        $s = $this->conn->prepare("SELECT * from oc_permission_pages where user_id=:user_id ");
        $s->bindValue(':user_id', $id);
        $s->execute();
        $status = $s->fetchAll(PDO::FETCH_ASSOC);
        return $status;
   
    }

}

