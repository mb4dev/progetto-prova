<?php

namespace core\utility\interfaces;

use core\http\Response;

abstract class Command {

	public function __construct() {}
	abstract public function execute(array $params, array $query = []) : Response;
    abstract public function getRequiredBodyParameters(): array;
	abstract public function getRequiredHttpMethod() : string;
	
	public function validateBody(array $body) : bool {
		$required = $this->getRequiredBodyParameters();
		foreach ($required as $param) {
			if(empty($body[$param]))
				return false;
		}
		return true;
	}
	public function validateHttpMethod(string $method): bool{
		return $this->getRequiredHttpMethod() === strtolower($method);
	}
}