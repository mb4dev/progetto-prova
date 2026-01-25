<?php 

namespace core\exceptions;
use Exception;

class UserAlreadyExistsException extends Exception {
	public function __construct($message = "Utente già registrato") {
		parent::__construct($message);
	}
}
