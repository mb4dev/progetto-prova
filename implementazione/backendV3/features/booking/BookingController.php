<?php

namespace features\booking;

use core\interfaces\HttpSecurity;
use core\utility\CommandController;
use features\booking\commands\GetBookingHistoryCommand;
use features\booking\commands\GetOccupiedFieldSlotCommand;
use features\booking\commands\InsertFieldBookingCommand;
use features\booking\fields\FieldsBookingService;

class BookingController extends CommandController{

	public function __construct(HttpSecurity $middleware, private FieldsBookingService $fieldService ){
		parent::__construct($middleware);
	}
	protected function registerCommands(): void{
		$this->registry->register("field", new InsertFieldBookingCommand($this->fieldService));
		$this->registry->register("occupied", new GetOccupiedFieldSlotCommand($this->fieldService));
		$this->registry->register("history", new GetBookingHistoryCommand($this->fieldService));
	}
}
