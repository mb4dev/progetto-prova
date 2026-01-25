<?php

namespace core\http;


use auth\AuthController;
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
		return new AuthController();
	}
}