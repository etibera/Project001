<?php
require_once "../include/database.php";
class Ppp_report {
	private $conn;
	public function __construct(){
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    public  function get_ppp_branch_name(){   
        $stmt = $this->conn->prepare("SELECT CONCAT(oc.firstname, ' ', oc.lastname) AS name ,oc.customer_id as customer_id FROM oc_customer oc INNER JOIN oc_customer_links ocl on oc.customer_id=ocl.invite_id where ocl.customer_id=:customer_id");
        $stmt->bindValue(':customer_id', '1568');
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }

    public  function get_ppp_branch_name_sales_manHQ(){   
        $stmt = $this->conn->prepare("SELECT CONCAT(oc.firstname, ' ', oc.lastname) AS name,oc.customer_id FROM oc_customer oc INNER JOIN oc_affiliate_program oap on oap.customer_id=oc.customer_id where oap.customer_id  not in ( SELECT occ.customer_id as customer_id FROM oc_customer occ INNER JOIN oc_customer_links occl on occ.customer_id=occl.invite_id INNER JOIN oc_affiliate_program oapc on oapc.customer_id=occl.invite_id where occl.customer_id BETWEEN '2407' and '2414') and oc.customer_id not BETWEEN '2407' and '2414' order by oc.firstname asc");
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    public  function get_details_persalesman($customer_id){   
        $stmt = $this->conn->prepare("SELECT * FROM  oc_affiliate_wallet where seller_id=:customer_id order by date desc");
        $stmt->bindValue(':customer_id', $customer_id);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    public  function get_details_share_persalesman($customer_id){   
        $stmt = $this->conn->prepare("SELECT oals.*,opd.name FROM oc_affiliate_link_share oals INNER JOIN oc_product_description opd ON oals.product_id=opd.product_id where oals.customer_id=:customer_id order by oals.date desc");
        $stmt->bindValue(':customer_id', $customer_id);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    public  function get_ppp_branch_name_sales_man($customer_id){   
        $stmt = $this->conn->prepare("SELECT CONCAT(oc.firstname, ' ', oc.lastname) AS name ,oc.customer_id as customer_id FROM oc_customer oc INNER JOIN oc_customer_links ocl on oc.customer_id=ocl.invite_id INNER JOIN oc_affiliate_program oap on oap.customer_id=ocl.invite_id where ocl.customer_id=:customer_id order by oc.firstname asc");
        $stmt->bindValue(':customer_id', $customer_id);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
     public  function get_count_share_links($salesman_id){   
        $stmt =$this->conn->prepare("SELECT count(id) as count FROM oc_affiliate_link_share where customer_id=:salesman_id");
        $stmt->bindValue(':salesman_id', $salesman_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }
    public  function get_count_s_sales($salesman_id,$status){   
        $stmt =$this->conn->prepare("SELECT count(id) as count FROM oc_affiliate_costomer_sold_items where seller_id= :salesman_id and status=:status");
        $stmt->bindValue(':salesman_id', $salesman_id);
        $stmt->bindValue(':status', $status);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }
    public  function get_total_sales_wallet($salesman_id){   
        $stmt =$this->conn->prepare("SELECT SUM(amount) as total  FROM oc_affiliate_wallet where seller_id=:salesman_id ");
        $stmt->bindValue(':salesman_id', $salesman_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

}
?>