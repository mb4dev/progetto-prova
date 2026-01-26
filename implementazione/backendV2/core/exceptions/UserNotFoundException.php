<?php

namespace core\exceptions;


class UserNotFoundException extends CustomException {
	public function __construct() {
		parent::__construct("Utente non trovato", 404);
	}
}