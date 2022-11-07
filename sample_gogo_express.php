<?php 


$curl = curl_init();
 $token='eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIyMjI2NzQ2MDliN2MyODNjZjVkIiwianRpIjoiSmFiZTk1M2M4LTM2MjEtNDE0Yi05OWU5LWEwMWQ5NDYxYjEyOSIsImlhdCI6MTQ2MzcwMjQwMH0.WXERprqXCJ8DENnXinVDmmYrhSZvrpBiSXWOu8CEl54';
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.sandbox.quadx.xyz/v2/orders',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
  "currency": "PHP",
  "total": "3153.25",
  "payment_method": "cod",
  "status": "for_pickup",
  "payment_provider": "lbcx",
  "shipment": "big-pouch",
  "buyer_name": "Test buyer",
  "buyer_id": "12345",
  "own_print": true,
  "pickup_total": 200.00,
  "metadata": {"key_1":"value_1","key_2":"value_2"},
  "delivery_address": {
    "name": "Test buyer",
    "company": "Maxis",
    "phone_number": "6358972",
    "mobile_number": "+63907117421",
    "line_1": "3F U311 Bldg. C",
    "line_2": "Jade St.",
    "district": "San Fernando",
    "city": "Mangaldan",
    "state": "Pangasinan",
    "postal_code": "4233",
    "country": "PH",
    "remarks": "Optional notes / remarks go here."
  },
  "return_address": {
    "name": "JJ. ABC",
    "company": "Maxis",
    "phone_number": "6358972",
    "mobile_number": "+63907117421",
    "line_1": "3F U311 Bldg. C",
    "line_2": "Jade St.",
    "city": "Baguio City",
    "state": "Benguet",
    "postal_code": "1226",
    "country": "PH",
    "remarks": "Optional notes / remarks go here."
  },
  "pickup_address": {
    "name": "JJJ. Doe",
    "company": "Maxis",
    "phone_number": "6358972",
    "mobile_number": "+63907117421",
    "line_1": "3F U311 Bldg. C",
    "line_2": "Jade St.",
    "city": "Baguio City",
    "state": "Benguet",
    "postal_code": "1226",
    "country": "PH",
    "remarks": "Optional notes / remarks go here."
  },
  "preferred_pickup_time": "morning",
  "preferred_delivery_time": "3pm - 5pm",
  "reference_id": "{{reference_id}}",
  "email": "johndoe@email.com",
  "contact_number": "+639172274819",
  "items": [
    {
      "type": "product",
      "description": "Red Shirt",
      "amount": 1250,
      "quantity": 1,
      "metadata": {"size":"medium","color":"red"}
    },
    {
      "type": "product",
      "description": "Blue Shirt",
      "amount": 700,
      "quantity": 2,
      "metadata": {"size":"medium","color":"blue"}
    },
    {
      "type": "tax",
      "description": "Tax",
      "amount": 132.50
    },
    {
      "type": "shipping",
      "description": "Expedited Shipping",
      "amount": 150
    },
    {
      "type": "fee",
      "description": "Handling Fee",
      "amount": 20
    },
    {
      "type": "fee",
      "description": "Gift Wrapping Fee",
      "amount": 50.75
    },
    {
      "type": "insurance",
      "description": "Insurance",
      "amount": 150
    }
  ]
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
   'Authorization: Bearer ' . $token
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;

?>