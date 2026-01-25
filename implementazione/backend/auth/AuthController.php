<?php

namespace auth;

use core\CommandController;
use commands\auth\LoginCommand;
use commands\auth\RegisterCommand;

class AuthController extends CommandController {

	public function __construct() {
		parent::__construct();
	}

	public function getMiddlewares() : array {
		return [];
	}

	protected function registerCommands(): void {
		$this->registry->register('login', new LoginCommand());
		$this->registry->register('register', new RegisterCommand());
	}
}
