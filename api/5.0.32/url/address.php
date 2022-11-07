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
        $address = $address->getAddresses($_GET['customer_id']);
        echo json_encode($address);
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
        $address = $address->add($_POST);
        echo json_encode($address);
        break;
        case 'edit':
        $address = $address->update($_POST);
        echo json_encode($address);
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
        case 'findAddress':
            echo json_encode($address->findAddress($_GET['customer_id'], $_GET['address_id']));
        break;
        case 'findRegion':
            echo json_encode($address->findRegion());
        break;
        case 'findCity':
        echo json_encode($address->findCity($_GET['province']));
        break;
        case 'findDistrict':
            echo json_encode($address->findDistrict($_GET['city']));
        break;
        case 'selectedAddress':
            echo json_encode($address->selectedAddress($_GET['customerId']));
        break;
        default:
        break;
}

