<?php

namespace booking;

use booking\commands\InsertFieldBookingCommand;
use booking\fields\FieldsBookingService;
use core\http\CommandController;

class BookingController extends CommandController {

	public function __construct(private FieldsBookingService $fieldService){
		parent::__construct();
	}
	protected function registerCommands(): void{
		$this->registry->register("field", new InsertFieldBookingCommand($this->fieldService));
	}
}
