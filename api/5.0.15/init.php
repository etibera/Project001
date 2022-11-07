<?php
// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Methods: GET, POST, DELETE, PUT');
// header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");
header("Content-Security-Policy: default-src 'self'");
header('X-Frame-Options: SAMEORIGIN');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Manila');
include 'config.php';
require SITE_ROOT."/include/database.php";


require_once SITE_ROOT.'/include/email_template.php';

require_once SITE_ROOT.'/PHPMailer/src/PHPMailer.php';
require_once SITE_ROOT.'/PHPMailer/src/Exception.php';
require_once SITE_ROOT.'/PHPMailer/src/SMTP.php';




$files = glob('../model/*.php');
foreach ($files as $file) {
    require_once($file);
}