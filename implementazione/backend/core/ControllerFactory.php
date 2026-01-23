<?php

final class ControllerFactory {
	public function __construct(private PDO $dbConnection) {}
	public function create($type): Controller {
		$controller = null;
		switch ($type) {
			case ControllerTypes::AUTH:
				$repository = new DefaultAuthRepository($this->dbConnection);
				$service = new DefaultAuthService($repository, new DefaultPasswordValidator(), new MockJwtTokenManager());
				$controller = new AuthController($service);
				break;
			case ControllerTypes::SPORTS:
				$repository = new DefaultSportsRepository($this->dbConnection);
				$service = new DefaultSportsService($repository);
				$controller = new SportsController($service);
				break;
			case ControllerTypes::BOOKINGS:
				$repository = new DefaultBookingsRepository($this->dbConnection);
				$service = new DefaultBookingsService($repository);
				$controller = new BookingsController($service);
				break;
			default: throw new InvalidArgumentException("Controller {$type->value} non esistente");
		}
		return $controller;	
	}
}