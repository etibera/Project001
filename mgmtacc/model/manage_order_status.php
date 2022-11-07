<?php
require_once "../include/database.php";
class OrderStatus
{
  private $conn;
  public function __construct()
  {
    $this->conn = new Database();
    $this->conn = $this->conn->getmyDB();
  }
  public function GetOrderStatus()
  {
    $s = $this->conn->prepare(" SELECT *,
    CASE WHEN type=0 THEN 'Admin' ELSE 'Seller' END AS typename,
    CASE
    WHEN status_type = 1 THEN 'New Order'
    WHEN status_type = 2 THEN 'Order Verification'
    WHEN status_type = 3 THEN 'Shipment Out'
    WHEN status_type = 4 THEN 'Order Received'
    END AS status_type_name
    FROM oc_order_status ORDER BY `name`");
    $s->execute();
    $status = $s->fetchAll(PDO::FETCH_ASSOC);
    return $status;
  }
  public function DeleteOrderStatus($id)
  {
    try {
      $stmt = $this->conn->prepare("DELETE FROM oc_order_status WHERE order_status_id=:order_status_id");
      $stmt->bindValue(':order_status_id', $id);
      $stmt->execute();
      return "Successfully Deleted.";
    } catch (PDOexception $e) {
      return $e;
    }
  }
  public function AddOrderStaus($name, $type, $status_type)
  {
    try {
      $stmt = $this->conn->prepare("INSERT INTO oc_order_status SET name=:name,type=:type,language_id=1,status_type=:status_type");
      $stmt->bindValue(':name', $name);
      $stmt->bindValue(':type', $type);
      $stmt->bindValue(':status_type', $status_type);
      $stmt->execute();
      return "Order Status Successfully Added.";
    } catch (PDOexception $e) {
      return $e;
    }
  }
  public function UpdateOrderStaus($name, $type, $id, $status_type)
  {
    try {
      $stmt = $this->conn->prepare("UPDATE oc_order_status SET name=:name,type=:type,status_type=:status_type WHERE order_status_id=:id");
      $stmt->bindValue(':name', $name);
      $stmt->bindValue(':type', $type);
      $stmt->bindValue(':id', $id);
      $stmt->bindValue(':status_type', $status_type);
      $stmt->execute();
      return "Order Status Successfully Updated.";
    } catch (PDOexception $e) {
      return $e;
    }
  }
}
