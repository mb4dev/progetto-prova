<?php

use core\factory\Factory;
use core\factory\FactoryMethod;
use core\interfaces\AuthRepository;
use core\interfaces\BookingHistoryRepository;
use core\interfaces\BookingStrategy;
use core\interfaces\CourseBookingRepository;
use core\interfaces\FieldBookingRepository;
use core\interfaces\CoursesRepository;
use core\interfaces\FieldsRepository;
use core\interfaces\HttpSecurity;
use core\interfaces\PasswordManager;
use core\interfaces\Selector;
use core\interfaces\Strategy;
use core\interfaces\SubscriptionsRepository;
use core\interfaces\TokenService;
use core\utility\CommandController;
use features\auth\controller\AuthController;
use features\auth\selectors\LoginStrategySelector;
use features\auth\selectors\RegisterStrategySelector;
use features\auth\repository\PostgreAuthRepository;
use features\auth\strategies\EmailLoginStrategy;
use features\auth\strategies\EmailRegisterStrategy;
use features\booking\controller\BookingController;
use features\booking\selectors\BookingStrategySelector;
use features\booking\repository\PostgreBookingHistoryRepository;
use features\booking\repository\PostgreFieldBookingRepository;
use features\booking\repository\PostgreCourseBookingRepository;
use features\booking\strategies\FieldBookingStrategy;
use features\booking\strategies\CourseBookingStrategy;
use features\resources\controller\ResourceController;
use features\resources\selectors\SimpleResourceSelector;
use features\resources\repository\PostgreCoursesRepository;
use features\resources\repository\PostgreFieldsRepository;
use features\resources\repository\PostgreSubscriptionsRepository;

return function(Factory $factory) {
	registerRepositories($factory);
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

	$factory->register(CoursesRepository::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory) : CoursesRepository{
			$dbconnection = $factory->get(PDO::class);
			return new PostgreCoursesRepository($dbconnection);
		}
	});

	$factory->register(SubscriptionsRepository::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory) : SubscriptionsRepository{
			$dbconnection = $factory->get(PDO::class);	
			return new PostgreSubscriptionsRepository($dbconnection);
		}
	});

	// Booking repositories
	$factory->register(BookingHistoryRepository::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory): BookingHistoryRepository {
			$dbconnection = $factory->get(PDO::class);
			return new PostgreBookingHistoryRepository($dbconnection);
		}
	});

	$factory->register(FieldBookingRepository::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory): FieldBookingRepository {
			$dbconnection = $factory->get(PDO::class);
			return new PostgreFieldBookingRepository($dbconnection);
		}
	});

	$factory->register(CourseBookingRepository::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory): CourseBookingRepository {
			$dbconnection = $factory->get(PDO::class);
			return new PostgreCourseBookingRepository($dbconnection);
		}
	});
}


function registerSelectors(Factory $factory): void {
	$factory->register(LoginStrategySelector::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory): Selector {
			return new LoginStrategySelector($factory);
		}
	});

	$factory->register(RegisterStrategySelector::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory): Selector {
			return new RegisterStrategySelector($factory);
		}
	});

	$factory->register(SimpleResourceSelector::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory): Selector {
			return new SimpleResourceSelector($factory);
		}
	});

	$factory->register(BookingStrategySelector::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory): Selector {
			return new BookingStrategySelector($factory);
		}
	});
}


function registerControllers(Factory $factory): void {

	$factory->register(AuthController::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory): CommandController {
			$loginSelector = $factory->get(LoginStrategySelector::class);
			$registerSelector = $factory->get(RegisterStrategySelector::class);
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
			$bookingSelector = $factory->get(BookingStrategySelector::class);
			$historyRepo = $factory->get(BookingHistoryRepository::class);
			$security = $factory->get(HttpSecurity::class);

			return new BookingController($security, $bookingSelector, $historyRepo);
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

	// Booking strategies
	$factory->register(FieldBookingStrategy::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory): BookingStrategy {
			$fieldsRepo = $factory->get(FieldsRepository::class);
			$bookingRepo = $factory->get(FieldBookingRepository::class);
			return new FieldBookingStrategy($fieldsRepo, $bookingRepo);
		}
	});

	$factory->register(CourseBookingStrategy::class, new class implements FactoryMethod {
		public function __invoke(Factory $factory): BookingStrategy {
			$coursesRepo = $factory->get(CoursesRepository::class);
			$bookingRepo = $factory->get(CourseBookingRepository::class);
			return new CourseBookingStrategy($coursesRepo, $bookingRepo);
		}
	});
}
