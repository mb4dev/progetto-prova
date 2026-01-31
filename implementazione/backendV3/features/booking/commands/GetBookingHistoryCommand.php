<?php


namespace features\booking\commands;

use core\http\HttpMethod;
use core\http\Response;
use core\interfaces\Command;
use core\model\Role;
use features\booking\fields\FieldsBookingService;


final class GetBookingHistoryCommand extends Command{

	public function __construct(private FieldsBookingService $service){
		parent::__construct();
	}
	public function getRequiredBodyParameters(): array{
		return [];
	}	

	public function getRequiredHttpMethod(): string{
		return HttpMethod::GET->value;
	}

	public function getRequiredRoles(): array{
		return [Role::USER->value];
	}

	public function getRequiredQueryParameters(): array{
		return ["id"];
	}

	public function requiresAuthentication(): bool{
		return true;
	}

	public function execute(array $params, array $query = []): Response	{
		return new Response(200, true, []);
	}
}