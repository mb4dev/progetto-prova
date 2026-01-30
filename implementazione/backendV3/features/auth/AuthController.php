<?php

namespace features\auth;

use core\interfaces\AuthService;
use core\interfaces\HttpSecurity;
use core\utility\CommandController;
use features\auth\commands\LoginCommand;
use features\auth\commands\RegisterCommand;

final class AuthController extends CommandController {

	public function __construct(private HttpSecurity $authMiddleware, private AuthService $service) {
		parent::__construct($authMiddleware);
	}
	protected function registerCommands(): void{
		$this->registry->register("login", new LoginCommand($this->service));
		$this->registry->register("register", new RegisterCommand($this->service));
	}	

}