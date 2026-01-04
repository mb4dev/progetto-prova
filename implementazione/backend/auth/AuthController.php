<?php

class AuthController extends Controller {

	public function __construct(
		private AuthService $authService
	) {
		parent::__construct();
	}

	public function getMiddlewares() : array {
		return [];
	}
	
	public function resolveAction(string $action): Response{
		$body = $this->getBody();

		//print_r($body);
		return match (strtolower($action)) {
			"login" => $this->login($body),
			"register" => $this->register($body),
			default => new Response(404, false, ["error" => "Action non trovata"])
		};
	}

	private function login(array $body): Response {
		if($_SERVER['REQUEST_METHOD'] !== "POST") {
			return new Response(405, false, ["error" => "Metodo non consentito"]);
		}
		if(empty($body["email"]) || empty($body["password"])) {
			return new Response(400, false, ["error" => "Parametri non validi"]);
		}
		return $this->authService->login($body["email"], $body["password"]);
	}

	private function register(array $body) : Response {
		if($_SERVER['REQUEST_METHOD'] !== "POST") {
			return new Response(405, false, ["error" => "Metodo non consentito"]);
		}
		if(empty($body["email"]) || empty($body["password"]) || empty($body["name"])) {
			return new Response(400, false, ["error" => "Parametri non validi"]);
		}

		return $this->authService->register($body["name"], $body["email"], $body["password"]);
	}

}
