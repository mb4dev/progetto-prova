<?php

namespace commands\resources;

use core\exceptions\InvalidSportTypeException;
use core\http\HttpMethods;
use core\http\Response;
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

}