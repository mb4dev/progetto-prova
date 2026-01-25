<?php

namespace auth;

use commands\auth\LoginCommand;
use commands\auth\RegisterCommand;
use core\http\CommandController;

final class AuthController extends CommandController {
	protected function registerCommands(): void{
		$this->registry->register("login", new LoginCommand());
		$this->registry->register("register", new RegisterCommand());
	}	
}