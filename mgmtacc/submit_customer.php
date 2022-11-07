<?php 

if(isset($_POST['update_customer'])){

  include 'model/Customer.php';
  $customer_id =  $_POST['customer_id'];
  $customer_group_id =  $_POST['customer_group_id'];
  $firstname =  $_POST['firstname'];
  $lastname =  $_POST['lastname'];
  $email =  $_POST['email'];
  $telephone =  $_POST['telephone'];
  $fax =  $_POST['fax'];
  $password =  $_POST['password'];
  $confirmpassword =  $_POST['confirmpassword'];
  $newsletter =  $_POST['newsletter'];
  $status =  $_POST['status'];
  $approved =  $_POST['approved'];
  $safe =  $_POST['safe'];
  $update = new Customer();
  $update->customer_update($customer_id, $customer_group_id, $firstname, $lastname, $email, 
    $telephone, $fax, $password, $confirmpassword, $newsletter, $status, $approved, $safe);

}else{
	header("location: index.php");
}

?>