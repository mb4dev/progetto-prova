<?php

use core\di\Container;

require_once("./autoload.php");

require_once("./config/container.php");


use auth\AuthControllerCreator;
use booking\BookingControllerCreator;
use resources\ResourceControllerCreator;
use core\factory\ControllerCreatorRegistry;


use core\factory\ControllerFactory;
use core\http\DefaultRouter;
use core\utility\DefaultURLParser;
use core\http\HttpResponse;
use core\utility\GlobalExceptionHandler;


$container = require(__DIR__ . "/config/container.php");

$exceptionHandler = new GlobalExceptionHandler();
$exceptionHandler->register();


$registry = new ControllerCreatorRegistry();

/*
$registry->register(ControllerTypes::AUTH, new AuthControllerCreator());
$registry->register(ControllerTypes::RESOURCE, new ResourceControllerCreator());
$registry->register(ControllerTypes::BOOKING, new BookingControllerCreator());
*/

$parser = new DefaultURLParser();
$factory = new ControllerFactory($container->get(PDO::class), $registry);
$response =new HttpResponse();

$router = new DefaultRouter($parser, $container, $factory, $response);

$router->dispatch();
