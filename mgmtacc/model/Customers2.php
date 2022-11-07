<?php
require_once "../include/database.php";

class Customers
{
  private $con;

  public function __construct()
  {
    $this->con = new Database();
    $this->con = $this->con->getmyDB();
  }


  public function get($type)
  {
    $output = $params = $data = $dataParams = $columns = array();

    $params = $_REQUEST;

    $columns = array('customerId', 'firstname', 'email', 'telephone', 'dateCreated', 'type');
    $fullname = $_POST['fullname'] ?? null;
    $from = $_POST['from'] ?? null;
    $to = $_POST['to'] ?? null;
    

    $sql = "SELECT customer_id AS customerId,
        CASE WHEN type ='guest' THEN username WHEN firstname ='' and lastname='' THEN email ELSE CONCAT(firstname,' ',lastname) END  as fullName ,
        email, telephone, date_added AS dateCreated, nexmo_status AS status,
        CASE WHEN (type IS NULL OR type = '') AND nexmo_status = 1 THEN  
          CASE WHEN landbankacc = 0 and fgivesacc=0 THEN 'Verified Customer' 
           WHEN landbankacc = 1 and fgivesacc=0  THEN 'Landbank Account' 
          ELSE '4Gives Account' END
        WHEN type = 'guest' THEN 'Guest'
        WHEN type = 'google' THEN 'Google Account'
        WHEN type = 'apple' THEN 'Apple Account'
        WHEN type = 'facebook' THEN 'Facebook Account'
        WHEN fgivesacc = '1' THEN '4Gives Account'
        ELSE 'Unverified Customer' END AS type,
        username, landbankacc
      FROM oc_customer";

    $base = $this->con->prepare($sql);
    $base->execute($dataParams);
    $totalCount = $base->rowCount();

    $connector = " WHERE";
    if (!empty($fullname)) {
      $sql .= " WHERE CONCAT(firstname, ' ', lastname) LIKE :fullname";
      $dataParams = array_merge($dataParams, [":fullname" => "%{$fullname}%"]);
      $connector = " AND";
    } else {
    if (!empty($from) && !empty($to)) {
        $sql .= " WHERE date_added BETWEEN :from AND :to";
        $dataParams = array_merge($dataParams, [":from" => "{$from} 00:00:00", ":to" => "{$to} 23:59:59"]);
        $connector = " AND";
      }
    }
    if ($type!="notset") {
       $sql .= $connector;
       $sql .= " 
        (CASE WHEN (type IS NULL OR type = '') AND nexmo_status = 1 THEN 
                CASE WHEN landbankacc = 0 and fgivesacc=0 THEN 'Verified_Customer'
                WHEN landbankacc = 1 and fgivesacc=0 THEN 'Landbank_Account' ELSE '4Gives_Account' END
              WHEN type = 'guest'   THEN 'Guest'
              WHEN fgivesacc = '1' THEN '4Gives_Account'
              WHEN type = 'google' OR type = 'apple' OR type = 'facebook' THEN 'google_apple_facebook'
         ELSE 'Unverified_Customer' END)=:valuetype";
        $dataParams = array_merge($dataParams, [":valuetype" => "$type"]);
        $connector = " AND";
    }

    $searchCount = $this->con->prepare($sql);
    $searchCount->execute($dataParams);
    $filterCount = $searchCount->rowCount();   
    if (!empty($params['search']['value'])) {
      $sql .= $connector;
      $sql .= " (customer_id LIKE :value";
      $sql .= " OR (CONCAT(firstname, ' ', lastname)) LIKE :value";
      $sql .= " OR email LIKE :value";
      $sql .= " OR telephone LIKE :value";
      $sql .= " OR date_added LIKE :value";
      $sql .= " OR
      ( CASE WHEN (type IS NULL OR type = '') AND nexmo_status = 1 THEN  
              CASE WHEN landbankacc = 0 and fgivesacc=0 THEN 'Verified Customer' 
              WHEN landbankacc = 1 and fgivesacc=0  THEN 'Landbank Account' 
              ELSE '4Gives Account' END
            WHEN type = 'guest' THEN 'Guest'
            WHEN type = 'google' THEN 'Google Account'
            WHEN type = 'apple' THEN 'Apple Account'
            WHEN type = 'facebook' THEN 'Facebook Account'
            WHEN fgivesacc = '1' THEN '4Gives Account'
        ELSE 'Unverified Customer' END) LIKE :value)";

      $dataParams = array_merge($dataParams, [":value" => "%{$params['search']['value']}%"]);

      $searchCount = $this->con->prepare($sql);
      $searchCount->execute($dataParams);

      $filterCount =  $searchCount->rowCount();
    }

    if (isset($params['order'])) {
      $sql .= " ORDER BY {$columns[$params['order'][0]['column']]} {$params['order'][0]['dir']}";
    } else {
      $sql .= " ORDER BY date_added DESC";
    }

    if ($params['draw'] != 0 && $params['length']>0) {
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
    $filterData = $stmt->fetchAll(PDO::FETCH_OBJ);

    foreach ($filterData as $row) {
      $data[] = array(
        $row->customerId,
        $row->fullName,
        $row->email,
        $row->telephone,
        $row->dateCreated,
        $row->type
      );
    }

    $output = array(
      'draw' => intval($params['draw']),
      'recordsTotal' => intval($totalCount),
      'recordsFiltered' => intval($filterCount),
      'data' => $data
    );
    return $output;
  }
}
