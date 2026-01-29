<?php

namespace auth\commands;

use auth\interfaces\AuthService;
use core\http\HttpMethods;
use core\http\Response;
use core\utility\interfaces\Command;

class LoginCommand extends Command {
	

	public function __construct(private AuthService $service){
		parent::__construct();
	}

	public function execute(array $params, array $query = []) : Response{
		$result = $this->service->login($params["email"], $params["password"]);
		return new Response(200, true, $result);
	}

	public function getRequiredHttpMethod(): string{
		return HttpMethods::POST->value;
	}

	public function getRequiredQueryParameters(): array{
		return [];
	}
	
	public function getRequiredBodyParameters(): array{
		return ["email", "password"];
	}
}