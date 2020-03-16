<?php
error_reporting(E_ALL);
ini_set("log_errors", 1);
ini_set("error_log", "/tmp/php-error.log");
error_log("App Start");
//load composer up first
$path = array(__DIR__, '..', 'restricted', 'app.php');
$app_boot = implode(DIRECTORY_SEPARATOR, $path);
require_once $app_boot;
$app = new AppMain('page');


?>
