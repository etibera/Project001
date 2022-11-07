<?php
require_once "../include/database.php";
class Home {
        private $conn;
   
        public function __construct()
        {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
        }
        public  function order_status(){
                
                $s = $this->conn->prepare("SELECT * FROM oc_order_status");
        $s->execute();
        $status = $s->fetchAll(PDO::FETCH_ASSOC);
        return $status;
        }
        
   
	public  function total_order(){
		//global $db;
        $stmt =$this->conn->prepare("SELECT COUNT(*) AS total FROM oc_order WHERE order_status_id > 0");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
	}
    public  function CountTotalCustomer($type){
        //global $db;
        $stmt =$this->conn->prepare("SELECT COUNT(customer_id) as total FROM oc_customer WHERE 
            (CASE WHEN (type IS NULL OR type = '') AND nexmo_status = 1 THEN 
                CASE WHEN landbankacc = 0 and fgivesacc=0 THEN 'Verified_Customer'
                WHEN landbankacc = 1 and fgivesacc=0 THEN 'Landbank_Account' ELSE '4Gives_Account' END
              WHEN type = 'guest'   THEN 'Guest'
              WHEN fgivesacc = '1' THEN '4Gives_Account'
              WHEN type = 'google' OR type = 'apple' OR type = 'facebook' THEN 'google_apple_facebook'
            ELSE 'Unverified_Customer' END)=:valuetype");
        $stmt->bindValue(':valuetype', $type);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return number_format($row['total']);
    }
     public  function GrandTotalCustomer(){
        //global $db;
        $stmt =$this->conn->prepare("SELECT COUNT(customer_id) as total FROM oc_customer");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return number_format($row['total']);
    }

	public  function total_customers_verifiedbymobile(){
		//global $db;
        $stmt = $this->conn->prepare("SELECT COUNT(customer_id) as total FROM oc_customer WHERE nexmo_status=:s and (type IS NULL OR type = '')");
        $stmt->bindValue(':s', 1);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
	}
    public  function total_customers_GAF(){
        //global $db;
        $stmt = $this->conn->prepare("SELECT COUNT(customer_id) as total FROM oc_customer WHERE type is not NULL");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt2 = $this->conn->prepare("SELECT COUNT(customer_id) as total FROM oc_customer WHERE type =:type");
        $stmt2->bindValue(':type', 'guest');
        $stmt2->execute();
        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        $total=$row['total']-$row2['total'];

        return $total;
    }
     public  function total_customers_LandBank(){
        //global $db;
        $stmt = $this->conn->prepare("SELECT COUNT(customer_id) as total FROM oc_customer WHERE landbankacc=1");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
    public  function TotalUnverifiedCustomers_bymobile(){
        //global $db;
        $stmt = $this->conn->prepare("SELECT COUNT(customer_id) as total FROM oc_customer WHERE  nexmo_status = :s AND (type IS NULL OR type = '') ");
        $stmt->bindValue(':s', 0);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
    public  function TotalUnverifiedCustomers_guest(){
        $stmt2 = $this->conn->prepare("SELECT COUNT(customer_id) as total FROM oc_customer WHERE type =:type");
        $stmt2->bindValue(':type', 'guest');
        $stmt2->execute();
        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        return  $row2['total'];
    }
	public  function total_sales(){
		//global $db;
        $stmt =$this->conn->prepare("SELECT SUM((SELECT oot.value from oc_order_total oot where oot.order_id= o.order_id and title='Total')) AS total FROM oc_order o WHERE order_status_id = :s");
        $stmt->bindValue(':s', 49);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return number_format($row['total'], 2);
	}
	public  function order_history(){
		//global $db;
		$stmt = $this->conn->prepare("SELECT o.customer_id,o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM oc_order_status os WHERE os.order_status_id = o.order_status_id) AS status, o.shipping_code,(SELECT oot.value from oc_order_total oot where oot.order_id= o.order_id and title='Total') AS total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM oc_order o WHERE o.order_status_id > 0 ORDER BY o.date_added DESC ");
		$stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;

	}
     public  function countOrderNumber($order_id){
        $total = array();
        $t = $this->conn->prepare("SELECT count(id) as total FROM store_orders where order_id=:order_id AND order_number is not null ");
        $t->bindValue(':order_id', $order_id);
        $t->execute();
        $total = $t->fetch(PDO::FETCH_ASSOC);
        return $total['total'];
    }
    public  function count_OlddataOrder($order_id){
        $total = array();
        $t = $this->conn->prepare("SELECT count(id) as total FROM store_orders WHERE order_id=:order_id");
        $t->bindValue(':order_id', $order_id);
        $t->execute();
        $total = $t->fetch(PDO::FETCH_ASSOC);
        return $total['total'];
    }
    public  function GetTrackingNumber($order_id){
        $row = array();
        $t = $this->conn->prepare("SELECT * FROM quadx_orders WHERE order_id like :order_id");
        $t->bindValue(':order_id', '%'.$order_id.'%');
        $t->execute();
        $row = $t->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
        public  function order_historyS($order_id,$customer_name,$orderstatus){
                //global $db;
                $qeury="SELECT o.customer_id,o.order_id,CONCAT(o.firstname, ' ', o.lastname) AS customer,os.name AS status,o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM oc_order o LEFT JOIN oc_order_status os ON os.order_status_id = o.order_status_id WHERE o.order_status_id > 0 ";
                if($customer_name!="notset"){
                        $qeury.=" and CONCAT(o.firstname, ' ', o.lastname) like :customer_name ";
                }
                 if($order_id!="notset"){
                       $qeury.=" and o.order_id= :order_id ";
                }
                 if($orderstatus!="notset"){
                       $qeury.=" and o.order_status_id=:orderstatus ";
                }                
                $qeury.=" ORDER BY o.date_added DESC ";

                $stmt = $this->conn->prepare($qeury);
                if($customer_name!="notset"){
                  $stmt->bindValue(':customer_name', '%'.$customer_name.'%');
                }
                if($order_id!="notset"){
                      $stmt->bindValue(':order_id', $order_id);
                }
                 if($orderstatus!="notset"){
                      $stmt->bindValue(':orderstatus', $orderstatus);
                }                  
                $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;

        }

        public  function activity_list($datefrom,$dateto,$customer,$ip) {
        $sql="";
        $sql1="";
        $sql2="";
        $sql3="";
        if($customer != ''){
            $sql=" and (c.firstname LIKE :customer or c.lastname LIKE :customer)";
        }
        if($ip != ''){
            $sql1=" and a.ip LIKE :ip";
        }
         if($datefrom != '' && $dateto != ''){
            $sql3=" and a.date_added between :date and :date1";
        }
                
        $stmt = $this->conn->prepare("SELECT a.*,c.firstname,c.lastname,c.email,c.type from oc_customer_activity a inner join oc_customer c on a.customer_id = c.customer_id where a.activity_id is not null ".$sql.$sql1.$sql2.$sql3." order by a.date_added desc LIMIT 10");

                 if(!empty($customer)) {
                   $stmt->bindValue(':customer', '%'.$customer.'%');
                }
                 if(!empty($ip)) {
                   $stmt->bindValue(':ip', '%'.$ip.'%');
                }
                 if(!empty($datefrom) && !empty($dateto)) {
                   $stmt->bindValue(':date', $datefrom.' 00:00:00');
                   $stmt->bindValue(':date1', $dateto.' 23:59:59');
                }
                      
              
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);       
                return $row;
    }

}