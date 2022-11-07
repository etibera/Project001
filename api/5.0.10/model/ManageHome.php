<?php
class ManageHome {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    public function list($customer_id){
        $stmt = $this->conn->prepare("SELECT ochc.*,cd.name,cd.category_id as cid,ochc.id as ochcid,
        IFNULL(ochc.show_limit,0) as show_limit,
        IFNULL(ochc.sort_order,'p.price ASC') as sort_order
        FROM oc_customer_home_category ochc  
        LEFT JOIN oc_category_description cd 
            ON cd.category_id= ochc.category_id 
        WHERE ochc.customer_id=:customer_id order by ochc.id asc");
        $stmt->bindValue(':customer_id', (int) $customer_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function delete($id){
        $stmt = $this->conn->prepare("DELETE FROM oc_customer_home_category WHERE id=:id");
        $stmt->bindValue(':id', (int) $id);
        $stmt->execute();
    }
    public function category(){
        $data = array();
        $stmt = $this->conn->prepare("SELECT * FROM oc_category c LEFT JOIN oc_category_description cd ON (c.category_id = cd.category_id) 
        LEFT JOIN oc_category_to_store c2s ON (c.category_id = c2s.category_id) 
        WHERE c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");
        $stmt->bindValue(':status', 1);
        $stmt->execute();
        foreach($row = $stmt->fetchAll(PDO::FETCH_ASSOC) as $cat){
            $data[] = array(
                'categoryId' => $cat['category_id'],
                'name' => $cat['name']
            );
        }
        return $data;
    }
    public function add($category_id, $customer_id){
        global $category;
        $data = array();
        try{
            if($category->check_home_category($category_id, $customer_id) == 0){
                $stmt = $this->conn->prepare("INSERT INTO oc_customer_home_category 
                SET customer_id=:customer_id , category_id= :category_id");
                $stmt->bindValue(':customer_id', $customer_id);
                $stmt->bindValue(':category_id', $category_id);
                if($stmt->execute()){
                    $data['success'] = "ok";
                }
            }
            return $data;
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}
$manageHome = new ManageHome();