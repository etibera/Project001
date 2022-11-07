<?php
require_once '../init.php';
class Returns {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    public function list($customer_id){
        $data = array();
        $st = $this->conn->prepare("SELECT r.return_id, r.order_id, r.firstname, r.lastname, rs.name as status, r.date_added 
        FROM oc_return r LEFT JOIN oc_return_status rs ON (r.return_status_id = rs.return_status_id) 
        WHERE r.customer_id = :customer_id ORDER BY r.return_id DESC ");
        $st->bindValue(':customer_id', (int) trim($customer_id));
        $st->execute();
        foreach($st->fetchAll(PDO::FETCH_ASSOC) as $result){
            $data[] = array(
                'returnId'  => $result['return_id'],
                'orderId'   => $result['order_id'],
                'status'     => $result['status'],
                'dateAdded' => date_format(date_create($result['date_added']),"F d, Y")
            );
        }
        return $data;
    }
    public function info($customer_id, $return_id){
        $data = array();
        $st = $this->conn->prepare("SELECT r.serial,r.return_id, r.order_id, r.firstname, r.lastname, r.email, r.telephone, r.product, r.model, r.quantity, r.opened, 
        (SELECT rr.name FROM oc_return_reason rr WHERE rr.return_reason_id = r.return_reason_id ) AS reason, 
        (SELECT ra.name FROM oc_return_action ra WHERE ra.return_action_id = r.return_action_id) AS action, 
        (SELECT rs.name FROM oc_return_status rs WHERE rs.return_status_id = r.return_status_id ) AS status, r.comment, r.date_ordered, r.date_added, r.date_modified 
        FROM oc_return r WHERE return_id = :return_id AND customer_id = :customer_id");
        $st->bindValue(':customer_id', (int) trim($customer_id));
        $st->bindValue(':return_id', (int) trim($return_id));
        $st->execute();
        foreach($st->fetchAll(PDO::FETCH_ASSOC) as $return_info){
            $data = array(
                'returnId' => intval($return_info['return_id']),
				'dateAdded' => date_format(date_create($return_info['date_added']),"F d, Y"),
				'orderId'=> $return_info['order_id'],
				'dateOrdered' => date_format(date_create($return_info['date_ordered']),"F d, Y"),
				'product' => $return_info['product'],
				'model' => $return_info['model'],
				'quantity' => $return_info['quantity'],
				'reason' => $return_info['reason'],
				'opened' => $return_info['opened'] ? 'Yes' : 'No',
				'comment' => nl2br($return_info['comment']),
				'action' => $return_info['action'],
				'serial' => $return_info['serial']
            );
        }
        return $data;
    }
    public function add($data = array()){
       try{
        $st = $this->conn->prepare("INSERT INTO oc_return SET serial =:serial_id, order_id = :order_id, 
        customer_id = :customer_id, firstname = :firstname, lastname = :lastname, email = :email, telephone = :telephone, 
        product = :product, model = :model, quantity = :quantity, opened = :opened, return_reason_id = :return_reason_id, 
        return_status_id = 1, product_id= 0, return_action_id = 0, comment = :comment, date_ordered = :date_ordered, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00'), 
        date_modified = convert_tz(utc_timestamp(),'-08:00','+0:00')");
        $st->bindValue(':serial_id', '');
        $st->bindValue(':order_id', (int) trim($data['order_id']));
        $st->bindValue(':customer_id', (int) trim($data['user_id']));
        $st->bindValue(':firstname', trim($data['firstname']));
        $st->bindValue(':lastname', trim($data['lastname']));
        $st->bindValue(':email', trim($data['email']));
        $st->bindValue(':telephone', trim($data['telephone']));
        $st->bindValue(':product', trim($data['product']));
        $st->bindValue(':model', trim($data['model']));
        $st->bindValue(':quantity', (int) trim($data['quantity']));
        $st->bindValue(':opened', (int) trim($data['opened']));
        $st->bindValue(':return_reason_id', (int) trim($data['return_reason_id']));
        $st->bindValue(':comment', trim($data['comment']));
        $st->bindValue(':date_ordered', date('Y-m-d', strtotime($data['date_ordered'])));
        $st->execute();
        return $this->conn->lastInsertId();
       }catch(Exception $e){
           echo $e->getMessage();
       }
    }
}
$return = new Returns();