<?php


class DefaultAuthService implements AuthService {
	private AuthRepository $authRepository;
	private PasswordValidator $passwordValidator;

	public function __construct(AuthRepository $authRepository, PasswordValidator $passwordValidator) {
		$this->authRepository = $authRepository;
		$this->passwordValidator = $passwordValidator;
	}

	public function login(string $username, string $password) : Response{
		return new Response(200, true, ["message" => "Login effettuato"]);
	}
	public function register(string $name, string $username, string $password) : Response{
		return new Response(200, true, ["message" => "Registrazione effettuata"]);
	}
	
}