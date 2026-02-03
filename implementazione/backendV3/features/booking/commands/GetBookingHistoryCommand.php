<?php

namespace features\booking\commands;

use core\exceptions\CustomException;
use core\http\HttpMethod;
use core\http\Response;
use core\interfaces\BookingHistoryRepository;
use core\interfaces\Command;
use core\model\Role;
use core\model\User;

final class GetBookingHistoryCommand extends Command {

	public function __construct(
		private BookingHistoryRepository $historyRepo
	) {
		parent::__construct();
	}
	
	public function getRequiredHttpMethod(): string {
		return HttpMethod::GET->value;
	}

	public function getRequiredRoles(): array {
		return [Role::USER->value];
	}

	public function execute(array $params, array $query = [], ?User $user = null): Response {
		$userId = $user->id ?? throw new CustomException("Utente richiesto per questa operazione", 400);
		$result = $this->historyRepo->getHistoryForUser($userId);
		return new Response(200, true, $result);
	}
}
