<?php

namespace features\booking\controller;

use core\interfaces\BookingHistoryRepository;
use core\interfaces\HttpSecurity;
use core\interfaces\Selector;
use core\utility\CommandController;
use features\booking\commands\GetBookingHistoryCommand;
use features\booking\commands\GetOccupiedSlotsCommand;
use features\booking\commands\InsertBookingCommand;

class BookingController extends CommandController {

	public function __construct(
		HttpSecurity $middleware,
		private Selector $bookingSelector,
		private BookingHistoryRepository $historyRepo
	) {
		parent::__construct($middleware);
	}

	protected function registerCommands(): void {
		$this->registry->register("insert", new InsertBookingCommand($this->bookingSelector));
		$this->registry->register("occupied", new GetOccupiedSlotsCommand($this->bookingSelector));
		$this->registry->register("history", new GetBookingHistoryCommand($this->historyRepo));
	}
}
