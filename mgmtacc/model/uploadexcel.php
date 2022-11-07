<?php
require_once "../include/database.php";
class uploadexcel {
  private $conn;
  public function __construct(){
    $this->conn = new Database();
    $this->conn = $this->conn->getmyDB();
  }
  
  public function savedata($Product_Name,$Model,$Description,$Price,$Quantity,$Image,$Image2,$Image3,$Product_Tags,$Category_Id,$Delivery_Charge_Id,$Product_Brand_Id,$Status_Code){
    $stats="";
    try { 
          $isert_p = $this->conn->prepare("INSERT INTO oc_product SET model = :modeladd, sku = :sku, upc = :upc, ean = :ean, jan = :jan, isbn = :isbn, mpn = :mpn, location =:location , quantity = :quantity, minimum =:minimum , subtract =:subtract, stock_status_id = :stock_status_id, date_available = convert_tz(utc_timestamp(),'-08:00','+0:00'), manufacturer_id =:manufacturer_id, shipping = :shipping, price =:price, points = :points, weight = :weight, weight_class_id = :weight_class_id, length =:length, width =:width , height =:height, length_class_id = :length_class_id, new_design=:new_design,status = :status, tax_class_id = :tax_class_id,image=:image, sort_order = :sort_order, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00'),date_modified=convert_tz(utc_timestamp(),'-08:00','+0:00')");
            $isert_p->bindValue(':modeladd', $Model);
            $isert_p->bindValue(':sku', '');
            $isert_p->bindValue(':upc', '');
            $isert_p->bindValue(':ean', '');
            $isert_p->bindValue(':jan', '');
            $isert_p->bindValue(':isbn', '');
            $isert_p->bindValue(':mpn', '');
            $isert_p->bindValue(':location', '');
            $isert_p->bindValue(':quantity', (int)$Quantity);
            $isert_p->bindValue(':minimum', '1');
            $isert_p->bindValue(':subtract', '1');
            $isert_p->bindValue(':subtract', '1');
            $isert_p->bindValue(':stock_status_id', '7');
            $isert_p->bindValue(':manufacturer_id', '0');
            $isert_p->bindValue(':shipping', '1');
            $isert_p->bindValue(':price', (int)$Price);
            $isert_p->bindValue(':points', '0');
            $isert_p->bindValue(':weight', 0);
            $isert_p->bindValue(':weight_class_id', '1');
            $isert_p->bindValue(':length', 0);
            $isert_p->bindValue(':width', 0);
            $isert_p->bindValue(':height', 0);
            $isert_p->bindValue(':length_class_id', '1');
            $isert_p->bindValue(':status',(int) $Status_Code);
            $isert_p->bindValue(':tax_class_id', '0');
            $isert_p->bindValue(':sort_order', '1');
            $isert_p->bindValue(':image', $Image);
            $isert_p->bindValue(':new_design', 1);
            $isert_p->execute();
            $lastId = $this->conn->lastInsertId();

            $isert_pd = $this->conn->prepare("INSERT INTO oc_product_description SET product_id = :lastId, language_id =:language_id, name =:product_name, description =:description, tag = :tag, meta_title =:meta_title, meta_description = :meta_description, meta_keyword =:meta_keyword");            
            $isert_pd->bindValue(':lastId', $lastId);
            $isert_pd->bindValue(':language_id', '1');
            $isert_pd->bindValue(':product_name', $Product_Name);
            $isert_pd->bindValue(':description', $Description);
            $isert_pd->bindValue(':tag', $Product_Tags);
            $isert_pd->bindValue(':meta_title', $Model);
            $isert_pd->bindValue(':meta_description', '');
            $isert_pd->bindValue(':meta_keyword', '');
            $isert_pd->execute();

            $isert_pdc = $this->conn->prepare("INSERT INTO oc_product_delivery_charge SET product_id = :lastId, delivery_charge_id =:delivery_category");
            $isert_pdc->bindValue(':lastId', $lastId);
            $isert_pdc->bindValue(':delivery_category',(int) $Delivery_Charge_Id);
            $isert_pdc->execute(); 

            $isert_ppc = $this->conn->prepare("INSERT INTO oc_product_to_category SET product_id = :lastId, category_id =:product_category");
            $isert_ppc->bindValue(':lastId', $lastId);
            $isert_ppc->bindValue(':product_category', (int)$Category_Id);
            $isert_ppc->execute();

            $isert_brand = $this->conn->prepare("INSERT INTO product_to_brand SET product_id = :lastId, brand_id =:brand_id");
            $isert_brand->bindValue(':lastId', $lastId);
            $isert_brand->bindValue(':brand_id',(int) $Product_Brand_Id);
            $isert_brand->execute();

            $isert_image2 = $this->conn->prepare("INSERT INTO oc_product_image SET product_id = :lastId, image =:Image2, sort_order=:sort_order" );
            $isert_image2->bindValue(':lastId', $lastId);
            $isert_image2->bindValue(':Image2',$Image2);
            $isert_image2->bindValue(':sort_order','1');
            $isert_image2->execute();

            $isert_image3 = $this->conn->prepare("INSERT INTO oc_product_image SET product_id = :lastId, image=:Image3, sort_order=:sort_order" );
            $isert_image3->bindValue(':lastId', $lastId);
            $isert_image3->bindValue(':Image3',$Image3);
            $isert_image3->bindValue(':sort_order','2');
            $isert_image3->execute();


            $isert_pts = $this->conn->prepare("INSERT INTO oc_product_to_store SET product_id = :lastId, store_id =:store_id");
            $isert_pts->bindValue(':lastId', $lastId);
            $isert_pts->bindValue(':store_id', 0);
            $isert_pts->execute();
        $stats="Product Successfully Uploaded";
        return $stats;
    }catch(PDOexception $e){
      $stats=$e;
      return $stats;
           
    }
  }
  
}