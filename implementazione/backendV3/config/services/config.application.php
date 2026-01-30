<?php

use core\factory\Factory;
use core\factory\FactoryMethod;
use core\interfaces\AuthRepository;
use core\interfaces\AuthService;
use core\interfaces\BookingRepository;
use core\interfaces\BookingService;
use core\interfaces\CoursesRepository;
use core\interfaces\FieldsRepository;
use core\interfaces\HttpSecurity;
use core\interfaces\PasswordManager;
use core\interfaces\ResourceService;
use core\interfaces\TokenService;
use core\utility\CommandController;
use features\auth\AuthController;
use features\auth\PostgreAuthRepository;
use features\auth\StandardAuthService;
use features\booking\BookingController;
use features\booking\fields\FieldBookingRepository;
use features\booking\fields\FieldsBookingService;
use features\resources\PostgreCoursesRepository;
use features\resources\PostgreFieldsRepository;
use features\resources\ResourceController;
use features\resources\StandardResourceService;

return function(Factory $factory) {
	registerRepositories($factory);
	registerServices($factory);
	registerControllers($factory);
};

function registerRepositories(Factory $factory): void {
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

	$factory->register(BookingRepository::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory) : BookingRepository{
			$dbconnection = $factory->get(PDO::class);
			return new FieldBookingRepository($dbconnection);
		}
	});
}

function registerServices(Factory $factory): void {
	$factory->register(AuthService::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory) : AuthService{
			$repository = $factory->get(AuthRepository::class);
			$passwordManager = $factory->get(PasswordManager::class);
			$tokenService = $factory->get(TokenService::class);
			return new StandardAuthService($repository, $passwordManager, $tokenService);
		}
	});

	$factory->register(ResourceService::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory) : ResourceService{
			$fieldRepo = $factory->get(FieldsRepository::class);
			$coursesRepo = $factory->get(CoursesRepository::class);
			return new StandardResourceService($fieldRepo, $coursesRepo);
		}
	});

	$factory->register(BookingService::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory) : BookingService{
			$fieldsRepo = $factory->get(FieldsRepository::class);
			$bookingRepo = $factory->get(BookingRepository::class);
			return new FieldsBookingService($fieldsRepo, $bookingRepo);
		}
	});
}

function registerControllers(Factory $factory): void {
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

	$factory->register(BookingController::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory) : CommandController{
			$service = $factory->get(BookingService::class);
			$security = $factory->get(HttpSecurity::class);

			return new BookingController($security, $service);
		}
	});
}
