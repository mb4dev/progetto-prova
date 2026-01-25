<?php

class BookingsController extends Controller {

	public function __construct(
		private BookingsService $bookingsService
	) {
		parent::__construct();
	}

	public function getMiddlewares() : array {
		return [
			AuthMiddleware::class
		];
	}

	public function resolveAction(string $action): Response {
		$body = $this->getBody();
		return match (strtolower($action)) {
			"occupied_slots" => $this->getSlots($body),
			default => new Response(404, false, ["error" => "Action non trovata"])
		};
	}


	private function getSlots(array $body): Response {
		if ($_SERVER['REQUEST_METHOD'] !== "POST") {
			return new Response(405, false, ["error" => "Metodo non consentito"]);
		}
		if (empty($body["resource_id"]) || empty($body["resource_type"]) || empty($body["start_day"])) {
			return new Response(400, false, ["error" => "Parametri mancanti"]);
		}

		$resourceType = ResourceType::tryFrom($body["resource_type"]);
		if (!$resourceType) {
			return new Response(400, false, ["error" => "Tipo risorsa non valido"]);
		}

		return $this->bookingsService->getOccupiedlots($resourceType->value, $body["resource_id"], $body["start_day"]);
	}
}

enum ResourceType : string {
	case CAMPO = "campo";
	case CORSO = "corso";
}