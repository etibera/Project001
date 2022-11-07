<?php
if(isset($_GET['action'])){
    $source = $_GET['action'];
}else{
    $source = "";
}
switch($source){
        case 'getcategory':
          $json = array();
          include "model/homecategory.php";
          $model=new homecategory();    
          $getcategories = $model->getCategories(0);
          $json['getcategories'] = array();
          foreach ($getcategories as $getcategory) {
            if($getcategory['top']){
              $json['getcategories'][] = array(
              'category_id'    => $getcategory['category_id'],
              'name'           => $getcategory['name'],
              );
            }
          }
          echo json_encode($json);
        break;
          case 'getpromoperseller':
          include "model/homecategory.php";
          $model=new homecategory();
          $json = array();
          $results  = $model->getpromoperseller($_POST['seller_id']);
          
          $json['seller_promo'] = $results;     
         echo json_encode($json);            
      break;
        case 'delete_seller':           
          include "model/homecategory.php";
          $model=new homecategory();
          $data=json_decode($_POST['chk_deduc_id']);
          $results = $model->delete_seller($data);
          $json['success'] = $results;
          echo json_encode($json);            
        break;
        case 'save_seller':           
          include "model/homecategory.php";
          $model=new homecategory();
          $data=json_decode($_POST['chk_deduc_id']);
          $results = $model->save_seller($data,$_POST['seller_id'],$_POST['lpid']);
          
          $json['success'] = $results ;
          echo json_encode($json);            
        break;
        case 'delete_seller_promo':           
          include "model/homecategory.php";
          $model=new homecategory();
          $data=json_decode($_POST['chk_deduc_id']);
          $results = $model->delete_seller_promo($data);
          $json['success'] = $results;
          echo json_encode($json);            
        break;
        case 'savecategory':
          include "model/homecategory.php";
          $model=new homecategory();
          $gethomecategory = $model->gethomecategory($_POST['options_category']);
          if($gethomecategory==0){
            $model->addhomecategory($_POST['options_category'],$_POST['show_limit'],$_POST['add_sort'],$_POST['add_sort_name']);
          }
          $json['success']="homecategory.php";
          echo json_encode($json);
        break;
        case 'deletecategory':
          include "model/homecategory.php";
          $model=new homecategory();
          $model->deletehomecategory($_POST['catid']);  
          $json['success']="homecategory.php";
          echo json_encode($json);    
        break;
        case 'updatecategory':
          include "model/homecategory.php";
          $model=new homecategory();
          $model->updatehomecategory($_POST['homecatid'],$_POST['category_id'],$_POST['show_limit_update'],$_POST['edit_sort'],$_POST['edit_sort_name']);
          $json['success']="homecategory.php";
          echo json_encode($json);            
        break;
        case 'delete_product_id':
          include "model/homecategory.php";
          $model=new homecategory();
          $model->delete_product_category_id($_POST['catecory_id'],$_POST['options_category_id']);
          $json['success']="homecategory.php";
          echo json_encode($json);            
        break;
        case 'getcategory_product':
          include "model/homecategory.php";
          $model=new homecategory();
          $json = array();
          $results  = $model->get_product_under_category_id($_POST['catecory_id']);
          $json['get_product_under_category_id'] = array();
      foreach ($results as $result) {
        $json['get_product_under_category_id'][] = array(
        'product_id'    => $result['product_id'],
        'name'           => $result['name'],
        );  
      }
          echo json_encode($json);            
        break;
        case 'add_product_id':
          include "model/homecategory.php";
          $model=new homecategory();
          $model->add_product_category_id($_POST['catecory_id'],$_POST['product_id'],$_POST['h_id']);
          $json['success']="homecategory.php";
          echo json_encode($json);            
        break;
      case 'add_currency':
          $json = array(); 
          include "model/Specification.php";
          $model = new Specification(); 
          $res = $model->save_currency($_POST['base_c'],$_POST['exchange_currency']);
          if($res=="200"){
            $json['success']="selected_currency.php";
            $sMsg="Successfully Added "; 
          }else{
            $json['success']="selected_currency.php";
            $errorr_msg=$res;
          }
          echo json_encode($json);
        break;
      case 'updateDR_CH':
          $json = array(); 
          include "model/Specification.php";
          $model = new Specification(); 
          $res = $model->updateDR_CH($_POST['id'],$_POST['dr_id']);
          if($res=="200"){
            $json['success']="selected_currency.php";
          }else{
             $json['failed']=$res;
          }
          echo json_encode($json);
      break;
      case 'search_all_products':
          include "model/homecategory.php";
          $model=new homecategory();
          $json = array();
          $results  = $model->get_search_all_products($_POST['searc_val']);
          foreach ($results as  $res) {
            $json['data_product'][]= array(
                'product_id' => $res['product_id'],
                'price' => $res['price'],
                'name' => utf8_encode($res['name']),
                'model' => utf8_encode($res['model']),
                'image' => utf8_encode($res['image']),
                'type' => $res['type'],
                'typedesc' => $res['typedesc']
            );
          }
          //$json['data_product'] = $results;
        //   echo'<pre>';
        //   print_r($json);
         echo json_encode($json);            
      break;
      default:
      break;
}


 
?>