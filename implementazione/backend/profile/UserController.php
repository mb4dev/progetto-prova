<?php

class UserController extends Controller {

	public function __construct(
		private UserService $userService
	) {
		parent::__construct();
	}

	public function getMiddlewares() : array {
		return [
			TempMiddleware::class
		];
	}
	
	public function resolveAction(string $action): Response{
		$body = $this->getBody();

		return match (strtolower($action)) {
			"profile" => $this->profile($body),
			"update" => $this->update($body),
			default => new Response(404, false, ["error" => "Action non trovata"])
		};
	}

	private function profile(array $body): Response {
		if($_SERVER['REQUEST_METHOD'] !== "POST") {
			return new Response(405, false, ["error" => "Metodo non consentito"]);
		}
		if(empty($body["id"])) {
			return new Response(400, false, ["error" => "Parametri id mancante"]);
		}
		return $this->userService->getById($body["id"]);
	}

	private function update(array $body): Response {
		throw new Exception("Metodo non implementato");
	}
}
