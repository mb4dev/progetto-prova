<?php

namespace features\booking\commands;

use core\exceptions\CustomException;
use core\http\HttpMethod;
use core\http\Response;
use core\interfaces\Command;
use core\interfaces\Selector;
use core\model\Role;
use core\model\User;

final class InsertBookingCommand extends Command {

	public function __construct(
		private Selector $bookingSelector
	) {
		parent::__construct();
	}

	public function getRequiredBodyParameters(): array {
		return ["resource_type", "resource_id", "date", "slot"];
	}

	public function getRequiredHttpMethod(): string {
		return HttpMethod::POST->value;
	}

	public function execute(array $params, array $query = [], ?User $user = null): Response {
		$userId = $user->id ?? throw new CustomException("Utente richiesto per questa operazione", 400);
		$strategy = $this->bookingSelector->select($params["resource_type"]);
		$result = $strategy->insertBooking(
			$userId,
			$params["resource_id"],
			$params["date"],
			$params["slot"]
		);
		return new Response(201, true, $result);
	}

	public function getRequiredRoles(): array {
		return [Role::USER->value];
	}
}
