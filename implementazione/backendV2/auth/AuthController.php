<?php

namespace auth;

use auth\interfaces\AuthService;
use auth\commands\LoginCommand;
use auth\commands\RegisterCommand;
use core\http\CommandController;

final class AuthController extends CommandController {

	public function __construct(private AuthService $service) {
		parent::__construct();
	}
	protected function registerCommands(): void{
		$this->registry->register("login", new LoginCommand($this->service));
		$this->registry->register("register", new RegisterCommand($this->service));
	}	
}