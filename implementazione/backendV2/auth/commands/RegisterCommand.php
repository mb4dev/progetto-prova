<?php

namespace auth\commands;

use auth\interfaces\AuthService;
use core\http\HttpMethods;
use core\http\Response;
use core\model\Role;
use core\utility\interfaces\Command;

class RegisterCommand extends Command {

	public function __construct(private AuthService $service){
		parent::__construct();
	}

	public function execute(array $params, array $query = []) : Response{
		$result = $this->service->register(
			$params["name"], 
			$params["email"], 
			$params["password"], 
			Role::tryFrom($params["role"])
		);
		return new Response(200, true, $result);
	}

	public function getRequiredHttpMethod(): string{
		return HttpMethods::POST->value;
	}

	public function getRequiredQueryParameters(): array{
		return [];
	}

	public function getRequiredBodyParameters(): array{
		return ["name", "email", "password", "role"];
	}

	public function requiresAuthentication(): bool{
		return false;
	}

	public function getRequiredRoles(): array{
		return [Role::USER->value, Role::ADMIN->value];
	}

	public function getMiddleware(): array {
		return []; // Register non richiede middleware
	}
}