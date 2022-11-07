<?php
require_once '../init.php';
class Address {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    public function setAddress($customer_id, $address_id){
        $st2 = $this->conn->prepare('UPDATE oc_customer SET address_id = :address_id WHERE customer_id = :customer_id');
        $st2->bindValue(':customer_id', $customer_id);
        $st2->bindValue(':address_id', $address_id);
        $st2->execute();
    }
    public function getSelectedAddressId($customer_id){
        $st = $this->conn->prepare('SELECT address_id FROM oc_customer WHERE customer_id = :customer_id LIMIT 1');
        $st->bindValue(':customer_id', $customer_id);
        $st->execute();
        $address_id = $st->fetch()['address_id'];
        if($address_id == 0){
            $st1 = $this->conn->prepare('SELECT * FROM oc_address WHERE customer_id = :customer_id ORDER BY address_id DESC LIMIT 1');
            $st1->bindValue(':customer_id', $customer_id);
            $st1->execute();
            
            if($st1->rowCount() > 0){
                $address_id = $st1->fetch()['address_id'];
                $st2 = $this->conn->prepare('UPDATE oc_customer SET address_id = :address_id WHERE customer_id = :customer_id');
                $st2->bindValue(':customer_id', $customer_id);
                $st2->bindValue(':address_id', $address_id);
                $st2->execute();
                return $address_id;
            }
            
        }else{
            return $address_id;
        }
    }
    public function getAddresses($customer_id){
        $data = array();
        $st = $this->conn->prepare("SELECT * FROM oc_address WHERE customer_id = :customer_id");
        $st->bindValue(":customer_id", $customer_id);
        $st->execute();
        foreach($st->fetchAll() as $address){
          $data[] = array(
                'addressId' => $address['address_id'],
                'company' => $address['company'],
                'address1' => $address['address_1'],
                'address2' => $address['address_2'],
                'city' => $address['city'],
                'postcode' => $address['postcode'],
                'countryId' => $address['country_id'],
                'district' => $address['district'],
                'trackingId' => $address['tracking_id'] == null ? 0 : $address['tracking_id'],
                'region' => $address['region'],
                'selected' => $this->isSelected($customer_id, $address['address_id'])
            );
        }
        return $data;

    }
    public function getAddress($address_id, $customer_id){
        $st = $this->conn->prepare("SELECT DISTINCT * FROM oc_address WHERE customer_id = :customer_id AND address_id = :address_id");
        $st->bindValue(":customer_id", $customer_id);
        $st->bindValue(":address_id", $address_id);
        $st->execute();
        $address = $st->fetch();
            $country_query = $this->conn->prepare('SELECT * FROM oc_country WHERE country_id = :country_id');
            $country_query->bindValue(':country_id', $address['country_id']);
            $country_query->execute();
            $country = $country_query->fetch();
            if($country_query->rowCount() > 0){
                $country_name = $country['name'];
                $iso_code_2 = $country['iso_code_2'];
                $iso_code_3 = $country['iso_code_3'];
                $address_format = $country['address_format'];
            }else{
                $country_name = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
            }

            $zone_query = $this->conn->prepare('SELECT * FROM oc_zone WHERE zone_id = :zone_id');
            $zone_query->bindValue(':zone_id', $address['zone_id']);
            $zone_query->execute();
            $zone = $zone_query->fetch();
            if($zone_query->rowCount() > 0){
               $zone_name = $zone['name'];
               $zone_code = $zone['code'];
            }else{
                $zone_name = '';
                $zone_code = '';
            }
            $address_data = array(
				'address_id'     => $address['address_id'],
				'firstname'      => $address['firstname'],
				'lastname'       => $address['lastname'],
				'company'        => $address['company'],
				'address_1'      => $address['address_1'],
				'address_2'      => $address['address_2'],
				'postcode'       => $address['postcode'],
				'city'           => $address['city'],
				'zone_id'        => $address['zone_id'],
				'zone'           => $zone_name,
				'zone_code'      => $zone_code,
				'country_id'     => $address['country_id'],
				'country'        => $country_name,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
                'district'       => $address['district'],
                'region'         => $address['region']
			);
        return $address_data;


    }
    public function getCountries(){
        $data = array();
        $s = $this->conn->prepare('SELECT * FROM oc_country WHERE status = 1 ORDER BY name ASC');
        $s->execute();
        foreach($s->fetchAll() as $country){
            $data[] = array(
                'countryId'=> $country['country_id'],
                'name' => $country['name']
            );
        }
        return $data;
    }
    public function getRegion($country_id){
        $data = array();
        $s = $this->conn->prepare('SELECT * FROM oc_zone WHERE country_id = :country_id AND status = 1 ORDER BY name');
        $s->bindValue(':country_id', (int) trim($country_id), PDO::PARAM_INT);
        $s->execute();
        foreach($s->fetchAll() as $zone){
            $data[] = array(
                'zone_id' => $zone['zone_id'],
                'country_id' => $zone['country_id'],
                'name' => $zone['name']
            );
        }
        return $data;
    }
    public function add($data){
        $s = $this->conn->prepare('SELECT * FROM oc_customer WHERE customer_id = :customer_id');
        $s->bindValue(':customer_id', (int) trim($data['customerId']), PDO::PARAM_INT);
        $s->execute();
        $customer = $s->fetch(PDO::FETCH_ASSOC);
        if($s->rowCount() > 0) {
            try{
                $s = $this->conn->prepare("INSERT INTO oc_address SET 
                customer_id = :customer_id, firstname = :firstname, 
                lastname = :lastname, company = :company, 
                address_1 = :address_1, address_2 = :address_2, 
                postcode = :postcode, city = :city, zone_id = :zone_id, 
                country_id = :country_id, custom_field = '', tracking_id = :trackingId, district = :district, region = :region");
                $s->bindValue(':customer_id', (int) trim($data['customerId']), PDO::PARAM_INT);
                $s->bindValue(':firstname',  trim($customer['firstname'], PDO::PARAM_STR));
                $s->bindValue(':lastname', trim($customer['lastname'], PDO::PARAM_STR));
                $s->bindValue(':company', trim($data['company'], PDO::PARAM_STR));
                $s->bindValue(':address_1', trim($data['address1'], PDO::PARAM_STR));
                $s->bindValue(':address_2', trim($data['address2'], PDO::PARAM_STR));
                $s->bindValue(':postcode', trim($data['postcode']));
                $s->bindValue(':city', trim($data['city'], PDO::PARAM_STR));
                $s->bindValue(':region', trim($data['region'], PDO::PARAM_STR));
                $s->bindValue(':district', trim($data['district'], PDO::PARAM_STR));
                $s->bindValue(':trackingId', (int) $data['trackingId'], PDO::PARAM_INT);
                $s->bindValue(':zone_id', 0);
                $s->bindValue(':country_id', '168');
                $s->execute();
                $address_id = $this->conn->lastInsertId();
                //$selected = $this->checkLengthOfAddress($data['customerId']);
                if($data['selected'] == true){
                    $this->setAddress($data['customerId'], $address_id);
                }
                $data['addressId'] = $address_id;
                $data['selected'] = $data['selected'] === 'true' ? true : false;
                return $data;
            }catch(Exception $e){
                echo $e->getMessage();
            }
        }
        
    }
    public function update($data){
        $s = $this->conn->prepare('SELECT * FROM oc_customer WHERE customer_id = :customer_id');
        $s->bindValue(':customer_id', (int) trim($data['customerId']), PDO::PARAM_INT);
        $s->execute();
        $customer = $s->fetch(PDO::FETCH_ASSOC);
        if($s->rowCount() > 0) {
            try{
                $s = $this->conn->prepare("UPDATE oc_address SET 
                customer_id = :customer_id, firstname = :firstname, 
                lastname = :lastname, company = :company, 
                address_1 = :address_1, address_2 = :address_2, 
                postcode = :postcode, city = :city, zone_id = :zone_id, 
                country_id = :country_id, custom_field = '', tracking_id = :trackingId, district = :district, region = :region WHERE address_id = :address_id");
                $s->bindValue(':address_id', (int) trim($data['addressId']), PDO::PARAM_INT);
                $s->bindValue(':customer_id', (int) trim($data['customerId']), PDO::PARAM_INT);
                $s->bindValue(':firstname',  trim($customer['firstname'], PDO::PARAM_STR));
                $s->bindValue(':lastname', trim($customer['lastname'], PDO::PARAM_STR));
                $s->bindValue(':company', trim($data['company'], PDO::PARAM_STR));
                $s->bindValue(':address_1', trim($data['address1'], PDO::PARAM_STR));
                $s->bindValue(':address_2', trim($data['address2'], PDO::PARAM_STR));
                $s->bindValue(':postcode', trim($data['postcode']));
                $s->bindValue(':city', trim($data['city'], PDO::PARAM_STR));
                $s->bindValue(':region', trim($data['region'], PDO::PARAM_STR));
                $s->bindValue(':district', trim($data['district'], PDO::PARAM_STR));
                $s->bindValue(':trackingId', (int) $data['trackingId'], PDO::PARAM_INT);
                $s->bindValue(':zone_id', 0);
                $s->bindValue(':country_id', '168');
                $s->execute();
                if($data['selected'] == true){
                    $this->setAddress($data['customerId'], $data['addressId']);
                }
                
                return $data;
            }catch(Exception $e){
                echo $e->getMessage();
            }
        }
    }
    public function delete($address_id, $customer_id){
       try{
        $s = $this->conn->prepare("DELETE FROM oc_address WHERE address_id = :address_id AND customer_id = :customer_id");
        $s->bindValue(':address_id', (int) $address_id, PDO::PARAM_INT);
        $s->bindValue(':customer_id', (int) $customer_id, PDO::PARAM_INT);
        $s->execute();
        $s = $this->conn->prepare("SELECT address_id from oc_address WHERE customer_id = :customer_id  ORDER BY address_id DESC LIMIT 1");
        $s->bindValue(':customer_id', (int) $customer_id, PDO::PARAM_INT);
        $s->execute();
        $lastAddressId = $s->rowCount() > 0 ? $s->fetch()['address_id'] : 0;
        $s = $this->conn->prepare("UPDATE oc_customer SET address_id = :address_id WHERE customer_id = :customer_id");
        $s->bindValue(':address_id', (int) $lastAddressId, PDO::PARAM_INT);
        $s->bindValue(':customer_id', (int) $customer_id, PDO::PARAM_INT);
        $s->execute();
        return $lastAddressId;
       }catch(Exception $e){
            echo $e->getMessage();
       }
    }
    public function findAddress($customer_id, $address_id){
        $st = $this->conn->prepare("SELECT * FROM oc_address WHERE customer_id = :customer_id AND address_id = :address_id LIMIT 1");
        $st->bindValue(":customer_id", $customer_id);
        $st->bindValue(":address_id", $address_id);
        $st->execute();
        $data = $st->fetch(PDO::FETCH_ASSOC);
        return array(
            'addressId' => $data['address_id'],
            'company' => $data['company'],
            'address1' => $data['address_1'],
            'address2' => $data['address_2'],
            'city' => $data['city'],
            'postcode' => $data['postcode'],
            'countryId' => $data['country_id'],
            'district' => $data['district'],
            'trackingId' => $data['tracking_id'] == null ? 0 : $data['tracking_id'],
            'region' => $data['region']
        );
    }
    public function findRegion(){
        $data = array();
        $st = $this->conn->prepare("SELECT province FROM address_tracker GROUP BY province");
        $st->execute();
        foreach($st->fetchAll(PDO::FETCH_ASSOC) as $val){
            $data[] = $val['province'];
        }
        return $data;
    }
    public function findCity($province){
        $data = array();
        $st = $this->conn->prepare("SELECT city FROM address_tracker WHERE province = :province GROUP BY city");
        $st->bindValue(':province', $province);
        $st->execute();
        foreach($st->fetchAll(PDO::FETCH_ASSOC) as $val){
            $data[] = $val['city'];
        }
        return $data;
    }
    public function findDistrict($city){
        $data = array();
        $st = $this->conn->prepare("SELECT district, tracking_id, postal_code FROM address_tracker WHERE city = :city");
        $st->bindValue(':city', $city);
        $st->execute();
        foreach($st->fetchAll(PDO::FETCH_ASSOC) as $val){
            $data[] = array('district' => $val['district'], 'trackingId' => intval($val['tracking_id']), 'postcode'=> $val['postal_code']);

        }
        return $data;
    }
    public function checkLengthOfAddress($customer_id){
        $st = $this->conn->prepare("SELECT COUNT(*) as count FROM oc_address where customer_id = :customer_id");
        $st->bindValue(':customer_id', $customer_id);
        $st->execute();
        return intval($st->fetch()['count']) == 1 ? true : false;
    }
    public function isSelected($customer_id, $address_id){
        $st = $this->conn->prepare("SELECT address_id FROM oc_customer where customer_id = :customer_id");
        $st->bindValue(':customer_id', $customer_id);
        $st->execute();
        return $st->fetch()['address_id'] == $address_id ? true : false;
    }
    public function selectedAddress($customer_id){
        $data = array();
        $stmt = $this->conn->prepare("SELECT address_id FROM oc_customer WHERE customer_id = :customer_id LIMIT 1");
        $stmt->bindValue(':customer_id', $customer_id);
        $stmt->execute();
        $address_id = $stmt->fetch()['address_id'];
        
        $stmt1 = $this->conn->prepare("SELECT address_1, address_2, company, district, city, region, tracking_id, address_id FROM oc_address WHERE address_id = :address_id LIMIT 1");
        $stmt1->bindValue(':address_id', $address_id);
        $stmt1->execute();

        $address = $stmt1->fetch(PDO::FETCH_ASSOC);
        if($address){
            $data = array(
                'company' => $address['company'],
                'district' => $address['district'],
                'city' => $address['city'],
                'region' => $address['region'],
                'trackingId' => intval($address['tracking_id']),
                'addressId' => intval($address['address_id']),
                'address1' => $address['address_1'],
                'address2' => $address['address_2']
            );
        }
        return $data;
    }
    
}
$address = new Address();