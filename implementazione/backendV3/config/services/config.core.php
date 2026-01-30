<?php

use core\factory\Factory;
use core\factory\FactoryMethod;
use core\http\HttpResponseStrategy;
use core\http\middlewares\AuthMiddleware;
use core\http\Router;
use core\http\StandardRouter;
use core\interfaces\AuthRepository;
use core\interfaces\HttpSecurity;
use core\interfaces\PasswordManager;
use core\interfaces\ResponseStrategy;
use core\interfaces\TokenService;
use core\interfaces\URLParser;
use core\utility\ConfigurationService;
use core\utility\DefaultPasswordManager;
use core\utility\jwt\JwtTokenService;
use core\utility\StandardURLParser;


return function(Factory $factory) {

	$factory->register(ConfigurationService::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory) : ConfigurationService{
			$configPath = __DIR__ . "/../config.php";
			return new ConfigurationService($configPath);	
		}
	});

	$factory->register(PDO::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory) {
			$configService = $factory->get(ConfigurationService::class);
			
			$host = $configService->get("database.host");
			$port = $configService->get("database.port");
			$dbname = $configService->get("database.dbname");
			$username = $configService->get("database.username");
			$password = $configService->get("database.password");
			
			
			$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
			$connection = new PDO($dsn, $username, $password);
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $connection;
		}
	});

	$factory->register(URLParser::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory){
			return new StandardURLParser();
		}
	});

	$factory->register(ResponseStrategy::class, new class implements FactoryMethod{
		public function __invoke(Factory $factory){
			return new HttpResponseStrategy();
		}
	});


	$factory->register(Router::class, new class implements FactoryMethod{
		public function __invoke(Factory $factory)		{
			$urlParser = $factory->get(URLParser::class);
			$responseStrategy = $factory->get(ResponseStrategy::class);

			return new StandardRouter($factory, $urlParser, $responseStrategy);
		}
	});

	$factory->register(PasswordManager::class, new class implements FactoryMethod{
		public function __invoke(Factory $factory)		{
			return new DefaultPasswordManager();
		}
	});


	$factory->register(TokenService::class, new class implements FactoryMethod{
		public function __invoke(Factory $factory)		{
			return new JwtTokenService();
		}
	});


	$factory->register(HttpSecurity::class, new class implements FactoryMethod{
		public function __invoke(Factory $factory)		{
			$tokenService = $factory->get(TokenService::class);
			$authRepository = $factory->get(AuthRepository::class);

			return new AuthMiddleware($tokenService, $authRepository);
		}
	});
	


};