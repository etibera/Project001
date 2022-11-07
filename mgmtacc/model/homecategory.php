  <?php
require_once "../include/database.php";
class homecategory {

    private $conn;
    public function __construct()
    {
    $this->conn = new Database();
    $this->conn = $this->conn->getmyDB();
    }

    public function gethomecategorylistbydef(){              
          
        $stmt = $this->conn->prepare("SELECT ochc.*,cd.name,cd.category_id as cid,ochc.id as ochcid FROM oc_customer_home_category ochc  LEFT JOIN oc_category_description cd ON cd.category_id= ochc.category_id WHERE ochc.customer_id='0' order by ochc.id asc");
        $stmt->execute(); 
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    } 
    public function getCategories($parent_id = 0)
    {
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
    public  function gethomecategory($category_id){
        //global $db;
        $stmt =$this->conn->prepare("SELECT count(id) as countid FROM oc_customer_home_category WHERE customer_id=:customer_id AND category_id=:category_id");
        $stmt->bindValue(':customer_id','0');
        $stmt->bindValue(':category_id',$category_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['countid'];
    }
    public function addhomecategory($options_category,$show_limit,$add_sort,$add_sort_name) {
        $stmt =$this->conn->prepare("INSERT INTO oc_customer_home_category SET customer_id=:customer_id , category_id=:options_category,show_limit=:show_limit,sort_order=:add_sort,sort_order_name=:add_sort_name");
        $stmt->bindValue(':customer_id','0');
        $stmt->bindValue(':options_category',$options_category);
        $stmt->bindValue(':show_limit',$show_limit);
        $stmt->bindValue(':add_sort',$add_sort);
        $stmt->bindValue(':add_sort_name',$add_sort_name);
        $stmt->execute();
        
    }
    public function deletehomecategory($id) {
        $stmt =$this->conn->prepare("DELETE FROM oc_customer_home_category WHERE id=:id");
        $stmt->bindValue(':id',$id);
        $stmt->execute();
    }
    public function updatehomecategory($id,$category_id,$show_limit,$add_sort,$add_sort_name) {
        
        $stmt =$this->conn->prepare("UPDATE oc_customer_home_category SET category_id=:category_id,show_limit=:show_limit,sort_order=:add_sort,sort_order_name=:add_sort_name WHERE id=:id");
        $stmt->bindValue(':category_id',$category_id);
        $stmt->bindValue(':show_limit',$show_limit);
        $stmt->bindValue(':add_sort',$add_sort);
        $stmt->bindValue(':add_sort_name',$add_sort_name);
        $stmt->bindValue(':id',$id);
        $stmt->execute();
    }
    public function delete_product_category_id($category_id,$id) {  
        $del =$this->conn->prepare("DELETE FROM  oc_home_category_product_list WHERE category_id=:category_id ");
        $del->bindValue(':category_id',$category_id);
        $del->execute();

        $update =$this->conn->prepare("UPDATE oc_customer_home_category SET category_id=:category_id,status=:status WHERE id=:id");
        $update->bindValue(':category_id',$category_id);
        $update->bindValue(':status',0);
        $update->bindValue(':id',$id);
        $update->execute();
    }
    public function get_product_under_category_id($catecory_id) {
        $stmt = $this->conn->prepare("SELECT optc.product_id as product_id,opd.name as name 
                                      FROM oc_product_to_category optc 
                                      INNER JOIN oc_product_description opd 
                                        on opd.product_id=optc.product_id  
                                      where optc.category_id=:catecory_id 
                                      order by opd.product_id DESC 
                                      LIMIT 300");
        $stmt->bindValue(':catecory_id',$catecory_id);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }   
    public function add_product_category_id($category_id,$product_id,$h_id) {
         $stmt =$this->conn->prepare("SELECT count(id) as countid  FROM oc_home_category_product_list 
                                      where category_id=:category_id and product_id =:product_id");
        $stmt->bindValue(':category_id',$category_id);
        $stmt->bindValue(':product_id',$product_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row['countid']==0) {
            $insert =$this->conn->prepare("INSERT INTO oc_home_category_product_list SET category_id=:category_id,product_id=:product_id");
            $insert->bindValue(':category_id',$category_id);
            $insert->bindValue(':product_id',$product_id);
            $insert->execute();
        }
        $insert =$this->conn->prepare("UPDATE  oc_customer_home_category SET category_id=:category_id,status=:status WHERE id=:h_id");
        $insert->bindValue(':category_id',$category_id);
        $insert->bindValue(':status',1);
        $insert->bindValue(':h_id',$h_id);
        $insert->execute();
    }
    public function getLatesPromolist() {
        $stmt = $this->conn->prepare("SELECT * FROM latest_promo order by id desc");
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    public function getLatesPromolist_id($id) {
        $stmt = $this->conn->prepare("SELECT * FROM latest_promo where id=:id order by id desc");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }  
    public function get_seller_list() {
        $stmt = $this->conn->prepare("SELECT *, concat('../img/company/',image) as image  FROM oc_seller where status=1 order by shop_name asc");
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    } 
    
    public function getLatesPromoSellerList($id) {
        $stmt = $this->conn->prepare("SELECT lpsl.*,os.shop_name, concat('../img/company/',os.image) as image  
                                      FROM latest_promo_seller_list lpsl
                                      INNER JOIN oc_seller os ON lpsl.seller_id=os.seller_id
                                      WHERE lpsl.latest_promo_id=:id order by id desc");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $row=array();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
             $row[] = array(
                'seller_id' => utf8_encode($value['seller_id']),
                'id' => utf8_encode($value['id']),
                'latest_promo_id' => $value['latest_promo_id'],
                'seller_list_id' => $value['seller_list_id'],
                'shop_name' => $value['shop_name'],
                'image' => $value['image'],
                'seller_promo_list' =>$this->getseller_promo_list($value['id']),
            );
        }
        return $row;
    } 
    public function delete_seller($data){
        try{   
          $chk_idstr = implode(",", $data);
          $stmt = $this->conn->prepare("DELETE FROM latest_promo_seller_list WHERE id IN (".$chk_idstr.")");
          $stmt->execute();
          $stmt2 = $this->conn->prepare("DELETE FROM lp_seller_promo_list WHERE seller_list_id IN (".$chk_idstr.")");
          $stmt2->execute();
          return "Successfully Deleted.";
        }catch(PDOexception $e){
             return $e;
        }
      }  
      public function save_seller($data,$seller_id,$lpid){
        try{             
          $stmt = $this->conn->prepare("DELETE FROM latest_promo_seller_list WHERE seller_id=:seller_id AND latest_promo_id =:lpid");
          $stmt->bindValue(':seller_id', $seller_id);
          $stmt->bindValue(':lpid', $lpid);
          $stmt->execute();

          $stmt1 = $this->conn->prepare("INSERT INTO latest_promo_seller_list SET 
                                                seller_id =:seller_id,latest_promo_id =:lpid,seller_list_id =:lpid");
          $stmt1->bindValue(':seller_id', $seller_id);
          $stmt1->bindValue(':lpid', $lpid);
          $stmt1->execute();
          $lastId = $this->conn->lastInsertId();
          foreach ($data  as $value) {              
              $stmt2 = $this->conn->prepare("INSERT INTO lp_seller_promo_list SET  deduction_id =:deduction_id,latest_promo_id =:lpid,seller_list_id =:lastId");              
              $stmt2->bindValue(':deduction_id', $value);
              $stmt2->bindValue(':lpid', $lpid);
              $stmt2->bindValue(':lastId', $lastId);
              $stmt2->execute();
          }
          return "Successfully Added.";
        }catch(PDOexception $e){
             return $e;
        }
      } 
    public function delete_seller_promo($data){
        try{   
          $chk_idstr = implode(",", $data);         
          $stmt2 = $this->conn->prepare("DELETE FROM lp_seller_promo_list WHERE id IN (".$chk_idstr.")");
          $stmt2->execute();
          return "Successfully Deleted.";
        }catch(PDOexception $e){
             return $e;
        }
      } 
    public function getseller_promo_list($seller_list_id){
        $stmt = $this->conn->prepare("SELECT lsp.*,sd.description,sd.type,sd.value,sd.seller_id, 
                                        CASE WHEN (sd.deduction_type = 0) THEN 'Percentage (%)' ELSE 'PHP' END AS d_type,
                                        DATE_FORMAT(sd.date_from, '%M %d %Y') as date_f,
                                        DATE_FORMAT(sd.date_to, '%M %d %Y') as date_t  
                            FROM lp_seller_promo_list lsp 
                            INNER JOIN seller_deductions sd on sd.id=lsp.deduction_id
                            where seller_list_id=:seller_list_id;");
        $stmt->bindValue(':seller_list_id', $seller_list_id);
        $stmt->execute();
        $row=array();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
             $row[] = array(
                'description' => utf8_encode($value['description']),
                'd_type' => utf8_encode($value['d_type']),
                'type' => $value['type'],
                'value' => $value['value'],
                'seller_id' => $value['seller_id'],
                'id' => $value['id'],
                'date_from' => $value['date_f'],
                'date_to' => $value['date_t'],
            );
        }
        return $row;
    }
    public function getpromoperseller($seller_id){
        $stmt = $this->conn->prepare("SELECT *, 
                                        CASE WHEN (deduction_type = 0) THEN 'Percentage (%)' ELSE 'PHP' END AS d_type,
                                        DATE_FORMAT(date_from, '%M %d %Y') as date_f,
                                        DATE_FORMAT(date_to, '%M %d %Y') as date_t  
                                    FROM seller_deductions 
                                    WHERE seller_id=:seller_id 
                                        AND 
                                            (
                                                date_from <=  DATE_FORMAT(convert_tz(utc_timestamp(),'-08:00','+0:00'),'%Y-%m-%d') 
                                                AND
                                                date_to >= DATE_FORMAT(convert_tz(utc_timestamp(),'-08:00','+0:00'),'%Y-%m-%d')
                                            )
                                    UNION ALL 
                                    SELECT *, 
                                                CASE WHEN (deduction_type = 0) THEN 'Percentage (%)' ELSE 'PHP' END AS d_type,
                                                DATE_FORMAT(date_from, '%M %d %Y') as date_f,
                                                DATE_FORMAT(date_to, '%M %d %Y') as date_t 
                                    FROM seller_deductions 
                                    where seller_id=:seller_id 
                                          AND  
                                            (
                                                date_from >  DATE_FORMAT(convert_tz(utc_timestamp(),'-08:00','+0:00'),'%Y-%m-%d') 
                                                AND 
                                                date_to > DATE_FORMAT(convert_tz(utc_timestamp(),'-08:00','+0:00'),'%Y-%m-%d')
                                            )");
        $stmt->bindValue(':seller_id', $seller_id);
        $stmt->execute();
        $row=array();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
             $row[] = array(
                'description' => utf8_encode($value['description']),
                'd_type' => utf8_encode($value['d_type']),
                'type' => $value['type'],
                'value' => $value['value'],
                'seller_id' => $value['seller_id'],
                'id' => $value['id'],
                'date_from' => $value['date_f'],
                'date_to' => $value['date_t'],
            );
        }
        return $row;
    }

    public function getLatesPromolist_product($id) {
        $stmt = $this->conn->prepare("SELECT * FROM latest_promo_products where promo_id=:id order by date desc");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    } 
     public function getLatesPromolist_category($id) {
        $stmt = $this->conn->prepare("SELECT lpc.*,ocd.name  FROM latest_promo_category  lpc
                                        INNER JOIN oc_category occ ON occ.category_id=lpc.category_id
                                        INNER JOIN  oc_category_description ocd ON  occ.category_id = ocd.category_id where lpc.lp_id=:id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    } 
    public function get_products_by_type($id,$type) {
        if($type=="0"){
            $stmt = $this->conn->prepare("SELECT p.product_id,p.price,pd.name,concat('../img/',p.image) as image
                                                FROM oc_product p 
                                                INNER JOIN oc_product_description pd ON (p.product_id = pd.product_id) 
                                                WHERE p.product_id=:id AND p.status = :s ");
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':s', 1);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        }else if($type=="1"){
            $stmt = $this->conn->prepare("SELECT cp.goods_sn as product_id,cp.price,cp.product_title as name,cp.product_img as image
                                        FROM oc_china_product cp 
                                        WHERE cp.goods_sn=:id AND cp.status=:s");
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':s', 1);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

        }else{
            $stmt = $this->conn->prepare("SELECT bg.product_id,bg.price,bg.product_name as name,bg.img as image
                                        FROM bg_product bg 
                                        WHERE bg.product_id=:id AND bg.status=:s ");
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':s', 1);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return $row;
    }
    public function save_pl_producst($data) {
         try { 
            foreach ($data['p_ids'] as $product_id) {
                $pid_data= explode("--",$product_id);
                $count_duplicate = $this->conn->prepare("SELECT count(id) as id FROM latest_promo_products where product_id=:product_id AND type=:type AND promo_id=:promo_id");
                $count_duplicate->bindValue(':product_id',$pid_data[0]);
                $count_duplicate->bindValue(':type',$pid_data[1]);
                $count_duplicate->bindValue(':promo_id',$data['lp_id']);
                $count_duplicate->execute();
                $count_val = $count_duplicate->fetch(PDO::FETCH_ASSOC);
                if($count_val['id']==0){
                    $insert =$this->conn->prepare("INSERT INTO latest_promo_products SET product_id=:product_id,type=:type,promo_id=:promo_id,date= convert_tz(utc_timestamp(),'-08:00','+0:00') ");
                    $insert->bindValue(':product_id',$pid_data[0]);
                    $insert->bindValue(':type',$pid_data[1]);
                    $insert->bindValue(':promo_id',$data['lp_id']);
                    $insert->execute();
                }
                $status = "200";
            }
            return  $status;
        }catch(Exception $e){
             return $e;
        } 
       
    }
     public function save_pl_cat($data) {
         try { 
                $count_duplicate = $this->conn->prepare("SELECT count(id) as id FROM latest_promo_category where category_id=:category_id AND lp_id=:lp_id");
                $count_duplicate->bindValue(':category_id',$data['category_add']);
                $count_duplicate->bindValue(':lp_id',$data['lp_id']);
                $count_duplicate->execute();
                $count_val = $count_duplicate->fetch(PDO::FETCH_ASSOC);
                if($count_val['id']==0){
                    $insert =$this->conn->prepare("INSERT INTO latest_promo_category SET lp_id=:lp_id,category_id=:category_id");
                    $insert->bindValue(':category_id',$data['category_add']);
                    $insert->bindValue(':lp_id',$data['lp_id']);
                    $insert->execute();
                }
                $status = "200";
            
            return  $status;
        }catch(Exception $e){
             return $e;
        } 
       
    }
    public function get_search_all_products($search_val) {
        $stmt = $this->conn->prepare("SELECT s.product_id,s.model,s.price,s.name,s.image,s.type,s.typedesc 
                                        FROM ( SELECT p.product_id,p.model,p.price,pd.name,concat('../img/',p.image) as image,'Local Product' as typedesc,'0' as type 
                                                FROM oc_product p 
                                                INNER JOIN oc_product_description pd ON (p.product_id = pd.product_id) 
                                                WHERE (pd.name like :searchval OR p.model like :searchval) AND p.status = :s 
                                               /* UNION ALL 
                                                SELECT bg.product_id,bg.price,bg.product_name as name,bg.img as image,'Banngood Product'as typedesc,'2' as type
                                                FROM bg_product bg 
                                                INNER JOIN bg_product_category bgpc ON bg.cat_id=bgpc.cat_id 
                                                WHERE (bg.product_name like :searchval OR bgpc.cat_name like :searchval) AND bg.status=:s AND bgpc.status=:s
                                                UNION ALL
                                                SELECT cp.goods_sn as product_id,cp.price,cp.product_title as name,cp.product_img as image,'China Brand Product' as typedesc,'1' as type
                                                FROM oc_china_product cp 
                                                WHERE cp.product_title like :searchval AND cp.status=:s*/
                                            ) as s order by s.name asc");
        $stmt->bindValue(':s', 1);
        $stmt->bindValue(':searchval','%'.$search_val.'%');
        $stmt->execute();
        $row = $stmt->fetchall(PDO::FETCH_ASSOC);
        return $row;
    }
    public function getlp_details($id) {
        $stmt = $this->conn->prepare("SELECT * FROM latest_promo where id=:id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
    public function lp_disable($id) {
        try {
            $stmt =$this->conn->prepare("UPDATE  latest_promo set status=:status WHERE id=:id ");
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':status', 0);
            $stmt->execute();
            return "200";
        }catch(Exception $e){
             return $e;
        } 
    }
    public function lp_enable($id) {
        try {
            $stmt =$this->conn->prepare("UPDATE  latest_promo set status=:status WHERE id=:id ");
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':status', 1);
            $stmt->execute();
            return "200";
        }catch(Exception $e){
             return $e;
        } 
    } 
    public function lp_delete($id) {
        try {
            $stmt =$this->conn->prepare("DELETE FROM latest_promo WHERE id=:id ");
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return "200";
        }catch(Exception $e){
             return $e;
        } 
    }  
    public function lp_delete_pr($id) {
        try {
            $stmt =$this->conn->prepare("DELETE FROM latest_promo_products WHERE id=:id ");
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return "200";
        }catch(Exception $e){
             return $e;
        } 
    } 
    public function lp_delete_cat($id) {
        try {
            $stmt =$this->conn->prepare("DELETE FROM latest_promo_category WHERE id=:id");
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return "200";
        }catch(Exception $e){
             return $e;
        } 
    } 

    public function add_latest_promo($data) {
        try {
                $insert =$this->conn->prepare("INSERT INTO latest_promo SET title=:title,image=:image,sort=:sort,status=:status,thumbnail_image=:thumbnail_image,featured_promo=:featured_promo,promo_title_image=:promo_title_image,exclusive_for=:exclusive_for");
                $insert->bindValue(':title',$data[0]['title']);
                $insert->bindValue(':image',$data[0]['image']);
                $insert->bindValue(':sort',$data[0]['sort_order']);
                $insert->bindValue(':status',$data[0]['status']);
                $insert->bindValue(':thumbnail_image',$data[0]['thumbnail_image']);
                $insert->bindValue(':featured_promo',$data[0]['featured_promo']);
                $insert->bindValue(':promo_title_image',$data[0]['promo_title_image']);
                $insert->bindValue(':exclusive_for',$data[0]['exclusive_for']);
                $insert->execute();
                $status = "200";
                return  $status;
        }catch(Exception $e){
             return $e;
        } 
    } 
    public function Update_latest_promo($data) {
        try {
                $insert =$this->conn->prepare("UPDATE latest_promo SET title=:title,image=:image,sort=:sort,status=:status,thumbnail_image=:thumbnail_image,featured_promo=:featured_promo,promo_title_image=:promo_title_image,exclusive_for=:exclusive_for WHERE id=:edit_id");
                $insert->bindValue(':title',$data[0]['title']);
                $insert->bindValue(':image',$data[0]['image']);
                $insert->bindValue(':sort',$data[0]['sort_order']);
                $insert->bindValue(':status',$data[0]['status']);
                $insert->bindValue(':edit_id',$data[0]['edit_id']);
                $insert->bindValue(':featured_promo',$data[0]['featured_promo']);
                $insert->bindValue(':thumbnail_image',$data[0]['thumbnail_image']);
                $insert->bindValue(':promo_title_image',$data[0]['promo_title_image']);
                $insert->bindValue(':exclusive_for',$data[0]['exclusive_for']);
                $insert->execute();
                $status = "200";
                return  $status;
        }catch(Exception $e){
             return $e;
        } 
    } 

}

?>