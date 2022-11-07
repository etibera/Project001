<?php
require_once "../include/database.php";
class SPayables{	
    private $conn;
    public function __construct(){
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    public  function getSPP_list($status,$date_from,$date_to){
         $sql=" ";
        if($date_from!="notset" && $date_to!="notset" ){
           $sql.=" WHERE date BETWEEN '$date_from 00:00:00' AND '$date_to 23:59:59'";
        }
        $stmt =  $this->conn->prepare("SELECT * FROM ( 
                                            SELECT sp.*,sb.bank_account_no,sb.bank_account_name,sb.bank_name,os.shop_name 
                                            FROM store_payables sp 
                                            INNER JOIN  oc_seller os 
                                                ON os.seller_id=sp.seller_id 
                                            INNER JOIN store_orders so 
                                                ON sp.seller_id=so.seller_id and sp.order_id=so.order_id and sp.order_number=so.order_number
                                            INNER JOIN seller_branch sb 
                                                ON sb.id=so.branch_id 
                                            WHERE sp.status=:status AND so.order_number IS NOT NULL $sql
                                            UNION ALL        
                                            SELECT sp.*,sb.bank_account_no,sb.bank_account_name,sb.bank_name,os.shop_name 
                                            FROM store_payables sp 
                                            INNER JOIN  oc_seller os 
                                                ON os.seller_id=sp.seller_id 
                                            INNER JOIN store_orders so 
                                                ON sp.seller_id=so.seller_id and sp.order_id=so.order_id
                                            INNER JOIN seller_branch sb 
                                                ON sb.id=so.branch_id 
                                            WHERE sp.status=:status AND so.order_number IS NULL $sql
                                         ) AS SPPL
                                         order by SPPL.`date` desc") ;
        $stmt->bindValue(':status', $status);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    public  function getSPP_listAll($date_from,$date_to){
        $sql=" ";
        if($date_from!="notset" && $date_to!="notset" ){
          $sql.=" WHERE date BETWEEN '$date_from 00:00:00' AND '$date_to 23:59:59'";
        }
        $stmt =  $this->conn->prepare("SELECT * FROM ( 
                                                SELECT sp.*,sb.bank_account_no,sb.bank_account_name,sb.bank_name,os.shop_name 
                                                FROM store_payables sp 
                                                INNER JOIN  oc_seller os 
                                                    ON os.seller_id=sp.seller_id 
                                                INNER JOIN store_orders so 
                                                    ON sp.seller_id=so.seller_id and sp.order_id=so.order_id and sp.order_number=so.order_number
                                                INNER JOIN seller_branch sb 
                                                    ON sb.id=so.branch_id AND so.order_number IS NOT NULL $sql 
                                                UNION ALL                                                 
                                                SELECT sp.*,sb.bank_account_no,sb.bank_account_name,sb.bank_name,os.shop_name 
                                                FROM store_payables sp 
                                                INNER JOIN  oc_seller os 
                                                    ON os.seller_id=sp.seller_id 
                                                INNER JOIN store_orders so 
                                                    ON sp.seller_id=so.seller_id and sp.order_id=so.order_id 
                                                INNER JOIN seller_branch sb 
                                                    ON sb.id=so.branch_id AND so.order_number is null $sql 

                                        ) AS SPPL
                                         order by SPPL.`date` desc") ;
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    public  function PayTransfer($seller_id,$order_id,$payableId,$bank_name,$bank_account_no,$amount,$reference_no){
        try {  
            $details="Payment transfered to Acc.# : ".$bank_account_no.", RF.#: ".$reference_no.", Order Id:".$order_id;
            $sw = $this->conn->prepare("INSERT INTO seller_wallet SET `desc` = :orderdesc, amount = :amount, seller_id=:seller_id, `date` = convert_tz(utc_timestamp(),'-08:00','+0:00')");
            $sw->bindValue(':orderdesc',  $details);
            $sw->bindValue(':amount', '-'.$amount);
            $sw->bindValue(':seller_id', $seller_id);
            $sw->execute();
            $sp = $this->conn->prepare("UPDATE store_payables SET status=:status,reference_number=:reference_no, `date` = convert_tz(utc_timestamp(),'-08:00','+0:00') WHERE id=:payableId");
            $sp->bindValue(':payableId', $payableId); 
            $sp->bindValue(':reference_no', $reference_no); 
            $sp->bindValue(':status', 1);
            $sp->execute();
            return "200";
        } catch(Exception $e){
            return $e;
        }
    }
}
?>