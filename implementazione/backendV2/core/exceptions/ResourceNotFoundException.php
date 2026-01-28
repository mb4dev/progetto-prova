<?php

namespace core\exceptions;

class ResourceNotFoundException extends CustomException {
	public function __construct() {
		parent::__construct("Risorsa non trovata", 404);
	}
}