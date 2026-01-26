<?php

namespace core\utility;


use auth\AuthController;
use auth\DefaultAuthRepository;
use auth\DefaultAuthService;
use core\http\CommandController;
use core\http\ControllerTypes;
use core\utility\interfaces\JwtTokenManager;
use PDO;
use InvalidArgumentException;

final class ControllerFactory {
	public function __construct(private PDO $dbConnection) {}
	public function create($type) : CommandController{
		$controller = null;
		switch ($type) {
			case ControllerTypes::AUTH:
				$controller = $this->createAuthController();
				break;
			case ControllerTypes::BOOKINGS:
				break;
			default: throw new InvalidArgumentException("Controller {$type->value} non esistente");
		}
		return $controller;	
	}

	private function createAuthController() : CommandController{
		$repository = new DefaultAuthRepository($this->dbConnection);

		$temp = new class implements JwtTokenManager {
			public function encode(){}
			public function decode(){}
		};

		$service = new DefaultAuthService($repository, new DefaultPasswordManager(), $temp);
		return new AuthController($service);
	}
}