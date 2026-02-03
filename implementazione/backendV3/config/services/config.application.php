<?php

use core\factory\Factory;
use core\factory\FactoryMethod;
use core\interfaces\AuthRepository;
use core\interfaces\BookingRepository;
use core\interfaces\CoursesRepository;
use core\interfaces\FieldsRepository;
use core\interfaces\HttpSecurity;
use core\interfaces\PasswordManager;
use core\interfaces\Selector;
use core\interfaces\Strategy;
use core\interfaces\TokenService;
use core\utility\CommandController;
use features\auth\controller\AuthController;
use features\auth\selectors\SimpleLoginStrategySelector;
use features\auth\selectors\SimpleRegisterStrategySelector;
use features\auth\repository\PostgreAuthRepository;
use features\auth\strategies\EmailLoginStrategy;
use features\auth\strategies\EmailRegisterStrategy;
use features\booking\BookingController;
use features\booking\fields\FieldsBookingService;
use features\booking\PostgreBookingRepository;
use features\resources\controller\ResourceController;
use features\resources\selectors\SimpleResourceSelector;
use features\resources\repository\PostgreCoursesRepository;
use features\resources\repository\PostgreFieldsRepository;

return function(Factory $factory) {
	registerRepositories($factory);
	registerServices($factory);
	registerSelectors($factory);
	registerControllers($factory);
	registerStrategies($factory);
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


function registerSelectors(Factory $factory): void {
	$factory->register(SimpleLoginStrategySelector::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory): Selector {
			return new SimpleLoginStrategySelector($factory);
		}
	});

	$factory->register(SimpleRegisterStrategySelector::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory): Selector {
			return new SimpleRegisterStrategySelector($factory);
		}
	});

	$factory->register(SimpleResourceSelector::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory): Selector {
			return new SimpleResourceSelector($factory);
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
			$loginSelector = $factory->get(SimpleLoginStrategySelector::class);
			$registerSelector = $factory->get(SimpleRegisterStrategySelector::class);
			$security = $factory->get(HttpSecurity::class);
			return new AuthController($security, $loginSelector, $registerSelector);
		}
	});

	$factory->register(ResourceController::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory): CommandController {
			$resourceSelector = $factory->get(SimpleResourceSelector::class);
			$security = $factory->get(HttpSecurity::class);

			return new ResourceController($security, $resourceSelector);
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


function registerStrategies(Factory $factory){
	$factory->register(EmailLoginStrategy::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory): Strategy {
			$repository = $factory->get(AuthRepository::class);
			$passwordManager = $factory->get(PasswordManager::class);
			$tokenService = $factory->get(TokenService::class);
			return new EmailLoginStrategy($repository, $passwordManager, $tokenService);
		}
	});	


	$factory->register(EmailRegisterStrategy::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory): Strategy {
			$repository = $factory->get(AuthRepository::class);
			$passwordManager = $factory->get(PasswordManager::class);
			$tokenService = $factory->get(TokenService::class);
			return new EmailRegisterStrategy($repository, $passwordManager, $tokenService);
		}
	});	
}