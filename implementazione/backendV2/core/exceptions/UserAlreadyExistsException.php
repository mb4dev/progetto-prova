<?php 

namespace core\exceptions;

class UserAlreadyExistsException extends CustomException {
	public function __construct() {
		parent::__construct("Utente già registrato", 409);
	}
}
