<?php

namespace features\resources\commands;

use core\exceptions\CustomException;
use core\http\HttpMethod;
use core\http\Response;
use core\interfaces\Command;
use core\interfaces\ResourceService;
use core\model\Role;
use core\model\User;
use features\resources\ResourceType;

final class GetAllResourceCommand extends Command {

	public function __construct(private ResourceService $service){
		parent::__construct();
	}

	public function execute(array $params, array $query = [], ?User $user = null): Response{

		$type = ResourceType::tryFrom($query["type"]);
		if ($type === null) throw new CustomException("parametro type=$type non valido", 400);
		
		$result = $this->service->getAllResourcesByType($type); 
		return new Response(200, true, $result);
	}

	public function getRequiredQueryParameters(): array{
		return ["type"];
	}

	public function getRequiredHttpMethod(): string{
		return HttpMethod::GET->value;
	}

	public function getRequiredRoles(): array{
		return [Role::USER->value, Role::ADMIN->value];
	}
}