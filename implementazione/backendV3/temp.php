<?php

use core\exceptions\GlobalExceptionHandler;
use core\factory\Factory;
use core\interfaces\PaymentsRepository;
use core\interfaces\ResponseStrategy;

require_once("./autoload.php");

$factory = new Factory();
$infrastructureConfig = require_once(__DIR__ . "/config/services/config.infrastructure.php");
$applicationConfig = require_once(__DIR__ . "/config/services/config.application.php");

$infrastructureConfig($factory);
$applicationConfig($factory);

$exceptionHandler = new GlobalExceptionHandler($factory->get(ResponseStrategy::class));
$exceptionHandler->register();

$repo = $factory->get(PaymentsRepository::class);

//var_dump(json_encode($repo->getAll()));
//var_dump(json_encode($repo->insertPagamento(1, 1000.00)));
var_dump(json_encode($repo->insertVocePagamento(1, "campo", 100, 1)));
