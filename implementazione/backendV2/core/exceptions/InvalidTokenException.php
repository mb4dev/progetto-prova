<?php

namespace core\exceptions;

class InvalidTokenException extends CustomException {
	public function __construct(string $message, int $httpcode) {
		parent::__construct($message, $httpcode);
	}
}