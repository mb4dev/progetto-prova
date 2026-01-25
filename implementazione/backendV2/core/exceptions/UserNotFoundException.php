<?php

namespace core\exceptions;
use Exception;

class UserNotFoundException extends Exception {
	public function __construct($message = "Utente non trovato") {
		parent::__construct($message);
	}
}