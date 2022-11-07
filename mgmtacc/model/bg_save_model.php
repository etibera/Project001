<?php
	require_once "../include/database.php";
	class BG_Model_db{
        private $conn;   
        public function __construct(){
            $this->conn = new Database();
            $this->conn = $this->conn->getmyDB();
        }
        public function save_category_bg($product_category) {
	         try{
	         	foreach ($product_category as $product_category_list) {
				  echo $product_category_list['cat_name'] .'</br>';
					$stmt = $this->conn->prepare("INSERT bg_product_category SET  cat_id=:cat_id,cat_name=:cat_name,parent_id=:parent_id,status=:status");
		            $stmt->bindValue(':cat_id',$id);
		            $stmt->bindValue(':cat_id',$product_category_list['cat_id']);
		            $stmt->bindValue(':cat_name',$product_category_list['cat_name']);
		            $stmt->bindValue(':parent_id',$product_category_list['parent_id']);
		            $stmt->bindValue(':status',
		            	1);
		            $stmt->execute();
		            
				}
				$status="200";
	        }catch(PDOexception $e){
	            $status =$e;
	        }
	        return $status;
	    }
	    public  function get_bg_category_count($parent_id){   
	        $stmt = $this->conn->prepare("SELECT count(id) as count FROM bg_product_category  where parent_id=:parent_id order by cat_name asc");
	         $stmt->bindValue(':parent_id',$parent_id);
	        $stmt->execute();
	        $row = $stmt->fetch(PDO::FETCH_ASSOC);
	        return $row['count'];
	    }
	    public  function get_bg_category($parent_id){   
	        $stmt = $this->conn->prepare("SELECT * FROM bg_product_category  where parent_id=:parent_id order by cat_name asc");
	         $stmt->bindValue(':parent_id',$parent_id);
	        $stmt->execute();
	        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
	        return $row;
	    }
	    public  function getbg_cat_image($id){   
	        $stmt = $this->conn->prepare("SELECT * FROM bg_product_category  where id=:id ");
	         $stmt->bindValue(':id',$id);
	        $stmt->execute();
	        $row = $stmt->fetch(PDO::FETCH_ASSOC);
	        return $row;
	    }
	   	public function UpdateImage($data) {
	        try {
	               $insert =$this->conn->prepare("UPDATE bg_product_category SET image=:image WHERE id=:edit_id");
	                $insert->bindValue(':image',$data[0]['image']);
	                $insert->bindValue(':edit_id',$data[0]['edit_id']);
	                $insert->execute();
	                $status = "200";

	        }catch(Exception $e){
	              $status=$e;
	        } 

	         return $status;
	    } 
	    public function bg_cat_disable($id) {
	        try {
	            $stmt =$this->conn->prepare("UPDATE  bg_product_category set status=:status WHERE id=:id ");
	            $stmt->bindValue(':id', $id);
	            $stmt->bindValue(':status', 0);
	            $stmt->execute();
	            return "200";
	        }catch(Exception $e){
	             return $e;
	        } 
	    }
	    public function bg_cat_enable($id) {
	        try {
	            $stmt =$this->conn->prepare("UPDATE  bg_product_category set status=:status WHERE id=:id ");
	            $stmt->bindValue(':id', $id);
	            $stmt->bindValue(':status', 1);
	            $stmt->execute();
	            return "200";
	        }catch(Exception $e){
	             return $e;
	        } 
	    } 

	    public function bg_prod_disable($id) {
	        try {
	            $stmt =$this->conn->prepare("UPDATE  bg_product set status=:status WHERE id=:id ");
	            $stmt->bindValue(':id', $id);
	            $stmt->bindValue(':status', 0);
	            $stmt->execute();
	            return "200";
	        }catch(Exception $e){
	             return $e;
	        } 
	    }
	    public function bg_prod_enable($id) {
	        try {
	            $stmt =$this->conn->prepare("UPDATE  bg_product set status=:status WHERE id=:id ");
	            $stmt->bindValue(':id', $id);
	            $stmt->bindValue(':status', 1);
	            $stmt->execute();
	            return "200";
	        }catch(Exception $e){
	             return $e;
	        } 
	    } 
	    public function save_bgproducts($product_id,$cat_id,$product_name,$img,$meta_desc,$add_date,$modify_date,$warehouse_gps,$price_pp) {
	        try {
	        	
	            $stmt =$this->conn->prepare("INSERT  bg_product set product_id=:product_id,cat_id=:cat_id,product_name=:product_name,img=:img,meta_desc=:meta_desc,add_date=:add_date,modify_date=:modify_date,warehouse=:warehouse_gps,price=:price_pp,status=:status");
	            $stmt->bindValue(':product_id', $product_id);
	            $stmt->bindValue(':cat_id', $cat_id);
	            $stmt->bindValue(':product_name', $product_name);
	            $stmt->bindValue(':img', $img);
	            $stmt->bindValue(':meta_desc', $meta_desc);
	            $stmt->bindValue(':add_date', $add_date);
	            $stmt->bindValue(':modify_date', $modify_date);
	            $stmt->bindValue(':warehouse_gps', $warehouse_gps);
	            $stmt->bindValue(':price_pp', $price_pp);
	            $stmt->bindValue(':status', 1);
	            $stmt->execute();
	            return "200";
	        }catch(Exception $e){
	             return $e;
	        } 
	    }

	    public function save_bgproducts_check($product_id) {
	        try {
	        	$count =$this->conn->prepare("SELECT count(id) as count FROM bg_product WHERE  product_id =:product_id");
	            $count->bindValue(':product_id', $product_id);
	            $count->execute();
	            $row = $count->fetch(PDO::FETCH_ASSOC);
	           
	            return $row['count'];
	        }catch(Exception $e){
	             return $e;
	        } 
	    }

	    public  function get_bg_product(){   
	        $stmt = $this->conn->prepare("SELECT * FROM bg_product order by id desc");
	        $stmt->execute();
	        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
	        return $row;
	    }
	}
?>