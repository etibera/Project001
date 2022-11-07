<?php
require_once "../include/database.php";
class Specification{
	
    private $conn;
    public function __construct(){
    $this->conn = new Database();
    $this->conn = $this->conn->getmyDB();
    }
    public function get_specification() {
            $stmt = $this->conn->prepare("SELECT * FROM oc_specification");
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    public function add_attribute($data) {
        try{
        	foreach ($data as $row) {
        		$attribute_name=$row["attribute_name"];
        		$description=$row["description"];
        		$img=$row["img"];
        		$insert = $this->conn->prepare("INSERT INTO oc_specification SET  name =:attribute_name,title = :title,image=:img");
                $insert->bindValue(':attribute_name', $attribute_name);
                $insert->bindValue(':title', $description);
                $insert->bindValue(':img', $img);
                $insert->execute();
                $lastId = $this->conn->lastInsertId();

        		foreach ($row['a_items'] as $a_item) {
	                $isert_a_items = $this->conn->prepare("INSERT INTO oc_specification_item SET  s_id =:lastId,name = :name,description = :descr,sort_order=:sort_order");
	                $isert_a_items->bindValue(':lastId', $lastId);
	                $isert_a_items->bindValue(':name',  $a_item['name']);
	                $isert_a_items->bindValue(':descr',  $a_item['desc']);
	                $isert_a_items->bindValue(':sort_order',  $a_item['sort_order']);
	                $isert_a_items->execute(); 
	                $status = true;
	            }
        	}
        }catch(PDOexception $e){
            echo "<p>Database Error: $e </p>";
            $status = false;
       }
       return $status;
    }
    public function delete_attribute($id) {
         try{
            $stmt = $this->conn->prepare("DELETE FROM oc_specification where id = :id");
            $stmt->bindValue(':id',$id);
            $stmt->execute();

            $stmt_items = $this->conn->prepare("DELETE FROM oc_specification_item where s_id = :id");
            $stmt_items->bindValue(':id',$id);
            $stmt_items->execute();
            $status=true;
        }catch(PDOexception $e){
            echo "<p>Database Error: $e </p>";
            $status = false;
        }
        return $status;
    }
    public function get_attr_details($id) {
            $stmt = $this->conn->prepare("SELECT * FROM oc_specification_item where s_id = :id");
            $stmt->bindValue(':id',$id);
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    public function get_attr($id) {
            $stmt = $this->conn->prepare("SELECT * FROM oc_specification where id = :id");
            $stmt->bindValue(':id',$id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
    public function save_china_products($goods_sn,$parent_sn,$get_product_title,$get_product_img,$sku,$china_price_usd) {
        try {

            $count =$this->conn->prepare("SELECT count(id) as count FROM oc_china_product WHERE  goods_sn =:goods_sn");
            $count->bindValue(':goods_sn', $goods_sn);
            $count->execute();
            $row = $count->fetch(PDO::FETCH_ASSOC);
            if($row['count']==0){
                $stmt =$this->conn->prepare("INSERT INTO  oc_china_product set  goods_sn=:goods_sn, parent_sn =:parent_sn,
                                product_title=:product_title,product_img=:product_img,status=:status,sku=:sku, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00'), 
                                date_modified = convert_tz(utc_timestamp(),'-08:00','+0:00'), price=:price");
                $stmt->bindValue(':goods_sn', $goods_sn);
                $stmt->bindValue(':parent_sn', $parent_sn);
                $stmt->bindValue(':product_title', $get_product_title);
                $stmt->bindValue(':product_img', $get_product_img);
                $stmt->bindValue(':status', 1);
                $stmt->bindValue(':sku', $sku);
                $stmt->bindValue(':price', $china_price_usd);
                $stmt->execute();
            }
            return "200";
        }catch(Exception $e){
             return $e;
        } 
    }
    public function delete_china_products($id) {
        try {
            $stmt =$this->conn->prepare("DELETE  FROM  oc_china_product WHERE id=:id ");
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return "200";
        }catch(Exception $e){
             return $e;
        } 
    } 
    public function delete_currency($id) {
        try {
            $stmt =$this->conn->prepare("DELETE  FROM  selected_currency WHERE id=:id ");
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return "200";
        }catch(Exception $e){
             return $e;
        } 
    } 
     public function currency_disable($id) {
        try {
            $stmt =$this->conn->prepare("UPDATE  selected_currency set status=:status WHERE id=:id ");
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':status', 0);
            $stmt->execute();
            return "200";
        }catch(Exception $e){
             return $e;
        } 
    }
    public function currency_enable($id) {
        try {
            $stmt =$this->conn->prepare("UPDATE  selected_currency set status=:status WHERE id=:id ");
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':status', 1);
            $stmt->execute();
            return "200";
        }catch(Exception $e){
             return $e;
        } 
    }
    public function china_products_disable($id) {
        try {
            $stmt =$this->conn->prepare("UPDATE  oc_china_product set status=:status WHERE id=:id ");
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':status', 0);
            $stmt->execute();
            return "200";
        }catch(Exception $e){
             return $e;
        } 
    }
    public function get_china_orders() {
            $stmt = $this->conn->prepare("SELECT * FROM china_order where status=1 order by id desc");
            $stmt->execute();
            $row = $stmt->fetchall(PDO::FETCH_ASSOC);
        return $row;
    }
   
    
    public function china_products_enable($id) {
        try {
            $stmt =$this->conn->prepare("UPDATE  oc_china_product set status=:status WHERE id=:id ");
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':status', 1);
            $stmt->execute();
            return "200";
        }catch(Exception $e){
             return $e;
        } 
    }
    public function getdelvery_charge() {
            $stmt = $this->conn->prepare("SELECT * FROM oc_delivery_charge");
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    public function get_china_products() {
            $stmt = $this->conn->prepare("SELECT * FROM oc_china_product order by id desc");
            $stmt->execute();
            $row = $stmt->fetchall(PDO::FETCH_ASSOC);
        return $row;
    }
    public function currency_list() {
            $stmt = $this->conn->prepare("SELECT * FROM selected_currency order by id desc");
            $stmt->execute();
            $row = $stmt->fetchall(PDO::FETCH_ASSOC);
        return $row;
    }
    public function currency_active() {
            $stmt = $this->conn->prepare("SELECT * FROM selected_currency WHERE status=1  order by id desc limit 1");
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
    public function count_fer($date_added) {
        $stmt = $this->conn->prepare("SELECT COUNT(id) as total FROM foreign_exchange_rates WHERE date_added=:s");
        $stmt->bindValue(':s', $date_added);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
    public function insert_fer($date_now,$BASE,$CNTO,$RATE,$api_date) {
        try{
            $isert_c = $this->conn->prepare("INSERT INTO foreign_exchange_rates SET  date_added =:date_added,api_date = :api_date,rate = :rate,details=:details");
                    $isert_c->bindValue(':date_added', $date_now);
                    $isert_c->bindValue(':api_date', $api_date);
                    $isert_c->bindValue(':rate', $RATE);
                    $isert_c->bindValue(':details',$BASE." to ".$CNTO);
                    $isert_c->execute(); 
                    $status ="200";
        }catch(PDOexception $e){
            $status =$e;
        }
       return $status;
    }
    public function update_fer($date_now,$BASE,$CNTO,$RATE,$api_date) {
        try{
            $isert_c = $this->conn->prepare("UPDATE foreign_exchange_rates SET  api_date = :api_date,rate = :rate,details=:details WHERE date_added=:date_added");
                    $isert_c->bindValue(':date_added', $date_now);
                    $isert_c->bindValue(':api_date', $api_date);
                    $isert_c->bindValue(':rate', $RATE);
                    $isert_c->bindValue(':details',$BASE." to ".$CNTO);
                    $isert_c->execute(); 
                    $status ="200";
        }catch(PDOexception $e){
            $status =$e;
        }
       return $status;
    }

    public function save_currency($base,$exchange_currency) {
        try{
            $isert_c = $this->conn->prepare("INSERT INTO selected_currency SET  base =:base,exchange_currency = :exchange_currency,status = :status");
                    $isert_c->bindValue(':base', $base);
                    $isert_c->bindValue(':exchange_currency', $exchange_currency);
                    $isert_c->bindValue(':status', 0);
                    $isert_c->execute(); 
                    $status ="200";
        }catch(PDOexception $e){
            $status ="<p>Database Error: $e </p>";
        }
       return $status;
    }
    public function updateDR_CH($id,$dr_id) {
        try{
            $isert_c = $this->conn->prepare("UPDATE oc_china_product SET  delivery_charge_id =:dr_id WHERE id=:id");
                    $isert_c->bindValue(':dr_id', $dr_id);
                    $isert_c->bindValue(':id', $id);
                    $isert_c->execute(); 
                    $status ="200";
        }catch(PDOexception $e){
            $status = $e;
        }
       return $status;
    }
    public function edit_attribute($data,$img,$a_items) {
        try{
        	
        		$attribute_name=$data["attribute_name"];
        		$description=$data["description"];
        		$id=$data["att_id"];
        		$insert = $this->conn->prepare("UPDATE oc_specification SET  name =:attribute_name,title = :title,image=:img WHERE id=:id");
                $insert->bindValue(':attribute_name', $attribute_name);
                $insert->bindValue(':title', $description);
                $insert->bindValue(':img', $img);
                $insert->bindValue(':id', $id);
                $insert->execute();

                $stmt_items = $this->conn->prepare("DELETE FROM oc_specification_item where s_id = :id");
	            $stmt_items->bindValue(':id',$id);
	            $stmt_items->execute();

                foreach ($a_items as $a_item) {
	                $isert_a_items = $this->conn->prepare("INSERT INTO oc_specification_item SET  s_id =:lastId,name = :name,description = :descr,sort_order=:sort_order");
	                $isert_a_items->bindValue(':lastId', $id);
	                $isert_a_items->bindValue(':name',  $a_item['name']);
	                $isert_a_items->bindValue(':descr',  $a_item['desc']);
	                $isert_a_items->bindValue(':sort_order',  $a_item['sort_order']);
	                $isert_a_items->execute(); 
	                $status = true;
	        	}
        	
        	
        }catch(PDOexception $e){
            echo "<p>Database Error: $e </p>";
            $status = false;
       }
       return $status;
    }

}
?>