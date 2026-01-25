<?php

namespace sport;

use core\CommandController;
use commands\sports\GetSportsCommand;
use core\middlewares\AuthMiddleware;

class SportsController extends CommandController {

	public function __construct() {
		parent::__construct();
	}

	public function getMiddlewares() : array {
		return [
			AuthMiddleware::class
		];
	}

	protected function registerCommands(): void {
		$this->registry->register('', new GetSportsCommand()); // Default action
		$this->registry->register('get_sports', new GetSportsCommand()); // Alternative name
	}
}