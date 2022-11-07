<?php
require_once "../../include/database.php";
require_once "../../include/aliexpress_token.php";
class Aliexpress {
    public static $appkey = '32053793';
    public static $secret = '6c8efcd09baefea3518f95a1b94f3735';
    function __construct()
    {
        $this->conn = (new Database())->getmyDB();
    }

    public function getCategories(){
        $stmt = $this->conn->prepare("SELECT * FROM aliexpress_categories where parent_category_id is null; ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getChildCategories($category_id){
        $stmt = $this->conn->prepare("SELECT * FROM aliexpress_categories where parent_category_id = :category_id ");
        $stmt->bindValue(':category_id', $category_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getProducts($category_id, $page_number){
        $no_of_records_per_page = 5;
        $res = AliexpressToken::getProducts($category_id, $no_of_records_per_page, $page_number);
            $json = json_decode(json_encode($res->resp_result), TRUE);
            $product = isset($json['result']) ? $this->products($json['result']['products']['product']) : [];
            $exist = false;
            return array(
                'code' => $json['resp_code'],
                'msg' => $json['resp_msg'],
                'total' => isset($json['result']) ? intval($json['result']['total_record_count']) : 0,
                'products' => $product,
                'page_number' => $page_number,
                'total_product' => $this->total_product($product),
                'test' => $json['result']
            );
        // return $res;
    }
    public function total_product($product){
        $count = 0;
        foreach($product as $value){
            if($value['exist'] == false){
                $count += 1;
            }
        }
        return $count;
    }
    public function products($datas){
        $res = array();
        foreach($datas as $data){
            $res[] = array(
                'product_id' => $data['product_id'],
                'name' => $data['product_title'],
                'img' => $data['product_main_image_url'],
                'exist' => $this->checkExistProduct($data['product_id']),
               
            );
        }
        return $res;
    }
    public function checkExistProduct($product_id){
        $stmt = $this->conn->prepare("SELECT * FROM aliexpress_products where product_id = :product_id ");
        $stmt->bindValue(':product_id', $product_id);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? true: false;
    }
    public function insertProduct($product_id){
        $res = AliexpressToken::getProduct($product_id);
        $data = json_decode(json_encode($res->resp_result), TRUE)['result']['products']['product'];
        // return $data;
       if($data){
        $stmt = $this->conn->prepare("INSERT INTO  aliexpress_products set product_id=:product_id, `name` = :name, `image` = :image, price = :price, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00'), date_modified = convert_tz(utc_timestamp(),'-08:00','+0:00') ");
        $stmt->bindValue(':product_id',$data['product_id']);
        $stmt->bindValue(':name', $data['product_title']);
        $stmt->bindValue(':image', $data['product_main_image_url']);
        $stmt->bindValue(':price', $data['original_price']);
        $stmt->execute();
       }
       return $product_id;
    }
    public function getProductList(){
        $stmt = $this->conn->prepare("SELECT * FROM aliexpress_products ORDER BY date_added DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function deleteProduct($id){
        $stmt = $this->conn->prepare("DELETE FROM aliexpress_products WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
    public function setQuantity($quantity, $id){
        $stmt = $this->conn->prepare("UPDATE aliexpress_products SET quantity = :quantity WHERE id = :id");
        $stmt->bindValue(':quantity', $quantity);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
    public function setStatus($status, $id){
        $stmt = $this->conn->prepare("UPDATE aliexpress_products SET `status` = :status WHERE id = :id");
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}
$ae = new Aliexpress();


if(isset($_GET['action'])){
    $source = $_GET['action'];
}else{
    $source = "";
}
$data = array();
switch($source){
        case 'parent_category':
            echo json_encode($ae->getCategories());
        break;
        case 'child_category':
            echo json_encode($ae->getChildCategories($_GET['category_id']));
        break;
        case 'products':
            echo json_encode($ae->getProducts($_GET['category_id'], $_GET['page_number']));
        break;
        case 'insertProduct':
            echo json_encode($ae->insertProduct($_POST['product_id']));
        break;
        case 'productLists':
            echo json_encode($ae->getProductList());
        break;
        case 'deleteProductList':
            echo json_encode($ae->deleteProduct($_POST['id']));
        break;
        case 'setQuantity':
            echo json_encode($ae->setQuantity($_POST['quantity'], $_POST['id']));
        break;
        case 'setStatus':
            echo json_encode($ae->setStatus($_POST['status'], $_POST['id']));
        break;
    	default:
        break;
}




