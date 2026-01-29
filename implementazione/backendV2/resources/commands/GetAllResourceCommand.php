<?php

namespace resources\commands;

use core\exceptions\InvalidSportTypeException;
use core\http\HttpMethods;
use core\http\Response;
use core\model\Role;
use core\utility\interfaces\Command;
use resources\interfaces\ResourceService;
use resources\ResourceType;
final class GetAllResourceCommand extends Command {

	public function __construct(private ResourceService $service){
		parent::__construct();
	}

	public function execute(array $params, array $query = []): Response{

		$type = ResourceType::tryFrom($query["type"]);
		if ($type === null) throw new InvalidSportTypeException();
		
		$result = $this->service->getAllResourcesByType($type); 
		return new Response(200, true, $result);
	}

	public function getRequiredBodyParameters(): array{
		return [];
	}

	public function getRequiredQueryParameters(): array{
		return ["type"];
	}

	public function getRequiredHttpMethod(): string{
		return HttpMethods::GET->value;
	}

	public function requiresAuthentication(): bool{
		return true;
	}

	public function getRequiredRoles(): array{
		return [Role::USER->value, Role::ADMIN->value];
	}

	public function getMiddleware(): array {
		// Il middleware di autenticazione verrà iniettato dal container
		return [];
	}
}