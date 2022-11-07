<?php
require_once "../include/database.php";

class CustomerComment{
  private $con;

  public function __construct(){
    $this->con = new Database();
    $this->con = $this->con->getmyDB();
  }
  public function getCustomerComment(){
    $output = $data = $dataParams = $params = $column = array();
    $column = ["","fullName", "cc.comment","cc.status", "cc.type","source_name", "cc.date",];
    $params = $_REQUEST;
    $ccType = $_POST['ccType'] ?? null;
    $ccStatus = $_POST['ccStatus'] ?? null;
    $from = $_POST['from'] ?? null;
    $to = $_POST['to'] ?? null;
    $sql = "";
   if($ccType==="1"){
    $sql .= " SELECT cc.id,pb.name as source_name,cc.comment,cc.date,cc.status,
              CASE WHEN oc.type ='guest' THEN oc.username 
              WHEN oc.firstname ='' AND oc.lastname='' THEN oc.email 
              ELSE CONCAT(oc.firstname,' ',oc.lastname) END  as fullName,
              DATE_FORMAT(cc.date,'%Y-%m-%d') as date_added ,
              CASE WHEN cc.status ='1' THEN 'Approved' ELSE 'Pending' END  as statusname,
              CASE WHEN cc.type ='1' THEN 'Brand' ELSE 'Store / Flagship' END  as typename
            FROM customer_comment cc 
            INNER JOIN oc_customer oc
              ON oc.customer_id=cc.customer_id
            INNER JOIN oc_product_brand pb
              ON pb.id=cc.source_id ";
   }
   if( $ccType=="2" || $ccType=="3"){
    $sql .= "SELECT cc.id,os.shop_name as source_name,cc.comment,cc.date,cc.status,
              CASE WHEN oc.type ='guest' THEN oc.username 
              WHEN oc.firstname ='' AND oc.lastname='' THEN oc.email 
              ELSE CONCAT(oc.firstname,' ',oc.lastname) END  as fullName,
              DATE_FORMAT(cc.date,'%Y-%m-%d') as date_added ,
              CASE WHEN cc.status ='1' THEN 'Approved' ELSE 'Pending' END  as statusname,
              CASE WHEN cc.type ='1' THEN 'Brand' 
              ELSE CASE WHEN os.seller_type ='2' THEN 'Flagship' ELSE 'Store' END 
              END  as typename
            FROM customer_comment cc 
            INNER JOIN oc_customer oc
              ON oc.customer_id=cc.customer_id
           INNER JOIN oc_seller os
              ON os.seller_id=cc.source_id ";
   }
    
    $base = $this->con->prepare($sql);
    $base->execute();
    $totalCount = $base->rowCount();
    $connector = " WHERE ";
    if ($ccStatus != 'all') {
      $sql .= $connector;
      $sql .= " cc.status = :ccStatus";
      $dataParams = array_merge($dataParams, [':ccStatus' => $ccStatus]);
      $connector = " AND ";
    }
    if ($ccStatus != 'all') {
      $sql .= $connector;
      $sql .= " cc.status = :ccStatus";
      $dataParams = array_merge($dataParams, [':ccStatus' => $ccStatus]);
      $connector = " AND ";
    }

    if ($ccType != 'all') {      
      if($ccType=="3"){
        $sql .= $connector;
        $sql .= " cc.type = :ccType AND os.seller_type = :seller_type";
        $dataParams = array_merge($dataParams, [':ccType' => "2" ,":seller_type" =>"2"]);
        $connector = " AND ";
      }else if($ccType=="2"){
        $sql .= $connector;
        $sql .= " cc.type = :ccType AND os.seller_type = :seller_type";
        $dataParams = array_merge($dataParams, [':ccType' => "2",":seller_type" =>"0"]);

        $connector = " AND ";

      }else{
        $sql .= $connector;
        $sql .= " cc.type = :ccType";
        $dataParams = array_merge($dataParams, [':ccType' => $ccType]);
        $connector = " AND ";
      }

    }
    if (!empty($from) && !empty($to)) {
      $sql .= $connector;
      $sql .= " cc.date BETWEEN :from AND :to";
      $dataParams = array_merge($dataParams, [":from" => "$from 00:00:00", ":to" => "$to 23:59:59"]);
      $connector = " AND ";
    }

    if (!empty($params['search']['value'])) {
      $sql .= $connector;
      $sql .= " (cc.comment LIKE :value";
      $sql .= " OR CONCAT(oc.firstname, ' ', oc.lastname) LIKE :value)";
      $dataParams = array_merge($dataParams, [":value" => "%" . trim($params['search']['value']) . "%"]);
    }

    $searchCount = $this->con->prepare($sql);
    $searchCount->execute($dataParams);

    $filterCount = $searchCount->rowCount();
    if (isset($params['order'])) {
      $sql .= " ORDER BY {$column[$params['order'][0]['column']]} {$params['order'][0]['dir']}";
    } else {
      $sql .= " ORDER BY cc.date DESC ";
    }
    if ($params['draw'] != 0 && $params['length'] > 0) {
      $sql .= " LIMIT :start, :length";
      $dataParams = array_merge($dataParams, [":start" => intval($params['start']), ":length" => intval($params['length'])]);
    }
    $stmt = $this->con->prepare($sql);
    foreach ($dataParams as $key => $value) {
      if (is_int($value)) {
        $paramType = PDO::PARAM_INT;
      } else {
        $paramType = PDO::PARAM_STR;
      }
      $stmt->bindValue($key, $value, $paramType);
    }
    $stmt->execute();
    $filteredData = $stmt->fetchAll(PDO::FETCH_OBJ);
    foreach ($filteredData as $row) {
      if ($row->status == 1) {
        $checkbox = "";
      } else {
        $checkbox = "<center><input name='ccid' type='checkbox' value='$row->id'></input></center>";
      }
      $data[] = array(
        $checkbox,
        $row->fullName,
        $row->comment,
        $row->statusname,
        $row->typename,
        $row->source_name,
        $row->date,
      );
    }
     $output = array(
      'draw' => intval($params['draw']),
      'recordsFiltered' => intval($filterCount),
      'recordsTotal' => intval($totalCount),
      'data' => $data,
      'sql' => $sql,
    );
    
    return $output;
  }
  public function ccApprove(){
    $ccid = $_POST['ccid'] ?? null;

    if (empty($ccid)) return array("type" => "error", "message" => "Please select Customer Comment");

    foreach ($ccid as $value) {
      $stmt = $this->con->prepare("UPDATE customer_comment SET status = :status WHERE id = :id");
      $result = $stmt->execute([":id" => $value, ":status" => 1]);
    }

    if ($result) {
      return array("type" => "success", "message" => "Transaction Success");
    }
  }
}