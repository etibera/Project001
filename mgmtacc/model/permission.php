<?php
require_once "../include/database.php";
class permission{
        private $conn;   
        public function __construct()
        {
                $this->conn = new Database();
                $this->conn = $this->conn->getmyDB();
        }

	public function getpermission($id,$page) {
        
        $s = $this->conn->prepare("SELECT * from oc_permission_pages where user_id=:user_id and user_pages =:page ");
        $s->bindValue(':user_id', $id);
        $s->bindValue(':page', $page);
        $s->execute();
        $status = $s->fetchAll(PDO::FETCH_ASSOC);
        return $status;
   
    }

    public function getuserdetails($id) {
        
        $s = $this->conn->prepare("SELECT * from oc_user where user_id=:user_id ");
        $s->bindValue(':user_id', $id);
        
        $s->execute();
        $status = $s->fetch(PDO::FETCH_ASSOC);
        return $status;
   
    }

    public  function getuser($fname,$lname,$user) {
        $sql="";
        $sql1="";
        $sql2="";
        $sql3="";
        if($fname != ''){
            $sql=" and firstname LIKE :fname";
        }
        if($lname != ''){
            $sql1=" and lastname LIKE :lname";
        } 
        if($user != ''){
            $sql1=" and username LIKE :user";
        }
       
                
        $stmt = $this->conn->prepare("SELECT * from oc_user where user_id is not null ".$sql.$sql1.$sql2.$sql3." ");

                 if(!empty($fname)) {
                   $stmt->bindValue(':fname', '%'.$fname.'%');
                }
                 if(!empty($lname)) {
                   $stmt->bindValue(':lname', '%'.$lname.'%');
                }
                 if(!empty($user)) {
                   $stmt->bindValue(':user', '%'.$user.'%');
                }
                      
              
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);       
                return $row;
    }

    public function getaccess1($userid) {
        
        $s = $this->conn->prepare("SELECT pr.*,p.page from oc_permission_pages pr INNER JOIN oc_pages p on pr.user_pages=p.page_id where pr.user_id=:user_id ");
        $s->bindValue(':user_id', $userid);
        
        $s->execute();
        $status = $s->fetchAll(PDO::FETCH_ASSOC);
        return $status;
   
    }

     public function getaccess2($userid) {
        
        $s = $this->conn->prepare("SELECT user_pages from oc_permission_pages where user_id=:user_id ");
        $s->bindValue(':user_id', $userid);
        
        $s->execute();
        $status = $s->fetchAll(PDO::FETCH_ASSOC);
        return $status;
   
    }


    public function adduser($user,$pass,$fname,$lname,$email) {
         try{
            $used_symbols = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
            $salt= substr(str_shuffle($used_symbols), 0, 9);


            $stmt = $this->conn->prepare("INSERT into oc_user (user_group_id,username,password,salt,firstname,lastname,email,image,code,ip,status,date_added) values ('1',:user,:pass,:salt,:fname,:lname,:email,'','',:ip,'1',convert_tz(utc_timestamp(),'-08:00','+0:00'))");
            $stmt->bindValue(':user',$user);
            $stmt->bindValue(':pass',sha1($salt . sha1($salt . sha1($pass))));
            $stmt->bindValue(':salt',$salt);
            $stmt->bindValue(':fname',$fname);
            $stmt->bindValue(':lname',$lname);
            $stmt->bindValue(':email',$email);
            $stmt->bindValue(':ip',$_SERVER['REMOTE_ADDR']);
            $stmt->execute();

            $lastId = $this->conn->lastInsertId();

            $status=$lastId;
        }catch(PDOexception $e){
            echo "<p>Database Error: $e </p>";
            $status = "error";
        }
        return $status;
    }

    public function addpermission($id,$data_chk) {
         try{
           
            foreach ($data_chk as $pages) 
            {

            $stmt = $this->conn->prepare("INSERT into oc_permission_pages (user_id,user_pages) values (:id,:page)");
            $stmt->bindValue(':id',$id);
            $stmt->bindValue(':page',$pages['id']);
         
            $stmt->execute();

            }
            

            $status="200";
        }catch(PDOexception $e){
            echo "<p>Database Error: $e </p>";
            $status = "error";
        }
        return $status;
    }

    public function updateuser($user,$pass,$fname,$lname,$email,$uid) {
         try{


            $sql="";
            if($pass != ''){
                $sql="password=:pass,salt=:salt,";
            }


            $used_symbols = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
            $salt= substr(str_shuffle($used_symbols), 0, 9);


            $stmt = $this->conn->prepare("UPDATE oc_user set username=:user,".$sql."firstname=:fname,lastname=:lname,email=:email where user_id=:uid ");
            $stmt->bindValue(':user',$user);
            if(!empty($pass)) {
                $stmt->bindValue(':pass',sha1($salt . sha1($salt . sha1($pass))));
                   
                $stmt->bindValue(':salt',$salt);
                }
            $stmt->bindValue(':fname',$fname);
            $stmt->bindValue(':lname',$lname);
            $stmt->bindValue(':email',$email);
            $stmt->bindValue(':uid',$uid);
            
            $stmt->execute();

            $status="success";
        }catch(PDOexception $e){
            echo "<p>Database Error: $e </p>";
            $status = "error".$e;
        }
        return $status;
    }
    
    public function updatepermission($id,$data_chk) {
         try{

            $stmt1 = $this->conn->prepare("DELETE from oc_permission_pages where user_id=:id");
            $stmt1->bindValue(':id',$id);
            
         
            $stmt1->execute();
           
            foreach ($data_chk as $pages) 
            {

            $stmt = $this->conn->prepare("INSERT into oc_permission_pages (user_id,user_pages) values (:id,:page)");
            $stmt->bindValue(':id',$id);
            $stmt->bindValue(':page',$pages['id']);
         
            $stmt->execute();

            }
            

            $status="200";
        }catch(PDOexception $e){
            echo "<p>Database Error: $e </p>";
            $status = "error";
        }
        return $status;
    }

     public function deleteuser($id) {
         try{

            $stmt1 = $this->conn->prepare("DELETE from oc_permission_pages where user_id=:id");
            $stmt1->bindValue(':id',$id);
            
         
            $stmt1->execute();

            $stmt = $this->conn->prepare("DELETE from oc_user where user_id=:id");
            $stmt->bindValue(':id',$id);
            
         
            $stmt->execute();
           
           
            

            $status="200";
        }catch(PDOexception $e){
            echo "<p>Database Error: $e </p>";
            $status = "error";
        }
        return $status;
    }
   

}