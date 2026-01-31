<?php

namespace booking;

use auth\interfaces\AuthRepository;
use booking\commands\InsertFieldBookingCommand;
use booking\fields\FieldsBookingService;
use core\http\CommandController;

class BookingController extends CommandController {

	public function __construct(AuthRepository $authRepo, private FieldsBookingService $fieldService){
		parent::__construct($authRepo);
	}
	protected function registerCommands(): void{
		$this->registry->register("field", new InsertFieldBookingCommand($this->fieldService));
		}
}
