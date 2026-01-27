<?php

use auth\AuthControllerCreator;
use core\factory\ControllerCreatorRegistry;

require_once("./autoload.php");

use core\factory\ControllerFactory;
use core\http\ControllerTypes;
use core\http\DefaultRouter;
use core\utility\DefaultURLParser;
use core\http\HttpResponse;
use core\utility\GlobalExceptionHandler;

$exceptionHandler = new GlobalExceptionHandler();
$exceptionHandler->register();

$connection = new PDO("pgsql:host=localhost;port=5432;dbname=postgres;", "postgres", "postgres");
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$registry = new ControllerCreatorRegistry();

$registry->register(ControllerTypes::AUTH, new AuthControllerCreator());

$parser = new DefaultURLParser();
$factory = new ControllerFactory($connection, $registry);
$response =new HttpResponse();

$router = new DefaultRouter($parser, $factory, $response);

$router->dispatch();