<?php

/**
 * File di configurazione centrale dell'applicazione
 * 
 * Questo file contiene tutte le configurazioni necessarie per l'applicazione.
 * Per un ambiente di produzione, considera l'uso di variabili d'ambiente.
 */

return [
    // Configurazione Database
    'database' => [
        'host' => 'localhost',
        'port' => '5432',
        'dbname' => 'postgres',
        'username' => 'postgres',
        'password' => 'postgres',
        'charset' => 'utf8',
    ],

    // Configurazione JWT
    'jwt' => [
        'secret' => 'your-secret-key-change-in-production',
        'expiration' => 3600, // 1 ora in secondi
        'algorithm' => 'HS256',
    ],

    // Configurazione Applicazione
    'app' => [
        'env' => 'development', // development, production
        'debug' => true,
        'timezone' => 'Europe/Rome',
    ],
];
