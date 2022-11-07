<?php 
require './composer/vendor/autoload.php'; 
use Zxing\QrReader;
$image="C:\Users\libot\Downloads\qr (1).png";
$qrcode = new QrReader($image);
print_r(" QRCOde:".$qrcode->text());
?>