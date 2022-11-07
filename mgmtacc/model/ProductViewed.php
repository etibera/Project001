<?php
require_once "../include/database.php";
class ProductViewed {

        private $conn;
        public function __construct()
        {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
        }

        public function messageBox($stats,$msg)
        {
                $message = '<div class="alert alert-'.$stats.' alert-dismissable" style="margin:0px 0px 10px 0px">
                <span id="msg-error">'.$msg.'</span>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button></div>'; 
                return $message;   
        }
        
        public  function product_viewed_list($name){                              

                $stmt = $this->conn->prepare("SELECT * FROM (
                                                SELECT pv.p_type,pd.name,p.model,SUM(pv.total_views) as viewed,ROUND( (SUM(pv.total_views) / (select SUM(total_views) from product_views) * 100), 2 ) as percentage 
                                                FROM product_views pv 
                                                INNER JOIN oc_product p ON p.product_id=pv.product_id
                                                INNER JOIN oc_product_description pd ON pd.product_id=p.product_id
                                                WHERE pd.name LIKE :name AND pv.p_type=0
                                                GROUP BY pv.product_id 
                                                UNION ALL
                                                SELECT pv.p_type,p.product_name as name ,p.meta_desc as model,SUM(pv.total_views) as viewed,ROUND( (SUM(pv.total_views) / (select SUM(total_views) from product_views) * 100), 2 ) as percentage 
                                                FROM product_views pv 
                                                INNER JOIN bg_product p ON p.product_id=pv.product_id
                                                WHERE p.product_name LIKE :name AND pv.p_type=2
                                                GROUP BY pv.product_id  ) AS apv
                                                
                                            order by apv.viewed desc");
                $stmt->bindValue(':name', '%'.$name.'%');
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $row;
        }

        public  function product_viewed_reset(){                              

                $stmt = $this->conn->prepare("UPDATE oc_product set viewed = '0'");
                $stmt->execute();
                $stats = 'Successfully Reset!';
                return $stats;
        }
}

?>