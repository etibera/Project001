<?php 

if(isset($_POST['add_returns'])){
  session_start();
  include 'model/returns.php';
  $ret= new Returns();  
  $order_id = $_POST['oid'];
  $product_id = $_POST['pid'];
  $customer_id = $_SESSION['user_login'];
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $email = $_POST['email'];
  $telephone = $_POST['telephone'];
  $product = $_POST['product'];
  $model = $_POST['model'];
  $quantity = $_POST['quantity'];
  $opened = $_POST['opened'];
  $return_reason_id = $_POST['return_reason_id'];
  $comment = $_POST['comment'];
  $date_ordered = $_POST['date_ordered'];
  $serial = $_POST['srl'];
  $return_save = $ret->return_add($order_id,$product_id,$customer_id,$firstname,$lastname,$email,$telephone,$product,
              $model,$quantity,$opened,$return_reason_id,$comment,$date_ordered,$serial);
}else{
	header("location: welcome.php");
}

?>