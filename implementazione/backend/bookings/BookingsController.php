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
			//"add_field" => $this->addFieldToCart($body),
			//"checkout_fields" => $this->checkoutFields($body),
			//"book_course" => $this->bookCourse($body),
			"available_slots" => $this->getAvailableSlots($body),
			default => new Response(404, false, ["error" => "Action non trovata"])
		};
	}

	/*
	private function addFieldToCart(array $body): Response {
		if ($_SERVER['REQUEST_METHOD'] !== "POST") {
			return new Response(405, false, ["error" => "Metodo non consentito"]);
		}
		if (empty($body["field_id"]) || empty($body["data"]) || empty($body["slot_start"])) {
			return new Response(400, false, ["error" => "Parametri mancanti"]);
		}

		$userId = $GLOBALS['user']['id'] ?? null;
		if (!$userId) return new Response(401, false, ['error' => 'User non autenticato']);

		return $this->bookingsService->addFieldToCart($userId, $body["field_id"], $body["data"], $body["slot_start"]);
	}

	private function checkoutFields(array $body): Response {
		if ($_SERVER['REQUEST_METHOD'] !== "POST") {
			return new Response(405, false, ["error" => "Metodo non consentito"]);
		}
		if (empty($body["booking_ids"]) || !is_array($body["booking_ids"])) {
			return new Response(400, false, ["error" => "Parametri mancanti"]);
		}

		$userId = $GLOBALS['user']['id'] ?? null;
		if (!$userId) return new Response(401, false, ['error' => 'User non autenticato']);

		return $this->bookingsService->checkoutFields($userId, $body["booking_ids"]);
	}

	private function bookCourse(array $body): Response {
		if ($_SERVER['REQUEST_METHOD'] !== "POST") {
			return new Response(405, false, ["error" => "Metodo non consentito"]);
		}
		if (empty($body["course_id"]) || empty($body["data"]) || empty($body["slot_start"])) {
			return new Response(400, false, ["error" => "Parametri mancanti"]);
		}

		$userId = $GLOBALS['user']['id'] ?? null;
		if (!$userId) return new Response(401, false, ['error' => 'User non autenticato']);
		$quantity = $body["quantity"] ?? 1;

		return $this->bookingsService->bookCourse($userId, $body["course_id"], $body["data"], $body["slot_start"], $quantity);
	}
*/
	private function getAvailableSlots(array $body): Response {
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