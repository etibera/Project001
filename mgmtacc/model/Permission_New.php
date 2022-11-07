<?php
require_once "../include/database.php";

class Permission
{
  private $con;

  public function __construct()
  {
    $this->con = new Database();
    $this->con = $this->con->getmyDB();
  }

  public function get()
  {
    $result = array();

    $stmt = $this->con->prepare("SELECT OU.user_id AS id, OU.username, CONCAT(OU.firstname, ' ', OU.lastname) as fullName, GROUP_CONCAT(OP.page SEPARATOR ', ') as page FROM oc_user AS OU 
    INNER JOIN oc_permission_pages as OPP ON OPP.user_id = OU.user_id
    INNER JOIN oc_pages AS OP ON OP.page_id = OPP.user_pages GROUP BY OU.username,OU.user_id ORDER BY id DESC
    ");
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    foreach ($data as $row) {
      $result['data'][] = array(
        $row->id,
        $row->username,
        $row->fullName,
        $row->page,
        "<center>
        <button id={$row->id} name='show' type='button' class='btn btn-info'><i class='fa fa-eye' aria-hidden='true'></i></button>
        <button id={$row->id} name='update' type='button' class='btn btn-warning'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button>
        <button id={$row->id} name='delete' type='button' class='btn btn-danger'><i class='fa fa-trash' aria-hidden='true'></i></button>
        </center>"
      );
    }

    return $result;
  }

  public function getType()
  {
    $result = array();

    $stmt = $this->con->prepare("SELECT * FROM page_type ORDER BY order_number");
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    foreach ($data as $row) {
      $result[] = array(
        $row->id,
        $row->name
      );
    }

    return $result;
  }

  public function getPage($id)
  {
    $result = array();

    $stmt = $this->con->prepare("SELECT page_id as id, page, page_type_id as pageTypeId FROM oc_pages WHERE page_type_id = ? ORDER BY order_number");
    $stmt->execute([$id]);

    $data = $stmt->fetchAll();

    foreach ($data as $row) {
      $result[] = array(
        $row['id'],
        $row['page'],
        $row['pageTypeId']
      );
    }

    return $result;
  }

  public function insert()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      $used_symbols = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
      $salt = substr(str_shuffle($used_symbols), 0, 9);

      $firstName = $_POST['firstName'];
      if (!$firstName) return array("type" => "error", "message" => "Please input your firstname");
      $lastName = $_POST['lastName'];
      if (!$lastName) return array("type" => "error", "message" => "Please input your lastname");
      $userName = $_POST['userName'];
      if (!$userName) return array("type" => "error", "message" => "Please input your username");
      $email = $_POST['email'];
      if (!$email) return array("type" => "error", "message" => "Please input your email");
      $password = $_POST['password'];
      if (!$password) return array("type" => "error", "message" => "Please input your password");
      $cpassword = $_POST['cpassword'];
      if ($password != $cpassword || !$cpassword) {
        return array("type" => "error", "message" => "Please confirm your password");
      }
      if (!$_POST['page']) return array("type" => "error", "message" => "Please select permissions");

      $ip = $this->getIPAddress();

      $stmt = $this->con->prepare("INSERT INTO oc_user (user_group_id, username, password, salt, firstname, lastname, email, image, code, ip, status, date_added) VALUES
      (1, :username, :password, :salt, :firstname, :lastname, :email, '', '', :ip, 1, convert_tz(utc_timestamp(),'-08:00','+0:00'))");
      $stmt->bindValue(':salt', $salt);
      $stmt->bindValue(':firstname', $firstName);
      $stmt->bindValue(':lastname', $lastName);
      $stmt->bindValue(':username', $userName);
      $stmt->bindValue(':password', sha1($salt . sha1($salt . sha1($password))));
      $stmt->bindValue(':email', $email);
      $stmt->bindValue(':ip', $ip);

      try {
        $result = $stmt->execute();
        $id = $this->con->lastInsertId();
        if ($result) return array("type" => "success", "userId" => $id);
      } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
          return array("type" => "error", "message" => "Username is already exist");
        } else {
          return array("type" => "error", "message" => $e->getMessage());
        }
      }
    }
  }

  public function insertPermission()
  {
    $id = $_POST['id'];
    $page = $_POST['page'];

    foreach ($page as $pageId) {
      $stmt = $this->con->prepare('INSERT INTO oc_permission_pages (user_id, user_pages) VALUES (:id, :page)');
      $result = $stmt->execute([':id' => $id, ':page' => $pageId]);
    }

    if ($result) {
      return array("type" => "success", "message" => "User Added");
    } else return $result;
  }

  public function getSingleUser($id)
  {
    $result = array();

    $stmt = $this->con->prepare('SELECT firstname, lastname, username, email FROM oc_user WHERE user_id = :id');
    $stmt->execute([':id' => $id]);

    $result[] = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt2 = $this->con->prepare('SELECT user_pages FROM oc_permission_pages WHERE user_id = :id');
    $stmt2->execute([':id' => $id]);

    $result[] = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    return $result;
  }

  function delete($id)
  {
    $stmt = $this->con->prepare("DELETE FROM oc_user WHERE user_id = :id");
    $result = $stmt->execute([":id" => $id]);

    if ($result) {
      $stmt2 = $this->con->prepare("DELETE FROM oc_permission_pages WHERE user_id = :id");
      $result2 = $stmt2->execute([':id' => $id]);

      if ($result2) {
        return array("type" => "success", "message" => "User Deleted");
      } else {
        return $result2;
      }
    } else return $result;
  }

  function update($id)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      $password = $_POST['password'];
      $cpassword = $_POST['cpassword'];

      $used_symbols = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
      $salt = substr(str_shuffle($used_symbols), 0, 9);

      $sql = "UPDATE oc_user SET firstname = :firstname, lastname = :lastname, username = :username, email = :email";

      $firstName = $_POST['firstName'];
      if (!$firstName) return array("type" => "error", "message" => "Please input your firstname");
      $lastName = $_POST['lastName'];
      if (!$lastName) return array("type" => "error", "message" => "Please input your lastname");
      $userName = $_POST['userName'];
      if (!$userName) return array("type" => "error", "message" => "Please input your username");
      $email = $_POST['email'];
      if (!$email) return array("type" => "error", "message" => "Please input your email");
      if (!$_POST['page']) return array("type" => "error", "message" => "Please select permissions");

      if ($password || $cpassword) {
        if ($password == $cpassword) {
          $sql .= ", salt = :salt, password = :password  WHERE user_id = :id";
          $params = array(
            ':salt' => $salt,
            ':password' => sha1($salt . sha1($salt . sha1($password))),
            ':firstname' => $firstName,
            ':lastname' => $lastName,
            ':username' => $userName,
            ':email' => $email,
            ':id' => $id
          );
        } else {
          return array("type" => "error", "message" => "Please confirm your password");
        }
      } else {
        $sql .= " WHERE user_id = :id";
        $params = array(
          ':firstname' => $firstName,
          ':lastname' => $lastName,
          ':username' => $userName,
          ':email' => $email,
          ':id' => $id
        );
      }

      $stmt = $this->con->prepare($sql);

      try {
        $result = $stmt->execute($params);

        if ($result) {
          return array("type" => "success", "userId" => $id);
        } else return $result;
      } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
          return array("type" => "error", "message" => "Username is already exist");
        } else {
          return array("type" => "error", "message" => $e->getMessage());
        }
      }
    }
  }

  public function updatePermission()
  {
    $id = $_POST['id'];
    $page = $_POST['page'];

    $stmt = $this->con->prepare("DELETE FROM oc_permission_pages WHERE user_id = :id");
    $result = $stmt->execute([':id' => $id]);

    if ($result) {
      foreach ($page as $pageId) {
        $stmt2 = $this->con->prepare("INSERT INTO oc_permission_pages (user_id, user_pages) VALUES (:id, :page)");
        $result2 = $stmt2->execute([':id' => $id, ':page' => $pageId]);
      }

      if ($result2) {
        return array("type" => "success", "message" => "User Updated");
      } else return $result2;
    } else {
      return $result;
    }
  }

  function getIPAddress()
  {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip_address = $_SERVER['REMOTE_ADDR'];
    }
    return $ip_address;
  }
}
