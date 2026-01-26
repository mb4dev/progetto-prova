<?php

namespace core\exceptions;
use Exception;

class CustomException extends Exception{
	public function __construct(string $message = "", int $httpCode){
		parent::__construct($message, $httpCode);
	}
}


