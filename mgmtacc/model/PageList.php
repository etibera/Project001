<?php
require_once '../include/database.php';

class PageList
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

    $stmt = $this->con->prepare("SELECT OP.page_id AS id, OP.page AS pageName, PT.name AS pageType, OP.order_number as orderNumber, OP.page_link AS pageLink FROM oc_pages AS OP INNER JOIN page_type AS PT ON PT.id = OP.page_type_id ORDER BY PT.order_number, OP.order_number");
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($data) {
      foreach ($data as $row) {
        $result['data'][] = array(
          $row->id,
          $row->orderNumber,
          $row->pageName,
          $row->pageLink,
          $row->pageType,
          "<center><button id={$row->id} name='update' type='button' class='btn btn-info'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button>
          </center>"
        );
      }

      return $result;
    } else {
      return array("data" => "");
    }
  }

  public function getType()
  {
    $result = array();

    $stmt = $this->con->prepare("SELECT * FROM page_type");
    $stmt->execute();

    $data = $stmt->fetchAll();

    foreach ($data as $row) {
      $result[] = array(
        "<option value={$row['id']}>{$row['name']}</option>"
      );
    }

    return $result;
  }

  public function getId()
  {
    $stmt = $this->con->prepare("SELECT page_id FROM oc_pages ORDER BY page_id DESC LIMIT 1");
    $stmt->execute();

    return $stmt->fetchColumn();
  }

  public function insert()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $pageName = $_POST["pageName"];
      $pageType = $_POST["pageType"];
      $orderNumber = $_POST["orderNumber"];
      $link = $_POST["link"];

      if (!$pageName) return array("type" => "error", "message" => "Please enter a page name");
      if (!$orderNumber) return array("type" => "error", "message" => "Please enter the order number");
      if (!$link) return array("type" => "error", "message" => "Please enter the link");


      $stmt = $this->con->prepare("INSERT INTO oc_pages (page_id, page, page_type_id, order_number, page_link) VALUES (:pageId, :page, :pageType, :orderNumber, :link)");

      $stmt->bindValue(':pageId', $this->getId() + 1);
      $stmt->bindValue(':page', $pageName);
      $stmt->bindValue(':pageType', $pageType);
      $stmt->bindValue(':orderNumber', $orderNumber);
      $stmt->bindValue(':link', $link);

      $result = $stmt->execute();

      if ($result) {
        return array("type" => "success", "message" => "Page Added");
      } else {
        return array("type" => "error", "message" => "Error");
      }
    }
  }

  public function update($id)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $pageName = $_POST["pageName"];
      $pageType = $_POST["pageType"];
      $orderNumber = $_POST["orderNumber"];
      $link = $_POST["link"];

      if (!$pageName) return array("type" => "error", "message" => "Please enter a page name");
      if (!$orderNumber) return array("type" => "error", "message" => "Please enter the order number");
      if (!$link) return array("type" => "error", "message" => "Please enter the link");

      $stmt = $this->con->prepare("UPDATE oc_pages SET page = :pageName, page_type_id = :pageType, order_number = :orderNumber, page_link = :link WHERE page_id = :id");
      $stmt->bindValue(':id', $id);
      $stmt->bindValue(':pageName', $pageName);
      $stmt->bindValue(':pageType', $pageType);
      $stmt->bindValue(':orderNumber', $orderNumber);
      $stmt->bindValue(':link', $link);

      $result = $stmt->execute();

      if ($result) {
        return array("type" => "success", "message" => "Page Updated");
      } else {
        return array("type" => "error", "message" => "Error");
      }
    }
  }

  public function getSingle($id)
  {
    $stmt = $this->con->prepare("SELECT page as pageName, page_type_id AS pageType, order_number AS orderNumber, page_link AS link FROM oc_pages WHERE page_id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
      return $result;
    } else {
      return array("type" => "error", "message" => "Error");
    }
  }
}
