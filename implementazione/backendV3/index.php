<?php

use core\factory\Factory;
use core\http\Router;

require_once("./autoload.php");

$factory = new Factory();
$coreConfig = require_once(__DIR__ . "/config/services/config.core.php");

$coreConfig($factory);



$router = $factory->get(Router::class);
$router->dispatch();