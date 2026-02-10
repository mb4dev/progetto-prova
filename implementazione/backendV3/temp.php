<?php

use core\exceptions\GlobalExceptionHandler;
use core\factory\Factory;
use core\interfaces\PaymentsRepository;
use core\interfaces\ResponseStrategy;
use features\payments\strategies\NormalPaymentStrategy;

require_once("./autoload.php");

$factory = new Factory();
$infrastructureConfig = require_once(__DIR__ . "/config/services/config.infrastructure.php");
$applicationConfig = require_once(__DIR__ . "/config/services/config.application.php");

$infrastructureConfig($factory);
$applicationConfig($factory);

$exceptionHandler = new GlobalExceptionHandler($factory->get(ResponseStrategy::class));
$exceptionHandler->register();

$strategy = $factory->get(NormalPaymentStrategy::class);

$strategy->pay(1, 110.00,[
	["tipo" => "campo", "prenotazione_id" => 7],
    ["tipo" => "corso", "prenotazione_id" => 4]
]);