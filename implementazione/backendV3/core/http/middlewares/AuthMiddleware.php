<?php

namespace core\http\middlewares;

use core\interfaces\AuthRepository;
use core\interfaces\HttpSecurity;
use core\interfaces\TokenService;
use core\model\User;

final class AuthMiddleware implements HttpSecurity {
	public function __construct(private TokenService $tokenService, private AuthRepository $authRepository) {}
	
	public function authenticate(string $token) :?User{
        $payload = $this->tokenService->decode($token);
        $user = $this->authRepository->getUserById($payload->id); 
        return $user;
	}

	/*
	public function authorize(string $token): bool{
		throw new \Exception('Not implemented');
	}
	*/
}