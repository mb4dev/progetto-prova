<?php

use core\factory\Factory;
use core\factory\FactoryMethod;
use core\interfaces\AuthRepository;
use core\interfaces\BookingRepository;
use core\interfaces\CoursesRepository;
use core\interfaces\FieldsRepository;
use core\interfaces\HttpSecurity;
use core\interfaces\PasswordManager;
use core\interfaces\TokenService;
use core\utility\CommandController;
use core\utility\Context;
use features\auth\controller\AuthController;
use features\auth\factory\AuthStrategyFactory;
use features\auth\repository\PostgreAuthRepository;
use features\booking\BookingController;
use features\booking\fields\FieldBookingRepository;
use features\booking\fields\FieldsBookingService;
use features\booking\PostgreBookingRepository;
use features\resources\controller\ResourceController;
use features\resources\ResourceRegistry;
use features\resources\repository\PostgreCoursesRepository;
use features\resources\repository\PostgreFieldsRepository;

return function(Factory $factory) {
	registerRepositories($factory);
	registerServices($factory);
	registerFactories($factory);
	registerResourceRegistry($factory);
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

	$factory->register(PostgreFieldsRepository::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory): PostgreFieldsRepository {
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

	$factory->register(PostgreCoursesRepository::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory): PostgreCoursesRepository {
			$dbconnection = $factory->get(PDO::class);
			return new PostgreCoursesRepository($dbconnection);
		}
	});

	$factory->register(BookingRepository::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory) : BookingRepository{
			$dbconnection = $factory->get(PDO::class);
			return new PostgreBookingRepository($dbconnection);
		}
	});
}


function registerFactories(Factory $factory) : void {
	$factory->register(AuthStrategyFactory::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory): AuthStrategyFactory {
			return new AuthStrategyFactory(
				$factory->get(AuthRepository::class),
				$factory->get(PasswordManager::class),
				$factory->get(TokenService::class)
			);
		}
	});
}


function registerResourceRegistry(Factory $factory): void {
	$factory->register(ResourceRegistry::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory): ResourceRegistry {
			$registry = new ResourceRegistry();

			$registry->register("campi", PostgreFieldsRepository::class);
			$registry->register("corsi", PostgreCoursesRepository::class);

			return $registry;
		}
	});
}


function registerServices(Factory $factory): void {

	$factory->register(\core\interfaces\BookingService::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory): \core\interfaces\BookingService {
			$fieldsRepo = $factory->get(FieldsRepository::class);
			$bookingRepo = $factory->get(BookingRepository::class);
			return new FieldsBookingService($fieldsRepo, $bookingRepo);
		}
	});
}

function registerControllers(Factory $factory): void {

	$factory->register(AuthController::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory): CommandController {
			$context = $factory->get(Context::class);
			$strategyFactory = $factory->get(AuthStrategyFactory::class);
			$security = $factory->get(HttpSecurity::class);
			return new AuthController($security, $context, $strategyFactory);
		}
	});

	$factory->register(ResourceController::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory): CommandController {
			$resourceRegistry = $factory->get(ResourceRegistry::class);
			$security = $factory->get(HttpSecurity::class);

			return new ResourceController($security, $resourceRegistry, $factory);
		}
	});


	$factory->register(BookingController::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory): CommandController {
			$service = $factory->get(\core\interfaces\BookingService::class);
			$security = $factory->get(HttpSecurity::class);

			return new BookingController($security, $service);
		}
	});
}
