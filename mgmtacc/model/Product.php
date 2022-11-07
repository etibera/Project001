<?php
require_once "../include/database.php";
class Product{
        private $conn;   
        public function __construct()
        {
                $this->conn = new Database();
                $this->conn = $this->conn->getmyDB();
        }
    public function deleteProduct($product_id) {         
        $stmt = $this->conn->prepare("DELETE FROM oc_product WHERE product_id = :product_id;
                                        DELETE FROM oc_product_description WHERE product_id = :product_id;
                                        DELETE FROM oc_product_delivery_charge WHERE product_id = :product_id;
                                        DELETE FROM oc_product_to_category WHERE product_id = :product_id;
                                        DELETE FROM product_to_brand WHERE product_id = :product_id;
                                        DELETE FROM oc_product_image WHERE product_id = :product_id;
                                        DELETE FROM oc_product_to_store WHERE product_id = :product_id;");
        $stmt->bindValue(':product_id', $product_id);
        $stmt->execute();
        $status = "Product Successfully Deleted";
        return $status;
    }  
    public function deleteProductBatch($sbrd){
        try{   
          $chk_idstr = implode(",", $sbrd);
          $sql="";
          $sql.=" DELETE FROM oc_product WHERE product_id  IN (".$chk_idstr."); ";
          $sql.=" DELETE FROM oc_product_description WHERE product_id  IN (".$chk_idstr."); ";
          $sql.=" DELETE FROM oc_product_delivery_charge WHERE product_id  IN (".$chk_idstr."); ";
          $sql.=" DELETE FROM oc_product_to_category WHERE product_id  IN (".$chk_idstr."); ";
          $sql.=" DELETE FROM product_to_brand WHERE product_id  IN (".$chk_idstr."); ";
          $sql.=" DELETE FROM oc_product_image WHERE product_id IN (".$chk_idstr."); ";
          $sql.=" DELETE FROM oc_product_to_store WHERE product_id IN (".$chk_idstr."); ";
          $stmt = $this->conn->prepare($sql);
          $stmt->execute();
          return "Successfully Deleted.";
        }catch(PDOexception $e){
             return $e;
        }
    }
	public  function product_list($product_name,$model,$price,$status,$quantity) {
		$s_price = "";
                $s_status = "";
                $s_qty= "";
                if(!empty($price)) {
                   $s_price =  "and p.price LIKE :price "; 
                }
                if($status != '2') {
                   $s_status =  "and p.status = :status "; 
                }
                if(!empty($quantity)) {
                   $s_qty =  "and p.quantity LIKE :quantity "; 
                }
		$stmt = $this->conn->prepare("SELECT p.*,p.product_id as prod_id, pd.name from oc_product p LEFT JOIN  oc_product_description pd ON p.product_id = pd.product_id where p.model LIKE :model and pd.name LIKE :product_name ".$s_price.$s_status.$s_qty." order by p.date_added desc limit 300");
		$stmt->bindValue(':product_name', '%'.$product_name.'%');
                $stmt->bindValue(':model', '%'.$model.'%');                
                if(!empty($price)) {
                   $stmt->bindValue(':price', '%'.$price.'%');
                }
                if($status != '2') {
                   $stmt->bindValue(':status',  $status);
                }
                if(!empty($quantity)) {
                   $stmt->bindValue(':quantity',  '%'.$quantity.'%');
                }
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);       
                return $row;
	}
    public function get_product($product_id){
         $stm = $this->conn->prepare("SELECT DISTINCT *, (SELECT keyword FROM oc_url_alias WHERE query = 'product_id=:product_id') AS keyword, (SELECT delivery_charge_id from oc_product_delivery_charge WHERE product_id = :product_id) as charge FROM oc_product p LEFT JOIN oc_product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = :product_id");
         $stm->bindValue(':product_id', $product_id);
         $stm->execute();
         return $stm->fetch();
    }
    public function edit_product($data,$img,$product_image,$p_att){
            //product
           try{
            $stm = $this->conn->prepare("UPDATE oc_product SET model = :model, sku = '', upc = '', ean = '', jan = '', isbn = '', mpn = '', location = '', quantity = :quantity, minimum = 1, subtract = 1, stock_status_id = 7, manufacturer_id = 0, shipping = 1, price = :price, points = 0, length_class_id = 1, status = :status, tax_class_id = 0, sort_order = 1, image=:image,new_design=:new_design, date_modified = convert_tz(utc_timestamp(),'-08:00','+0:00'),user_id=:user_id WHERE product_id = :product_id");
            $stm->bindValue(':model', $data['model']);
            $stm->bindValue(':quantity', $data['quantity']);
            $stm->bindValue(':price', $data['price']);
            $stm->bindValue(':status', $data['status']);
            $stm->bindValue(':image', $img);
            $stm->bindValue(':product_id', $data['product_id']);
            $stm->bindValue(':new_design', 1);
            $stm->bindValue(':user_id', $_SESSION['user_id']);
            $stm->execute();
            //product images 
            foreach ($product_image as $product_images) {
                if($product_images['id_edit']==0){
                    $isert_p_img = $this->conn->prepare("INSERT INTO oc_product_image SET product_id = :product_id, image =:image,sort_order=:sort_order");
                    $isert_p_img->bindValue(':product_id', $data['product_id']);
                    $isert_p_img->bindValue(':image',  $product_images['product_img']);
                    $isert_p_img->bindValue(':sort_order',  $product_images['sort_order']);
                    $isert_p_img->execute(); 
                }else{
                    $update_p_img = $this->conn->prepare("UPDATE oc_product_image SET image =:image,sort_order=:sort_order where product_image_id = :product_image_id");
                    $update_p_img->bindValue(':product_image_id', $product_images['id_edit']);
                    $update_p_img->bindValue(':image',  $product_images['product_img']);
                    $update_p_img->bindValue(':sort_order',  $product_images['sort_order']);
                    $update_p_img->execute(); 
                }
               
            }
            //product description and delivery charge
            $stm =  $this->conn->prepare(
                "DELETE FROM oc_product_description WHERE product_id = :product_id; 
                 DELETE FROM oc_product_delivery_charge WHERE product_id = :product_id;
                 DELETE FROM oc_product_to_store WHERE product_id = :product_id;
                 DELETE FROM oc_product_to_category WHERE product_id = :product_id;
                 DELETE FROM product_to_brand WHERE product_id = :product_id;
                 DELETE FROM oc_product_specification WHERE product_id = :product_id;
                ");
            $stm->bindValue(':product_id', $data['product_id']);
            $stm->execute();
          
            $stm = $this->conn->prepare(
                "INSERT INTO oc_product_description SET product_id = :product_id, language_id = 1, name = :name, description = :description, tag = :tag, meta_title = :name, meta_description = '', meta_keyword = '' ");
            $stm->bindValue(':product_id', $data['product_id']);
            $stm->bindValue(':name', $data['name']);
            $stm->bindValue(':description', $data['description']);
            $stm->bindValue(':tag', $data['tag']);
            $stm->execute();
            if($p_att){
                foreach ($p_att as $p_att_edit) {
                    $isert_p_atte = $this->conn->prepare("INSERT INTO oc_product_specification SET product_id = :product_id, specification_id =:att_id");
                    $isert_p_atte->bindValue(':product_id', $p_att_edit['product_id']);
                    $isert_p_atte->bindValue(':att_id',  $p_att_edit['id']);
                    $isert_p_atte->execute(); 

                }
            }
            

            $stm = $this->conn->prepare(
                " INSERT INTO oc_product_delivery_charge SET product_id = :product_id, delivery_charge_id = :charge;");
            $stm->bindValue(':product_id', $data['product_id']);
            $stm->bindValue(':charge', $data['charge']);
            $stm->execute();

            $isert_pts = $this->conn->prepare("INSERT INTO oc_product_to_store SET product_id = :product_id, store_id =:store_id");
            $isert_pts->bindValue(':product_id',$data['product_id']);
            $isert_pts->bindValue(':store_id', 0);
            $isert_pts->execute();

            $isert_ppc = $this->conn->prepare("INSERT INTO oc_product_to_category SET product_id = :product_id, category_id =:product_category");
            $isert_ppc->bindValue(':product_id',  $data['product_id']);
            $isert_ppc->bindValue(':product_category', $data['product_category']);
            $isert_ppc->execute();

            $isert_brand = $this->conn->prepare("INSERT INTO product_to_brand SET product_id = :lastId, brand_id =:brand_id");
            $isert_brand->bindValue(':lastId', $data['product_id']);
            $isert_brand->bindValue(':brand_id', $data["product_brand"]);
            $isert_brand->execute();

            

            $status = true;
           }catch(PDOexception $e){
                echo "<p>Database Error: $e </p>";
                $status = false;
           }
           return $status;
    }

    public function getdelvery_charge() {
            $stmt = $this->conn->prepare("SELECT * FROM oc_delivery_charge");
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
     public function delte_image($id) {
         try{
            $stmt = $this->conn->prepare("DELETE FROM oc_product_image where product_image_id = :product_image_id");
            $stmt->bindValue(':product_image_id',$id);
            $stmt->execute();
            $status=true;
        }catch(PDOexception $e){
            echo "<p>Database Error: $e </p>";
            $status = false;
        }
        return $status;
    }
    public  function get_product_cat($product_id){
        $stmt =$this->conn->prepare("SELECT category_id FROM oc_product_to_category where product_id=:product_id");
        $stmt->bindValue(':product_id',$product_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['category_id'];
    } 
    public  function get_brand_id($product_id){
        $stmt =$this->conn->prepare("SELECT brand_id FROM product_to_brand where product_id=:product_id");
        $stmt->bindValue(':product_id',$product_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['brand_id'];
    }

    public  function get_product_brand($status){
        $stmt =$this->conn->prepare("SELECT * FROM oc_product_brand");
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    
    public function getCategories($parent_id = 0){
        $stmt = $this->conn->prepare("SELECT * FROM oc_category c 
                                    LEFT JOIN oc_category_description cd 
                                    ON (c.category_id = cd.category_id) 
                                    LEFT JOIN oc_category_to_store c2s ON (c.category_id = c2s.category_id) 
                                    WHERE c.parent_id = :parent_id 
                                        AND cd.language_id = 1
                                        AND c2s.store_id = 0 
                                        AND c.status = '1' 
                                    ORDER BY c.sort_order, LCASE(cd.name)");
        $stmt->bindValue(':parent_id',$parent_id);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    } 
    public function get_product_img($product_id){
        $stmt = $this->conn->prepare("SELECT * FROM oc_product_image  WHERE product_id = :product_id order by sort_order ASC");
        $stmt->bindValue(':product_id',$product_id);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
     public function get_product_att($product_id){
        $stmt = $this->conn->prepare("SELECT * FROM oc_product_specification  WHERE product_id = :product_id order by id ASC");
        $stmt->bindValue(':product_id',$product_id);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
     public function get_attribute(){
        $stmt = $this->conn->prepare("SELECT * FROM oc_specification  order by id desc");
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    public function add_product($data) {
            
        try{
        foreach ($data as $row) {
            $product_name=$row["product_name"];
            $description=$row["description"];
            $delivery_category=$row["delivery_category"];
            $product_category=$row["product_category"];
            $product_tags=$row["product_tags"];
            $modeladd=$row["modeladd"];
            $price=$row["price"];
            $quantity=$row["quantity"];
            $statusadd=$row["status"];
            $img=$row["img"];
            $isert_p = $this->conn->prepare("INSERT INTO oc_product SET model = :modeladd, sku = :sku, upc = :upc, ean = :ean, jan = :jan, isbn = :isbn, mpn = :mpn, location =:location , quantity = :quantity, minimum =:minimum , subtract =:subtract, stock_status_id = :stock_status_id, date_available = convert_tz(utc_timestamp(),'-08:00','+0:00'), manufacturer_id =:manufacturer_id, shipping = :shipping, price =:price, points = :points, weight = :weight, weight_class_id = :weight_class_id, length =:length, width =:width , height =:height, length_class_id = :length_class_id, new_design=:new_design,status = :status, tax_class_id = :tax_class_id,image=:image, sort_order = :sort_order, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00'),date_modified=convert_tz(utc_timestamp(),'-08:00','+0:00')");
            $isert_p->bindValue(':modeladd', $modeladd);
            $isert_p->bindValue(':sku', '');
            $isert_p->bindValue(':upc', '');
            $isert_p->bindValue(':ean', '');
            $isert_p->bindValue(':jan', '');
            $isert_p->bindValue(':isbn', '');
            $isert_p->bindValue(':mpn', '');
            $isert_p->bindValue(':location', '');
            $isert_p->bindValue(':quantity', $quantity);
            $isert_p->bindValue(':minimum', '1');
            $isert_p->bindValue(':subtract', '1');
            $isert_p->bindValue(':subtract', '1');
            $isert_p->bindValue(':stock_status_id', '7');
            $isert_p->bindValue(':manufacturer_id', '0');
            $isert_p->bindValue(':shipping', '1');
            $isert_p->bindValue(':price', $price);
            $isert_p->bindValue(':points', '0');
            $isert_p->bindValue(':weight', 0);
            $isert_p->bindValue(':weight_class_id', '1');
            $isert_p->bindValue(':length', 0);
            $isert_p->bindValue(':width', 0);
            $isert_p->bindValue(':height', 0);
            $isert_p->bindValue(':length_class_id', '1');
            $isert_p->bindValue(':status', $statusadd);
            $isert_p->bindValue(':tax_class_id', '0');
            $isert_p->bindValue(':sort_order', '1');
            $isert_p->bindValue(':image', $img);
            $isert_p->bindValue(':new_design', 1);
            $isert_p->execute();
            $lastId = $this->conn->lastInsertId();

            foreach ($row['product_image'] as $product_images) {
                $isert_p_img = $this->conn->prepare("INSERT INTO oc_product_image SET product_id = :lastId, image =:image,sort_order=:sort_order");
                $isert_p_img->bindValue(':lastId', $lastId);
                $isert_p_img->bindValue(':image',  $product_images['product_image']);
                $isert_p_img->bindValue(':sort_order',  $product_images['sort_order']);
                $isert_p_img->execute(); 
            }
            if($row['product_att']){
                foreach ($row['product_att'] as $product_attribute) {
                    $isert_p_img = $this->conn->prepare("INSERT INTO oc_product_specification SET product_id = :lastId, specification_id =:att_id");
                    $isert_p_img->bindValue(':lastId', $lastId);
                    $isert_p_img->bindValue(':att_id',  $product_attribute['id']);
                    $isert_p_img->execute(); 
                }

            }
            
            $isert_pd = $this->conn->prepare("INSERT INTO oc_product_description SET product_id = :lastId, language_id =:language_id, name =:product_name, description =:description, tag = :tag, meta_title =:meta_title, meta_description = :meta_description, meta_keyword =:meta_keyword");            
            $isert_pd->bindValue(':lastId', $lastId);
            $isert_pd->bindValue(':language_id', '1');
            $isert_pd->bindValue(':product_name', $product_name);
            $isert_pd->bindValue(':description', $description);
            $isert_pd->bindValue(':tag', $product_tags);
            $isert_pd->bindValue(':meta_title', $modeladd);
            $isert_pd->bindValue(':meta_description', '');
            $isert_pd->bindValue(':meta_keyword', '');
            $isert_pd->execute();

            $isert_pdc = $this->conn->prepare("INSERT INTO oc_product_delivery_charge SET product_id = :lastId, delivery_charge_id =:delivery_category");
            $isert_pdc->bindValue(':lastId', $lastId);
            $isert_pdc->bindValue(':delivery_category', $delivery_category);
            $isert_pdc->execute(); 

            $isert_ppc = $this->conn->prepare("INSERT INTO oc_product_to_category SET product_id = :lastId, category_id =:product_category");
            $isert_ppc->bindValue(':lastId', $lastId);
            $isert_ppc->bindValue(':product_category', $product_category);
            $isert_ppc->execute();

            $isert_brand = $this->conn->prepare("INSERT INTO product_to_brand SET product_id = :lastId, brand_id =:brand_id");
            $isert_brand->bindValue(':lastId', $lastId);
            $isert_brand->bindValue(':brand_id', $row["product_brand"]);
            $isert_brand->execute();

            $isert_pts = $this->conn->prepare("INSERT INTO oc_product_to_store SET product_id = :lastId, store_id =:store_id");
            $isert_pts->bindValue(':lastId', $lastId);
            $isert_pts->bindValue(':store_id', 0);
            $isert_pts->execute();
            $status = true;
        }

        }catch(PDOexception $e){
                echo "<p>Database Error: $e </p>";
                $status = false;
           }
           return $status;
    }
	
}