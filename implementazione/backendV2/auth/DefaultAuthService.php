<?php

namespace auth;

use auth\interfaces\AuthRepository;
use auth\interfaces\AuthService;
use core\exceptions\ValidationException;
use core\model\Role;
use core\utility\interfaces\JwtTokenManager;
use core\utility\interfaces\PasswordManager;

class DefaultAuthService implements AuthService {
	public function __construct(
		private AuthRepository $authRepository, 
		private PasswordManager $passwordManager,
		private JwtTokenManager $jwtTokenManager
		) {}

	public function login(string $email, string $password) : array{	
		$user = $this->authRepository->login($email, $password);

		if ($this->passwordManager->validate($password, $user->password))
			throw new ValidationException("password non valida", 401);

		$token = $this->jwtTokenManager->encode();

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
		$token = $this->jwtTokenManager->encode();
		
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