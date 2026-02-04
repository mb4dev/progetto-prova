<?php

use core\exceptions\GlobalExceptionHandler;
use core\factory\Factory;
use core\http\Router;
use core\interfaces\ResponseStrategy;

require_once("./autoload.php");

$factory = new Factory();
$infrastructureConfig = require_once(__DIR__ . "/config/services/config.infrastructure.php");
$applicationConfig = require_once(__DIR__ . "/config/services/config.application.php");

$infrastructureConfig($factory);
$applicationConfig($factory);

$exceptionHandler = new GlobalExceptionHandler($factory->get(ResponseStrategy::class));
$exceptionHandler->register();

$router = $factory->get(Router::class);
$router->dispatch();