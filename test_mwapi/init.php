<?php
require_once "../../include/database.php";
$files = glob('../model/*.php');
foreach ($files as $file) {
    require_once($file);
}