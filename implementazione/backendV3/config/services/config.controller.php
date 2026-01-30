<?php

use core\factory\Factory;
use core\factory\FactoryMethod;
use core\interfaces\AuthService;
use core\interfaces\HttpSecurity;
use core\interfaces\ResourceService;
use core\utility\CommandController;
use features\auth\AuthController;
use features\resources\ResourceController;

return function(Factory $factory) {

	$factory->register(AuthController::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory) : CommandController{
			$service = $factory->get(AuthService::class);
			$security = $factory->get(HttpSecurity::class);
			return new AuthController($security, $service);
		}
	});

	$factory->register(ResourceController::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory) : CommandController{
			$service = $factory->get(ResourceService::class);
			$security = $factory->get(HttpSecurity::class);

			return new ResourceController($security, $service);
		}
	});

};