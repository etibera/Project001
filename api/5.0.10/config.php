<?php 
$project_path = '';
    if (isset($_SERVER['HTTPS'])){
        $project_path = '/home/irpge67jnamu/public_html/';
    }else{
        $project_path = 'C://xampp/htdocs/peso-web-new/';
    }
    define('SITE_ROOT', $project_path);
?>