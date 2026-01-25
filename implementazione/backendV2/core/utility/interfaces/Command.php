<?php

namespace core\utility\interfaces;

use core\http\Response;

abstract class Command {

	public function __construct() {}
	abstract public function execute(array $params, array $query = []) : Response;
    abstract public function getRequiredBodyParameters(): array;
	abstract public function getRequiredHttpMethod() : string;
	
	public function validateBody(array $body) : bool {
		print_r($body);
		echo "<br>";
		$required = $this->getRequiredBodyParameters();
		foreach ($required as $param) {
			if(empty($body[$param]))
				return false;
		}
		return true;
	}
	public function validateHttpMethod(string $method): bool{
		echo "metodo: $method" . "<br>";
		return $this->getRequiredHttpMethod() === strtolower($method);
	}
}