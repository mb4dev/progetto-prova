<?php

namespace core\exceptions;

class BookingConflictException extends CustomException {
	public function __construct(string $message = "la risorsa scelta è già stata prenotata") {
		parent::__construct($message, 409);
	}
}