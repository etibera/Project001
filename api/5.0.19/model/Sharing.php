<?php
require_once '../init.php';
class Sharing {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    public function productSharing($product_id, $customer_id, $type){
        try{
            $s = $this->conn->prepare('SELECT count(id) as countid FROM oc_customer_link_shares 
        WHERE customer_id = :customer_id and product_id = :product_id');
        $s->bindValue(':product_id', (int) trim($product_id));
        $s->bindValue(':customer_id', (int) trim($customer_id));
        $s->execute();
        $count = $s->fetch()['countid'];
        if($count == 0){
            $s = $this->conn->prepare("INSERT INTO oc_customer_link_shares SET customer_id=:customer_id, product_id=:product_id, type='', total='1'");
        }else{
            $s = $this->conn->prepare("UPDATE oc_customer_link_shares SET total=total+1 WHERE customer_id = :customer_id and product_id = :product_id");
        }
        $s->bindValue(':customer_id', $customer_id);
        $s->bindValue(':product_id', $product_id);
        $s->execute();

        $s = $this->conn->prepare('select id from  oc_affiliate_program  where  customer_id=:customer_id');
        $s->bindValue(':customer_id', $customer_id);
        $s->execute();
        if($s->rowCount() > 0){
            $s = $this->conn->prepare("INSERT INTO  oc_affiliate_link_share  SET customer_id=:customer_id,product_id= :product_id ,type=:type,date = convert_tz(utc_timestamp(),'-08:00','+0:00')");
            $s->bindValue(':product_id', (int) trim($product_id));
            $s->bindValue(':customer_id', (int) trim($customer_id));
            $s->bindValue(':type', trim($type));
            $s->execute();
        }
        $s = $this->conn->prepare("select customer_id from  oc_customer_share_validation where customer_id= :customer_id ");
        $s->bindValue(':customer_id', $customer_id);
        $s->execute();
        $customer_count = $s->rowCount();
        if($customer_count > 0){
            $s = $this->conn->prepare("INSERT INTO oc_customer_share_validation set share_perday=1,total_share=0,date_share=:date_now,customer_id=:customer_id ");
        }else{
            $s = $this->conn->prepare("UPDATE oc_customer_share_validation set date_share=:date_now where customer_id=:customer_id");
        }
        $s->bindValue(':customer_id', (int) trim($customer_id));
        $s->bindValue(':date_now',  (new DateTime("now", new DateTimeZone('Asia/Manila') ))->format('Y-m-d'));
        $s->execute();

        $s = $this->conn->prepare("select id from oc_customer_shared_products where customer_id=:customer_id and product_id=:product_id and status='0'");
        $s->bindValue(':customer_id', $customer_id);
        $s->bindValue(':product_id', $product_id);
        $s->execute();
        if($s->rowCount() == 0){
            $s = $this->conn->prepare("INSERT INTO oc_customer_shared_products set customer_id=:customer_id, product_id=:product_id,status='0', date = convert_tz(utc_timestamp(),'-08:00','+0:00'), ip=:ip, visit_count='0', vistip=''");
            $s->bindValue(':customer_id', $customer_id);
            $s->bindValue(':product_id', $product_id);
            $s->bindValue(':ip', $_SERVER['REMOTE_ADDR']);
            $s->execute();
        }
        return 'Thank you for sharing our product to';
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function insertSharing($product_id, $seller_id, $customer_id){
        $data['set'] = false;
        if($product_id != 0){
            
            if($seller_id == null || $seller_id == 0){
                $s = $this->conn->prepare("select date_share from  oc_customer_share_validation where customer_id=:customer_id ORDER BY id desc LIMIT 1");
                $s->bindValue(':customer_id', $customer_id);
                $s->execute();
                $customer_get_date = $s->rowCount() > 0 ? $s->fetch()['date_share'] : 0;
                if($customer_get_date != 0){
                    $date_now = (new DateTime("now", new DateTimeZone('Asia/Manila') ))->format('Y-m-d');
                    if($customer_get_date != $date_now){
                        $s = $this->conn->prepare("UPDATE oc_customer_share_validation set share_perday='0',date_share=:date_share where customer_id=:customer_id");
                        $s->bindValue(':customer_id', $customer_id);
                        $s->bindValue(':date_share', $date_now);
                        $s->execute();
                    }
                }
            }else{
                $data['set'] = true;
                $s = $this->conn->prepare("select date from  oc_customer_shared_products  where  customer_id=:customer_id and product_id=:product_id and status='0' LIMIT 1");
                $s->bindValue(':customer_id', $seller_id);
                $s->bindValue(':product_id', $product_id);
                $s->execute();
                $get_product_shared_date = $s->rowCount() > 0 ? $s->fetch()['date'] : 0;
                if($get_product_shared_date != 0){
                    date_default_timezone_set("Asia/Manila");
                    $mins = (new DateTime())->diff(new DateTime($get_product_shared_date))->format('%i');
                    if($mins > 3){
                        $s = $this->conn->prepare("select id from  oc_customer_shared_products  where  customer_id=:customer_id and product_id=:product_id and status='0' and ip!=:ip and visit_count='0'");
                        $s->bindValue(':customer_id', $seller_id);
                        $s->bindValue(':product_id', $product_id);
                        $s->bindValue(':ip', $_SERVER['REMOTE_ADDR']);
                        $s->execute();
                        $check_ip = $s->rowCount() > 0 ? $s->fetch()['id'] : 0;
                        $data['set'] = true;
                        if($check_ip != 0){
                            $s = $this->conn->prepare("UPDATE oc_customer_shared_products set status='1',visit_count=visit_count+1,vistip=:ip WHERE customer_id=:customer_id AND product_id=:product_id AND `status`='0'");
                            $s->bindValue(':customer_id', $seller_id);
                            $s->bindValue(':product_id', $product_id);
                            $s->bindValue(':ip', $_SERVER['REMOTE_ADDR']);
                            $s->execute();

                            $s = $this->conn->prepare("UPDATE oc_customer_share_validation set share_perday=share_perday+1,total_share=total_share+1 where customer_id=:customer_id ");
                            $s->bindValue(':customer_id', $seller_id);
                            $s->execute();


                            $s = $this->conn->prepare("select share_perday from  oc_customer_share_validation where customer_id=:customer_id");
                            $s->bindValue(':customer_id', $seller_id);
                            $s->execute();
                            $share_limit = $s->rowCount() > 0 ? $s->fetch()['share_perday'] : 0;

                            if($share_limit < 6){
                                $s = $this->conn->prepare("select total_share from oc_customer_share_validation where customer_id=:customer_id ");
                                $s->bindValue(':customer_id', $seller_id);
                                $s->execute();
                                $total_share_limit = $s->rowCount() > 0 ? $s->fetch()['total_share'] : 0;

                                $s = $this->conn->prepare("select * from oc_product_description where product_id = :product_id");
                                $s->bindValue(':product_id', $product_id);
                                $s->execute();
                                $product_name = $s->rowCount() > 0 ? $s->fetch()['name'] : "";

                                if($total_share_limit < 11){
                                    $this->insert_digital_wallet($customer_id, $product_name, 2);
                                }else if($total_share_limit < 51 && $total_share_limit > 10){
                                    $this->insert_digital_wallet($customer_id, $product_name, 5);
                                }else{
                                    $this->insert_digital_wallet($customer_id, $product_name, 10);
                                }

                            }



                        }

                    }

                }


            }
        }
        return $data;
    }
    public function insert_digital_wallet($customer_id, $product_name, $amount){
        $s = $this->conn->prepare("INSERT INTO oc_customer_wallet set customer_id = :customer_id, particulars =:particulars, amount = :amount, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00')");
        $s->bindValue(':customer_id', $customer_id);
        $s->bindValue(':particulars', "Incentive for sharing product ($product_name)");
        $s->bindValue(':amount', $amount);
        $s->execute();
    }
}
$sharing = new Sharing();