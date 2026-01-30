<?php

use features\auth\PostgreAuthRepository;
use core\factory\Factory;
use core\factory\FactoryMethod;
use core\interfaces\AuthRepository;
use core\interfaces\CoursesRepository;
use core\interfaces\FieldsRepository;
use features\resources\PostgreCoursesRepository;
use features\resources\PostgreFieldsRepository;

return function(Factory $factory) {

	$factory->register(AuthRepository::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory) : AuthRepository{
			$dbconnection = $factory->get(PDO::class);	
			return new PostgreAuthRepository($dbconnection);
		}
	});


	$factory->register(FieldsRepository::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory) : FieldsRepository{
			$dbconnection = $factory->get(PDO::class);	
			return new PostgreFieldsRepository($dbconnection);
		}
	});

	$factory->register(CoursesRepository::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory) : CoursesRepository{
			$dbconnection = $factory->get(PDO::class);	
			return new PostgreCoursesRepository($dbconnection);
		}
	});

	


};