<?php
require_once "../include/database.php";
class China_PO{
	private $conn;
    public function __construct(){
    $this->conn = new Database();
    $this->conn = $this->conn->getmyDB();
    }
    public function get_china_PO() {
            $stmt = $this->conn->prepare("SELECT * FROM china_pending_order WHERE status=:status");
            $stmt->bindValue(':status', 0);
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    } 
    public function get_order_products($order_id) {
            $stmt = $this->conn->prepare("SELECT * FROM oc_order_product WHERE order_id=:order_id and p_type=1");
            $stmt->bindValue(':order_id', $order_id);
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    } 
    public function get_batch_order_id($batch_id) {
            $stmt = $this->conn->prepare("SELECT * FROM china_batch_order WHERE id=:batch_id order by id desc");
            $stmt->bindValue(':batch_id', $batch_id);
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    } 
    public function get_batch_order() {
            $stmt = $this->conn->prepare("SELECT * FROM china_batch_order order by id desc");
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    public function get_batch_order_det($batch_id) {
            $stmt = $this->conn->prepare("SELECT cod.*,CONCAT(oo.firstname, ' ', oo.lastname) as name FROM china_batch_order_details cod left join oc_order oo on oo.order_id=cod.order_id WHERE batch_id=:batch_id");
            $stmt->bindValue(':batch_id', $batch_id);
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    public function add_batch_order($order_id_arr) {
    	$isert_batch = $this->conn->prepare("INSERT INTO china_batch_order SET  date_added = convert_tz(utc_timestamp(),'-08:00','+0:00')");
        $isert_batch->execute();
        $lastId = $this->conn->lastInsertId();
        foreach ($order_id_arr as $order_id) {
                $isert_b_items = $this->conn->prepare("INSERT INTO china_batch_order_details SET  batch_id =:lastId,order_id = :order_id");
                $isert_b_items->bindValue(':lastId', $lastId);
                $isert_b_items->bindValue(':order_id',  $order_id);
                $isert_b_items->execute(); 
            }
        return $lastId;
    } 
    public function update_pending_orders($order_id_arr) {
        foreach ($order_id_arr as $order_id) {
                $isert_b_items = $this->conn->prepare("Update china_pending_order SET  status =:status WHERE order_id = :order_id");
                $isert_b_items->bindValue(':status', 1);
                $isert_b_items->bindValue(':order_id',  $order_id);
                $isert_b_items->execute(); 
            }
       
    }
    public function save_order_res_china($get_msg,$lastId,$order_id_arr){
    	$status=0;
        foreach ($get_msg as $value) {
            $res_status_ccp=$value->status;
            if($res_status_ccp==1){
                 //insert success
                $add_res_china = $this->conn->prepare("INSERT INTO china_order SET order_id = :order_id, order_sn = :order_sn, msg = :msg, goods_amount = :goods_amount, shipping_fee = :shipping_fee, packaging_fee = :packaging_fee, handling_fee = :handling_fee, insure_fee = :insure_fee, discount_amount = :discount_amount,grand_total=:grand_total,tax_fee=:tax_fee,status=:status,date=convert_tz(utc_timestamp(),'-08:00','+0:00')");
                $add_res_china->bindValue(':order_id', $lastId);
                $add_res_china->bindValue(':order_sn', $value->order_sn);
                $add_res_china->bindValue(':msg', $value->msg);
                $add_res_china->bindValue(':goods_amount', $value->goods_amount);
                $add_res_china->bindValue(':shipping_fee', $value->shipping_fee);
                $add_res_china->bindValue(':packaging_fee', $value->packaging_fee);
                $add_res_china->bindValue(':handling_fee', $value->handling_fee);
                $add_res_china->bindValue(':insure_fee', $value->insure_fee);
                $add_res_china->bindValue(':discount_amount', $value->discount_amount);
                $add_res_china->bindValue(':grand_total', $value->grand_total);
                $add_res_china->bindValue(':tax_fee', $value->tax_fee);
                $add_res_china->bindValue(':status', $value->status);
                $add_res_china->execute();
                $this->update_pending_orders($order_id_arr);
                $status=1;
            }else{
                 //insert ERROr
                $add_res_china = $this->conn->prepare("INSERT INTO china_order SET order_id = :order_id, msg = :msg,status=:status,date=convert_tz(utc_timestamp(),'-08:00','+0:00')");
                $add_res_china->bindValue(':order_id', $lastId);
                $add_res_china->bindValue(':msg', $value->msg);
                $add_res_china->bindValue(':status', $value->status);
                $add_res_china->execute();
                $status=$value->msg;
            }
        }
          return $status;
    }
}
?>