<?php

/*
class DefaultAuthService implements AuthService {
	private AuthRepository $authRepository;
	private PasswordValidator $passwordValidator;
	private JwtTokenManager $jwtTokenManager;

	public function __construct(AuthRepository $authRepository, PasswordValidator $passwordValidator, JwtTokenManager $jwtTokenManager) {
		$this->authRepository = $authRepository;
		$this->passwordValidator = $passwordValidator;
		$this->jwtTokenManager = $jwtTokenManager;
	}

	public function login(string $email, string $password) : Response{	
		try{
			$user = $this->authRepository->login($email, $password);

			if (!$this->passwordValidator->validate($password, $user->password)) {
				return new Response(401, false, ["error" => "Password non valida"]);
			}

			$token = $this->jwtTokenManager->encode();

			return new Response(200, true, ["message" => "Login effettuato", "token" => $token, "user" => [
				"id" => $user->id,
				"name" => $user->name,
				"email" => $user->email,
				"role" => $user->role
			]]);
		}
		catch(Exception $e){
			if ($e instanceof UserNotFoundException) {
				return new Response(400, false, ["error" => "Utente non trovato"]);
			}
			return new Response(500, false, ["error" => $e->getMessage()]);
		}
	}

	public function register(string $name, string $email, string $password, Role $role = Role::USER) : Response{
		try{
			$user = $this->authRepository->register($name, $email, $password, $role);
			$token = $this->jwtTokenManager->encode();
			return new Response(200, true, ["message" => "Registrazione effettuata", "token"=> $token, "user" => [
				"id" => $user->id,
				"name" => $user->name,
				"email" => $user->email,
				"role" => $user->role
			]]);
		}
		catch(Exception $e){
			if ($e instanceof UserAlreadyExistsException) {
				return new Response(400, false, ["error" => "Utente già registrato"]);
			}
			return new Response(500, false, ["error" => $e->getMessage()]);
		}
	}

}
*/