<?php

class AuthMiddleware implements Middleware {

	public function __construct() {
	}
	public function handle(): ?Response {
		$token = $_COOKIE['jwt_token'] ?? null;
		if (!$token) {
			return new Response(401, false, ['error' => 'Non autorizzato']);
		}
		try {
			$payload = $this->jwtManager->decode($token);
			// Save user globally
			$GLOBALS['user'] = $payload;
			return null;
		} catch (Exception $e) {
			return new Response(401, false, ['error' => 'Token invalido']);
		}
	}
}