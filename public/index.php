<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

define('APP_ROOT', dirname(dirname(__FILE__)));

include APP_ROOT . '/autoload.php';
include APP_ROOT .'/config.php';

use \lib\Dispatcher;

//
/* http://172.16.0.224:8042/index.php/Index/queryBalance */
$dispatcher = new Dispatcher($_SERVER['REQUEST_URI']);
$dispatcher->dispatch();
