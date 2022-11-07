<?php
require_once "../include/database.php";
require_once '../model/ImageResizer.php';
require_once '../model/Image.php';
class Order{
    private $conn;   
    public function __construct(){
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
     
    
    public  function OrderPrdDetails($order_id){
        global $image;
        $stores = array();
        $select_stmt = $this->conn->prepare("SELECT os.shop_name,so.order_id,so.seller_id,
                                                concat('company/',os.image) as image ,so.branch_id
                                            FROM store_orders so 
                                            INNER JOIN oc_seller os ON so.seller_id = os.seller_id 
                                            where so.order_id=:order_id");
        $select_stmt->bindValue(':order_id', $order_id);
        $select_stmt->execute();
        $storeData = $select_stmt->fetchAll();
        foreach ($storeData as $row) {
            $stores[] = array(
                'shop_name' => $row['shop_name'],
                'seller_id' => $row['seller_id'],                
                'branch_id' => $row['branch_id'],                
                'order_status' => $this->GetOrderStatusPerStore($order_id,$row['seller_id']),                
                'thumb' => $image->resize($row['image'], 70,70),
                'details' => $this->GetOrderPrd($order_id,$row['seller_id']),
                'totals' => $this->GetOrderPrdTotals($order_id,$row['seller_id']),
            );
        }
        return $stores; 
    } 
    public  function OrderPrdDetailsGlobal($order_id){
        $productsGlobal = array();
        $s = $this->conn->prepare("SELECT ops.serial,ops.id as serialid, oop.*,ifnull(oop.no_serial_added,0)as NOA FROM oc_order_product as oop left join oc_product_serial as ops on oop.order_id=ops.order_id and oop.order_product_id=ops.order_product_id WHERE oop.order_id = :order_id AND oop.p_type!=0 order by oop.order_product_id asc");
        $s->bindValue(':order_id', $order_id);
        $s->execute();
        $productsGlobal = $s->fetchAll(PDO::FETCH_ASSOC);
         return $productsGlobal;

    }
    public  function GetOrderStatusPerStore($order_id,$seller_id){
        $status = array();
        $s = $this->conn->prepare("SELECT oos.name AS status,osps.order_status_id FROM order_status_per_store osps
                                    INNER JOIN oc_order_status oos 
                                        ON oos.order_status_id = osps.order_status_id
                                    WHERE  osps.order_id = :order_id AND osps.seller_id=:seller_id ");
        $s->bindValue(':order_id', $order_id);
        $s->bindValue(':seller_id', $seller_id);
        $s->execute();
        $status = $s->fetchAll(PDO::FETCH_ASSOC);
        return $status;
    }
    public  function GetOrderPrd($order_id,$seller_id){
        $products = array();
        $s = $this->conn->prepare("SELECT ops.serial,ops.id as serialid, oop.*,ifnull(oop.no_serial_added,0)as NOA 
                                   FROM oc_order_product as oop 
                                   left join oc_product_serial as ops on oop.order_id=ops.order_id and oop.order_product_id=ops.order_product_id 
                                   WHERE oop.order_id = :order_id AND seller_id=:seller_id order by oop.order_product_id asc");
        $s->bindValue(':order_id', $order_id);
        $s->bindValue(':seller_id', $seller_id);
        $s->execute();
        $products = $s->fetchAll(PDO::FETCH_ASSOC);
        return $products;
    }
    public  function GetOrderPrdTotals($order_id,$seller_id){
        $total = array();
        $t = $this->conn->prepare("SELECT * from order_total_per_store where order_id=:order_id and seller_id=:seller_id  order by sort_order asc;");
        $t->bindValue(':order_id', $order_id);
        $t->bindValue(':seller_id', $seller_id);
        $t->execute();
        $total = $t->fetchAll(PDO::FETCH_ASSOC);
        return $total;
    }
	public  function order_details($order_id){
		
		 $stmt = $this->conn->prepare("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM oc_customer c WHERE c.customer_id = o.customer_id) AS customer FROM oc_order o WHERE o.order_id = :order_id");
		$stmt->bindValue(':order_id', $order_id);
        $stmt->execute();
        $row = $stmt->fetch();
        $row['products'] = array();
        $row['history'] = array();
        $row['historyNew'] = array();
        $row['total'] = array();
        if($stmt->rowCount()){
        	$s = $this->conn->prepare("SELECT ops.serial,ops.id as serialid, oop.*,ifnull(oop.no_serial_added,0)as NOA FROM oc_order_product as oop left join oc_product_serial as ops on oop.order_id=ops.order_id and oop.order_product_id=ops.order_product_id WHERE oop.order_id = :order_id order by oop.order_product_id asc");
        	$s->bindValue(':order_id', $order_id);
        	$s->execute();
        	$products = $s->fetchAll(PDO::FETCH_ASSOC);
        	foreach($products as $product){
        		$row['products'][] = $product;
        	}

        	$h = $this->conn->prepare("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM oc_order_history oh LEFT JOIN oc_order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = :order_id ORDER BY oh.date_added DESC");
        	$h->bindValue(':order_id', $order_id);
        	$h->execute();
        	$histories = $h->fetchAll(PDO::FETCH_ASSOC);
        	foreach($histories as $history){
        		$row['history'][] = $history;
        	}
            $hn = $this->conn->prepare("SELECT oh.date_added, os.name AS status,st.shop_name,oh.comment,
                                            oh.notify FROM oc_order_history oh 
                                        LEFT JOIN oc_order_status os 
                                            ON oh.order_status_id = os.order_status_id
                                        LEFT JOIN oc_seller st  
                                            ON st.seller_id = oh.seller_id
                                        WHERE oh.order_id = :order_id ORDER BY oh.date_added DESC");
            $hn->bindValue(':order_id', $order_id);
            $hn->execute();
            $historiesn = $hn->fetchAll(PDO::FETCH_ASSOC);
            foreach($historiesn as $historyn){
                $row['historyNew'][] = $historyn;
            }
                $t = $this->conn->prepare("SELECT * from oc_order_total where order_id=:order_id  order by sort_order asc;");
                $t->bindValue(':order_id', $order_id);
                $t->execute();
                $total = $t->fetchAll(PDO::FETCH_ASSOC);
                foreach($total as $totals){
                        $row['total'][] = $totals;
                }
        }
        return $row;
	}
	public  function order_status(){
		
		$s = $this->conn->prepare("SELECT * FROM oc_order_status where type=0");
        $s->execute();
        $status = $s->fetchAll(PDO::FETCH_ASSOC);
        return $status;
	}
        public  function getProduct($product_id){
                 $data = array();
                $pr = $this->conn->prepare("SELECT p.price,pd.name from oc_product p LEFT JOIN oc_product_description pd ON p.product_id=pd.product_id WHERE p.product_id=:product_id");
                $pr->bindValue(':product_id', $product_id);
                $pr->execute();
                $prod = $pr->fetchAll(PDO::FETCH_ASSOC);
                foreach($prod as $product){
                        $data[] = $product;
                }
                return $data;
        }
        public  function getserilal($serialno){
                 $getserilal = $this->conn->prepare("SELECT COUNT(*) AS total FROM oc_product_serial WHERE serial =:serialno ");
                $getserilal->bindValue(':serialno',  $serialno);
                $getserilal->execute();
                $count=$getserilal->fetch(PDO::FETCH_ASSOC);
                $countno=$count['total'];
                return $countno;
        }
        public function addserial($S_order_id,$S_order_qty,$S_order_serial,$order_id,$NOA,$product_cost,$c_p_id,$product_price,$product_name) {
                $status="";
                $addedNO=$NOA+1;
                try {
                        $insert = $this->conn->prepare("INSERT INTO oc_product_serial SET order_id = :order_id, serial = :S_order_serial, order_product_id = :S_order_id ");
                        $insert->bindValue(':order_id',  $order_id);
                        $insert->bindValue(':S_order_serial',  $S_order_serial);
                        $insert->bindValue(':S_order_id',  $S_order_id);
                        $insert->execute();

                        $updte = $this->conn->prepare("UPDATE oc_order_product SET no_serial_added =:addedNO WHERE  order_id =:order_id and order_product_id=:S_order_id");
                        $updte->bindValue(':addedNO',  $addedNO);
                        $updte->bindValue(':S_order_id',  $S_order_id);
                        $updte->bindValue(':order_id',  $order_id);
                        $updte->execute();

                        $p_base_cost = $this->conn->prepare("SELECT COUNT(id) as total FROM oc_product_base_cost WHERE product_id=:product_id");
                        $p_base_cost->bindValue(':product_id',  $c_p_id);
                        $p_base_cost->execute();                        
                        $p_base_count=$p_base_cost->fetch(PDO::FETCH_ASSOC);                       
                        if ($p_base_count['total']!=0) {
                            $updte_b = $this->conn->prepare("UPDATE oc_product_base_cost SET cost = :product_cost WHERE product_id=:product_id");
                            $updte_b->bindValue(':product_cost',  $product_cost);
                            $updte_b->bindValue(':product_id',  $c_p_id);
                            $updte_b->execute();
                        }else{
                            $insert_b = $this->conn->prepare("INSERT INTO oc_product_base_cost SET product_id=:product_id,cost=:product_cost");
                            $insert_b->bindValue(':product_id',  $c_p_id);
                            $insert_b->bindValue(':product_cost', $product_cost);
                            $insert_b->execute();
                        }

                        $select_product_aff = $this->conn->prepare("SELECT * FROM  oc_affiliate_costomer_sold_items  
                                                                    WHERE order_id=:order_id and product_id=:product_id  and status=:status");
                        $select_product_aff->bindValue(':order_id', $order_id);
                        $select_product_aff->bindValue(':product_id', $c_p_id);
                        $select_product_aff->bindValue(':status',  0);
                        $select_product_aff->execute();
                        $get_affiliate_seller_by_order_id=$select_product_aff->fetch(PDO::FETCH_ASSOC);
                        if($select_product_aff->rowCount()){
                            $SELLER_ID=$get_affiliate_seller_by_order_id['seller_id'];

                            $sql_validation = $this->conn->prepare("SELECT seller_id FROM oc_affiliate_program_validation where seller_id=:sid");
                            $sql_validation->bindValue(':sid', $SELLER_ID);
                            $sql_validation->execute();

                            if($sql_validation->rowCount()){
                                $updte_v = $this->conn->prepare("UPDATE oc_affiliate_program_validation SET total_sales=total_sales+1, date = convert_tz(utc_timestamp(),'-08:00','+0:00') WHERE seller_id =:sid");
                                $updte_v->bindValue(':sid', $SELLER_ID);
                                $updte_v->execute();
                            }else{
                                $insert_v = $this->conn->prepare("INSERT INTO oc_affiliate_program_validation SET seller_id=:sid,total_sales=1, date = convert_tz(utc_timestamp(),'-08:00','+0:00')");
                                $insert_v->bindValue(':sid', $SELLER_ID);
                                $insert_v->execute();
                            }

                            //no of sales
                            $get_no_sales = $this->conn->prepare("SELECT total_sales as total FROM oc_affiliate_program_validation where seller_id=:sid");
                            $get_no_sales->bindValue(':sid', $SELLER_ID);
                            $get_no_sales->execute();
                            $no_sales_count=$get_no_sales->fetch(PDO::FETCH_ASSOC);

                            //total deduction
                            $total_deductions=0;
                            $get_deductions = $this->conn->prepare("SELECT sum(value) as deduction FROM oc_order_total where order_id=:order_id and title=:title ");
                            $get_deductions->bindValue(':order_id', $order_id);
                            $get_deductions->bindValue(':title','(OP) system charges');
                            $get_deductions->execute();
                            $val_deductions=$get_deductions->fetch(PDO::FETCH_ASSOC);

                            if($get_deductions->rowCount()){
                                $total_deductions= $val_deductions['deduction'];
                            }else{
                               $total_deductions=0;
                            }

                            $gros_profit=0;
                            $gros_profit=$product_price-($total_deductions+$product_cost);

                            if($get_no_sales->rowCount()){
                                $total_ern=0;
                                if($no_sales_count['total']<4){
                                    //New member (1-3 successful sales) = 10% of GP
                                    $total_ern=$gros_profit*.1;
                                }else if($no_sales_count['total']<11){
                                    //Regular Member (4-10 successful sales) = 15% of GP
                                    $total_ern=$gros_profit*.15;                                    
                                }else{
                                    //Pro Member (10 above successful sales) = 20% of GP
                                    $total_ern=$gros_profit*.2;
                                }

                                $insert_cw = $this->conn->prepare("INSERT INTO oc_affiliate_wallet SET order_id=:order_id, seller_id =:seller_id,product_id=:product_id, product_name=:product_name,amount=:amount,date = convert_tz(utc_timestamp(),'-08:00','+0:00')");
                                $insert_cw->bindValue(':order_id', $order_id);
                                $insert_cw->bindValue(':seller_id', $SELLER_ID);
                                $insert_cw->bindValue(':product_id', $c_p_id);
                                $insert_cw->bindValue(':product_name', $product_name);
                                $insert_cw->bindValue(':amount', $total_ern);
                                $insert_cw->execute();

                                $updte_acsi = $this->conn->prepare("UPDATE oc_affiliate_costomer_sold_items SET status =:status WHERE seller_id =:s_id and product_id =:p_id");
                                $updte_acsi->bindValue(':status', 1);
                                $updte_acsi->bindValue(':s_id', $SELLER_ID);
                                $updte_acsi->bindValue(':p_id', $c_p_id);
                                $updte_acsi->execute();
                            }

                        }
                        $status="200" ;
                }catch(PDOexception $e){
                        $error_message = $e->getMessage();
                        $status="Database Error:". $error_message ;
                
                }
                 return $status;
               
        }

        public function creal_serlial($op_id,$clorder_id_s) {
                $status="";
                
                try {
                        $insert = $this->conn->prepare("DELETE FROM oc_product_serial WHERE order_id =:order_id AND order_product_id = :order_product_id");
                        $insert->bindValue(':order_id',  $clorder_id_s);
                        $insert->bindValue(':order_product_id',  $op_id);
                        $insert->execute();

                        $updte = $this->conn->prepare("UPDATE oc_order_product SET no_serial_added =0 where  order_id =:order_id and order_product_id=:S_order_id");
                        $updte->bindValue(':S_order_id',  $op_id);
                        $updte->bindValue(':order_id',  $clorder_id_s);
                        $updte->execute();

                        $status="200" ;

                }catch(PDOexception $e){
                        $error_message = $e->getMessage();
                        $status="Database Error:". $error_message ;
                
                }
                 return $status;
               
        }
	public  function insert_order_history($status_id, $comment, $order_id){
			
		$h = $this->conn->prepare("INSERT INTO oc_order_history SET order_id = :order_id, order_status_id = :order_status_id, notify = 0, comment=:comment, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00')");
        	$h->bindValue(':order_id', $order_id);
        	$h->bindValue(':order_status_id', $status_id);
        	$h->bindValue(':comment', $comment);
            $h->execute();
            if($status_id==49){
                $h_update = $this->conn->prepare("UPDATE oc_order SET date_modified = convert_tz(utc_timestamp(),'-08:00','+0:00'),wr=:wr WHERE order_id = :order_id");
                $h_update->bindValue(':order_id', $order_id);
                $h_update->bindValue(':wr', $comment);
                $h_update->execute();

            }
            $h = $this->conn->prepare("UPDATE oc_order SET order_status_id = :status_id WHERE order_id = :order_id");
            $h->bindValue(':order_id', $order_id);
            $h->bindValue(':status_id', $status_id);
            return $h->execute();

	}
}