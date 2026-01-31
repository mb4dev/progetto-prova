<?php 

namespace config;

use core\di\Container;
use core\utility\DefaultPasswordManager;
use core\utility\interfaces\JwtTokenService;
use core\utility\interfaces\PasswordManager;
use core\utility\jwt\MyJwtService;
use PDO;

$config = require(__DIR__ . "/config.php");

$container = new Container();

$container->register(PDO::class, function(Container $container) use ($config){
	$dbConfig = $config["database"];
    $dsn = "pgsql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['dbname']}";
	$connection = new PDO($dsn, $dbConfig["username"], $dbConfig["password"]);
	$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	return $connection;
});

$container->register(PasswordManager::class, function (Container $container) {
	return new DefaultPasswordManager();
});


$container->register(JwtTokenService::class, function (Container $container) {
	return new MyJwtService();
});



return $container;
