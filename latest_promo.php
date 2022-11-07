<?php
require_once "include/database.php";
require_once 'model/ImageResizer.php';
require_once 'model/Image.php';
class LatestPromo {
	private $conn;
    public function __construct(){
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    public function LPBanner($banner_id){
        $stmt = $this->conn->prepare("SELECT * FROM oc_banner_image bi 
                                    LEFT JOIN oc_banner_image_description bid 
                                        ON (bi.banner_image_id  = bid.banner_image_id) 
                                    WHERE bi.banner_id = :banner_id 
                                    ORDER BY bi.sort_order ASC");
        $stmt->bindValue(':banner_id',$banner_id);
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
    public function GetLp_Thumbnail_image($id) {
        $stmt = $this->conn->prepare("SELECT thumbnail_image FROM latest_promo where id=:id ");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['thumbnail_image'];
    }  
    public function getCount_selected_seller($promo_id) {
        $stmt = $this->conn->prepare("SELECT count(id) as countid FROM lp_seller_promo_list where latest_promo_id=:promo_id");
        $stmt->bindValue(':promo_id', $promo_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['countid'];
    } 
    public function get_lp_seller_products($promo_id) {
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
                                    ORDER BY RAND()");
        $stmt->bindValue(':promo_id', $promo_id);
        $stmt->bindValue(':status', 1);
        $stmt->bindValue(':quantity', 1);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
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
                'thumb' => $image->resize($value['image'], 200,200)
            );
        }
        return $row; 
    }
    public function GetHopgeSellerPromo($promo_id) {
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
                                    ORDER BY RAND()");
        $stmt->bindValue(':promo_id', $promo_id);
        $stmt->bindValue(':status', 1);
        $stmt->bindValue(':quantity', 1);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
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
                'thumb' => $image->resize($value['image'], 200,200)
            );
        }
        return $row; 
    }
    public function get_lp_products($promo_id) {
        global $image;
        $stmt = $this->conn->prepare("SELECT s.product_id,s.price,s.name,s.image,s.type,s.typedesc,s.href,s.date 
									FROM ( SELECT p.product_id,p.price,pd.name,p.image as image,'Local Product' as typedesc,'0' as type,concat('product.php?product_id=',p.product_id) as href,lpp.date
											FROM oc_product p 
											INNER JOIN oc_product_description pd ON (p.product_id = pd.product_id) 
								            INNER JOIN latest_promo_products lpp ON p.product_id=lpp.product_id
											WHERE lpp.promo_id=:promo_id AND lpp.type=0 AND p.status = 1 
											UNION ALL 
                                            SELECT p.product_id,p.price,pd.name,p.image as image,'Local Product' as typedesc,'0' as type,concat('product.php?product_id=',p.product_id) as href,p.date_modified 
                                            FROM oc_product p 
                                            INNER JOIN oc_product_description pd ON (p.product_id = pd.product_id) 
                                            INNER JOIN oc_product_to_category PTC ON PTC.product_id=p.product_id
                                            INNER JOIN latest_promo_category LPC ON PTC.category_id=LPC.category_id 
                                            WHERE LPC.lp_id=:promo_id AND p.status = 1 
                                            UNION ALL 
											SELECT bg.product_id,bg.price,bg.product_name as name,bg.img as image,'Banngood Product'as typedesc,'2' as type ,concat('bg_product.php?product_idbg=',bg.product_id) as href,lpp.date
											FROM bg_product bg INNER JOIN latest_promo_products lpp ON bg.product_id=lpp.product_id
											WHERE lpp.promo_id=:promo_id AND lpp.type=2 AND bg.status = 1
										) as s order by s.date desc");            
        $stmt->bindValue(':promo_id',$promo_id);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $value) {
            if($value['type']==0){
                 $row[] = array(
                    'image' => $value['image'],
                    'product_id' => $value['product_id'],
                    'name' => $value['name'],
                    'price' => $value['price'], 
                    'type' => $value['type'], 
                    'typedesc' => $value['typedesc'], 
                    'href' => $value['href'], 
                    'date' => $value['date'], 
                    'thumb' => $image->resize($value['image'], 200,200)
                );
            }else{
                 $row[] = array(
                    'image' => $value['image'],
                    'product_id' => $value['product_id'],
                    'name' => $value['name'],
                    'price' => $value['price'], 
                    'type' => $value['type'], 
                    'typedesc' => $value['typedesc'], 
                    'href' => $value['href'], 
                    'date' => $value['date'], 
                    'thumb' => $value['image']
                );
            }
            

        } 
        return $row; 
    }
}
?>