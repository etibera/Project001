<?php
require_once '../init.php';
class Wishlist {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    function __destruct(){
        $this->conn = null;
    }
    public function lists($customer_id){
        global $image;
        global $product;
        $data = array();
        $s = $this->conn->prepare('SELECT cw.*, pp.name, pp.img FROM 
        (SELECT p.product_id, p.status, pd.name, p.image img FROM oc_product p LEFT JOIN oc_product_description pd ON pd.product_id = p.product_id UNION ALL SELECT bg.product_id, bg.status, bg.product_name as `name`, bg.img FROM bg_product bg ) pp 
        JOIN oc_customer_wishlist cw ON cw.product_id = pp.product_id WHERE cw.customer_id = :customer_id AND pp.status = 1 ORDER BY cw.date_added DESC');
        $s->bindValue(':customer_id', (int) $customer_id);
        $s->execute();
        foreach($s->fetchAll(PDO::FETCH_ASSOC) as $w){
                $img = $w['p_type'] == 0 ? $image->resize($w['img'], 200, 200) : $w['img'];
                $data[] = array(
                    'date' => $w['date_added'],
                    'product_id' => $w['product_id'],
                    'name' => $w['name'],
                    'thumb' => $img,
                    'type' => $this->p_typeString($w['p_type'])
                );
        }
        return $data;
    }
    public function getProduct($product_id){
        global $image;
        global $product;
        $data = array();
        $s = $this->conn->prepare('	SELECT pp.name, pp.img, pp.product_id, pp.price, pp.type FROM 
	(SELECT p.product_id, p.status, pd.name, p.image img, p.price, 0 as `type` FROM oc_product p LEFT JOIN oc_product_description pd ON pd.product_id = p.product_id UNION ALL 
    SELECT bg.product_id, bg.status, bg.product_name as `name`, bg.img, bg.price, 2 as `type`  FROM bg_product bg ) pp 
	WHERE pp.product_id = :product_id');
        $s->bindValue(':product_id', (int) $product_id);
        $s->execute();
        $w = $s->fetch();
                $img = $w['type'] == 0 ? $image->resize($w['img'], 200, 200) : $w['img'];
                $data = array(
                    'productId' => $w['product_id'],
                    'name' => $w['name'],
                    'thumb' => $img,
                    'type' => $this->p_typeString($w['type']),
                    'price' => (float)$w['price'],
                    'quantity' => 1,
                    'brand' => $product->brandName($w['product_id']),
                    'category' => html_entity_decode($product->product_category_names($w['product_id']))
        );
        return $data;
    }
    public function add($customer_id, $product_id, $p_type){
        $p_type = $this->p_typeNum($p_type);
        $s = $this->conn->prepare('SELECT * FROM oc_customer_wishlist WHERE customer_id = :customer_id AND product_id = :product_id AND p_type = :p_type');
        $s->bindValue(':customer_id', (int) trim($customer_id));
        $s->bindValue(':product_id', (int) trim($product_id));
        $s->bindValue(':p_type', (int) trim($p_type));
        $s->execute();
        if($s->rowCount() > 0){
            $message = 'Item already on Wishlist';
        }else{
            try {
                $s = $this->conn->prepare("INSERT INTO oc_customer_wishlist SET customer_id = :customer_id, product_id = :product_id, p_type = :p_type, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00')");
                $s->bindValue(':customer_id', (int) trim($customer_id));
                $s->bindValue(':product_id', (int) trim($product_id));
                $s->bindValue(':p_type', (int) trim($p_type));
                $s->execute();
                $message = 'Item added on Wishlist';
            }catch(Exception $e){
                $message = $e->getMessage();
            }
        }
        $data['message'] = $message;
        $data['product'] = $this->getProduct($product_id);
        return $data;
    }
    public function delete($customer_id, $product_id){
        try{
            $s = $this->conn->prepare('DELETE FROM oc_customer_wishlist WHERE customer_id = :customer_id AND product_id = :product_id');
            $s->bindValue(':customer_id', (int) trim($customer_id));
            $s->bindValue(':product_id', (int) trim($product_id));
            $s->execute();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function p_typeNum($type){
        switch($type){
            case 'reg':
            return 0;
            case 'bg':
            return 2;
        }
    }
    public function p_typeString($type){
        switch($type){
            case 0:
            return 'reg';
            case 2:
            return 'bg';
        }
    }
}
$wishlist = new Wishlist();