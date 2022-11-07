<?php 

// include 'model/cart.php';
if(isset($_POST['add_order']))
{
  include 'model/checkout.php';
  $checkout = new Checkout(); 
	$shipping_rate = $_POST['shipping_rate'];
	$payment_method = $_POST['payment_method'];
  $checkout->confirm_order($shipping_rate, $payment_method, $_POST['customer_id'], $_POST['b_address'], $_POST['d_address']);
   
}else if(isset($_POST['add_address'])){

  include 'model/address.php';
  $cust_id =  $_POST['customer_id'];
  $firstname =  $_POST['firstname'];
  $lastname =  $_POST['lastname'];
  $company =  $_POST['company'];
  $address1 =  $_POST['address1'];
  $address2 =  $_POST['address2'];
  $city =  $_POST['city'];
  $postal =  $_POST['postal'];
  $country =  $_POST['country'];
  $zone =  $_POST['region'];
  $add_address = new Address();
  $add_address->save_address($cust_id, $firstname, $lastname, $company, $address1, $address2, $city, $postal, $country, $zone);

}else if(isset($_POST['add_customer'])){

  include 'model/register.php';
  $fname =  $_POST['txtfname'];
  $lname =  $_POST['txtlname'];
  $bday =  $_POST['txtbday'];
  $email =  $_POST['txtemail'];
  $mobile =  $_POST['txtmobile'];
  $username =  $_POST['txtusername'];
  $password =  $_POST['txtpassword'];
  $confirmpassword =  $_POST['txtconfirmpassword'];
  $add_address = new Register();
  $add_address->save_customer($fname, $lname, $bday, $email, $mobile, $username, $password, $confirmpassword);

}else if(isset($_POST['update_password'])){

  include 'model/register.php';
  $id =  $_POST['txtid'];
  $oldpassword =  $_POST['oldpassword'];
  $password =  $_POST['txtpassword'];
  $confirmpassword =  $_POST['txtconfirmpassword'];
  $update = new Register();
  $update->update_password($id, $oldpassword, $password, $confirmpassword);

}else if(isset($_POST['update_account'])){

  include 'model/account.php';
  $customer_id =  $_POST['customer_id'];
  $firstname =  $_POST['firstname'];
  $lastname =  $_POST['lastname'];
  $b_day =  $_POST['b_day'];
  $email =  $_POST['email'];
  $telephone =  $_POST['telephone'];
  $update = new Account();
  $update->update_account($customer_id, $firstname, $lastname, $b_day, $email, $telephone);

}else if(isset($_POST['update_address'])){

  include 'model/address.php';
  $address_id =  $_POST['address_id'];
  $cust_id =  $_POST['customer_id'];
  $firstname =  $_POST['firstname'];
  $lastname =  $_POST['lastname'];
  $company =  $_POST['company'];
  $address1 =  $_POST['address_1'];
  $address2 =  $_POST['address_2'];
  $city =  $_POST['city'];
  $postal =  $_POST['postcode'];
  $country =  $_POST['country_id'];
  $zone =  $_POST['zone_id'];
  $add_address = new Address();
  if($address_id === '0'){
    $add_address->save_address_mod($cust_id, $firstname, $lastname, $company, $address1, $address2, $city, $postal, $country, $zone);
  }else{
    $add_address->update_address_mod($address_id,$cust_id, $firstname, $lastname, $company, $address1, $address2, $city, $postal, $country, $zone);
  }

}else{
	header("location: welcome.php");
}



?>