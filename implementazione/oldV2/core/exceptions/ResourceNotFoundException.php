<?php

namespace core\exceptions;

class ResourceNotFoundException extends CustomException {
	public function __construct(string $message = "Risorsa non esistente nel database") {
		parent::__construct($message, 404);
	}
}