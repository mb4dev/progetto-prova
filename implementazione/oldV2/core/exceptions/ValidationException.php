<?php 

namespace core\exceptions;

class ValidationException extends CustomException {
	public function __construct(string $message, int $httpCode) {
		parent::__construct($message, $httpCode);
	}
}