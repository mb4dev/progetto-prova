<?php

namespace commands\auth;

use core\http\HttpMethods;
use core\http\Response;
use core\utility\interfaces\Command;

class RegisterCommand extends Command {

	public function __construct(){
		parent::__construct();
	}

	public function execute(array $params, array $query = []) : Response{
		echo "execute()" . "<br>";
		return new Response(200, true, []);
	}

	public function getRequiredHttpMethod(): string{
		return HttpMethods::POST->value;
	}

	public function getRequiredBodyParameters(): array{
		return ["name", "email", "password", "role"];
	}
}