<?php

namespace features\auth\commands;

use core\http\HttpMethod;
use core\http\Response;
use core\interfaces\AuthService;
use core\interfaces\Command;
use core\model\Role;
use core\model\User;

class RegisterCommand extends Command {

	public function __construct(private AuthService $service){
		parent::__construct();
	}

	public function execute(array $params, array $query = [], ?User $user = null) : Response{
		$result = $this->service->register(
			$params["name"], 
			$params["email"], 
			$params["password"], 
			Role::tryFrom($params["role"])
		);
		return new Response(200, true, $result);
	}

	public function getRequiredHttpMethod(): string{
		return HttpMethod::POST->value;
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
}