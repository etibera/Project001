<?php 
    include "common/header.php";   
    $custid = isset($_SESSION['user_login']) ? $_SESSION['user_login']: 0;
    if(isset($_GET["lbpCustid"])){
      require_once 'model/home_new.php'; 
      require_once 'model/reg_new.php';
      $modAddGoogle = new Register_new();  
      $home_new_mod=new home_new();
        $reslastid=$_GET['lbpCustid'];  
        $cust_detGoogle=$modAddGoogle->getCustomerdetails($reslastid);
        if($cust_detGoogle){
            $_SESSION['regok_mobile_verify'] = 'Landbank Pay Account Successfully Login'; 
            $session->login($cust_detGoogle);
            $modAddGoogle->insertactivity($cust_detGoogle['customer_id'],'Lanbank Pay Account');    
           
            $locationV="<script> window.location.href='home.php";
            $locationV.="';</script>";
            echo $locationV;      
        }
      }else if (isset($_GET["fgivesCustid"])) {
        require_once 'model/home_new.php'; 
        require_once 'model/reg_new.php';
        $modAddGoogle = new Register_new();  
        $home_new_mod=new home_new();
        $reslastid=$_GET['fgivesCustid'];  
        $cust_detGoogle=$modAddGoogle->getCustomerdetails($reslastid);
        if($cust_detGoogle){
            $_SESSION['regok_mobile_verify'] = '4Gives Account Successfully Login'; 
            $session->login($cust_detGoogle);
            $modAddGoogle->insertactivity($cust_detGoogle['customer_id'],'4Gives Account');    
            $locationV = "<script> window.location.href='reg_activatemobile.php?RegIdVal=" . $reslastid.'&retohome=1' ;
            $locationV .= "';</script>";
            echo $locationV; 
        }

      }else if (isset($_GET["regCustid"])) {
      require_once 'model/home_new.php'; 
      require_once 'model/reg_new.php';
      $modAddGoogle = new Register_new();  
      $home_new_mod=new home_new();
      $reslastid=$_GET['regCustid'];  
      $cust_detGoogle=$modAddGoogle->getCustomerdetails($reslastid);
      if($cust_detGoogle){
            $session->login($cust_detGoogle);
            $modAddGoogle->insertactivity($cust_detGoogle['customer_id'],$cust_detGoogle['firstname'].' logged in');     
            $locationV="<script> window.location.href='home.php";
            $locationV.="';</script>";
            echo $locationV;      
      }

   }else if (isset($_GET["regExistCustid"])) {
      require_once 'model/home_new.php'; 
      require_once 'model/reg_new.php';
      $modAddGoogle = new Register_new();  
      $home_new_mod=new home_new();
      $reslastid=$_GET['regExistCustid'];  
      $checkout_cart=$_GET['getcart_id'];  
      $cust_detGoogle=$modAddGoogle->getCustomerdetails($reslastid);
      if($cust_detGoogle){
            $session->login($cust_detGoogle);
            $modAddGoogle->insertactivity($cust_detGoogle['customer_id'],$cust_detGoogle['firstname'].' logged in');     
            $locationV="<script> window.location.href='checkout.php?checkout_cart=".$checkout_cart;
            $locationV.="';</script>";
            echo $locationV;      
      }

   }
?>