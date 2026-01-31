<?php

namespace core\exceptions;

class AuthException extends CustomException {
	public function __construct(string $message, int $httpCode) {
		parent::__construct($message, $httpCode);
	}
}