<?php

require_once("./autoload.php");


use core\http\DefaultRouter;
use core\utility\DefaultURLParser;
use core\http\HttpResponse;
use core\utility\ControllerFactory;
use core\utility\GlobalExceptionHandler;

$exceptionHandler = new GlobalExceptionHandler();
$exceptionHandler->register();

$connection = new PDO("pgsql:host=localhost;port=5432;dbname=postgres;", "postgres", "postgres");
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$router = new DefaultRouter(
    new DefaultURLParser(),
    new ControllerFactory($connection),
    new HttpResponse() 
);

$router->dispatch();