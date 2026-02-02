<?php

namespace core\interfaces;

use core\exceptions\CustomException;
use core\http\Response;
use core\model\User;

abstract class Command {

	public function __construct() {}
	abstract public function execute(array $params, array $query = [], ?User $user = null) : Response;
    abstract public function getRequiredHttpMethod() : string;
    abstract public function getRequiredRoles() : array;
	
	public function requiresAuthentication(): bool {
		return true;
	}
	
	public function getRequiredBodyParameters(): array {
		return [];
	}

	public function getRequiredQueryParameters(): array {
		return [];
	}

	public function validateBody(array $body): void {
		$this->validate($body, $this->getRequiredBodyParameters(), 'body');
	}

	public function validateQueryParameters(array $query): void {
		$this->validate($query, $this->getRequiredQueryParameters(), 'query');
	}
	public function validateHttpMethod(string $method): void {
		if ($this->getRequiredHttpMethod() !== strtolower($method)) {
			throw new CustomException("Metodo $method non consentito", 405);
		}
	}

	private function validate(array $current, array $expected, string $type): void {
		foreach ($expected as $param) {
			if (empty($current[$param])) {
				$requiredString = implode(', ', $expected);
				throw new CustomException(
					"Parametri $type malformati, parametri richiesti: $requiredString", 
					400
				);
			}
		}
	}

}