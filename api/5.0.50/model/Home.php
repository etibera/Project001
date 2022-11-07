<?php
require_once '../init.php';
class Home {
    public $no_per_page = 1;
    public $offset = 0;
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    function __destruct(){
        $this->conn = null;
    }
    public function getStoreDetails($store_id){
        global $image;
        $data = array();
        $stmt = $this->conn->prepare("SELECT * FROM oc_seller WHERE seller_id = :seller_id LIMIT 1");
        $stmt->bindValue(':seller_id', $store_id);
        $stmt->execute();
        
        $stmt1 = $this->conn->prepare("SELECT * FROM seller_banner WHERE seller_id = :seller_id ORDER BY sort_order ASC");
        $stmt1->bindValue(':seller_id', $store_id);
        $stmt1->execute();
        $img = array();
        foreach($stmt1->fetchAll(PDO::FETCH_ASSOC) as $value){
            $img[] = $image->resize($value['banner_mobile'], 600,400);
        }
        
        $data = array(
            'name' => $stmt->fetch()['shop_name'],
            'banner' => $img
        );
        return $data;
    }
    public function getStores(){
        global $image;  
        $stmt = $this->conn->prepare("SELECT os.seller_id,os.shop_name,concat('company/',os.image) as image FROM oc_seller os WHERE os.image is not null AND `status` = :status AND os.seller_type = 0 ORDER BY RAND()");
        $stmt->bindValue(':status',1);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
             $row[] = array(
                'sellerId' => utf8_encode($value['seller_id']),
                'shopName' => $value['shop_name'],
                'image' => $image->resize($value['image'], 95,95)
            );
        }
           
        return $row;
    }
    public function popup_ads(){
        global $image;
        $res = array();
        $stmt = $this->conn->prepare('SELECT * FROM popup_ads WHERE `status` = 1 ORDER BY RAND() LIMIT 1');
        $stmt->execute();
        $data = $stmt->fetch();
        if($stmt->rowCount() > 0){
            $res = array(
                'image' => $image->img_path('popup_ads/') . $data['image'], 
                'webUrl' => $data['web_url'],
                'webmobileUrl' => $data['webmobile_url'],
                'mobileUrl' => $data['mobile_url']
            );
        }
        
        return $res;
    }
    public function brands(){
            global $image;
            $data = array();
            $stmt = $this->conn->prepare("SELECT * FROM oc_product_brand pb 
            WHERE pb.status = 1 ORDER BY RAND()");
            $stmt->execute();
            foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $cat){
                $data[] = array(
                    'categoryId' => $cat['id'],
                    'name' => $cat['name'],
                    'image' => $image->resize($cat['image'], 150,150)
                );
            }
            
            return $data;
    }
    public function brandProductDetails($brand_id){
            global $image;
            $data = array();
            $stmt = $this->conn->prepare("SELECT banner_moblie_img FROM oc_product_brand pb 
            WHERE pb.status = 1 AND pb.id = :brand_id");
            $stmt->bindValue(':brand_id', $brand_id);
            $stmt->execute();
            $cat = $stmt->fetch();
            if($cat['banner_moblie_img'] != null){
                $data = array(
                    'image' => [$image->resize($cat['banner_moblie_img'], 600, 400)]
                );
            }else{
                $data['image'] = $this->banner(14);
            }
           
            return $data;
    }
    public function globalCategories(){
        global $image;
        $stmt = $this->conn->prepare("SELECT * from bg_product_category WHERE status = 1 and parent_id = 0");
        $stmt->execute();
        $data = array();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $value){
                $img = null;
                if($value['image'] !== null || $value['image'] !== ''){
                    $img = $image->resize($value['image'], 75, 75, '');
                }
                $data[] = array(
                    'categoryId' => $value['cat_id'],
                    'name' => $value['cat_name'],
                    'image' => $img,
                    'label' => $this->globalCategoryName($value['cat_id'])
                );
            
        }
        
        return $data;
    }
    public function globalCategoryName($categoryId){
        $data = array();
        $stmt = $this->conn->prepare("SELECT cat_name, cat_id from bg_product_category WHERE status = 1 and parent_id = :parent_id");
        $stmt->bindValue(':parent_id', $categoryId);
        $stmt->execute();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $value){
            $items = $this->globalCategorySubItem($value['cat_id']);
                $data[] = array(
                'name' => $value['cat_name'],
                'categoryId' => $value['cat_id'],
                'items' => $items
            );
        }
        return $data;
    }
    public function globalCategorySubItem($categoryId){
        global $image;
        $data = array();
        $stmt = $this->conn->prepare("SELECT cat_name, cat_id, image from bg_product_category WHERE status = 1 and parent_id = :parent_id");
        $stmt->bindValue(':parent_id', $categoryId);
        $stmt->execute();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $value){
                $img = null;
                if($value['image'] !== null || $value['image'] !== ''){
                    $img = $image->resize($value['image'], 75, 75, '');
                }
                $data[] = array(
                    'categoryId' => $value['cat_id'],
                    'name' => $value['cat_name'],
                    'image' => $img
                );  
        }
        return $data;
    }
    public function latestPromoDetail($lp_id){
        global $image;
        $row = array();
        $stmt = $this->conn->prepare("SELECT * FROM oc_banner_image bi 
                                    LEFT JOIN oc_banner_image_description bid 
                                        ON (bi.banner_image_id  = bid.banner_image_id) 
                                    WHERE bi.banner_id = :banner_id AND  bi.sort_order=:lp_id
                                    ORDER BY bi.sort_order ASC");
        $stmt->bindValue(':banner_id',15);
        $stmt->bindValue(':lp_id',$lp_id);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
            $row['images'][] = $image->resize($value['image'], 600,400);
        }
        
        return $row;
    }
     public function lastestPromoProducts($page_number, $id){
         global $image;
         $offset = ($page_number-1) * 20;
        $data = array();
        $checkSellerCountPromo = $this->getCount_selected_seller($id);
        $sql = "";

        if($checkSellerCountPromo == 0){
            $sql .= "SELECT null as seller_id, null as deduction_type, null as rate, null as sellerimage, null as shop_name, s.product_id,s.price,s.name,s.image,s.type,s.typedesc,s.href,s.date 
            FROM ( SELECT p.product_id,p.price,pd.name, p.image as image,'Local Product' as typedesc,'reg' as type,concat('product.php?product_id=',p.product_id) as href,lpp.date
                    FROM oc_product p 
                    INNER JOIN oc_product_description pd ON (p.product_id = pd.product_id) 
                    INNER JOIN latest_promo_products lpp ON p.product_id=lpp.product_id
                    WHERE lpp.promo_id=:promo_id AND lpp.type=0 AND p.status = 1 
                    UNION ALL 
                    SELECT p.product_id,p.price,pd.name, p.image as image,'Local Product' as typedesc,'reg' as type,concat('product.php?product_id=',p.product_id) as href,p.date_modified 
                    FROM oc_product p 
                    INNER JOIN oc_product_description pd ON (p.product_id = pd.product_id) 
                    INNER JOIN oc_product_to_category PTC ON PTC.product_id=p.product_id
                    INNER JOIN latest_promo_category LPC ON PTC.category_id=LPC.category_id 
                    WHERE LPC.lp_id=:promo_id AND p.status = 1 
                    UNION ALL 
                    SELECT bg.product_id,bg.price,bg.product_name as name,bg.img as image,'Banngood Product'as typedesc,'bg' as type ,concat('bg_product.php?product_idbg=',bg.product_id) as href,lpp.date
                    FROM bg_product bg INNER JOIN latest_promo_products lpp ON bg.product_id=lpp.product_id
                    WHERE lpp.promo_id=:promo_id AND lpp.type=2 AND bg.status = 1 
                    
                ) as s order by s.date desc LIMIT :offset , :no_per_page";
        }else{
            $sql .= "SELECT 'reg' as type, spl.deduction_id,sd.deduction_type,sd.value,sd.seller_id,
                            CASE WHEN (sd.deduction_type = 0) THEN 'Percentage (%)' ELSE 'PHP' END AS d_type,
                            CASE WHEN (IFNULL(sd.deduction_type,0)=0) THEN IFNULL(sd.value,0) / 100  ELSE IFNULL(sd.value,0) END AS  rate,
                            os.shop_name, concat('img/company/',os.image) as sellerimage,sdp.product_id,p.price,pd.name,p.image as image,
                            'Local Product' as typedesc,concat('product.php?product_id=',p.product_id,'&store_id=',sd.seller_id) as href
                    FROM lp_seller_promo_list spl 
                    INNER JOIN seller_deductions sd 
                        ON sd.id=spl.deduction_id 
                    INNER JOIN oc_seller os
                        ON os.seller_id=sd.seller_id
                    INNER JOIN seller_deductions_product sdp
                        ON sdp.deduction_id=spl.deduction_id
                    INNER JOIN seller_product_selected sps
                        ON sps.seller_id=os.seller_id AND sps.product_id=sdp.product_id
                    INNER JOIN oc_product p   
                        ON  p.product_id=sdp.product_id
                    INNER JOIN oc_product_description pd 
                        ON p.product_id = pd.product_id 
                    WHERE spl.latest_promo_id=:promo_id 
                        AND sps.quantity!=0 AND sd.date_to >= DATE_FORMAT(convert_tz(utc_timestamp(),'-08:00','+0:00'),'%Y-%m-%d') 
                        AND p.status = 1 ORDER BY RAND() LIMIT :offset , :no_per_page";
        }
        $stmt = $this->conn->prepare($sql);            
        $stmt->bindValue(':promo_id', $id);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->bindValue(':no_per_page', 20 , PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($row as $value){
                $img = '';
                if($value['type'] == 'reg'){
                    if($value['image']){
                        $img =  $image->resize($value['image'], 200,200);
                      }else{
                        $img = '';
                      }
                }else{
                    $img = $value['image'];
                }
                $oldPrice = 0;
                $newPrice = $value['price'];
                $priceDeduct = null;
                if($value['deduction_type'] !== null){
                    if($value['deduction_type'] == 0){
                        $priceDeduct = ((float)$value['rate'] * 100) . "% OFF";
                    }else{
                        $priceDeduct = "₱".(int)$value['rate']. " OFF";
                    }
                    $oldPrice = $value['price'];
                    if($value['deduction_type'] == 0 && $value['rate'] != 0){
                       $newPrice = $oldPrice - ($newPrice * (float)$value['rate']);
                    }else if($value['deduction_type'] == 1 && $value['rate'] != 0){
                       $newPrice = $oldPrice - ((float)$value['rate']);
                    }
                }

            $data[] = array(
                'productId' => $value['product_id'],
                'name' => utf8_encode($value['name']),
                'price' => $newPrice,
                'thumb' => $img,
                'type' => $value['type'],
                'deductionType' => $value['deduction_type'],                  
                'rate' => $value['rate'],                  
                'sellerImage' => $value['sellerimage'] ? $image->https_image.$value['sellerimage'] : null,                  
                'shopName' => $value['shop_name'],
                'sellerId' => $value['seller_id'],
                'pricePromoText' => $priceDeduct,
                'promoImageValue' => $checkSellerCountPromo == 0 ? null : $this->GetLp_Thumbnail_image($id),
                'oldPrice' => $oldPrice
                );
        }
    	return $data;
    }
    public function GetLp_Thumbnail_image($id) {
        global $image;
        $stmt = $this->conn->prepare("SELECT thumbnail_image FROM latest_promo where id=:id ");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row['thumbnail_image']){
            return $image->img_path($row['thumbnail_image']);
        }else{
            return null;
        }
        
    }
    public function getCount_selected_seller($promo_id) {
        $stmt = $this->conn->prepare("SELECT count(id) as countid FROM lp_seller_promo_list where latest_promo_id=:promo_id");
        $stmt->bindValue(':promo_id', $promo_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['countid'];
    }
    public function most_popular(){
         global $product;
        $data = array();
        $sql = "SELECT * FROM (
                                        SELECT p.product_id,pv.p_type,pv.date_viewed,pd.name,p.price,p.image,concat('product.php?product_id=',p.product_id) as href,SUM(pv.total_views) as viewed 
                                        FROM product_views pv 
                                        INNER JOIN oc_product p ON p.product_id=pv.product_id
                                        INNER JOIN oc_product_description pd ON pd.product_id=p.product_id
                                        INNER JOIN seller_product_selected sps ON sps.product_id=p.product_id
                                        INNER JOIN oc_seller os ON os.seller_id=sps.seller_id 
                                        WHERE  pv.p_type=0 AND  sps.quantity!=0 and os.status=1
                                        GROUP BY pv.product_id,sps.product_id,pv.p_type,pv.date_viewed,pd.name,p.price,p.image ) AS apv
                                      order by RAND() LIMIT 10";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        foreach($stmt->fetchAll() as $prod){
            $type = 'reg';
            if($prod['p_type'] == 2){
                $type = 'bg';
            }
            $single_product = $product->get_single_product($prod['product_id'], $type);
            if($single_product){
                $data[] = $single_product;
            }
        }
        return $data;
    }
    public function recommended($customer_id){
       $customer_id = $customer_id == 'null' ? 1601 : $customer_id;
       global $image;
       $recommended_product = array();
        $limit = 10;
        $type = 'reg';
        $stmt = $this->conn->prepare("SELECT * FROM (
                                        SELECT p.product_id,pv.p_type,pv.date_viewed,pd.name,p.price, p.image, SUM(pv.total_views) as viewed 
                                        FROM product_views pv 
                                        INNER JOIN oc_product p ON p.product_id=pv.product_id
                                        INNER JOIN oc_product_description pd ON pd.product_id=p.product_id
                                        INNER JOIN seller_product_selected sps ON sps.product_id=p.product_id
                                        INNER JOIN oc_seller os ON os.seller_id=sps.seller_id 
                                        WHERE pv.customer_id=:customer_id AND pv.p_type=0 AND sps.quantity!=0 and os.status=1
                                        GROUP BY pv.product_id,sps.product_id, pv.p_type,pv.date_viewed,pd.name,p.price, p.image ) AS apv
                                      order by RAND() limit :offset");
        $stmt->bindValue(':customer_id',$customer_id);
        $stmt->bindValue(':offset', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
            if($value['p_type'] == 2){
                $type = 'bg';
            }else{
                $type = 'reg';
            }
            $img = '';
                if($type == 'reg'){
                    if($value['image']){
                        $img =  $image->resize($value['image'], 200,200);
                      }else{
                        $img = '';
                      }
                }else{
                    $img = $value['image'];
                }
             $recommended_product[] = array(
                'product_id' => $value['product_id'],
                'name' => utf8_encode($value['name']),
                'price' => $value['price'], 
                'thumb' => $img,
                'type' => $type
            );
        }
        
        return $recommended_product;
    }
    public function home_page(){
        $stmt = $this->conn->prepare("SELECT cd.category_id, cd.name,
        IFNULL(hc.show_limit,0) as show_limit,
        IFNULL(hc.sort_order,'p.price ASC') as sort_order
        FROM oc_customer_home_category hc  
        LEFT JOIN oc_category_description cd 
            ON cd.category_id= hc.category_id 
        WHERE hc.customer_id =0 order by hc.id asc");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function home_product_page($data = array()){
        global $image;
        $res = array();
        $stmt = $this->conn->prepare("SELECT p.image, p.product_id, p.price, pd.name, 'reg' as type
            FROM seller_product_selected sps
            INNER JOIN oc_seller os ON os.seller_id=sps.seller_id 
            INNER JOIN oc_product p On sps.product_id=p.product_id
            INNER JOIN oc_product_to_category p2c
                ON p2c.product_id = p.product_id
            INNER JOIN oc_product_description pd ON p.product_id = pd.product_id          
            WHERE  sps.quantity!=0 and p.status = '1' and os.status=1  AND p2c.category_id= :category_id
            GROUP by sps.product_id, p.image, p.product_id, p.price, pd.name 
            ORDER BY RAND() LIMIT ".$data['limit']);
        $stmt->bindValue(':category_id',$data['category_id']);
        $stmt->execute();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $product){
            $res[] = array(
                'productId' => $product['product_id'],
                'name' => utf8_encode($product['name']),
                'price' => $product['price'],
                'type' => $product['type'],
                'thumb' => $image->resize($product['image'], 200,200)
            );
        }
        return $res;
    }
    public function banner($banner_id)
    {
        global $image;
        $data = array();
        $stmt = $this->conn->prepare("SELECT * FROM oc_banner_image bi 
                                    LEFT JOIN oc_banner_image_description bid 
                                        ON (bi.banner_image_id  = bid.banner_image_id) 
                                    WHERE bi.banner_id = :banner_id 
                                    ORDER BY bi.sort_order ASC");
        $stmt->bindValue(':banner_id',$banner_id);
        $stmt->execute();
        foreach($row = $stmt->fetchAll(PDO::FETCH_ASSOC) as $banner){
            $data[] = $image->img_path($banner['image']);
        }
        
        return $data;

    }
    public function promo(){
        global $image;
        $data = array();
        $stmt = $this->conn->prepare("SELECT *  FROM latest_promo WHERE status =:status AND featured_promo = 0 ORDER BY sort ASC");
        $stmt->bindValue(':status', 1);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($row as $key=> $value){
            $data[] = array(
                'img' => $image->img_path($value['image']),
                'id' => $value['id'],
                'title' => $value['title']
                );
        }
        
        return $data;
    }
    public function categories(){
        global $image;
        $data = array();
        $stmt = $this->conn->prepare("SELECT * FROM oc_category c 
        LEFT JOIN oc_category_description cd ON (c.category_id = cd.category_id)
        WHERE c.status = :status AND c.top = 1 ORDER BY c.sort_order, LCASE(cd.name)");
        $stmt->bindValue(':status', 1);
        $stmt->execute();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $cat){
            if($cat['category_id'] !== null){
                $data[] = array(
                'categoryId' => $cat['category_id'],
                'name' => $cat['name'],
                'image' => $image->resize($cat['image'], 75,75)
                );
            }
        }
        
        // return $this->fill_chunck($data, 3);
        return $data;
        
    }
    function fill_chunck($array, $parts) {
        $t = 0;
        $result = array_fill(0, $parts - 1, array());
        $max = ceil(count($array) / $parts);
        foreach($array as $v) {
            count($result[$t]) >= $max and $t ++;
            $result[$t][] = $v;
        }
        return $result;
    }
    public function home_category($page_number, $customer_id){
        global $image;
        $data = array();
        try{
        $total_category = $this->total_home_page_by_customer($customer_id);
        if($total_category > 0 && $customer_id !== 'null'){
            $category = $this->home_category_default($page_number, $customer_id);
        }else{
            $category = $this->home_category_default($page_number);
        }
        $products = array();
        if(count($category) > 0){
            foreach($category as $cat){
                $category_name = $cat['name'];
                $category_id = $cat['category_id'];
                $show_order = $cat['sort_order'];
                $show_limit = $cat['show_limit'] == 0 ? 3 : 3;
                foreach($this->product_by_category($category_id, $show_limit, $show_order) as $product){
                    $rand = rand(1,10);
                    $name_sub =$product['name'];
                    $products[] = array(
                        'productId' => $product['product_id'],
                        'thumb' => $image->resize($product['image'], 200,200),
                        'name' => $name_sub,
                        'price' => $product['special'] != null ? $product['special'] : $product['price'],
                        'oldPrice' => $product['special'] != null ? $product['price'] : false,
                        'rating' => $product['rating'],
                        'totalSold' => "$rand sold",
                        'type' => $product['type']
                    );
                }
               
            }
            $data[] = array(
                'name' => $category_name,
                'categoryId' => $category_id,
                'products' => $products,
    
            );
        }

        return $data;
       }catch(Exception $e){
        echo $e->getMessage();
        // $date = (new DateTime('now', new DateTimezone('Asia/Manila')))->format('m-d-Y h i s a');
        // error_log($e, 3, "/home/irpge67jnamu/public_html/mwapi/error_log/$date.txt");

        // $s = $this->conn->prepare("INSERT INTO error_log SET 
        // error_message = :msg, date_created = convert_tz(utc_timestamp(),'-08:00','+0:00')");
        // $s->bindValue(':msg', $e);
        // $s->execute();
       }
    }
    public function product($product_id, $type = 'reg', $token = '', $customer_id, $storeId){
        global $product;
        global $chinabrands;
        global $aliexpress;
        global $image;
        global $banggood;
        $p_type = 0;
        if($type == 'bg'){
            $p_type = 2;
        }
        $s = $this->conn->prepare('SELECT count(id) as cntid FROM product_views where customer_id = :customer_id 
            AND product_id = :product_id and p_type=:p_type');
        $s->bindValue(':customer_id', $customer_id);
        $s->bindValue(':product_id', $product_id);
        $s->bindValue(':p_type', $p_type);
        $s->execute();
        $row = $s->fetch(PDO::FETCH_ASSOC);
        $nowDate = (new DateTime('now', new DateTimezone('Asia/Manila')))->format('Y-m-d H:i:s');
        if($customer_id == 'null' || $customer_id == 'undefined'){
            $customer_id = null;
        }
        if($row['cntid'] == 0){
            $stmt = $this->conn->prepare("INSERT INTO product_views 
                                                  SET customer_id = :customer_id, ip = :ip,p_type=:p_type,
                                                      product_id=:product_id,date_viewed=convert_tz(utc_timestamp(),'-08:00','+0:00')");
            $stmt->bindValue(':customer_id', $customer_id);
            $stmt->bindValue(':ip', $_SERVER['REMOTE_ADDR']);
            $stmt->bindValue(':p_type', $p_type);
            $stmt->bindValue(':product_id', $product_id);
            $stmt->execute();
        }else{
           $stmt = $this->conn->prepare("UPDATE product_views 
                                                  SET date_viewed=convert_tz(utc_timestamp(),'-08:00','+0:00')
                                                where customer_id = :customer_id 
                                                AND product_id = :product_id and p_type=:p_type");
            $stmt->bindValue(':customer_id', $customer_id);
            $stmt->bindValue(':p_type', $p_type);
            $stmt->bindValue(':product_id', $product_id);
            $stmt->execute();
        }
        switch($type){
            case 'reg':
                return $product->localProduct($product_id, $storeId);
            break;
            case 'cb':
                return $chinabrands->getProduct($token, $product_id);
            break;
            case 'bg':
                return $banggood->getProduct($product_id);
            break;
            case 'ae':
                return $aliexpress->getProduct($product_id);
            break;
        }
        
    }
    public function home_category_default($page_number, $customer_id = 0){
        $offset = $this->set_offset($page_number);
        $stmt = $this->conn->prepare("SELECT ochc.*,cd.name,cd.category_id as cid,ochc.id as ochcid,
        IFNULL(ochc.show_limit,0) as show_limit,
        IFNULL(ochc.sort_order,'p.price ASC') as sort_order
        FROM oc_customer_home_category ochc  
        LEFT JOIN oc_category_description cd 
            ON cd.category_id= ochc.category_id 
        WHERE ochc.customer_id=:customer_id order by ochc.id asc LIMIT :offset , :no_per_page");
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->bindValue(':no_per_page', $this->no_per_page, PDO::PARAM_INT);
        $stmt->bindValue(':customer_id', (int) $customer_id);
        $stmt->execute();
        return $stmt->fetchAll();
        
    }
    public function set_offset($page_number){
        return ($page_number-1) * $this->no_per_page;
    }
    public function total_home_page_by_customer($customer_id){
        $stmt = $this->conn->prepare("SELECT count(id) as total FROM oc_customer_home_category WHERE customer_id=:customer_id");
        $stmt->bindValue(':customer_id', (int) trim($customer_id), PDO::PARAM_INT);
        $stmt->execute();
        return intval($stmt->fetch()['total']);

    }
    public function product_by_category($category_id, $row_num, $sort_order){
        global $product;
        $sql = "SELECT p2c.product_id FROM  oc_product_to_category p2c  
        LEFT JOIN oc_product p 
            ON p2c.product_id = p.product_id
        LEFT JOIN oc_product_description pd 
            ON p.product_id = pd.product_id 
        LEFT JOIN oc_product_to_store p2s 
            ON (p.product_id = p2s.product_id) 
        WHERE pd.language_id = '1' AND p.status = '1' 
        AND p.date_available <= convert_tz(utc_timestamp(),'-08:00','+0:00') 
        AND p2s.store_id = 0 AND p2c.category_id=:category_id ORDER BY $sort_order limit :show ";
        $st = $this->conn->prepare($sql);
        $st->bindValue(':category_id',$category_id);
        $st->bindParam(':show', $row_num, PDO::PARAM_INT);
        $st->execute();
        // echo $st->debugDumpParams();
        $products = array();
        foreach($row = $st->fetchAll(PDO::FETCH_ASSOC) as $res){
            $products[$res['product_id']] = $product->get_single_product($res['product_id'], 'reg');
        }
        return $products;
    }
    public function getTotalSold($product_id){
        $st = $this->conn->prepare("SELECT IFNULL(SUM(op.quantity), 0) AS quantity FROM oc_order_product op 
        LEFT JOIN oc_order o ON (op.order_id = o.order_id) 
        WHERE o.order_status_id = 20 AND op.product_id =:product_id");
        $st->bindValue(':product_id', $product_id);
        $st->execute();
        return intval($st->fetch()['quantity']);
    }
    public function get_attribute($product_id){
        $textdescription="";
        $attr = array();
        $description_data = array();
        $select_att = $this->conn->prepare("SELECT ag.attribute_group_id, agd.name 
                    FROM oc_product_attribute pa LEFT JOIN oc_attribute a ON pa.attribute_id = a.attribute_id
                    LEFT JOIN oc_attribute_group ag ON a.attribute_group_id = ag.attribute_group_id
                    LEFT JOIN oc_attribute_group_description agd ON ag.attribute_group_id = agd.attribute_group_id 
                    WHERE pa.product_id = :product_id  GROUP BY ag.attribute_group_id 
                    ORDER BY ag.sort_order, agd.name");
        $select_att->bindValue(':product_id', $product_id);
        $select_att->execute();
        $attval = $select_att->fetchAll(PDO::FETCH_ASSOC);
        foreach($attval as $attributedes){
            $select_att_des = $this->conn->prepare("SELECT a.attribute_id, ad.name, pa.text 
                FROM oc_product_attribute pa LEFT JOIN oc_attribute a ON pa.attribute_id = a.attribute_id 
                LEFT JOIN oc_attribute_description ad ON a.attribute_id = ad.attribute_id
                WHERE pa.product_id = :product_id AND a.attribute_group_id = :attribute_group_id 
                ORDER BY a.sort_order, ad.name");
            $select_att_des->bindValue(':product_id', $product_id);
            $select_att_des->bindValue(':attribute_group_id', $attributedes['attribute_group_id']);
            $select_att_des->execute();
            $attval_des = $select_att_des->fetchAll(PDO::FETCH_ASSOC);
            foreach($attval_des as $attval_desc){
                $textdescription = utf8_encode($attval_desc['text']);
                 $row = array_map('utf8_encode', $attval_desc);
                 $description_data[] = array(
                    'attribute_id' => $attval_desc['attribute_id'],
                    'name'         => $attval_desc['name'],
                    'text'         =>   $textdescription,
                );
            }
              $attr[] = array(
                'attribute_group_id' => $attributedes['attribute_group_id'],
                'name' => $attributedes['name'],
                'attribute' => $description_data,
              
            );
        }

        return $attr;
    }
    public function show_products(){
        $products = $search->search_products();

                echo json_encode($data);
    }
    public function search_products($type){
        global $product;
        global $category;
        global $image;
        $data = array();
        $filter_data = array();
        $data['products'] = array();
        $products = array();

        $search = isset($_GET['value']) ? trim($_GET['value']) : '';
        $category_id = isset($_GET['category_id']) ? trim($_GET['category_id']) : 0;
        $page = isset($_GET['page']) ? trim($_GET['page']) : 1;

        if(isset($_GET['range'])){
            if($_GET['range'] !== 'null'){
                $price_range = explode('_', $_GET['range']);
            }else{
                $price_range = array();
            }
        }
        $limit = 12;
        $sort = '';
        $order = '';
        
        if($search){
           if($type=="category"){
            if(isset($_GET['sort'])){
                switch ($_GET['sort']) {
                    case 'naz':
                        $sort = 'pd.name';
                        $order = 'ASC';
                    break;
                    case 'nza':
                        $sort = 'pd.name';
                        $order = 'DESC';
                    break;
                    case 'plh':
                        $sort = 'p.price';
                        $order = 'ASC';
                    break;
                    case 'phl':
                        $sort = 'p.price';
                        $order = 'DESC';
                    break;
                    case 'dl':
                        $sort = 'p.date_added';
                        $order = 'DESC';
                    break;
                    case 'do':
                        $sort = 'p.date_added';
                        $order = 'ASC';
                    break;
                    default:
                        $sort = 'pd.name';
                        $order = 'ASC';
                    break;
                }
            }
            $filter_data = array(
                'filter_category_id'  => $search,
                'start'               => ($page - 1) * $limit,
                'limit'               => $limit,
                'order'               => $order,
                'sort'                => $sort,
                'price_range'         => $price_range
            );
            $products = $product->get_category_products($filter_data);
           }else if($type=="search"){
            if(isset($_GET['sort'])){
                switch ($_GET['sort']) {
                    case 'naz':
                        $sort = 'pp.name';
                        $order = 'ASC';
                    break;
                    case 'nza':
                        $sort = 'pp.name';
                        $order = 'DESC';
                    break;
                    case 'plh':
                        $sort = 'pp.price';
                        $order = 'ASC';
                    break;
                    case 'phl':
                        $sort = 'pp.price';
                        $order = 'DESC';
                    break;
                    case 'dl':
                        $sort = 'pp.date_added';
                        $order = 'DESC';
                    break;
                    case 'do':
                        $sort = 'pp.date_added';
                        $order = 'ASC';
                    break;
                    default:
                        $sort = 'pp.name';
                        $order = 'ASC';
                    break;
                }
            }
            $filter_data = array(
                'filter_name'               => $search,
                'filter_description'         => $search,
                'start'               => ($page - 1) * $limit,
                'limit'               => $limit,
                'order'               => $order,
                'sort'                => $sort,
                'price_range'         => $price_range,
                'token'               => $_GET['token'],
                'type'                => $_GET['type']
            );
            if($_GET['type']=="local"){
                $products = $product->store_products_new($filter_data);
            }else{
                $products = $product->get_products($filter_data);
            }
            
           }else if($type=="store"){
            if(isset($_GET['sort'])){
                switch ($_GET['sort']) {
                    case 'naz':
                        $sort = 'p.name';
                        $order = 'ASC';
                    break;
                    case 'nza':
                        $sort = 'pd.name';
                        $order = 'DESC';
                    break;
                    case 'plh':
                        $sort = 'p.price';
                        $order = 'ASC';
                    break;
                    case 'phl':
                        $sort = 'p.price';
                        $order = 'DESC';
                    break;
                    case 'dl':
                        $sort = 'p.date_added';
                        $order = 'DESC';
                    break;
                    case 'do':
                        $sort = 'p.date_added';
                        $order = 'ASC';
                    break;
                    default:
                        $sort = 'pd.name';
                        $order = 'ASC';
                    break;
                }
            }
            $filter_data = array(
                'seller_id'          => $search,
                'start'               => ($page - 1) * $limit,
                'limit'               => $limit,
                'order'               => $order,
                'sort'                => $sort,
                'price_range'         => $price_range
            );
            $products = $product->store_products($filter_data);
            
           }
        }
        // $regular_products = array();
        foreach($products as $res){
                $rand = rand(1,10);
            // $totalSold = $this->getTotalSold($res['product_id']);
            $name_sub = mb_strimwidth($res['name'], 0, 30, "...");
            if (strpos($res['thumb'], 'http') !== false) {
               $img = $res['thumb'];
            }else{
               $img = $image->resize($res['thumb'], 200, 200);
            }
            
            $addNum = 50;
            $min = 0;
            $max = 0;
            $productPrice = (float) $res['price'];
            if($productPrice < $addNum){
                $min = $productPrice;
            }else{
                $min = $productPrice - $addNum;
            }
            $max = $productPrice * 1.25;
            
            if($res['type'] == 'bg'){
                $data['products'][] = array(
                'productId' => $res['product_id'],
                'name' => $name_sub,
                // 'price'       => $res['special'] != null ? $res['special'] : $res['price'],
                // 'oldPrice'    => $res['special'] != null ? $res['price'] : false,
                'priceRange' => array('min' => $min, 'max' => $max),
                'thumb' =>  $img,
                // 'quantity' => $res['quantity'],
                'totalSold' => "$rand sold",
                'type' => $res['type']
            );
            }else{
                $data['products'][] = array(
                'productId' => $res['product_id'],
                'name' => $name_sub,
                'price'       => $res['price'],
                'thumb' =>  $img,
                'totalSold' => "$rand sold",
                'type' => $res['type']
            );
            }
            
        }
        // $china_products = $product->get_products_cb($filter_data);
        // $data['products'] = array_merge($china_products, $regular_products);

        switch($type){
            case 'category':
                $data['name'] = $category->get_category_by_id($search)['name'];
                $data['logo'] = '';
            break;
            case 'search':
                $data['name'] = $search;
                $data['logo'] = '';
            break;
        }
        return $data;
    }

    public function search_cb($token){
        global $product;
        $result = array();
        $data = array(
            'token' => $token,
            'filter_name' => $_GET['value'],
            'start' => $_GET['page_number']
        );
        $result = $product->get_products_cb($data);
        return $result;
    }
    public function getStoreCategories($sid){
       global $image;  
       $row=array();
       $stmt = $this->conn->prepare("SELECT PTC.category_id, c.category_id,c.image,cd.name FROM oc_category c 
										INNER JOIN oc_category_description cd ON c.category_id = cd.category_id
										INNER JOIN oc_product_to_category PTC ON PTC.category_id=c.category_id
										INNER JOIN seller_product_selected sps ON PTC.product_id=sps.product_id
										WHERE c.parent_id = 0 AND c.status = '1' AND c.top = 1 AND  sps.seller_id=:sid
										GROUP BY PTC.category_id, c.category_id,c.image,cd.name");
        $stmt->bindValue(':sid',$sid);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
             $row[] = array(
                'categoryId' => $value['category_id'],
                'name' => utf8_encode($value['name']),
                'image' => $image->resize($value['image'], 75,75)
            );
        }
        return $row;
    }
    public function latestPromoName($promoId){
        $stmt = $this->conn->prepare("SELECT * FROM latest_promo WHERE id = :id");
        $stmt->bindValue(':id',$promoId);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            return $stmt->fetch()['title'];
        }else{
            return null;
        }
        
    }
    public function getSellerLatestPromo($seller_id, $page_number) {
        global $image;
        $offset = ($page_number-1) * 12;
        $row=array();
        $stmt = $this->conn->prepare("SELECT * FROM (SELECT 'reg' as type, sd.deduction_type,sd.value,sd.seller_id,
                                            CASE WHEN (sd.deduction_type = 0) THEN 'Percentage (%)' ELSE 'PHP' END AS d_type,
                                            CASE WHEN (IFNULL(sd.deduction_type,0)=0) THEN IFNULL(sd.value,0) / 100  ELSE IFNULL(sd.value,0) END AS  rate,  os.shop_name, concat('img/company/',os.image) as sellerimage,sdp.product_id,p.price,pd.name,p.image as image,
                                            'Local Product' as typedesc,concat('product.php?product_id=',p.product_id,'&store_id=',sd.seller_id) as href
									FROM seller_deductions sd 
									INNER JOIN  seller_deductions_product sdp 
										ON sd.id=sdp.deduction_id
									 INNER JOIN seller_product_selected psp 
									 	ON psp.product_id=sdp.product_id AND sd.seller_id=psp.seller_id
									 INNER JOIN  oc_product p 
									 	ON  psp.product_id=p.product_id
									 INNER JOIN oc_product_description pd 
									 	ON p.product_id = pd.product_id
									 INNER JOIN oc_seller os
                                        ON os.seller_id=sd.seller_id
									WHERE sd.seller_id=:sellerid AND psp.quantity!=:quantity AND p.status = :status
										AND 
											(
												sd.date_from <=  DATE_FORMAT(convert_tz(utc_timestamp(),'-08:00','+0:00'),'%Y-%m-%d') 
												and
												sd.date_to >= DATE_FORMAT(convert_tz(utc_timestamp(),'-08:00','+0:00'),'%Y-%m-%d')
											)
									UNION ALL
									SELECT 'reg' as type, sd.deduction_type,sd.value,sd.seller_id,
                                            CASE WHEN (sd.deduction_type = 0) THEN 'Percentage (%)' ELSE 'PHP' END AS d_type,
                                            CASE WHEN (IFNULL(sd.deduction_type,0)=0) THEN IFNULL(sd.value,0) / 100  ELSE IFNULL(sd.value,0) END AS  rate,  os.shop_name, concat('img/company/',os.image) as sellerimage,sdp.product_id,p.price,pd.name,p.image as image,
                                            'Local Product' as typedesc,concat('product.php?product_id=',p.product_id,'&store_id=',sd.seller_id) as href
									FROM seller_deductions sd 
									INNER JOIN  seller_deductions_product sdp 
										ON sd.id=sdp.deduction_id
									 INNER JOIN seller_product_selected psp 
									 	ON psp.product_id=sdp.product_id AND sd.seller_id=psp.seller_id
									 INNER JOIN  oc_product p 
									 	ON  psp.product_id=p.product_id
									 INNER JOIN oc_product_description pd 
									 	ON p.product_id = pd.product_id
									  INNER JOIN oc_seller os
                                        ON os.seller_id=sd.seller_id
									WHERE sd.seller_id=:sellerid AND psp.quantity!=:quantity AND p.status = :status
										AND  
										(
											sd.date_from >  DATE_FORMAT(convert_tz(utc_timestamp(),'-08:00','+0:00'),'%Y-%m-%d') 
											and 
											sd.date_to > DATE_FORMAT(convert_tz(utc_timestamp(),'-08:00','+0:00'),'%Y-%m-%d')
										)
									ORDER BY RAND()) pp LIMIT :offset , :no_per_page");
        $stmt->bindValue(':sellerid', $seller_id);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->bindValue(':no_per_page', 12 , PDO::PARAM_INT);
        $stmt->bindValue(':status', 1);
        $stmt->bindValue(':quantity', 0);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
                $img = '';
                if($value['type'] == 'reg'){
                    if($value['image']){
                        $img =  $image->resize($value['image'], 200,200);
                      }else{
                        $img = '';
                      }
                }else{
                    $img = $value['image'];
                }
                $oldPrice = 0;
                $newPrice = $value['price'];
                $priceDeduct = null;
                if($value['deduction_type'] !== null){
                    if($value['deduction_type'] == 0){
                        $priceDeduct = ((float)$value['rate'] * 100) . "% OFF";
                    }else{
                        $priceDeduct = "₱".(int)$value['rate']. " OFF";
                    }
                    $oldPrice = $value['price'];
                    if($value['deduction_type'] == 0 && $value['rate'] != 0){
                       $newPrice = $oldPrice - ($newPrice * (float)$value['rate']);
                    }else if($value['deduction_type'] == 1 && $value['rate'] != 0){
                       $newPrice = $oldPrice - ((float)$value['rate']);
                    }
            }
            $row[] = array(
                'productId' => $value['product_id'],
                'name' => utf8_encode($value['name']),
                'price' => $newPrice,
                'thumb' => $img,
                'type' => $value['type'],
                'deductionType' => $value['deduction_type'],                  
                'rate' => $value['rate'],                  
                'sellerImage' => $value['sellerimage'] ? $image->https_image.$value['sellerimage'] : null,                  
                'shopName' => $value['shop_name'],
                'sellerId' => $value['seller_id'],
                'pricePromoText' => $priceDeduct,
                'promoImageValue' => null,
                'oldPrice' => $oldPrice,
                
            );
        }
        return $row; 
    
}
    public function getGuestCategory(){
        global $image;
        $stmt = $this->conn->prepare("SELECT * FROM guest_category_list WHERE `status` = 1");
        $stmt->execute();
        $data = array();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $value){
            $data[] = array(
                'id' => $value['id'],
                'name' => $value['name'],
                'image' => $image->resize('guest-category/'.$value['image'], 100, 100),
                
            );
        }
        return $data;
    }
    public function insertGuestCategory($data){
        $categories = json_decode($data);
        foreach($categories as $value){
            $stmt = $this->conn->prepare("INSERT customer_guest_category SET guest_category_id = :guest_category_id, customer_id = :customer_id");
            $stmt->bindValue(':customer_id',$value->customerId);
            $stmt->bindValue(':guest_category_id',$value->id);
            $stmt->execute();
        }
        return array('message' => 'Successfully added.');
    }
    public function flagshipStore(){
        global $image;
        $stmt = $this->conn->prepare("SELECT os.seller_id,fp.flagship_name as shop_name,concat('company/',fp.flagship_logo) as image FROM oc_seller os INNER JOIN flagship_profile fp ON fp.seller_id= os.seller_id WHERE os.seller_type=2 and os.status=1 order by RAND()");
        $stmt->execute();
        $data = array();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $value){
            $data[] = array(
                'sellerId' => $value['seller_id'],
                'name' => $value['shop_name'],
                'image' => $image->resize($value['image'], 50, 50)
            );
        }
        return $data;
    }
    public function featuredPromo($status){
        global $image;
        $stmt = $this->conn->prepare("SELECT *  FROM latest_promo where featured_promo =:featured_promo order by sort asc");
        $stmt->bindValue(':featured_promo',$status);
        $stmt->execute();
        $row = array();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
            $count = $this->getCount_selected_seller($value['id']);
            $img = '';
            if($value['featured_promo'] == 1){
                $img = $image->resize($value['image'], 518,246);
            }else if($value['featured_promo'] == 2){
                $img = $image->resize($value['image'], 280,280);
            }else if($value['featured_promo'] == 3){
                $img = $image->resize($value['image'], 280,280);
            }else{
                $img = $image->resize($value['image'], 200,200);
            }
            $row[] = array(
                'image' => $value['image'],
                'id' => $value['id'],
                'title' => $value['title'],
                'thumb' => $img,
                'products' => $count > 0 && $value['featured_promo'] == 1 ? $this->FeaturedPromoProducts($value['id'], 10) : []
            );
        } 
        return $row; 
    }
    public function FeaturedPromoProducts($promo_id,$limit) {
        global $image;
        $row=array();
        $stmt = $this->conn->prepare("SELECT spl.deduction_id,sd.deduction_type,sd.value,sd.seller_id,
                                            CASE WHEN (sd.deduction_type = 0) THEN 'Percentage (%)' ELSE 'PHP' END AS d_type,
                                            CASE WHEN (IFNULL(sd.deduction_type,0)=0) THEN IFNULL(sd.value,0) / 100  ELSE IFNULL(sd.value,0) END AS  rate,
                                            os.shop_name, concat('img/company/',os.image) as sellerimage,sdp.product_id,p.price,pd.name,p.image as image,
                                            'Local Product' as typedesc,'0' as type,concat('product.php?product_id=',p.product_id,'&store_id=',sd.seller_id) as href
                                    FROM lp_seller_promo_list spl 
                                    INNER JOIN seller_deductions sd 
                                        ON sd.id=spl.deduction_id 
                                    INNER JOIN oc_seller os
                                        ON os.seller_id=sd.seller_id
                                    INNER JOIN seller_deductions_product sdp
                                        ON sdp.deduction_id=spl.deduction_id
                                    INNER JOIN seller_product_selected sps
                                        ON sps.seller_id=os.seller_id AND sps.product_id=sdp.product_id
                                    INNER JOIN oc_product p   
                                        ON  p.product_id=sdp.product_id
                                    INNER JOIN oc_product_description pd 
                                        ON p.product_id = pd.product_id 
                                    WHERE spl.latest_promo_id=:promo_id 
                                        AND sps.quantity!=:quantity
                                        AND p.status = :status 
                                        AND sd.date_to >= DATE_FORMAT(convert_tz(utc_timestamp(),'-08:00','+0:00'),'%Y-%m-%d')
                                    ORDER BY RAND() limit :offset");
        $stmt->bindValue(':promo_id', $promo_id);
        $stmt->bindValue(':status', 1);
        $stmt->bindValue(':quantity', 0);
        $stmt->bindValue(':offset', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
                $oldPrice = 0;
                $newPrice = $value['price'];
                $priceDeduct = null;
                if($value['deduction_type'] !== null){
                    if($value['deduction_type'] == 0){
                        $priceDeduct = ((float)$value['rate'] * 100) . "% OFF";
                    }else{
                        $priceDeduct = "₱".(int)$value['rate']. " OFF";
                    }
                    $oldPrice = $value['price'];
                    if($value['deduction_type'] == 0 && $value['rate'] != 0){
                       $newPrice = $oldPrice - ($newPrice * (float)$value['rate']);
                    }else if($value['deduction_type'] == 1 && $value['rate'] != 0){
                       $newPrice = $oldPrice - ((float)$value['rate']);
                    }
                }
            $row[] = array(
                'image' => $value['image'],
                'product_id' => $value['product_id'],
                'name' => $value['name'],
                'price' => $value['price'], 
                'type' => $value['type'], 
                'typedesc' => $value['typedesc'], 
                'href' => $value['href'],                 
                'deduction_type' => $value['deduction_type'],                  
                'rate' => $value['rate'],                  
                'sellerimage' => $value['sellerimage'],                  
                'shop_name' => $value['shop_name'],                  
                'value' => $value['value'],                  
                'seller_id' => $value['seller_id'],                  
                'promoImgVal' =>$this->GetLp_Thumbnail_image($promo_id),                  
                'thumb' => $image->resize($value['image'], 200,200),
                'pricePromoText' => $priceDeduct
            );
        }
        return $row; 
    }
}
$home = new Home();