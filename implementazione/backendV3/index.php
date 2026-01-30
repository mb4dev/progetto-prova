<?php

use core\exceptions\GlobalExceptionHandler;
use core\factory\Factory;
use core\http\Router;

require_once("./autoload.php");

$factory = new Factory();
$coreConfig = require_once(__DIR__ . "/config/services/config.core.php");
$controllerConfig = require_once(__DIR__ . "/config/services/config.controller.php");
$repositoryConfig = require_once(__DIR__ . "/config/services/config.repository.php");
$servicesConfig = require_once(__DIR__ . "/config/services/config.services.php");

$coreConfig($factory);
$controllerConfig($factory);
$repositoryConfig($factory);
$servicesConfig($factory);

$exceptionHandler = new GlobalExceptionHandler();
$exceptionHandler->register();

$router = $factory->get(Router::class);
$router->dispatch();