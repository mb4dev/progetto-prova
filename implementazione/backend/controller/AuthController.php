<?php

final class AuthController extends Controller {

	public function __construct(
		private AuthService $authService
	) {
		parent::__construct();
	}

	
	public function resolveAction(string $action): Response{
		$body = $this->getBody();

		return match (strtolower($action)) {
			"login" => $this->authService->login($body["email"], $body["password"]),
			"register" => $this->authService->register($body["name"], $body["email"], $body["password"]),
			default => new Response(404, false, "Action non trovata")
		};
	}


}
