<?php

namespace bookings;

use core\CommandController;
use commands\bookings\GetOccupiedSlotsCommand;
use core\middlewares\AuthMiddleware;

class BookingsController extends CommandController {

	public function __construct() {
		parent::__construct();
	}

	public function getMiddlewares() : array {
		return [
			AuthMiddleware::class
		];
	}

	protected function registerCommands(): void {
		$this->registry->register('occupied_slots', new GetOccupiedSlotsCommand());
	}
}

enum ResourceType : string {
	case CAMPO = "campo";
	case CORSO = "corso";
}