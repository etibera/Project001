<?php
require_once '../init.php';
class Affiliate {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    public function mobile_image_path(){
        global $image;
        return $image->https_image . 'img/';
    }
    public function partner_program($customer_id){
        global $image;
        $data = array();
        $id = $this->check_affiliate($customer_id);
        
        if($id == 0){
            $data = array(
                array(
                'image' => $this->mobile_image_path() . 'mobile/peso.jpg',
                'title' => 'PESO Partner Program',
                'subTitle' => '<small> Welcome to the Partner Program of Pinoy Electronic Store Online (PESO)</small>'
                ),
                array(
                'image' => $this->mobile_image_path(). 'mobile/invite_friend.jpg',
                'title' => 'Sharing our products to your friends',
                'subTitle' => '<small>PESO Partner Program member must be able to send a product referral link to his friends from the PESO site/app to facebook messenger, viber from a smart phone.</small>'
                ),
                array(
                'image' => $this->mobile_image_path() . 'mobile/earn.jpg',
                'title' => 'Earn Cash Wallet Credit',
                'subTitle' => ' <small>PESO Partner Program rewards are based on a percentage of Gross Profit. Rewards are credited to a separate PESO cash wallet and can be converted to cash.</small>'
                ),
                
                array(
                'image' => $this->mobile_image_path() . 'mobile/tos.jpg',
                'title' => 'Terms of Service',
    
                'subTitle' => '<div class="box-scroll">
                    <ul>
                    <li>Please read the following Terms of Service carefully and be aware of your rights and obligations with respect to Partner Program of Pinoy Electronic Store Online. These Terms and Conditions govern your use of the Partner Program provided by Pinoy Electronic Store Online.</li>
                    <li>The Partner Program allows a Pinoy Electronic Store Online (PESO) user to sign-up and earn rewards by referring PESO products to friends. Referral must use referral facility within the Pinoy Electronic Store Online (PESO) site/app for proper tracking. A PESO Partner Program member must be able to send a product referral link to his friends from the PESO site/app to facebook messenger, viber from a smart phone. In order to earn, referral must result into a successful sale via PESO site/app. PESO Partner Program participants are not allowed to recommend product to him/her self. Any referral made outside the site/app cannot be tracked or verified and therefore cannot be rewarded.</li>
                    <li>
                    PESO Partner Program rewards are based on a percentage of Gross Profit (SRP (actual selling price) less (shipping + product cost + other cost)) and Partner Program level. Rewards are credited to a separate PESO cash wallet and can be converted to cash. Amount in the cash wallet can also be transferred to your PESO discount wallet should you intend to use the amount as discount on your PESO purchase subject to PESO discount regulations. 
                    </li>
                    <li>
                    There are three (3) levels in the PESO Partner Program with increasing rewards percentages for each higher level. You will be able to see your level by going to Accounts/PESO Partner Program.
                        Novice (1-3 successful sales in 30 days) = reward
                        Regular (4-10 successful sales in 30 days) 1.5 x reward
                        Pro (10 or more successful sales in 30 days) 2 x reward
                    </li>
                    <li>
                    PESO Partners need to maintain activity to retain their level. Inactivity (no successful sales in 30 days) will result in level downgrade to Novice. The inactivity period will be computed from the last successful sale resulting from your referral. 
                    </li>
                    <li>
                    By signing up to become a PESO Partner, you agree not to commit fraud, false and/or misleading claims,  misrepresentation.
                    </li>
                    <li>
                    Pinoy Electronic Store Online (PESO) reserves the right to change, modify, suspend or discontinue all or any part of the Partner Program at any time or upon notice as required by local law. Pinoy Electronic Store Online  may also impose limits on certain features or restrict your access to parts of, or the entire, Site or Services in its sole discretion and without notice or liability.
                    </li>
                    <li>
                    Pinoy Electronic Store Online reserves the right to refuse to provide you access to the Partner Program for any reason.
                    </li>
                    </ul>
                    </div>'),
                array(
                'image' => $this->mobile_image_path() . 'mobile/success.jpg',
                'title' => 'Congratulations!',
                'subTitle' => '<small>You are now registered to PESO Affiliate Program</small>',
                'button' => 'Continue Shopping'
                )
            );
            }else{
                $data = array(
                array(
                'image' => $this->mobile_image_path() . 'mobile/success.jpg',
                'title' => 'Already Registered',
                'subTitle' => '<small>You already registered to PESO Affiliate Program</small>',
                'button' => 'Close'
                )
            );
            }
            return $data;

    }
    public function check_affiliate($customer_id){
        $stmt = $this->conn->prepare("select id from  oc_affiliate_program  where  customer_id=:customer_id");
        $stmt->bindValue(':customer_id', $customer_id);
        $stmt->execute();
        $row = $stmt->fetch();
        if($stmt->rowCount() > 0){
            return intval($row['id']);
        }else{
            return 0;
        }
    }
    public function register_affiliate($customer_id){
        $stmt = $this->conn->prepare("INSERT INTO  oc_affiliate_program set  customer_id=:customer_id, date = convert_tz(utc_timestamp(),'-08:00','+0:00') ");
        $stmt->bindValue(':customer_id', $customer_id);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }
    public function add_shipping_wallet($customer_id){
        $start_date = strtotime('2021-06-19');
        $end_date = strtotime('2021-10-31');
        $current_date = strtotime(date('Y-m-d'));
        if(($current_date >= $start_date) && ($current_date <= $end_date)){
            $stmt = $this->conn->prepare('SELECT nexmo_status FROM oc_customer WHERE customer_id = :customer_id');
            $stmt->bindValue(':customer_id', $customer_id);
            $stmt->execute();
            $status = $stmt->fetch()['nexmo_status'];$stmt = $this->conn->prepare('SELECT * FROM shipping_wallet WHERE customer_id = :customer_id LIMIT 1');
            $stmt->bindValue(':customer_id', $customer_id);
            $stmt->execute();
            $isExists = $stmt->rowCount() > 0 ? true : false;
            
            if($status == 1 && !$isExists){
                $stmt2 = $this->conn->prepare("INSERT INTO shipping_wallet set  customer_id=:customer_id, particulars=:particulars, amount=:amount, date_added = convert_tz(utc_timestamp(),'-08:00','+0:00'), `status`= 0");
                $stmt2->bindValue(':customer_id', $customer_id);
                $stmt2->bindValue(':particulars', 'Promo for Registration');
                $stmt2->bindValue(':amount', 1000);
                $stmt2->execute();
            }
        }
    }
}
$affiliate = new Affiliate();