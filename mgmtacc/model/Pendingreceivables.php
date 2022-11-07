<?php

require_once "../include/database.php";   
require_once '../include/email_template.php';
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/Exception.php';
require_once '../PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
class PendingReceivables { 
  private $conn;  
  public function __construct(){
    $this->conn = new Database();
    $this->conn = $this->conn->getmyDB(); 
  }  


  public function GetPendingReceivables($pageNum,$tatus_id,$date_from,$date_to){
    $no_per_page = 20;
    $offset = ($pageNum-1) * $no_per_page;
    $sql=" ";
    if($date_from!="notset" && $date_to!="notset" ){
      $sql.=" AND date_modified BETWEEN '$date_from' AND '$date_to'";
    }
    $stmt = $this->conn->prepare("SELECT o.*,ot.value as grandTotal,CONCAT(o.firstname, ' ', o.lastname) AS fullname,
                                        ocs.name as statusName  
                                  FROM oc_order o
                                  INNER JOIN oc_order_total ot ON ot.order_id=o.order_id
                                  INNER JOIN oc_order_status ocs ON o.order_status_id=ocs.order_status_id             
                                  where ot.title='Total' AND o.order_status_id=:order_status_id 
                                    AND o.order_id in (SELECT distinct  so.order_id FROM store_orders so)  $sql
                                  ORDER BY o.order_id DESC LIMIT :offset , :no_per_page");
    $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    $stmt->bindValue(':no_per_page', $no_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':order_status_id',$tatus_id);
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $row;
  }
  public function GetPendingReceivablesPrint($tatus_id,$date_from,$date_to,$OrderId,$FundStatus){   
    $sql=" ";
    if($date_from!="notset" && $date_to!="notset" ){
      $sql.=" AND date_modified BETWEEN '$date_from' AND '$date_to'";
    }
    if($OrderId!='notset'){
        $sql.=" AND o.order_id=$OrderId";
    }
    if($FundStatus!='notset'){
        if($FundStatus=='UnPaid'){
           $sql.=" AND (o.fund_status is null OR fund_status = '') ";
         }else{
           $sql.=" AND o.fund_status='".$FundStatus."'";
         }
       
    }
    $stmt = $this->conn->prepare("SELECT o.*,ot.value as grandTotal,CONCAT(o.firstname, ' ', o.lastname) AS fullname,
                                        ocs.name as statusName  
                                  FROM oc_order o
                                  INNER JOIN oc_order_total ot ON ot.order_id=o.order_id
                                  INNER JOIN oc_order_status ocs ON o.order_status_id=ocs.order_status_id             
                                  where ot.title='Total' AND o.order_status_id=:order_status_id 
                                    AND o.order_id in (SELECT distinct  so.order_id FROM store_orders so)  $sql
                                  ORDER BY o.order_id DESC");
    $stmt->bindValue(':order_status_id',$tatus_id);
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $row;
  }
   public function GetDefPendingReceivables($pageNum,$date_from,$date_to,$OrderId,$FundStatus){
    $no_per_page = 20;
    $offset = ($pageNum-1) * $no_per_page;
    $sql=" ";
    if($date_from!="notset" && $date_to!="notset" ){
      $sql.=" AND date_modified BETWEEN '$date_from' AND '$date_to' ";
    }
    if($OrderId!='notset'){
        $sql.=" AND o.order_id=$OrderId";
    }
    if($FundStatus!='notset'){
        if($FundStatus=='UnPaid'){
           $sql.=" AND (o.fund_status is null OR fund_status = '') ";
         }else{
           $sql.=" AND o.fund_status='".$FundStatus."'";
         }
       
    }
    $stmt = $this->conn->prepare("SELECT o.*,ot.value as grandTotal,CONCAT(o.firstname, ' ', o.lastname) AS fullname,
                                      ocs.name as statusName  
                                  FROM oc_order o
                                  INNER JOIN oc_order_total ot ON ot.order_id=o.order_id  
                                  INNER JOIN oc_order_status ocs ON o.order_status_id=ocs.order_status_id          
                                  where ot.title='Total' AND o.order_status_id > 0
                                    AND o.order_id in (SELECT distinct  so.order_id FROM store_orders so) $sql
                                  ORDER BY o.order_id DESC LIMIT :offset , :no_per_page");
    $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    $stmt->bindValue(':no_per_page', $no_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $row;
  }
  public function GetDefPendingReceivablesPrint($date_from,$date_to,$OrderId,$FundStatus){   
    $sql=" ";
    if($date_from!="notset" && $date_to!="notset" ){
      $sql.=" AND date_modified BETWEEN '$date_from' AND '$date_to'";
    }
    if($OrderId!='notset'){
        $sql.=" AND o.order_id=$OrderId";
    }
    if($FundStatus!='notset'){
        if($FundStatus=='UnPaid'){
           $sql.=" AND (o.fund_status is null OR fund_status = '') ";
         }else{
           $sql.=" AND o.fund_status='".$FundStatus."'";
         }
       
    }
    $stmt = $this->conn->prepare("SELECT o.*,ot.value as grandTotal,CONCAT(o.firstname, ' ', o.lastname) AS fullname,
                                      ocs.name as statusName  
                                  FROM oc_order o
                                  INNER JOIN oc_order_total ot ON ot.order_id=o.order_id  
                                  INNER JOIN oc_order_status ocs ON o.order_status_id=ocs.order_status_id          
                                  where ot.title='Total' AND o.order_status_id > 0
                                    AND o.order_id in (SELECT distinct  so.order_id FROM store_orders so) $sql
                                  ORDER BY o.order_id DESC");
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $row;
  }
  
  public function getDeffRGPageNumber($date_from,$date_to,$OrderId,$FundStatus){   
    $sql=" ";
    if($date_from!="notset" && $date_to!="notset" ){
      $sql.=" AND date_modified BETWEEN '$date_from' AND '$date_to'";
    }
    if($OrderId!='notset'){
        $sql.=" AND o.order_id=$OrderId";
    }
    if($FundStatus!='notset'){
        if($FundStatus=='UnPaid'){
           $sql.=" AND (o.fund_status is null OR fund_status = '') ";
         }else{
           $sql.=" AND o.fund_status='".$FundStatus."'";
         }
       
    }
    $stmt = $this->conn->prepare("SELECT ROUND(COUNT(o.order_id)/20,0) as pagenumber 
                                  FROM oc_order o                                 
                                  where o.order_status_id > 0
                                    AND o.order_id in (SELECT distinct  so.order_id FROM store_orders so) $sql
                                    ");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['pagenumber'];
   }
   public function getRGPageNumber($tatus_id,$date_from,$date_to,$OrderId,$FundStatus){  
    $sql=" ";
    if($date_from!="notset" && $date_to!="notset" ){
      $sql.=" AND date_modified BETWEEN '$date_from' AND '$date_to'";
    } 
    if($OrderId!='notset'){
        $sql.=" AND o.order_id=$OrderId";
    }
    if($FundStatus!='notset'){
        if($FundStatus=='UnPaid'){
           $sql.=" AND (o.fund_status is null OR fund_status = '') ";
         }else{
           $sql.=" AND o.fund_status='".$FundStatus."'";
         }
       
    }
    $stmt = $this->conn->prepare("SELECT ROUND(COUNT(o.order_id)/20,0) as pagenumber 
                                  FROM oc_order o                                 
                                  where o.order_status_id=:order_status_id 
                                    AND o.order_id in (SELECT distinct  so.order_id FROM store_orders so) $sql 
                                    ");
    $stmt->bindValue(':order_status_id', $tatus_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['pagenumber'];
   }
 
  public function approveReceivables($data) {
      foreach ($data  as $value) {         
        $stmtcust = $this->conn->prepare("UPDATE oc_order SET  fund_status = :fund_status where order_id=:order_id");
        $stmtcust->bindValue(':order_id', $value);
        $stmtcust->bindValue(':fund_status', "Paid");
        $stmtcust->execute();
      }
    $status = "Receivables Successfully Paid";
    return $status;
  }
  public function VerifyCustomer($order_id) {         
    $stmtcust = $this->conn->prepare("UPDATE oc_order SET  ops_verification = :ops_verification where order_id=:order_id");
    $stmtcust->bindValue(':order_id', $order_id);
    $stmtcust->bindValue(':ops_verification', "Verified");
    $stmtcust->execute();
    $status = "Customer Successfully Verified";
    return $status;
  }  
 
}