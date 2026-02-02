<?php 

namespace features\booking\commands;

use core\http\HttpMethod;
use core\http\Response;
use core\interfaces\Command;
use core\model\Role;
use core\model\User;
use features\booking\fields\FieldsBookingService;

final class InsertFieldBookingCommand extends Command {

	public function __construct( 
		private FieldsBookingService $service
	){
		parent::__construct();
	}

	public function getRequiredBodyParameters(): array{
		return ["user_id", "field_id", "data", "slot"];
	}

	public function getRequiredHttpMethod(): string{
		return HttpMethod::POST->value;
	}

	public function execute(array $params, array $query = [], ?User $user = null): Response{
		$result = $this->service->insertBooking($params["user_id"], $params["field_id"], $params["data"], $params["slot"]);
		return new Response(201, true, $result);
	}

	public function getRequiredRoles(): array{
		return [Role::USER->value];
	}
}