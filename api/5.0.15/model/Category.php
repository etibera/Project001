<?php
require_once '../init.php';
class Category {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    public function categoryName($category_id){
        $st = $this->conn->prepare("SELECT name FROM oc_category_description WHERE category_id = :category_id");
        $st->bindValue(':category_id', $category_id);
        $st->execute();
        return $st->fetch()['name'];
    }
    public function get_category_by_id($category_id){
        global $image;
        $data = array();
        $stmt = $this->conn->prepare("SELECT * FROM oc_category c 
        LEFT JOIN oc_category_description cd ON (c.category_id = cd.category_id)
        WHERE c.status = :status AND c.top = 1 AND c.category_id = :category_id ORDER BY c.sort_order, LCASE(cd.name)");
        $stmt->bindValue(':status', 1);
        $stmt->bindValue(':category_id', $category_id);
        $stmt->execute();
        $res= $stmt->fetch();
        return array(
            'name' => $res['name'],
            'image' => $image->resize($res['image'], 75,75),
            'categoryId' => $res['category_id']
        );
        // foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $cat){
        //     $data[] = array(
        //         'categoryId' => $cat['category_id'],
        //         'name' => $cat['name'],
        //         'image' => $image->resize($cat['image'], 75,75)
        //     );
        // }
    }
    public function check_home_category($category_id, $customer_id){
        $st = $this->conn->prepare("SELECT count(id) as countid FROM oc_customer_home_category 
        WHERE customer_id=:customer_id AND category_id=:category_id");
        $st->bindValue(':category_id', $category_id);
        $st->bindValue(':customer_id', $customer_id);
        $st->execute();
        return intval($st->fetch()['countid']);
    }
    public function globalCategory(){
        global $image;
        global $home;
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
                    'image' => $img
                );
            }
            
        return $data;
    }
    public function getBrandCategory($bid){
       global $image;  
       $row=array();
       $stmt = $this->conn->prepare("SELECT c.category_id,c.image,cd.name FROM oc_category c 
                                    INNER JOIN oc_category_description cd ON c.category_id = cd.category_id
                                    INNER JOIN oc_product_to_category PTC ON PTC.category_id=c.category_id
                                    INNER JOIN product_to_brand PTB ON PTC.product_id=PTB.product_id
                                    WHERE c.parent_id = 0 AND c.status = '1' AND c.top = 1 AND  PTB.brand_id=:bid
                                    GROUP BY PTC.category_id");
        $stmt->bindValue(':bid',$bid);
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
}
$category = new Category();