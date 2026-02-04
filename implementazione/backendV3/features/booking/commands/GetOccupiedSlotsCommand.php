<?php

namespace features\booking\commands;

use core\http\HttpMethod;
use core\http\Response;
use core\interfaces\Command;
use core\interfaces\Selector;
use core\model\Role;
use core\model\User;

final class GetOccupiedSlotsCommand extends Command {

	public function __construct(
		private Selector $bookingSelector
	) {
		parent::__construct();
	}

	public function getRequiredBodyParameters(): array {
		return [];
	}

	public function getRequiredQueryParameters(): array {
		return ["resource_type", "resource_id", "date"];
	}

	public function getRequiredHttpMethod(): string {
		return HttpMethod::GET->value;
	}

	public function execute(array $params, array $query = [], ?User $user = null): Response {
		$strategy = $this->bookingSelector->select($query["resource_type"]);
		$result = $strategy->getOccupiedSlots((int) $query["resource_id"], $query["date"]);
		return new Response(200, true, $result);
	}

	public function getRequiredRoles(): array {
		return [Role::USER->value];
	}
}
