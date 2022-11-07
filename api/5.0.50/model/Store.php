<?php
require_once '../init.php';
class Store {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    function __destruct(){
        $this->conn = null;
    }
    public function get_seller($seller_id){
        global $image;
        $data = array();
        $stmt = $this->conn->prepare("SELECT s.seller_id, s.shop_name, concat('company/', fp.flagship_logo) as image, fp.theme_color FROM oc_seller s LEFT JOIN flagship_profile fp ON fp.seller_id = s.seller_id WHERE s.seller_id = :seller_id LIMIT 1");
        $stmt->bindValue(':seller_id', $seller_id);
        $stmt->execute();
        $res = $stmt->fetch();
        if($res){
            $data = array(
                'sellerId' => $res['seller_id'],
                'image' => $image->resize($res['image'], 40,40),
                'shopName'=> $res['shop_name']
            );
        }
        return $data;
    }
    public function get_branch_name($branch_id){
        $stmt = $this->conn->prepare("SELECT * FROM seller_branch where id = :id");
        $stmt->bindValue(':id', $branch_id);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? $stmt->fetch()['b_name'] : '';
    }
    public function seller_total_products($seller_id){
        $sql = "SELECT SUM(quantity) as total FROM oc_product WHERE ";
        if($seller_id == null){
            $sql .= "seller_id is null ";
            $sql .= "AND status = 1";
            $s = $this->conn->prepare($sql);
        }else{
            $sql .= "seller_id = :seller_id ";
            $sql .= "AND status = 1";
            $s = $this->conn->prepare($sql);
            $s->bindValue(":seller_id", $seller_id);
        }
       
        $s->execute();
        return $s->fetch()['total'];
    }
    public function seller_total_orders($seller_id){
        $sql = "SELECT COUNT(op.order_product_id) as total 
        FROM oc_order_product op 
        LEFT JOIN oc_order o ON o.order_id = op.order_id 
        LEFT JOIN oc_product p ON op.product_id = p.product_id 
        WHERE ";
        if($seller_id == null){
            $sql .= "p.seller_id is null ";
            $sql .= "AND o.order_status_id > 0";
            $s = $this->conn->prepare($sql);
        } else{
            $sql .= "p.seller_id = :seller_id ";
            $sql .= "AND o.order_status_id > 0";
            $s = $this->conn->prepare($sql);
            $s->bindValue(":seller_id", $seller_id);
        }
      
        $s->execute();
        return $s->fetch()['total'];
    }
    public function store_profile($seller_id, $customer_id){
        global $image;
        $data = array();
        $stmt = $this->conn->prepare("SELECT 
            s.seller_id,
            CONCAT('company/', fp.flagship_logo) as image, 
            IF(EXISTS(SELECT * FROM followers WHERE seller_id = :seller_id AND customer_id = :customer_id ), 1, 0) as followed,
            (SELECT COUNT(*) FROM followers WHERE seller_id = :seller_id ) as followers,
            fp.flagship_name,
            fp.theme_color,
            fp.flagship_desc
            FROM oc_seller s LEFT JOIN flagship_profile fp ON fp.seller_id = s.seller_id WHERE s.seller_id = :seller_id LIMIT 1");
        $stmt->bindValue(':seller_id', $seller_id);
        $stmt->bindValue(':customer_id', $customer_id);
        $stmt->execute();
        $res = $stmt->fetch();
        if($res){
            $data = array(
                'sellerId' => $res['seller_id'],
                'image' => $image->resize($res['image'], 200,200),
                'name'=> $res['flagship_name'],
                'description'=> $res['flagship_desc'],
                'color' => $res['theme_color'],
                'followers' => 1000,
                'followed' => (int)$res['followed'] == 1 ? true : false
            );
        }
        return $data;
    }
    public function follow($customer_id, $seller_id){
        $stmt1 = $this->conn->prepare("SELECT * FROM followers WHERE customer_id=:customer_id AND seller_id=:seller_id");
        $stmt1->bindValue(':seller_id', $seller_id);
        $stmt1->bindValue(':customer_id', $customer_id);
        $stmt1->execute();
        if($stmt1->rowCount() > 0){
            $stmt2 = $this->conn->prepare("DELETE FROM followers WHERE customer_id=:customer_id AND seller_id=:seller_id");
            $stmt2->bindValue(':seller_id', $seller_id);
            $stmt2->bindValue(':customer_id', $customer_id);
            $stmt2->execute(); 
            return array(
                'message' => 'Unfollowed',
                'status' => false
            );
        }else{
            $stmt3 = $this->conn->prepare("INSERT INTO followers SET customer_id=:customer_id, seller_id=:seller_id");
            $stmt3->bindValue(':seller_id', $seller_id);
            $stmt3->bindValue(':customer_id', $customer_id);
            $stmt3->execute(); 
            return array(
                'message' => 'Followed',
                'status' => true
            );
        }
    }
    public function banner($seller_id){
        global $image;
        $data = array();
        $stmt = $this->conn->prepare("SELECT * FROM `seller_banner` where seller_id = :seller_id order by sort_order");
        $stmt->bindValue(':seller_id', $seller_id);
        $stmt->execute();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $value){
            $data[] = array(
                'sellerId' => $value['seller_id'],
                'image' => $image->img_path($value['banner_web'])
            );
        }
        return $data;
        
    }
    public function flagship_announcement($seller_id, $type){
        global $image;
        $data = array();
        $stmt = $this->conn->prepare("SELECT * FROM `flagship_announcement` where seller_id = :seller_id and type = :type ORDER BY sort_order");
        $stmt->bindValue(':seller_id', $seller_id);
        $stmt->bindValue(':type', $type);
        $stmt->execute();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $value){
            $data[] = array(
                'sellerId' => $value['seller_id'],
                'image' => $image->img_path($value['image'])
            );
        }
        return $data;
    }
        public function GetFlagShipProduct($seller_id){
        $limit = 10;
        $row=array();
        global $image;
        $b_id=$this->GetFlasShipSelectedBrand($seller_id);
        $stmt = $this->conn->prepare("SELECT p.image, p.product_id, p.price, pd.name,
                                        concat('product.php?product_id=',p.product_id) as href
                                    FROM seller_product_selected sps
                                    INNER JOIN oc_seller os ON os.seller_id=sps.seller_id 
                                    INNER JOIN oc_product p On sps.product_id=p.product_id
                                    INNER JOIN product_to_brand ptb
                                        ON sps.product_id = ptb.product_id 
                                    INNER JOIN oc_product_description pd ON p.product_id = pd.product_id          
                                    WHERE  sps.quantity!=0 and p.status = '1' and os.status=1  AND ptb.brand_id = :b_id
                                    GROUP by sps.product_id,p.image, p.product_id, p.price, pd.name
                                   order by RAND() limit :offset");
        $stmt->bindValue(':offset', (int) $limit, PDO::PARAM_INT);         
        $stmt->bindValue(':b_id',$b_id);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
                 $row[] = array(
                    'image' => $value['image'],
                    'product_id' => $value['product_id'],
                    'name' => $value['name'],
                    'price' => $value['price'], 
                    'type' => '0', 
                    'href' => $value['href'], 
                    'thumb' => $image->resize($value['image'], 200,200)
                );
        } 
        return $row; 
    }
    public function GetFlasShipSelectedBrand($seller_id){
        $data=array();
        $stmt = $this->conn->prepare("SELECT * FROM seller_brand WHERE seller_id=:seller_id limit 1");
        $stmt->bindValue(':seller_id',$seller_id);
        $stmt->execute();
        $data=$stmt->fetch(PDO::FETCH_ASSOC);        
        return $data['brand_id'];
    }
    public function getbrandcategory($seller_id){
       $bid = $this->GetFlasShipSelectedBrand($seller_id);
       global $image;  
       $stmt = $this->conn->prepare("SELECT c.category_id,c.image,cd.name FROM oc_category c 
                                    INNER JOIN oc_category_description cd ON c.category_id = cd.category_id
                                    INNER JOIN oc_product_to_category PTC ON PTC.category_id=c.category_id
                                    INNER JOIN product_to_brand PTB ON PTC.product_id=PTB.product_id
                                    WHERE c.parent_id = 0 AND c.status = '1' AND c.top = 1 AND  PTB.brand_id=:bid
                                    GROUP BY PTC.category_id, c.category_id,c.image,cd.name");
        $stmt->bindValue(':bid',$bid);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
             $row[] = array(
                'image' => $value['image'],
                'category_id' => utf8_encode($value['category_id']),
                'name' => $value['name'],
                'thumb' => $image->resize($value['image'], 75,75)
            );
        }
        return $row;
    }
    public function getCategory($category_id){
        $stmt = $this->conn->prepare("SELECT c.category_id, c.image, cd.name FROM oc_category c LEFT JOIN oc_category_description cd ON cd.category_id = c.category_id WHERE c.category_id = :category_id");
        $stmt->bindValue(':category_id',$category_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function FlagShipMostPularWithCategory($seller_id, $category_id) {
    $limit = 10;
    $FSBrand_id = $this->GetFlasShipSelectedBrand($seller_id);
    global $image;
    $stmt = $this->conn->prepare("SELECT * FROM (
                                            SELECT PTC.category_id,ptb.brand_id,p.product_id,pv.p_type,pv.date_viewed,pd.name,p.price,p.image,
                                            concat('product.php?product_id=',p.product_id) 
                                            as href,SUM(pv.total_views) as viewed
                                            FROM product_views pv 
                                            INNER JOIN oc_product p ON p.product_id=pv.product_id
                                            INNER JOIN oc_product_description pd ON pd.product_id=p.product_id
                                            INNER JOIN seller_product_selected sps ON sps.product_id=p.product_id
                                            INNER JOIN product_to_brand ptb ON ptb.product_id = pv.product_id
                                            INNER JOIN oc_product_to_category PTC ON PTC.product_id=pv.product_id 
                                            INNER JOIN oc_seller os ON os.seller_id=sps.seller_id 
                                            WHERE  pv.p_type=0 AND sps.quantity!=0 and os.status=1 AND ptb.brand_id = :b_id AND PTC.category_id=:category_id 
                                            GROUP BY pv.product_id,sps.product_id,p.product_id,pv.p_type,pv.date_viewed,pd.name,p.price,p.image,PTC.category_id,ptb.brand_id) AS apv
                                          order by RAND()  limit :offset");
        $stmt->bindValue(':offset', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':b_id',$FSBrand_id);
        $stmt->bindValue(':category_id',$category_id);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
             $most_popular[] = array(
                'image' => $value['image'],
                'product_id' => $value['product_id'],
                'name' => $value['name'],
                'price' => $value['price'], 
                'href' => $value['href'], 
                'thumb' => $image->resize($value['image'], 200,200),
                'type' => $value['p_type']
            );
        } 
        return $most_popular;
    }
    public function FlagShipMostPularOnProduct($seller_id) {
        $limit = 10;
        $FSBrand_id = $this->GetFlasShipSelectedBrand($seller_id);
        global $image;
        $stmt = $this->conn->prepare("SELECT * FROM (
                                            SELECT ptb.brand_id,p.product_id,pv.p_type,pv.date_viewed,pd.name,p.price,p.image,
                                            concat('product.php?product_id=',p.product_id) 
                                            as href,SUM(pv.total_views) as viewed
                                            FROM product_views pv 
                                            INNER JOIN oc_product p ON p.product_id=pv.product_id
                                            INNER JOIN oc_product_description pd ON pd.product_id=p.product_id
                                            INNER JOIN seller_product_selected sps ON sps.product_id=p.product_id
                                            INNER JOIN product_to_brand ptb ON ptb.product_id = pv.product_id
                                            INNER JOIN oc_seller os ON os.seller_id=sps.seller_id 
                                            WHERE  pv.p_type=0 AND sps.quantity!=0 and os.status=1 AND ptb.brand_id = :b_id 
                                            GROUP BY pv.product_id,sps.product_id,p.product_id,pv.p_type,pv.date_viewed,pd.name,p.price,p.image,ptb.brand_id) AS apv
                                          order by RAND()  limit :offset");
        $stmt->bindValue(':offset', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':b_id',$FSBrand_id);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
             $most_popular[] = array(
                'image' => $value['image'],
                'product_id' => $value['product_id'],
                'name' => $value['name'],
                'price' => $value['price'], 
                'href' => $value['href'], 
                'thumb' => $image->resize($value['image'], 200,200),
                'type' => $value['p_type']
            );
        } 
        return $most_popular;
    }
    public function flagshipBestSellerOnCategory($seller_id){
        $limit = 10;
        $row=array();
        global $image;
        $b_id=$this->GetFlasShipSelectedBrand($seller_id);
        $stmt = $this->conn->prepare("SELECT p.image, p.product_id, p.price, pd.name,
                                        concat('product.php?product_id=',p.product_id) as href
                                    FROM seller_product_selected sps
                                    INNER JOIN oc_seller os ON os.seller_id=sps.seller_id 
                                    INNER JOIN oc_product p On sps.product_id=p.product_id
                                    INNER JOIN product_to_brand ptb
                                        ON sps.product_id = ptb.product_id 
                                    INNER JOIN oc_product_description pd ON p.product_id = pd.product_id          
                                    WHERE  sps.quantity!=0 and p.status = '1' and os.status=1  AND ptb.brand_id = :b_id
                                    GROUP by sps.product_id,p.image, p.product_id, p.price, pd.name
                                   order by RAND() limit :offset");
        $stmt->bindValue(':offset', (int) $limit, PDO::PARAM_INT);         
        $stmt->bindValue(':b_id',$b_id);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
                 $row[] = array(
                    'image' => $value['image'],
                    'product_id' => $value['product_id'],
                    'name' => $value['name'],
                    'price' => $value['price'], 
                    'type' => '0', 
                    'href' => $value['href'], 
                    'thumb' => $image->resize($value['image'], 200,200)
                );
            } 
        return $row; 
    }
    public function FlagShipRecommendedForYouWithCategory($custid,$seller_id,$category_id) {
        $recommended_product = array();
        $FSBrand_id = $this->GetFlasShipSelectedBrand($seller_id);
        $limit = 10;
       global $image;
        $countids=0;
        $stmt = $this->conn->prepare(" SELECT * FROM (
                                        SELECT p.product_id,pv.p_type,pv.date_viewed,pd.name,p.price,p.image,concat('product.php?product_id=',p.product_id) as href,SUM(pv.total_views) as viewed 
                                        FROM product_views pv 
                                        INNER JOIN oc_product p ON p.product_id=pv.product_id
                                        INNER JOIN oc_product_description pd ON pd.product_id=p.product_id
                                        INNER JOIN seller_product_selected sps ON sps.product_id=p.product_id
                                        INNER JOIN product_to_brand ptb ON ptb.product_id = pv.product_id
                                        INNER JOIN oc_product_to_category PTC ON PTC.product_id=pv.product_id 
                                        INNER JOIN oc_seller os ON os.seller_id=sps.seller_id 
                                        WHERE pv.customer_id=:customer_id AND pv.p_type=0 AND sps.quantity!=0 and os.status=1 AND ptb.brand_id = :b_id AND PTC.category_id=:category_id 
                                        GROUP BY pv.product_id,sps.product_id,p.product_id,pv.p_type,pv.date_viewed,pd.name,p.price,p.image ) AS apv
                                      order by RAND()  limit :offset");
        $stmt->bindValue(':customer_id',$custid);
        $stmt->bindValue(':offset', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':b_id',$FSBrand_id);
        $stmt->bindValue(':category_id',$category_id);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
             $recommended_product[] = array(
                'image' => $value['image'],
                'product_id' => $value['product_id'],
                'name' => $value['name'],
                'price' => $value['price'], 
                'href' => $value['href'], 
                'thumb' => $image->resize($value['image'], 200,200),
                'type' => $value['p_type']
            );
            $countids++;
        } 
        if($countids<$limit){
          $new_limit=$limit-$countids;
          $stmt = $this->conn->prepare(" SELECT * FROM (
                                        SELECT p.product_id,pv.p_type,pv.date_viewed,pd.name,p.price,p.image,concat('product.php?product_id=',p.product_id) as href,SUM(pv.total_views) as viewed 
                                        FROM product_views pv 
                                        INNER JOIN oc_product p ON p.product_id=pv.product_id
                                        INNER JOIN oc_product_description pd ON pd.product_id=p.product_id
                                        INNER JOIN seller_product_selected sps ON sps.product_id=p.product_id
                                        INNER JOIN product_to_brand ptb ON ptb.product_id = pv.product_id
                                        INNER JOIN oc_product_to_category PTC ON PTC.product_id=pv.product_id 
                                        INNER JOIN oc_seller os ON os.seller_id=sps.seller_id 
                                        WHERE pv.customer_id=:customer_id AND pv.p_type=0 AND sps.quantity!=0 and os.status=1 and os.status=1 AND ptb.brand_id = :b_id AND PTC.category_id=:category_id 
                                        GROUP BY pv.product_id,sps.product_id,p.product_id,pv.p_type,pv.date_viewed,pd.name,p.price,p.image  ) AS apv
                                      order by RAND()limit :offset");
          $stmt->bindValue(':customer_id',0);
          $stmt->bindValue(':offset', (int) $new_limit, PDO::PARAM_INT);
          $stmt->bindValue(':b_id',$FSBrand_id);
          $stmt->bindValue(':category_id',$category_id);
          $stmt->execute();
          foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
               $recommended_product[] = array(
                  'image' => $value['image'],
                  'product_id' => $value['product_id'],
                  'name' => $value['name'],
                  'price' => $value['price'], 
                  'href' => $value['href'], 
                  'thumb' => $image->resize($value['image'], 200,200),
                  'type' => $value['p_type']
              );
             
          } 

        }
        return $recommended_product;
    }
         public function FlagShipRecommendedOnProduct($custid,$seller_id) {
             $limit = 10;
             $FSBrand_id = $this->GetFlasShipSelectedBrand($seller_id);
           global $image;
            $countids=0;
            $stmt = $this->conn->prepare(" SELECT * FROM (
                                        SELECT p.product_id,pv.p_type,pv.date_viewed,pd.name,p.price,p.image,concat('product.php?product_id=',p.product_id) as href,SUM(pv.total_views) as viewed 
                                        FROM product_views pv 
                                        INNER JOIN oc_product p ON p.product_id=pv.product_id
                                        INNER JOIN oc_product_description pd ON pd.product_id=p.product_id
                                        INNER JOIN seller_product_selected sps ON sps.product_id=p.product_id
                                        INNER JOIN product_to_brand ptb ON ptb.product_id = pv.product_id
                                        INNER JOIN oc_seller os ON os.seller_id=sps.seller_id 
                                        WHERE pv.customer_id=:customer_id AND pv.p_type=0 AND sps.quantity!=0 and os.status=1 AND ptb.brand_id = :b_id 
                                        GROUP BY pv.product_id,sps.product_id,p.product_id,pv.p_type,pv.date_viewed,pd.name,p.price,p.image ) AS apv
                                      order by RAND()  limit :offset");
        $stmt->bindValue(':customer_id',$custid);
        $stmt->bindValue(':offset', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':b_id',$FSBrand_id);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
             $recommended_product[] = array(
                'image' => $value['image'],
                'product_id' => $value['product_id'],
                'name' => $value['name'],
                'price' => $value['price'], 
                'href' => $value['href'], 
                'thumb' => $image->resize($value['image'], 200,200),
                'type' => $value['p_type']
            );
            $countids++;
        } 
        if($countids<$limit){
          $new_limit=$limit-$countids;
          $stmt = $this->conn->prepare(" SELECT * FROM (
                                        SELECT p.product_id,pv.p_type,pv.date_viewed,pd.name,p.price,p.image,concat('product.php?product_id=',p.product_id) as href,SUM(pv.total_views) as viewed 
                                        FROM product_views pv 
                                        INNER JOIN oc_product p ON p.product_id=pv.product_id
                                        INNER JOIN oc_product_description pd ON pd.product_id=p.product_id
                                        INNER JOIN seller_product_selected sps ON sps.product_id=p.product_id
                                        INNER JOIN product_to_brand ptb ON ptb.product_id = pv.product_id
                                        INNER JOIN oc_seller os ON os.seller_id=sps.seller_id 
                                        WHERE pv.customer_id=:customer_id AND pv.p_type=0 AND sps.quantity!=0 and os.status=1 and os.status=1 AND ptb.brand_id = :b_id  
                                        GROUP BY pv.product_id,sps.product_id,p.product_id,pv.p_type,pv.date_viewed,pd.name,p.price,p.image  ) AS apv
                                      order by RAND()limit :offset");
          $stmt->bindValue(':customer_id',0);
          $stmt->bindValue(':offset', (int) $new_limit, PDO::PARAM_INT);
          $stmt->bindValue(':b_id',$FSBrand_id);
          $stmt->execute();
          foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
               $recommended_product[] = array(
                  'image' => $value['image'],
                  'product_id' => $value['product_id'],
                  'name' => $value['name'],
                  'price' => $value['price'], 
                  'href' => $value['href'], 
                  'thumb' => $image->resize($value['image'], 200,200),
                  'type' => $value['p_type']
              );
             
          } 

        }
        return $recommended_product;
    }
}
$store = new Store();