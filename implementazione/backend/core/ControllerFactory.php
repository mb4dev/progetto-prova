<?php

final class ControllerFactory {
	public function __construct(private PDO $dbConnection) {}
	public function create($type): Controller {
		$controller = null;
		switch ($type) {
			case ControllerTypes::AUTH :
				$repository = new DefaultAuthRepository($this->dbConnection);
				$service = new DefaultAuthService($repository, new DefaultPasswordValidator());
				$controller = new AuthController($service);
				break;
			
			default: throw new InvalidArgumentException("Controller $type non esistente");
		}
		return $controller;	
	}
}