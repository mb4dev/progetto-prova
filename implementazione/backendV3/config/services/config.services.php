<?php

use features\auth\StandardAuthService;
use core\factory\Factory;
use core\factory\FactoryMethod;
use core\interfaces\AuthRepository;
use core\interfaces\AuthService;
use core\interfaces\PasswordManager;
use core\interfaces\TokenService;

return function(Factory $factory) {

	$factory->register(AuthService::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory) : AuthService{
			$repository = $factory->get(AuthRepository::class);
			$passwordManager = $factory->get(PasswordManager::class);
			$tokenService = $factory->get(TokenService::class);
			return new StandardAuthService($repository, $passwordManager, $tokenService);
		}
	});

};