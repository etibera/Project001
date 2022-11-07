<?php
require_once "../include/database.php";

class PageType
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

    $stmt = $this->con->prepare("SELECT * FROM page_type ORDER BY id DESC");
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($data) {
      foreach ($data as $row) {
        $result['data'][] = array(
          $row->id,
          $row->order_number,
          $row->name,
          "<center><button id={$row->id} name='update' type='button' class='btn btn-info'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button>
          </center>"
        );
      }
    } else {
      return array("data" => "");
    }

    return $result;
  }

  public function insert()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $typeName = $_POST["typeName"];
      $order = $_POST["order"];

      if (!$typeName) return array("type" => "error", "message" => "Please enter a name");
      if (!$order) return array("type" => "error", "message" => "Please enter the order number");

      $stmt = $this->con->prepare("INSERT INTO page_type (name, order_number) VALUES (:name, :order)");
      $result = $stmt->execute([":name" => $typeName, ":order" => $order]);

      if ($result) {
        return array("type" => "success", "message" => "Page Type Added");
      } else {
        return array("type" => "error", "message" => "Error");
      }
    }
  }

  public function update($id)
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $typeName = $_POST["typeName"];
      $order = $_POST["order"];

      if (!$typeName) return array("type" => "error", "message" => "Please enter a name");
      if (!$order) return array("type" => "error", "message" => "Please enter the order number");

      $stmt = $this->con->prepare("UPDATE page_type SET name = :name, order_number = :order WHERE id = :id");
      $result = $stmt->execute([":name" => $typeName, ":id" => $id, ":order" => $order]);

      if ($result) {
        return array("type" => "success", "message" => "Data Updated");
      } else {
        return array("type" => "error", "message" => "Error");
      }
    }
  }

  public function getSingle($id)
  {
    $stmt = $this->con->prepare("SELECT * FROM page_type WHERE id = :id");
    $stmt->execute([":id" => $id]);

    $result = $stmt->fetch();

    return $result;
  }
}
