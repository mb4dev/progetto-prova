<?php

namespace auth;

use core\factory\interfaces\ControllerCreator;
use PDO;
use core\http\CommandController;
use core\utility\DefaultPasswordManager;
use core\utility\jwt\MyJwtService;

final class AuthControllerCreator implements ControllerCreator {
	public function create(PDO $dbConnection): CommandController{
		$repository = new PostgreAuthRepository($dbConnection);

		$temp = new MyJwtService();

		$service = new DefaultAuthService($repository, new DefaultPasswordManager(), $temp);
		return new AuthController($repository, $service);
		
	}
}