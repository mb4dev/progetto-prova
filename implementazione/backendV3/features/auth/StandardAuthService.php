<?php

namespace features\auth;

use core\exceptions\CustomException;
use core\interfaces\AuthRepository;
use core\interfaces\AuthService;
use core\interfaces\PasswordManager;
use core\interfaces\TokenService;
use core\model\Role;

class StandardAuthService implements AuthService {
	public function __construct(
		private AuthRepository $authRepository, 
		private PasswordManager $passwordManager,
		private TokenService $tokenService
		) {}

	public function login(string $email, string $password) : array{	
		$user = $this->authRepository->login($email, $password);

		if (!$this->passwordManager->validate($password, $user->password))
			throw new CustomException("password non valida", 401);

		$token = $this->tokenService->encode($user);

		return [
			"token" => $token, 
			"user" => [
				"id" => $user->id,
				"name" => $user->name,
				"email" => $user->email,
				"role" => $user->role
			]
		];
	}

	public function register(string $name, string $email, string $password, Role $role = Role::USER) : array{
		$hashedPassword = $this->passwordManager->hash($password);
		$user = $this->authRepository->register($name, $email, $hashedPassword, $role);
		$token = $this->tokenService->encode($user);
		
		return [
			"token"=> $token,
			"user" => [
				"id" => $user->id,
				"name" => $user->name,
				"email" => $user->email,
				"role" => $user->role
			]
		];
	}
}