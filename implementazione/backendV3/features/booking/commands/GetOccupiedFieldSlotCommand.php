<?php


namespace features\booking\commands;

use core\http\HttpMethod;
use core\http\Response;
use core\interfaces\Command;
use core\model\Role;
use core\model\User;
use features\booking\fields\FieldsBookingService;


final class GetOccupiedFieldSlotCommand extends Command{

	public function __construct(private FieldsBookingService $service){
		parent::__construct();
	}
	public function getRequiredBodyParameters(): array{
		return ["resource_id", "resource_type", "date"];
	}	

	public function getRequiredHttpMethod(): string{
		return HttpMethod::POST->value;
	}

	public function getRequiredRoles(): array{
		return [Role::USER->value];
	}

	public function execute(array $params, array $query = [], ?User $user = null): Response	{
		$result = $this->service->getBooking($params["resource_type"],$params["resource_id"], $params["date"]);
		return new Response(200, true, $result);
	}
}