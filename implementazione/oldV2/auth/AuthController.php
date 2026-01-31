<?php

namespace auth;

use auth\interfaces\AuthService;
use auth\commands\LoginCommand;
use auth\commands\RegisterCommand;
use auth\interfaces\AuthRepository;
use core\http\CommandController;

final class AuthController extends CommandController {

	public function __construct(AuthRepository $authRepo, private AuthService $service) {
		parent::__construct($authRepo);
	}
	protected function registerCommands(): void{
		$this->registry->register("login", new LoginCommand($this->service));
		$this->registry->register("register", new RegisterCommand($this->service));
	}	
}