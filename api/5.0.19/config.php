<?php 
$project_path = '';
    if (isset($_SERVER['HTTPS'])){
        $project_path = '/home/irpge67jnamu/public_html/';
        

        $mail_host = 'mail.pinoyelectronicstore.com';
        $mail_username = 'customer@pinoyelectronicstore.com';
        $mail_password = 'change55me';
        $mail_port = 587;
    }else{
        $project_path = 'C://xampp/htdocs/peso-web-new/';

        $mail_host = 'smtp.mailtrap.io';
        $mail_username = '6f8285bbba6457';
        $mail_password = '337f88bfe84116';
        $mail_port = 2525;
    }
    define('SITE_ROOT', $project_path);

    define('MAIL_HOST', $mail_host);
    define('MAIL_USERNAME', $mail_username);
    define('MAIL_PASSWORD', $mail_password);
    define('MAIL_PORT', $mail_port);




?>