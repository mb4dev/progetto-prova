<?php

/**
 * Bootstrap dell'applicazione
 * 
 * Questo file:
 * 1. Carica l'autoloader
 * 2. Carica la configurazione
 * 3. Configura il container DI
 * 4. Registra i service provider
 * 5. Avvia il router
 */

require_once("./autoload.php");

use auth\AuthServiceProvider;
use booking\BookingServiceProvider;
use resources\ResourceServiceProvider;
use core\di\Container;
use core\http\DefaultRouter;
use core\utility\DefaultURLParser;
use core\http\HttpResponse;
use core\utility\GlobalExceptionHandler;
use PDO;

// Registra il gestore delle eccezioni
$exceptionHandler = new GlobalExceptionHandler();
$exceptionHandler->register();

// Carica la configurazione
$config = require_once("./config/config.php");

// Crea il container DI
$container = new Container();

// Registra la connessione al database come singleton
$container->register('pdo', function($c) use ($config) {
    $dbConfig = $config['database'];
    $dsn = "pgsql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['dbname']}";
    $connection = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $connection;
}, true);

// Registra i controller (Factory Method Pattern)
// Ogni controller sa come registrare le proprie dipendenze
\auth\AuthController::register($container);
\booking\BookingController::register($container);
\resources\ResourceController::register($container);

// Crea il router con le sue dipendenze
$parser = new DefaultURLParser();
$response = new HttpResponse();

// Crea il router passando direttamente il Container
// I controller sono già registrati tramite i loro Factory Method
$router = new DefaultRouter($parser, $container, $response);

// Gestisce la request
$router->dispatch();