<?php

namespace booking;

use auth\PostgreAuthRepository;
use booking\fields\FieldBookingRepository;
use booking\fields\FieldsBookingService;
use core\factory\interfaces\ControllerCreator;
use PDO;
use core\http\CommandController;
use resources\PostgreFieldsRepository;

final class BookingControllerCreator implements ControllerCreator {
	public function create(PDO $dbConnection): CommandController{
		$bookingRepo = new FieldBookingRepository($dbConnection);
		$authRepo = new PostgreAuthRepository($dbConnection);
		$fieldRepo = new PostgreFieldsRepository($dbConnection);
		$fieldService = new FieldsBookingService( $fieldRepo,  $bookingRepo);

		return new BookingController($authRepo, $fieldService);
	}

}