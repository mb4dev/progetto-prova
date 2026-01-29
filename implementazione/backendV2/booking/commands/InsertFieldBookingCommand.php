<?php 

namespace booking\commands;

use booking\fields\FieldsBookingService;
use core\http\HttpMethods;
use core\http\Response;
use core\utility\interfaces\Command;


final class InsertFieldBookingCommand extends Command {

	public function __construct( 
		private FieldsBookingService $service
	){
		parent::__construct();
	}

	public function getRequiredBodyParameters(): array{
		return ["user_id", "field_id", "data", "slot"];
	}

	public function getRequiredQueryParameters(): array{
		return [];
	}

	public function getRequiredHttpMethod(): string{
		return HttpMethods::POST->value;
	}

	public function execute(array $params, array $query = []): Response{
		$result = $this->service->insertBooking($params["user_id"], $params["field_id"], $params["data"], $params["slot"]);
		return new Response(201, true, $result);
	}
}