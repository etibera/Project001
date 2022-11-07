<?php
require_once '../init.php';
if(isset($_GET['action'])){
    $source = $_GET['action'];
}else{
    $source = "";
}
$data = array();
switch($source){
        case 'storeProfile':
            echo json_encode($store->store_profile($_GET['seller_id'], $_GET['customer_id']));
        break;
        case 'follow':
            echo json_encode($store->follow($_POST['customer_id'], $_POST['seller_id']));
        break;
        case 'banner':
             echo json_encode($store->banner($_GET['seller_id']));
        break;
        case 'flagshipAnnouncement':
             echo json_encode($store->flagship_announcement($_GET['seller_id'], $_GET['type']));
        break;
        case 'flagshipProducts':
            $data = $store->GetFlagShipProduct($_GET['seller_id']);
            echo json_encode($data);
        break;
        case 'flagshipCategories':
            $data = $store->getbrandcategory($_GET['seller_id']);
            echo json_encode($data);
        break;
        case 'categoryDetail':
            $data = $store->getCategory($_GET['category_id']);
            echo json_encode($data);
        break;
        case 'flagshipMostPopularProductOnCategory':
            $data = $store->FlagShipMostPularWithCategory($_GET['seller_id'], $_GET['category_id']);
            echo json_encode($data);
        break;
        case 'flagshipMostPopularProductOnProduct':
            $data = $store->FlagShipMostPularOnProduct($_GET['seller_id']);
            echo json_encode($data);
        break;
        case 'flagshipBestSellerProductOnCategory':
            $data = $store->flagshipBestSellerOnCategory($_GET['seller_id']);
            echo json_encode($data);
        break;
        case 'flagshipRecommendedProductOnCategory':
            $data = $store->FlagShipRecommendedForYouWithCategory($_GET['customer_id'], $_GET['seller_id'], $_GET['category_id']);
            echo json_encode($data);
        break;
        case 'flagshipRecommendedProductOnProduct':
            $data = $store->FlagShipRecommendedOnProduct($_GET['customer_id'], $_GET['seller_id']);
            echo json_encode($data);
        break;
        default:
        break;
}

