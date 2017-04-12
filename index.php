<?php
#入口文件
define('BASE_PATH', realpath(dirname(__FILE__)));
define('APP_PATH', BASE_PATH.DIRECTORY_SEPARATOR.'application');
define('HTTP_HOST', $_SERVER['HTTP_HOST']);

$app = new Yaf_Application(BASE_PATH .DIRECTORY_SEPARATOR.'conf'.DIRECTORY_SEPARATOR.'application.ini');
$app->bootstrap()->run();