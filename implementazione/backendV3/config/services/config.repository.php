<?php

use features\auth\PostgreAuthRepository;
use core\factory\Factory;
use core\factory\FactoryMethod;
use core\interfaces\AuthRepository;

return function(Factory $factory) {

	$factory->register(AuthRepository::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory) : AuthRepository{
			$dbconnection = $factory->get(PDO::class);	
			return new PostgreAuthRepository($dbconnection);
		}
	});

};