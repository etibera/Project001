<?php
require_once '../init.php';
if(isset($_GET['action'])){
    $source = $_GET['action'];
}else{
    $source = "";
}
$data = array();
switch($source){
        case 'getAddresses':
        $data = array();
        $adrs = $address->getAddresses($_GET['customer_id']);
        $address_id = $address->getSelectedAddressId($_GET['customer_id']);
        foreach($adrs as $adr){
            $data[] = array(
                'id'    => intval($adr['address_id']),
                'company'    => $adr['company'],
                'address1'    => $adr['address_1'],
                'address2'    => $adr['address_2'],
                'postcode'    => $adr['postcode'],
                'city'    => $adr['city'],
                'zoneId'    => intval($adr['zone_id']),
                'zone'    => $adr['zone'],
                'countryId'    => intval($adr['country_id']),
                'country'    => $adr['country'],
                'selected' => $address_id == $adr['address_id'] ? true : false
            );
        }
        echo json_encode($data);
        break;
        case 'countries':
        $countries = $address->getCountries();
        echo json_encode($countries);
        break;
        case  'regions':
        $regions = $address->getRegion($_GET['country_id']);
        echo json_encode($regions);
        break;
        case 'add':
        $address_id = $address->add($_POST);
        $address = $address->getAddress($address_id, $_POST['customer_id']);
        if($address_id){
            $data = array(
                'id' => strval($address_id),
                'country' => $address['country'],
                'zone' => $address['zone'],
            );
        }else{
            $data = $address_id;
        }
        echo json_encode($data);
        break;
        case 'edit':
            $address_data = $address->update($_POST);
            $address = $address->getAddress($_POST['address_id'], $_POST['customer_id']);
            if($address_data){
                $data = array(
                    'country' => $address['country'],
                    'zone' => $address['zone'],
                );
            }else{
                $data = $address_data;
            }
            echo json_encode($data);
            break;
        case 'delete':
            $address_data = $address->delete($_POST['address_id'], $_POST['customer_id']);
            if($address_data){
               $data = array(
                'customerAddressId' => $address_data
               );
            }else{
                $data = $address_data;
            }
            echo json_encode($data);
        break;
        case 'setAddress':
           echo json_encode($address->setAddress($_POST['customer_id'], $_POST['address_id']));
        break;
        default:
        break;
}

