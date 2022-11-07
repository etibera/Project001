<?php
require_once '../init.php';
if(isset($_GET['action'])){
    $source = $_GET['action'];
}else{
    $source = "";
}
$data = array();
switch($source){
        case 'banner':
            if(is_numeric($_GET['id'])){
                $banner = $home->banner(trim($_GET['id']));
                echo json_encode($banner);
            }
        break;
        case 'category':
            $category = $home->categories();
            echo json_encode($category);
        break;
        case 'home_category':
            $category = array();
            $category = $home->home_category($_GET['page'], $_GET['id']);
            echo json_encode($category);
        break;
        case 'get_product':
            $product = $home->product($_GET['product_id'], $_GET['type'], $_GET['token'], $_GET['customer_id'], $_GET['storeId']);
            echo json_encode($product);
        break;
        case 'search_product':
            $search = $home->search_products('search');
            echo json_encode($search);
        break;
        case 'category_product':
            $category = $home->search_products('category');
            echo json_encode($category);
        break;
        case 'store_product':
            $category = $home->search_products('store');
            echo json_encode($category);
        break;
        case 'getPeopleViewProducts':
            $data = $product->getPeopleViewProducts($_GET['product_id'], $_GET['type']);
            echo json_encode($data);
        break;
        case 'getDiscoverProducts':
            $data = $product->getDiscoverProducts($_GET['product_ids'], $_GET['pageNumber']);
            echo json_encode($data);
        break;
        case 'getCBProducts':
            $data = $product->globalProducts($_POST['pageNumber']);
            echo json_encode($data);
        break;
        case 'getProductCB':
            $data = $chinabrands->getProduct($_GET['token'], $_GET['sku']);
            echo json_encode($data);
        break;
        case 'search_cb':
            $data = $home->search_cb($_GET['token']);
            echo json_encode($data);
        break;
        case 'home1':
            $data = $home->home_page();
            echo json_encode($data);
        break;
        case 'homeproduct1':
            $data = $home->home_product_page($_GET);
            echo json_encode($data);
        break;
        case 'banggoodtest':
            echo json_encode($banggood->product(1783698));
        break;
        case 'recommend':
            $data = $home->recommended($_GET['customer_id']);
            echo json_encode($data);
        break;
        case 'most_popular':
            $data = $home->most_popular();
            echo json_encode($data);
        break;
        case 'teststock':
            $data = $chinabrands->getCBPrice($_GET['token'], $_GET['product_id']);
            echo json_encode($data);
        break;
        case 'promo':
            $data = $home->promo();
            echo json_encode($data);
        break;
        case 'getLastestPromoProduct':
            $data = $home->lastestPromoProducts($_GET['pageNumber'], $_GET['promoId']);
            echo json_encode($data);
        break;
        case 'globalCategories':
            $data = $home->globalCategories();
            echo json_encode($data);
        break;
        case 'globalProductByCategory':
            $data = $product->globalProductsByCategory($_GET['pageNum'], $_GET['categoryId']);
            echo json_encode($data);
        break;
        case 'globalCategoryName':
            $data = $product->globalCategoryName($_GET['categoryId']);
            echo json_encode($data);
        break;
        case 'homeGlobalCategories':
            $data = $category->globalCategory();
            echo json_encode($data);
        break;
        case 'brands':
            echo json_encode($home->brands());
        break;
        case 'brandProducts':
            echo json_encode($product->brandProducts($_GET['pageNum'], $_GET['brandId']));
        break;
        case 'brandDetails':
            echo json_encode($home->brandProductDetails($_GET['brand_id']));
        break;
        case 'brandCategories':
            echo json_encode($category->getBrandCategory($_GET['brand_id']));
        break;
        case 'brandCategoryProduct':
            echo json_encode($product->brandProductCategory($_GET['pageNum'], $_GET['category_id'], $_GET['brand_id']));
        break;
        case 'latestPromoDetail':
            echo json_encode($home->latestPromoDetail($_GET['latest_promo_id']));
        break;
        case 'popup_ads':
            echo json_encode($home->popup_ads());
        break;
        case 'getStores':
            echo json_encode($home->getStores());
        break;
        case 'getStoreDetails':
            echo json_encode($home->getStoreDetails($_GET['store_id']));
        break;
        case 'getStoreCategoryDetail':
            echo json_encode($product->storeProductCategory($_GET['pageNum'], $_GET['category_id'], $_GET['store_id']));
        break;
        case 'getStoreCategories':
            echo json_encode($home->getStoreCategories($_GET['store_id']));
        break;
        case 'getStoreCategoryProduct':
            echo json_encode($product->storeProductCategory($_GET['pageNum'], $_GET['category_id'], $_GET['store_id']));
        break;
        case 'categoryName':
            echo json_encode($category->categoryName($_GET['category_id']));
        break;
        default:
        echo phpinfo();
        break;

}

