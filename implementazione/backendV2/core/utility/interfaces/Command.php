<?php

namespace core\utility\interfaces;

use core\exceptions\ValidationException;
use core\http\Response;

abstract class Command {

	public function __construct() {}
	abstract public function execute(array $params, array $query = []) : Response;
    abstract public function getRequiredBodyParameters(): array;
	abstract public function getRequiredQueryParameters(): array;
	abstract public function getRequiredHttpMethod() : string;
	
	public function validateBody(array $body): void {
		$this->validate($body, $this->getRequiredBodyParameters(), 'body');
	}

	public function validateQueryParameters(array $query): void {
		$this->validate($query, $this->getRequiredQueryParameters(), 'query');
	}

	private function validate(array $current, array $expected, string $type): void {
		foreach ($expected as $param) {
			if (empty($current[$param])) {
				$requiredString = implode(', ', $expected);
				throw new ValidationException(
					"Parametri $type malformati, parametri richiesti: $requiredString", 
					400
				);
			}
		}
	}

	public function validateHttpMethod(string $method): void {
		if ($this->getRequiredHttpMethod() !== strtolower($method)) {
			throw new ValidationException("Metodo $method non consentito", 405);
		}
	}

}