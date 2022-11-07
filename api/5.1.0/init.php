<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, PUT');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
date_default_timezone_set('Asia/Manila');
include 'config.php';
require SITE_ROOT."/include/database.php";


require_once SITE_ROOT.'/include/email_template.php';
require_once SITE_ROOT.'/include/landBankApi.php';
require_once SITE_ROOT.'/include/M360Api.php';
require_once SITE_ROOT.'/include/p2mApi.php';
require_once SITE_ROOT.'/PHPMailer/src/PHPMailer.php';
require_once SITE_ROOT.'/PHPMailer/src/Exception.php';
require_once SITE_ROOT.'/PHPMailer/src/SMTP.php';





$files = glob('../model/*.php');
foreach ($files as $file) {
    require_once($file);
}