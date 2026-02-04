<?php

use core\exceptions\GlobalExceptionHandler;
use core\factory\Factory;
use core\http\Router;
use core\interfaces\BookingRepository;
use core\interfaces\FieldsRepository;
use core\interfaces\ResponseStrategy;
use core\interfaces\SubscriptionsRepository;
use features\booking\fields\FieldBookingRepository;
use features\booking\repository\PostgreFieldBookingRepository;
use features\resources\repository\PostgreFieldsRepository;

require_once("./autoload.php");

$factory = new Factory();
$infrastructureConfig = require_once(__DIR__ . "/config/services/config.infrastructure.php");
$applicationConfig = require_once(__DIR__ . "/config/services/config.application.php");

$infrastructureConfig($factory);
$applicationConfig($factory);

$exceptionHandler = new GlobalExceptionHandler($factory->get(ResponseStrategy::class));
$exceptionHandler->register();

$repo = $factory->get(FieldsRepository::class);

//var_dump(json_encode($repo->getAll()));
var_dump(json_encode($repo->getResourceById(-1)));
