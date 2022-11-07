<?php
require_once '../init.php';
class Product {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    public function brandProducts($pageNum, $brandId){
        global $image;
        $data = array();
        $no_per_page = 12;
        $offset = ($pageNum-1) * $no_per_page;
        $st1 = $this->conn->prepare("SELECT p.image, p.product_id, p.price, pd.name,
            'reg' as `type`
            FROM seller_product_selected sps
            INNER JOIN oc_seller os ON os.seller_id=sps.seller_id 
            INNER JOIN oc_product p On sps.product_id=p.product_id
            INNER JOIN product_to_brand ptb
                ON sps.product_id = ptb.product_id 
            INNER JOIN oc_product_description pd ON p.product_id = pd.product_id          
            WHERE  sps.quantity!=0 and p.status = '1' and os.status=1  AND ptb.brand_id = :brandId 
            GROUP by sps.product_id
            ORDER BY RAND() LIMIT :offset , :no_per_page");
        $st1->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $st1->bindValue(':no_per_page', $no_per_page, PDO::PARAM_INT);
        $st1->bindValue(':brandId', $brandId);
        $st1->execute();
        foreach($st1->fetchAll() as $product){
            $img = null;
            if($product['image'] !== null){
                $img = $image->resize($product['image'], 200, 200);
            }
            $data[] = array(
                'productId' => $product['product_id'],
                'name' => utf8_encode($product['name']),
                'thumb' => $img,
                'type' => $product['type'],
                'price' => (float) $product['price']
            );
        }
        return $data;
    }
      public function getCategoryIds($categoryId){
            $st = $this->conn->prepare("SELECT cat_id FROM bg_product_category where cat_id = :category_id OR parent_id = :category_id AND `status` = 1");
            $st->bindValue(':category_id', $categoryId);
            $st->execute();
            $cat_ids = array();
            $store_cat_ids = array();
            $catIdString = '';
            $categoryIdsString = '';
                if($st->rowCount() > 0){
                    foreach($st->fetchAll() as $cat){
                        $cat_ids[] = $cat['cat_id'];
                        $store_cat_ids[] = $cat['cat_id'];
                    }
                    $catIdString = implode(',', $cat_ids);
                    $st1 = $this->conn->prepare("SELECT cat_id FROM bg_product_category where parent_id IN (".$catIdString.") AND `status` = 1");
                    $st1->execute();
                    if($st1->rowCount() > 0){
                            $cat_ids = array();
                            $catIdString = '';
                            foreach($st1->fetchAll() as $cat1){
                                  $cat_ids[] = $cat1['cat_id'];
                                  $store_cat_ids[] = $cat1['cat_id'];
                            }
                            $catIdString = implode(',', $cat_ids);
                            $st2 = $this->conn->prepare("SELECT cat_id FROM bg_product_category where parent_id IN (".$catIdString.") AND `status` = 1");
                            $st2->execute();
                            if($st2->rowCount() > 0){
                                $cat_ids = array();
                                $catIdString = '';
                                foreach($st2->fetchAll() as $cat2){
                                      $cat_ids[] = $cat2['cat_id'];
                                      $store_cat_ids[] = $cat2['cat_id'];
                                }
                                
                                $catIdString = implode(',', $cat_ids);
                                $st3 = $this->conn->prepare("SELECT cat_id FROM bg_product_category where parent_id IN (".$catIdString.") AND `status` = 1");
                                $st3->execute();
                                if($st3->rowCount() > 0){
                                    $cat_ids = array();
                                    $catIdString = '';
                                    foreach($st2->fetchAll() as $cat3){
                                          $cat_ids[] = $cat3['cat_id'];
                                          $store_cat_ids[] = $cat3['cat_id'];
                                    }
                                }
                            }
                            
                    }
                }
                return array_unique($store_cat_ids);
        }
        public function globalCategoryName($categoryId){
        $st = $this->conn->prepare("SELECT cat_name FROM bg_product_category where cat_id = :category_id");
        $st->bindValue(':category_id', $categoryId);
        $st->execute();
        return $st->fetch()['cat_name'];
        }
        
        public function globalProductsByCategory($pageNum, $categoryId){
        $data = array();
        // $st = $this->conn->prepare("SELECT cat_id FROM bg_product_category where cat_id = :category_id OR parent_id = :category_id AND `status` = 1");
        // $st->bindValue(':category_id', $categoryId);
        // $st->execute();
        // $cat_ids = array();
        // $categoryIdsString = '';
        // foreach($st->fetchAll() as $cat){
        //     $cat_ids[] = $cat['cat_id'];
        // }
        $no_per_page = 12;
        $offset = ($pageNum-1) * $no_per_page;
        $categoryIdsString = implode(",", $this->getCategoryIds($categoryId));
        $st1 = $this->conn->prepare("SELECT * FROM bg_product where cat_id IN (".$categoryIdsString.") AND `status` = 1 ORDER BY id desc LIMIT :offset , :no_per_page");
        $st1->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $st1->bindValue(':no_per_page', $no_per_page, PDO::PARAM_INT);
        $st1->execute();
        foreach($st1->fetchAll() as $product){
            $addNum = 200;
            $min = 0;
            $max = 0;
            $productPrice = (float) $product['price'];
            if($productPrice < $addNum){
                $min = $productPrice;
            }else{
                $min = $productPrice - $addNum;
            }
            $max = $productPrice + $addNum;
            $data[] = array(
                'productId' => $product['product_id'],
                'name' => $product['product_name'],
                'thumb' => $product['img'],
                'type' => 'bg',
                'priceRange' => array('min' => $min, 'max' => $max)
            );
        }
        return $data;
    }
    public function localProduct($product_id, $storeId){
        global $product;
        global $image;
            $select_product = $this->conn->prepare("SELECT  p.image,
                                                    p.product_id, pd.name AS name, 
                                                    (SELECT price FROM oc_product_special ps 
                                                         WHERE ps.product_id = p.product_id 
                                                         AND ((ps.date_start = null 
                                                         OR ps.date_start < convert_tz(utc_timestamp(),'-08:00','+0:00')) 
                                                         AND (ps.date_end = null 
                                                         OR ps.date_end > convert_tz(utc_timestamp(),'-08:00','+0:00'))) 
                                                         ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special,
                                                    p.price,
                                                    AVG(r1.rating) AS rating,
                                                    p.quantity,
                                                    pd.tag,
                                                    p.viewed,
                                                    pd.description,
                                                    p.seller_id,
                                                    p.model
                                            FROM oc_product p
                                            LEFT JOIN oc_product_description pd ON p.product_id = pd.product_id  
                                            LEFT JOIN oc_manufacturer m ON p.manufacturer_id = m.manufacturer_id 
                                            LEFT JOIN oc_review r1 ON r1.product_id = p.product_id
                                            where p.product_id=:product_id  
                                            AND p.status = '1' AND p.date_available <= convert_tz(utc_timestamp(),'-08:00','+0:00')");
            $select_product->bindValue(':product_id',  $product_id);
            $select_product->execute();
            $data=$select_product->fetch(PDO::FETCH_ASSOC);
       if($select_product->rowCount()){
            if($data['image']){
                $img = $image->resize($data['image'], 200, 200);
            }else{
                $img = '';
            }
            $totalSold = $this->getTotalSold($product_id);
            return array(
                'hasShipMethod' => true,
                'productId' => $data['product_id'],
                'name' => utf8_encode($data['name']),
                'price' => $data['special'] != null ? $data['special'] : round($data['price'], 2),
                'oldPrice' => $data['special'] != null ? $data['price'] : false,
                'thumb' => $img,
                'image' => $this->get_product_images($product_id, $img),
                'rating' => $data['rating'],
                'quantity' => $data['quantity'],
                'tag' => $data['tag'],
                'viewed' => $data['viewed'],
                'description' => html_entity_decode(utf8_encode($data['description'])),
                'totalSold' => $totalSold > 0 ? "$totalSold sold" : "",
                'discount' => [],
                'reviews' => [],
                'warehouse' => [],
                //'store' => $product->store_by_id($data['seller_id']),
                'freebie'=> [],
                'detail'  => array(
                    // array(
                    //     'label' => 'stocks',
                    //     'value' => $data['quantity']
                    // ), 
                      array(    
                        'label' => 'tags',
                        'value' => $data['tag']
                    ), 
                      array(
                        'label' => 'model',
                        'value' => html_entity_decode(utf8_encode($data['model'])),
                    ),
                    array(
                        'label' => 'category',
                        'value' => html_entity_decode($product->product_category_names($data['product_id']))
                    ),
                ),
                'attribute' => $this->get_attribute($product_id),
                'storeList' => $this->get_store_list($product_id, $storeId)
    
            );
        }else{
            return array('error' => 'There\'s something wrong to your server');
        }
    }
    public function get_store_list($product_id, $storeId){   
    $row = array();
        global $image;
        if($storeId !== 'null'){
                $stmt = $this->conn->prepare("SELECT os.seller_id,os.shop_name,concat('company/',os.image) as image,
                sps.brand_id,opd.name,sps.quantity as qty ,spf.description as freebies
            FROM seller_product_selected sps 
            LEFT JOIN seller_product_freebies spf ON  spf.product_id=sps.product_id AND sps.seller_id=spf.seller_id
            INNER JOIN  oc_seller os 
                ON sps.seller_id=os.seller_id
            INNER JOIN oc_product_brand  opd
                ON opd.id=sps.brand_id
            WHERE sps.product_id=:product_id AND os.status=1 AND os.seller_id=:store_id");
                $stmt->bindValue(':product_id',$product_id);
                $stmt->bindValue(':store_id',$storeId);
                $stmt->execute();
        }else{
                $stmt = $this->conn->prepare("SELECT os.seller_id,os.shop_name,concat('company/',os.image) as image,
                sps.brand_id,opd.name,sps.quantity as qty  ,spf.description as freebies
            FROM seller_product_selected sps 
             LEFT JOIN seller_product_freebies spf ON  spf.product_id=sps.product_id AND sps.seller_id=spf.seller_id
            INNER JOIN  oc_seller os 
                ON sps.seller_id=os.seller_id
            INNER JOIN oc_product_brand  opd
                ON opd.id=sps.brand_id
            WHERE sps.product_id=:product_id AND os.status=1");
                $stmt->bindValue(':product_id',$product_id);
                $stmt->execute();
        }

       foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
            $row[] = array(
               'image' => $value['image'],
               'shopName' => $value['shop_name'],
               'quantity' => $value['qty'],
               'freebies' => $value['freebies'],
               'sellerId' => $value['seller_id'],
               'deductionData' =>  $this->get_deductions_per_seller($value['seller_id'],$product_id),
               'thumb' => $image->resize($value['image'], 70,70)
           );
       } 
       return $row; 
   }
    public function get_deductions_per_seller($seller_id,$product_id){  
        $row = array(); 
        global $image;
       $stmt = $this->conn->prepare("SELECT sd.id,IFNULL(sd.deduction_type,0) as deduction_type,sd.description,sd.value,
                                       CASE WHEN (IFNULL(sd.deduction_type,0)=0) THEN IFNULL(sd.value,0) / 100  ELSE IFNULL(sd.value,0) END AS  rate 
                                   FROM seller_deductions sd 
                                   INNER JOIN  seller_deductions_product sdp 
                                       ON sd.id=sdp.deduction_id
                                   WHERE sd.seller_id=:seller_id AND sdp.product_id=:product_id");
       $stmt->bindValue(':seller_id',$seller_id);
       $stmt->bindValue(':product_id',$product_id);
       $stmt->execute();
       foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
            $row[] = array(
               'deduction_type' => $value['deduction_type'],
               'label' => $value['description'],
               'deductionDetails' => $value['value'],
               'value' => $value['id'],
               'rate' => $value['rate'],
               'type'=> 'radio',
               'checked' => true
           );
       } 
       return $row; 
   }
    public function get_product_images($product_id, $main_image){
        global $image;
        $data = array();
        $st = $this->conn->prepare("SELECT * FROM oc_product_image WHERE product_id = :product_id ORDER BY sort_order ASC");
        $st->bindValue(':product_id', $product_id);
        $st->execute();
        foreach($st->fetchAll() as $images){
            $data[] = $image->resize($images['image'], 200, 200);
        }
        array_unshift($data, $main_image);
        return $data;
    }
    public function get_single_local_product($product_id, $type){
        global $image;
        $data = array();
        try {
            $sql = "SELECT DISTINCT p.product_id,
            p.price,
            pd.name AS name, 
            p.image,
            p.status,
            p.model,
            'reg' as `type`
            FROM oc_product p 
            LEFT JOIN oc_product_description pd ON (p.product_id = pd.product_id)
            WHERE p.status = 1 AND p.product_id = :product_id LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':product_id', $product_id);
            $stmt->execute();
            $row = $stmt->fetch();
            if($stmt->rowCount()){
                $img = '';
                if($type == 'reg'){
                    if($row['image']){
                        $img =  $image->resize($row['image'], 200,200);
                      }else{
                        $img = '';
                      }
                }else{
                    $img = $row['image'];
                }
                $data = array(
                    'product_id' => $row['product_id'],
                    'model' => $row['model'],
                    'name' => $row['name'],
                    'thumb' => $img,
                    'price' => $row['price'],
                    'type' => $type
                );
            }
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $data;
    }
    public function get_single_product($product_id, $type){
       global $image;
        $data = array();
        try {
            $sql = "SELECT * FROM (SELECT DISTINCT p.product_id,
            p.price,
            pd.name AS name, 
            p.image,
            p.status,
             'reg' as `type`,
             p.model
             FROM oc_product p 
             LEFT JOIN oc_product_description pd ON (p.product_id = pd.product_id) 
             UNION All
             SELECT bg.product_id, bg.price, bg.product_name as name, bg.img as image, 
             bg.status, 'bg' as `type`, bg.product_id as model FROM bg_product bg
             ) as pp 
             WHERE pp.status = 1 AND pp.product_id = :product_id AND pp.type = :p_type";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':product_id', $product_id);
            $stmt->bindValue(':p_type', $type);
            $stmt->execute();
            $row = $stmt->fetch();
            if($stmt->rowCount()){
                $img = '';
                if($type == 'reg'){
                    if($row['image']){
                        $img =  $image->resize($row['image'], 200,200);
                      }else{
                        $img = '';
                      }
                }else{
                    $img = $row['image'];
                }
                $data = array(
                    'product_id' => $row['product_id'],
                    'model' => $row['model'],
                    'name' => $row['name'],
                    'thumb' => $img,
                    'price' => $row['price'],
                    'type' => $type
                );
            }
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $data;
    }
    public function get_product($product_id){
       $data = array();
       try {
        $sql = "SELECT DISTINCT p.product_id,
        pd.description,
        pd.tag,
        p.model,
        p.quantity,
        p.price,
        p.date_available,
        p.status,
        p.date_added,
        p.date_modified,
        p.viewed,
        pd.name AS name, 
        p.image, 
        p.seller_id, 
        m.name AS manufacturer, 
         (SELECT price FROM oc_product_discount pd2 
         WHERE pd2.product_id = p.product_id 
         AND pd2.quantity = '1' AND ((pd2.date_start = null 
         OR pd2.date_start < convert_tz(utc_timestamp(),'-08:00','+0:00')) 
         AND (pd2.date_end = null 
         OR pd2.date_end > convert_tz(utc_timestamp(),'-08:00','+0:00'))) 
         ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, 
         (SELECT price FROM oc_product_special ps 
         WHERE ps.product_id = p.product_id 
         AND ((ps.date_start = null 
         OR ps.date_start < convert_tz(utc_timestamp(),'-08:00','+0:00')) 
         AND (ps.date_end = null 
         OR ps.date_end > convert_tz(utc_timestamp(),'-08:00','+0:00'))) 
         ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special,
         (SELECT AVG(rating) AS total FROM oc_review r1 
         WHERE r1.product_id = p.product_id AND r1.status = '1' 
         GROUP BY r1.product_id) AS rating, 
         (SELECT COUNT(*) AS total FROM oc_review r2 
         WHERE r2.product_id = p.product_id AND r2.status = '1' 
         GROUP BY r2.product_id) AS reviews, p.sort_order 
         FROM oc_product p 
         LEFT JOIN oc_product_description pd ON (p.product_id = pd.product_id) 
         LEFT JOIN oc_product_to_store p2s ON (p.product_id = p2s.product_id) 
         LEFT JOIN oc_manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
         WHERE p.product_id = :product_id 
         AND p.status = '1' AND p.date_available <= convert_tz(utc_timestamp(),'-08:00','+0:00');";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':product_id', (int) $product_id);
        $stmt->execute();
        $row = $stmt->fetch();
        }catch(Exception $e){
            $s = $this->conn->prepare("INSERT INTO error_log SET 
            error_message = :msg, date_created = convert_tz(utc_timestamp(),'-08:00','+0:00')");
            $s->bindValue(':msg', $e);
            $s->execute();
        }
        if($stmt->rowCount()){
            $data = array(
                'product_id' => (int)$row['product_id'],
                'name' => $row['name'],
                'image' => $row['image'],
                // 'description' => $row['description'],
                // 'tag' => $row['tag'],
                // 'model' => $row['model'],
                'quantity' => (int)$row['quantity'],
                'price' => round(($row['discount'] ? $row['discount'] : $row['price']), 2),
                 'special' => $row['special'],
                //  'date_available' => $row['date_available'],
                //  'rating' => $row['rating'],
                //  'reviews' => ($row['reviews'] ? $row['reviews'] : 0),
                //  'sort_order' => $row['sort_order'],
                //  'status' => $row['status'],
                //  'date_added' => $row['date_added'],
                //  'date_modified' => $row['date_modified'],
                //  'viewed' => $row['viewed'],
                //  'seller_id' => $row['seller_id'],
                 'type' => 'reg'
            );
        }else{
            $data = false;
        }
        return $data;
     
    }
    public function get_products_cb($data = array()){
        $res = array();
        global $chinabrands;
        if(!empty($data['token'])){
        $no_per_page = 12;
        $get_products = array();
        $sql = 'SELECT cp.* FROM oc_china_product cp where cp.status = 1 ';
        if(!empty($data['filter_name'])){
            $sql .= 'AND (';
            $implode = array();

            $words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

            foreach ($words as $word) {
                $implode[] = "cp.product_title LIKE '%" . $word . "%'";
            }

            if ($implode) {
                $sql .= " " . implode(" AND ", $implode) . "";
            }
            $sql .= ") ";
        }
            $sql .= 'LIMIT :offset , :no_per_page';
            $st = $this->conn->prepare($sql);
            $st->bindValue(':offset', (int) $data['start'], PDO::PARAM_INT);
            $st->bindValue(':no_per_page', $data['limit'], PDO::PARAM_INT);
            $st->execute();
            $product_ids = '';
            if($st->rowCount() > 0){
                foreach($st->fetchAll(PDO::FETCH_ASSOC) as $ids){
                    $product_ids .= $ids['goods_sn']. ',';
                } 
            }
            $goods_sn = rtrim($product_ids, ',');
            $post_data_gpd = array(
            'token' => $data['token'],
            'goods_sn' => json_encode($goods_sn)
            );
            $curl_gpd = curl_init('https://gloapi.chinabrands.com/v2/product/index');
            curl_setopt($curl_gpd, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl_gpd, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_gpd, CURLOPT_POST, 1);
            curl_setopt($curl_gpd, CURLOPT_POSTFIELDS, $post_data_gpd);
            $resul_gpd = curl_exec($curl_gpd); 
            curl_close($curl_gpd);
            $res_gpd= json_decode($resul_gpd);
            $get_products= $res_gpd->msg;
            if(is_array($get_products)){
                foreach($get_products as $product){
                    if($product->status == 1){
                        $res[] = array(
                            'sku' => $product->sku,
                            'name'=> $product->title,
                            'thumb' => $product->original_img[0],
                            'priceRange' => $chinabrands->getMinMaxPrice($product->warehouse_list)
                        );
                    }
                }
            }
        }
        
        return $res;
    }
    public function get_category_products($data = array()){
        $sql = "SELECT p.product_id, pd.name ";
       if (!empty($data['filter_category_id'])) {
        if (!empty($data['filter_sub_category'])) {
            $sql .= " FROM oc_category_path cp LEFT JOIN oc_product_to_category p2c ON (cp.category_id = p2c.category_id)";
        } else {
            $sql .= " FROM oc_product_to_category p2c";
        }
            $sql .= " LEFT JOIN oc_product p ON (p2c.product_id = p.product_id)";
    } else {
        $sql .= " FROM oc_product p";
    }
    $sql .= " LEFT JOIN oc_product_description pd ON (p.product_id = pd.product_id) LEFT JOIN oc_product_to_store p2s ON (p.product_id = p2s.product_id) 
    WHERE p.status = '1' ";
		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

        }
        if(isset($data['price_range'])){
			if(count($data['price_range']) != 0){
				$sql .= " AND p.price ";
				if($data['price_range'][1] == 'Above'){
					$sql .= ">= ".$data['price_range'][0];
				}else{
					$sql .= "BETWEEN ".$data['price_range'][0]." AND ".$data['price_range'][1];
				}
			
			}
        }
        
        $sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.price',
			'p.sort_order',
			'p.date_added'
        );
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY p.price ";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
        }
        if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
        }
        if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
        
        $product_data = array();
        $st = $this->conn->prepare($sql);
        $st->execute();

		foreach ($st->fetchAll() as $result) {
            // if($result !== false){
                $product_data[] = $this->get_single_local_product($result['product_id'], 'reg');
            // }
		}

		return $product_data;
    }
    public function get_products($data = array()){
        global $image;
        $sql = "SELECT pp.product_id, pp.type, pp.name, pp.description, pp.price, pp.image, pp.date_added FROM (
        SELECT 'bg' as `type`, bp.product_id, bp.status, bp.product_name as name, '' as description, bp.price, bp.img as image, bp.add_date as date_added FROM bg_product bp WHERE bp.status = 1
        UNION ALL ";
        $sql .= "SELECT 'reg' as `type`, p.product_id, p.status, pd.name, pd.description, p.price, p.image, p.date_added ";
        $sql .= " FROM oc_product p";
        $sql .= " LEFT JOIN oc_product_description pd ON (p.product_id = pd.product_id) ";
        $sql .= ") as pp";
        $sql .= " WHERE pp.status = '1' ";
        if(!empty($data['type'])){
            $type = $data['type'] == 'local' ? 'reg' : 'bg';
            $sql .= " AND pp.type='". $type . "'";
        }
        if (!empty($data['filter_name'])) {
			$sql .= " AND ";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));
                $sql .= "( pp.name LIKE '%".$data['filter_name']."%' ";
				foreach ($words as $word) {
                    $word = rtrim($word, "s");
					$implode[] = "OR pp.name LIKE '%" . $word . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" ", $implode) . "";
				}
                $sql .= ")";
                

				// if (!empty($data['filter_description'])) {
				// 	$sql .= " OR pp.description LIKE '%" .$data['filter_name'] . "%'";
				// }
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$sql .= "pd.tag LIKE '%" . $data['filter_tag'] . "%'";
			}
        }
        
        if(isset($data['price_range'])){
			if(count($data['price_range']) != 0){
				$sql .= " AND pp.price ";
				if($data['price_range'][1] == 'Above'){
					$sql .= ">= ".$data['price_range'][0];
				}else{
					$sql .= "BETWEEN ".$data['price_range'][0]." AND ".$data['price_range'][1];
				}
			
			}
        }
        
        $sql .= " GROUP BY pp.product_id, pp.type, pp.name";

		$sort_data = array(
			'pp.name',
			'pp.price',
			'pp.date_added'
        );
        $sql .=" ORDER BY FIELD(pp.name, '".$data['filter_name']."') DESC, ";
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pp.name' || $data['sort'] == 'pp.model') {
				$sql .= " LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " pp.price";
			} else {
				$sql .= " " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
        }
        if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pp.name) DESC ";
		} else {
			$sql .= " ASC, LCASE(pp.name) ASC ";
        }
        if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
        
        $product_data = array();
        //echo $sql;
        $st = $this->conn->prepare($sql);
        $st->execute();
		foreach ($st->fetchAll() as $row) {
            $img = '';
            if($row['type'] == 'reg'){
                if($row['image']){
                    $img =  $image->resize($row['image'], 200,200);
                  }else{
                    $img = '';
                  }
            }else{
                $img = $row['image'];
            }
            $product_data[] = array(
                'product_id' => $row['product_id'],
                'name' => $row['name'],
                'thumb' => $img,
                'price' => $row['price'],
                'type' => $row['type']
            );
		}
		return $product_data;

    }
        public function store_products($data = array()){
        $sql = "SELECT p.price, p.date_added, p.product_id, pd.name, 'reg' as type FROM oc_product p 
                                    INNER JOIN oc_product_description pd ON p.product_id = pd.product_id
                                    INNER JOIN seller_product_selected psp ON psp.product_id=p.product_id
                                    where p.status = 1 ";
        $sql .= "AND psp.seller_id = " . $data['seller_id']. " AND psp.quantity != 0 ";
        
        if(isset($data['price_range'])){
			if(count($data['price_range']) != 0){
				$sql .= " AND p.price ";
				if($data['price_range'][1] == 'Above'){
					$sql .= ">= ".$data['price_range'][0];
				}else{
					$sql .= "BETWEEN ".$data['price_range'][0]." AND ".$data['price_range'][1];
				}
			
			}
        }
		$sort_data = array(
			'pd.name',
			'p.price',
			'p.date_added'
        );
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY p.price ";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
        }
        if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
        }
        if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
        
        $product_data = array();
        //echo $sql;
        $st = $this->conn->prepare($sql);
        $st->execute();
		foreach ($st->fetchAll() as $result) {
                $product_data[] = $this->get_single_local_product($result['product_id'], $result['type']);
		}
		return $product_data;

    }
    public function review_by_product($product_id, $start = 0, $limit = 20){
        $st = $this->conn->prepare("SELECT r.review_id, r.author, r.rating, r.text, p.product_id, pd.name, p.price, p.image, r.date_added 
        FROM oc_review r LEFT JOIN oc_product p ON (r.product_id = p.product_id) 
        LEFT JOIN oc_product_description pd ON (p.product_id = pd.product_id) 
        WHERE p.product_id = :product_id 
        AND p.date_available <= convert_tz(utc_timestamp(),'-08:00','+0:00') 
        AND p.status = '1' AND r.status = '1' 
        ORDER BY r.date_added DESC LIMIT :start, :limit");
        $st->bindValue(':product_id', (int)trim($product_id), PDO::PARAM_INT);
        $st->bindValue(':start', (int)trim($start), PDO::PARAM_INT);
        $st->bindValue(':limit',(int)trim($limit), PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_attribute($product_id){
        $attr = array();
        $st = $this->conn->prepare("SELECT ag.attribute_group_id, agd.name 
        FROM oc_product_attribute pa LEFT JOIN oc_attribute a ON (pa.attribute_id = a.attribute_id) 
        LEFT JOIN oc_attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) 
        LEFT JOIN oc_attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) 
        WHERE pa.product_id = :product_id  GROUP BY ag.attribute_group_id, agd.name  
        ORDER BY ag.sort_order, agd.name");
        $st->bindValue(':product_id', (int) trim($product_id), PDO::PARAM_INT);
        $st->execute();
        foreach($st->fetchAll(PDO::FETCH_ASSOC) as $attribute){
            $description_data = array();
            $stm = $this->conn->prepare("SELECT a.attribute_id, ad.name, pa.text 
            FROM oc_product_attribute pa LEFT JOIN oc_attribute a ON (pa.attribute_id = a.attribute_id) 
            LEFT JOIN oc_attribute_description ad ON (a.attribute_id = ad.attribute_id) 
            WHERE pa.product_id = :product_id AND a.attribute_group_id = :attribute_group_id 
            ORDER BY a.sort_order, ad.name");
            $stm->bindValue(':product_id', (int) trim($product_id), PDO::PARAM_INT);
            $stm->bindValue(':attribute_group_id', (int) trim($attribute['attribute_group_id']), PDO::PARAM_INT);
            $stm->execute();
            foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $description){
                $description_data[] = array(
                    'attribute_id' => $description['attribute_id'],
					'name'         => utf8_encode($description['name']),
					'text'         => utf8_encode($description['text'])
                );
            }
            $attr[] = array(
                'attribute_group_id' => $attribute['attribute_group_id'],
                'name' => utf8_encode($attribute['name']),
                'attribute' => $description_data
            );
          
        }
        return $attr;
    }
    public function product_category_names($product_id){
        $data = array();
        $st = $this->conn->prepare("SELECT category_id FROM oc_product_to_category WHERE product_id = :product_id");
        $st->bindValue(':product_id', $product_id);
        $st->execute();
        foreach($st->fetchAll() as $id){
            $s = $this->conn->prepare("SELECT name FROM oc_category_description WHERE category_id = :category_id");
            $s->bindValue(':category_id', $id['category_id']);
            $s->execute();
            $data[] = $s->fetch()['name'];
        }
        return implode(", " , $data);
    }
    public function store_by_id($seller_id){
        global $store;
        global $image;
        $value = array();
        $st = $this->conn->prepare("SELECT * FROM oc_seller WHERE seller_id = :seller_id");
        $st->bindValue(":seller_id", $seller_id);
        $st->execute();
        $data = $st->fetch(PDO::FETCH_ASSOC);
        if($data['image']){
            $img = $image->resize("company/" . $data['image'], 70,70);
        }else{
            $img = 'https://pesoapp.ph/img/peso-seller.png';
        }
        $value = array(
            'sellerId' => $seller_id,
            'shopName' => $data['shop_name'] ?? 'Pinoy Electronic Store Online',
            'image' =>  $img,
            'mobile' => $data['mobile'] ?? '',
            'address' => 'Quezon City, Metro Manila',
            'totals' => array(
                array(
                    'label' => 'Products',
                    'value' => $store->seller_total_products($seller_id),
                ),
                array(
                    'label' => 'Orders',
                    'value' => $store->seller_total_orders($seller_id)
                )
            )
        );
        return $value;
    }
    public function getProductDiscount($product_id){
        $s = $this->conn->prepare("SELECT * FROM oc_product_discount 
        WHERE product_id = :product_id AND quantity > 1 
        AND ((date_start = null 
        OR date_start < convert_tz(utc_timestamp(),'-08:00','+0:00')) 
        AND (date_end = null 
        OR date_end > convert_tz(utc_timestamp(),'-08:00','+0:00'))) 
        ORDER BY quantity ASC, priority ASC, price ASC"
        );
        $s->bindValue(":product_id", $product_id);
        return $s->fetchAll();
    }
    public function getPeopleViewProducts($product_id, $type){
  global $image;
        $product_data = array();
        $final_products = array();

            switch($type){
                case 'reg':
                    $s = $this->conn->prepare("SELECT p2c.category_id, pd.name FROM oc_product_to_category p2c 
                    LEFT JOIN oc_product_description pd ON pd.product_id = p2c.product_id WHERE p2c.product_id =:product_id"
                    );
                    $s->bindValue(":product_id", $product_id);
                    $s->execute();
                    foreach($s->fetchAll(PDO::FETCH_ASSOC) as $p1){
                    $firstWordName = explode(' ', $p1['name'])[0];
                    $s1 = $this->conn->prepare("SELECT p2c.product_id FROM  oc_product_to_category p2c 
                    LEFT JOIN oc_product p ON (p2c.product_id = p.product_id) 
                    LEFT JOIN oc_product_description pd ON (p.product_id = pd.product_id) 
                    LEFT JOIN oc_product_to_store p2s ON (p.product_id = p2s.product_id) 
                    WHERE p.status = '1' AND p.date_available <= convert_tz(utc_timestamp(),'-08:00','+0:00') 
                    AND p2s.store_id = 0 AND p.product_id != :product_id AND pd.name LIKE :search_value
                    AND p2c.category_id=:category_id ORDER BY p.date_added DESC limit 10");
                    $s1->bindValue(':product_id', $product_id);
                    $s1->bindValue(':category_id', $p1['category_id']);
                    $s1->bindValue(':search_value', $firstWordName."%");
                    $s1->execute();
                    foreach($s1->fetchAll(PDO::FETCH_ASSOC) as $result){
                        $product_data[] = $this->get_product($result['product_id']);
                    }
                    foreach($product_data as $p2){
                       if($p2 != false){
                        if($p2['image']){
                            $img =  $image->resize($p2['image'], 200,200);
                          }else{
                            $img = '';
                          }
                          $totalSold = $this->getTotalSold($p2['product_id']);
                          $final_products[] = //$p1['category_id'];
                          array(
                              'productId'  => $p2['product_id'],
                              'thumb'       => $img,
                              'name'        => mb_strimwidth($p2['name'], 0, 30, "..."),
                              'price'       => $p2['special'] != false ? $p2['special'] : $p2['price'],
                              'oldPrice'    => $p2['special'] != false ? $p2['price'] : false,
                              'totalSold'      => $totalSold > 0 ? "$totalSold sold" : rand(1,10)." sold",
                              'type' => $type
                          );
                       }
                    }
                }
                return $final_products;
                break;
                case 'bg':
                    $s1 = $this->conn->prepare('SELECT product_name from bg_product where product_id = :product_id LIMIT 1');
                    $s1->bindValue(':product_id', $product_id);
                    $s1->execute();
                    $firstWordName = explode(' ', $s1->fetch()['product_name'])[0];

                    $s2 = $this->conn->prepare('SELECT * from pcvill_ocnew.bg_product where product_name LIKE :search_value');
                    $s2->bindValue(':search_value', $firstWordName. "%");
                    $s2->execute();
                    foreach($s2->fetchAll(PDO::FETCH_ASSOC) as $p2){
                        $final_products[] = array(
                            'productId'  => $p2['product_id'],
                            'thumb'       => $p2['img'],
                            'name'        => mb_strimwidth($p2['product_name'], 0, 30, "..."),
                            'price'       => $p2['price'],
                            'oldPrice'    => false,
                            'totalSold'      => rand(1,10)." sold",
                            'type' => $type
                        );
                    }
                return $final_products;
                break;
            }
    }
    public function getTotalSold($product_id){
        $s = $this->conn->prepare("SELECT IFNULL(SUM(op.quantity), 0) AS quantity FROM oc_order_product op 
        LEFT JOIN oc_order o ON (op.order_id = o.order_id) WHERE o.order_status_id = 20 AND op.product_id = :product_id");
        $s->bindValue(":product_id", $product_id);
        $s->execute();
        $s->fetch()['quantity'];
    }
    public function getDiscoverProducts($productIds, $page_number){
        global $image;
        $product_data = array();
        $final_products = array();
        $firstWordNamesArray = array();
        $categoryIds = '';
        $no_per_page = 12;
        $offset = ($page_number-1) * $no_per_page;
            $s1 = $this->conn->prepare("SELECT p.image, p.product_id, p.price, pd.name ,sps.quantity 
                    FROM seller_product_selected sps
                    INNER JOIN oc_seller os ON os.seller_id=sps.seller_id
                    INNER JOIN oc_product p ON sps.product_id=p.product_id
                    INNER JOIN oc_product_description pd ON pd.product_id = p.product_id 
                    where sps.quantity!=0 and p.status = '1' and os.status=1
                    GROUP by sps.product_id
                    ORDER BY RAND() LIMIT :offset , :no_per_page");
            $s1->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
            $s1->bindValue(':no_per_page', $no_per_page, PDO::PARAM_INT);
            $s1->execute();
            foreach($s1->fetchAll(PDO::FETCH_ASSOC) as $p2){
                
                if($p2['image']){
                    $img =  $image->resize($p2['image'], 200,200);
                  }else{
                    $img = '';
                  }
                $product_data[] =  array(
                      'productId'  => $p2['product_id'],
                      'thumb'       => $img,
                      'name'        => utf8_encode($p2['name']),
                      'price'       => $p2['price'],
                      'oldPrice'    => false,
                      'type'        => 'reg'
                  );
            }
        return $product_data;
    }
    public function setPriceByType($price, $type){
        global $currency;
        switch($type){
            case 'cb':
                return $currency->setPriceForCB(inval($price));
            break;
            case 'reg':
                return $price;
            break;
        }
    }
    public function globalProducts($page_number){
        global $currency;
        $data = array();
        $prices = array();
        $no_per_page = 12;
        $get_products = array();
        $offset = ($page_number-1) * $no_per_page;
        $st = $this->conn->prepare("SELECT p.id, p.date_added, p.product_id, p.name, p.image, p.price, p.type 
        FROM (
        -- SELECT cp.date_added, cp.goods_sn as product_id, cp.product_title as `name`, cp.product_img as image, cp.`status`, ROUND(((cp.price * IFNULL((SELECT rate FROM foreign_exchange_rates WHERE date_added = STR_TO_DATE(NOW(), '%Y-%m-%d')), (select IFNULL(rate, 0) from foreign_exchange_rates ORDER BY date_added DESC LIMIT 1))) * 1.3), 2) as price, 'cb' as `type` FROM oc_china_product cp WHERE cp.status = 1
        -- UNION ALL
        -- SELECT ap.date_added, ap.id as product_id, name, image, status, price, 'ae' as `type` from aliexpress_products ap WHERE ap.status = 1
        -- UNION ALL
        SELECT bp.id as id, bp.add_date as date_added, bp.product_id, bp.product_name as name, bp.img as image, bp.status, bp.price, 'bg' as `type` FROM bg_product bp WHERE bp.status = 1
        ) p WHERE p.status = 1 ORDER BY RAND() LIMIT :offset , :no_per_page");
        $st->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $st->bindValue(':no_per_page', $no_per_page, PDO::PARAM_INT);
        $st->execute();
        $product_ids = '';
        if($st->rowCount() > 0){
            foreach($st->fetchAll(PDO::FETCH_ASSOC) as $product){
                $addNum = 50;
                $min = 0;
                $max = 0;
                $productPrice = (float) $product['price'];
                if($productPrice < $addNum){
                    $min = $productPrice;
                }else{
                    $min = $productPrice - $addNum;
                }
                $max = $productPrice * 1.25;
                $data[] = array(
                    'productId' => $product['product_id'],
                    'name' => $product['name'],
                    'thumb' => $product['image'],
                    // 'price' => $product['price'],
                    'type' => $product['type'],
                    'priceRange' => array('min' => $min, 'max' => $max)
                );
            }
            return $data;
        }
    }
    public function storeProductCategory($pageNum, $categoryId, $sellerId){
        global $image;
        $data = array();
        $no_per_page = 12;
        $offset = ($pageNum-1) * $no_per_page;
        $st1 = $this->conn->prepare("SELECT p.*,pd.name FROM oc_product p 
                                    INNER JOIN oc_product_description pd ON p.product_id = pd.product_id
                                    INNER JOIN seller_product_selected psp ON psp.product_id=p.product_id
                                    INNER JOIN oc_product_to_category PTC ON PTC.product_id=p.product_id
                                    where p.status = 1 AND psp.seller_id=:s_id AND PTC.category_id=:cid LIMIT :offset , :no_per_page");
        $st1->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $st1->bindValue(':no_per_page', $no_per_page, PDO::PARAM_INT);
        $st1->bindValue(':cid', $categoryId);
        $st1->bindValue(':s_id', $sellerId);
        $st1->execute();
        foreach($st1->fetchAll() as $product){
            $data[] = array(
                'productId' => $product['product_id'],
                'name' => $product['name'],
                'thumb' => $image->resize($product['image'], 200,200),
                'type' => 'reg',
                'price' => $product['price']
            );
        }
        return $data;
    }
    public function brandProductCategory($pageNum, $categoryId, $brandId){
        global $image;
        $data = array();
        $no_per_page = 12;
        $offset = ($pageNum-1) * $no_per_page;
        $st1 = $this->conn->prepare("SELECT p.image, p.product_id, p.price, pd.name,
                                             concat('product.php?product_id=',p.product_id) as href
                                    FROM seller_product_selected sps
                                    INNER JOIN oc_seller os ON os.seller_id=sps.seller_id 
                                    INNER JOIN oc_product p On sps.product_id=p.product_id
                                    INNER JOIN product_to_brand ptb  ON sps.product_id = ptb.product_id 
                                    INNER JOIN oc_product_to_category PTC ON PTC.product_id=p.product_id
                                    INNER JOIN oc_product_description pd ON p.product_id = pd.product_id          
                                    WHERE  sps.quantity!=0 and p.status = '1' and os.status=1  AND ptb.brand_id = :b_id AND PTC.category_id=:cid
                                    GROUP by sps.product_id
                                    ORDER BY RAND() LIMIT :offset , :no_per_page");
        $st1->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $st1->bindValue(':no_per_page', $no_per_page, PDO::PARAM_INT);
        $st1->bindValue(':cid', $categoryId);
        $st1->bindValue(':b_id', $brandId);
        $st1->execute();
        foreach($st1->fetchAll() as $product){
            $data[] = array(
                'productId' => $product['product_id'],
                'name' => $product['name'],
                'thumb' => $image->resize($product['image'], 200,200),
                'type' => 'reg',
                'price' => $product['price']
            );
        }
        return $data;
    }
}

$product = new Product();