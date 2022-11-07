 <?php 
include 'template/header.php';
if(isset($_POST['update_returns'])){
 // session_start();
  require_once 'model/Returns.php';
  $ret= new Returns();  
  $order_id = $_POST['order_id'];
  $return_id = $_POST['return_id'];
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
  //order history
  $return_status_id = $_POST['return_status_id'];
  $return_action_id = $_POST['return_action_id'];
  $notify = $_POST['notify'];
  $comment_a = $_POST['comment_a'];
  //update returns
  $return_save = $ret->return_update($order_id,$return_id,$firstname,$lastname,$email,$telephone,$product,
              $model,$quantity,$opened,$return_reason_id,$comment,$date_ordered,$return_action_id,$return_status_id,$notify,$comment_a);
}else{
  header("location: index.php");
}

?>