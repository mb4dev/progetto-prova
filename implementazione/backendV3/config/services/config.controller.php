<?php

use core\factory\Factory;
use core\factory\FactoryMethod;
use core\interfaces\AuthService;
use core\interfaces\HttpSecurity;
use core\model\Role;
use core\model\User;
use core\utility\CommandController;
use features\auth\AuthController;

return function(Factory $factory) {

	$factory->register(AuthController::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory) : CommandController{
			$service = $factory->get(AuthService::class);
			return new AuthController(new class implements HttpSecurity{
				public function authenticate(string $token): ?User{
					return new User(1, "test", "test", "", Role::USER);
				}
			}, $service);
		}
	});

};