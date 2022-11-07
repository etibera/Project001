<?php
require_once '../init.php';
class Review {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    function __destruct(){
        $this->conn = null;
    }
      public function getReviewCount($type = null, $order_number = null, $product_id = null)
      {
        $dataParams = array(':customer_id' => $_GET['customerId'] ?? 0);
    
        $sql = "SELECT COUNT(DISTINCT oop.order_number, oop.product_id) AS review_count FROM oc_order_product AS oop
        INNER JOIN oc_order AS oo ON oo.order_id = oop.order_id
        INNER JOIN oc_order_history AS ooh ON ooh.order_number = oop.order_number
        INNER JOIN order_status_per_store AS os ON os.order_number = oop.order_number AND oop.review_id = 0 AND os.order_status_id = 49
        WHERE oo.customer_id = :customer_id";
    
        if ($type === "orderNumber") {
          $sql .= " AND oop.order_number = :order_number";
          $dataParams = array_merge($dataParams, [':order_number' => $order_number]);
        } else if ($type === "product") {
          $sql .= " AND oop.order_number = :order_number AND oop.product_id = :product_id";
          $dataParams = array_merge($dataParams, [':order_number' => $order_number, ':product_id' => $product_id]);
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($dataParams);
    
        return $stmt->fetchObject()->review_count;
      }
      public function getReviewDetail(){
        $result = array();
        $userId = $_GET['customer_id'];
        $order_id = $_GET['order_id'];
        $product_id = $_GET['product_id'];
    
        if (!empty($this->reviewExist($userId, $product_id))) {
          $result = array_merge($result, ['exist' => $this->reviewExist($userId, $product_id)]);
        }
    
        $stmt = $this->conn->prepare("SELECT oo.customer_id, oop.product_id, oop.name AS product_name, CONCAT('img/',op.image) AS product_image, os.seller_id, sb.id as branch_id, sb.b_name AS branch_name, CONCAT('img/',sb.branch_logo) AS branch_image
        FROM oc_order_product AS oop 
        INNER JOIN oc_product AS op ON op.product_id = oop.product_id
        INNER JOIN oc_seller AS os ON os.seller_id = oop.seller_id 
        INNER JOIN seller_branch AS sb ON sb.id = oop.branch_id 
        INNER JOIN store_orders AS so ON so.order_id = oop.order_id AND so.branch_id = oop.branch_id
        INNER JOIN oc_order AS oo ON oo.order_id = oop.order_id
        WHERE oop.order_number = :order_id AND oop.product_id = :product_id LIMIT 1");
        $stmt->execute([':order_id' => $order_id, ':product_id' => $product_id]);
    
        $result = array_merge($result, $stmt->fetchAll(PDO::FETCH_ASSOC)[0]);
        
        if (intval($result['customer_id'] ?? 0) != intval($userId)) {
          return array('type' => 'error', 'message' => 'Invalid Credentials');
        } else {
          return $result;
        }
      }
      public function reviewExist($userId, $product_id)
      {
        $stmt = $this->conn->prepare("SELECT * FROM oc_review WHERE product_id = :product_id AND customer_id = :customer_id");
        $stmt->execute([':product_id' => $product_id, ':customer_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0] ?? "";
      }
      public function get($userId, $order_id, $product_id)
      {
        $result = array();
        // $userId = $_GET['customer_id'];
        // $order_id = $_GET['order_id'];
        // $product_id = $_GET['product_id'];
    
        if (!empty($this->reviewExist($userId, $product_id))) {
          $result = array_merge($result, ['exist' => $this->reviewExist($userId, $product_id)]);
        }
    
        $stmt = $this->conn->prepare("SELECT oo.customer_id, oop.product_id, oop.name AS product_name, CONCAT('img/',op.image) AS product_image, os.seller_id, sb.id as branch_id, sb.b_name AS branch_name, CONCAT('img/',sb.branch_logo) AS branch_image
        FROM oc_order_product AS oop 
        INNER JOIN oc_product AS op ON op.product_id = oop.product_id
        INNER JOIN oc_seller AS os ON os.seller_id = oop.seller_id 
        INNER JOIN seller_branch AS sb ON sb.id = oop.branch_id 
        INNER JOIN store_orders AS so ON so.order_id = oop.order_id AND so.branch_id = oop.branch_id
        INNER JOIN oc_order AS oo ON oo.order_id = oop.order_id
        WHERE oop.order_number = :order_id AND oop.product_id = :product_id LIMIT 1");
        $stmt->execute([':order_id' => $order_id, ':product_id' => $product_id]);
    
        $result = array_merge($result, $stmt->fetchAll(PDO::FETCH_ASSOC)[0]);
    
        if (intval($result['customer_id'] ?? 0) != intval($userId)) {
          return array('type' => 'error', 'message' => 'Invalid Credentials');
        } else {
          return $result;
        }
      }
      public function submitReview(){
        // return pathinfo($_FILES['image']['tmp_name'][0], PATHINFO_EXTENSION);;
        // return $_FILES['image'];
        $userId = $_POST['customer_id'];
        $order_id = $_POST['order_id'];
        $product_id = $_POST['product_id'];
        $rating = $_POST['rating'];
        $review = $_POST['review'];
    
        $dataParams = array();
    
        $stmt = $this->conn->prepare("SELECT customer_id, CONCAT(firstname, ' ', lastname) AS full_name FROM oc_customer WHERE customer_id = :userId LIMIT 1");
        $stmt->execute([':userId' => $userId]);
        $customer =  $stmt->fetchObject();
    
        if (!empty($this->reviewExist($userId, $product_id))) {
          $sql = "UPDATE oc_review SET rating = :rating, `text` = :text, date_modified = convert_tz(utc_timestamp(),'-08:00','+0:00') WHERE review_id = :review_id";
          $dataParams = array_merge($dataParams, [':rating' => $rating, ':text' => $review, ':review_id' => $this->reviewExist($userId, $product_id)['review_id']]);
        } else {
          $sql = "INSERT INTO oc_review (product_id, customer_id, branch_id, author, `text`, rating, `status`, date_added, date_modified) VALUES (:product_id, :customer_id, :branch_id, :author, :text, :rating, 1, convert_tz(utc_timestamp(),'-08:00','+0:00'), convert_tz(utc_timestamp(),'-08:00','+0:00'))";
          $dataParams = array_merge($dataParams, [':product_id' => $this->get($userId, $order_id, $product_id)['product_id'], ':customer_id' => $customer->customer_id, ':branch_id' => $this->get($userId, $order_id, $product_id)['branch_id'], ':author' => $customer->full_name, ':text' => $review, ':rating' => $rating]);
        }
    
        $stmt = $this->conn->prepare($sql);
    
        try {
          $stmt->execute($dataParams);
          $lastId = $this->reviewExist($userId, $product_id)['review_id'];
          $stmt = $this->conn->prepare("UPDATE oc_order_product SET review_id = :review_id WHERE order_number = :order_number AND product_id = :product_id");
          $stmt->execute([':review_id' => $lastId, ':order_number' => $order_id, ':product_id' => $product_id]);
        } catch (PDOException $e) {
          return array('type' => 'error', 'message' => $e->getMessage());
        }
    
        if (isset($_FILES['image'])) {
    
          $path = isset($_SERVER['HTTPS']) ? '/home/pesoappdadmin/public_html/img/review/' : "/Applications/XAMPP/xamppfiles/htdocs/peso-web-new/img/review/";
    
          $stmt = $this->conn->prepare("SELECT * FROM oc_review_image WHERE review_id = :review_id");
          $stmt->execute([':review_id' => $lastId]);
          $images = $stmt->fetchAll(PDO::FETCH_OBJ);
    
          if (!empty($images)) {
            $stmt = $this->conn->prepare("DELETE FROM oc_review_image WHERE review_id = :review_id");
            $stmt->execute([':review_id' => $lastId]);
            foreach ($images as $image) {
              unlink((str_replace('review/', '', $path) . $image->filename));
            }
          }
    
          for ($i = 0; $i < count($_FILES['image']['name']); $i++) {
    
            $extension = pathinfo($_FILES['image']['name'][$i], PATHINFO_EXTENSION);
            $validExtension = array("jpg", "jpeg", "png");
    
            if (in_array(strtolower($extension), $validExtension)) {
    
              $newFileName = md5(rand()) . "." . $extension;
              $newPath = $path . $newFileName;
    
              move_uploaded_file($_FILES['image']['tmp_name'][$i], $newPath);
              $stmt = $this->conn->prepare("INSERT INTO oc_review_image (review_id, `filename`, date_added) VALUES (:review_id, :filename, convert_tz(utc_timestamp(),'-08:00','+0:00'))");
              try {
                $stmt->execute([':review_id' => $lastId, ':filename' => 'review/' . $newFileName]);
              } catch (PDOException $e) {
                return array('type' => 'error', 'message' => $e->getMessage());
              }
            } else {
              return array("type" => "error", "message" => "Invalid image extension");
            }
          }
        }
    
        return array('type' => 'success', 'message' => 'Thank you for your review!');
      }
}
$review = new Review();