<?php


class DefaultAuthService implements AuthService {
	private AuthRepository $authRepository;
	private PasswordValidator $passwordValidator;

	public function __construct(AuthRepository $authRepository, PasswordValidator $passwordValidator) {
		$this->authRepository = $authRepository;
		$this->passwordValidator = $passwordValidator;
	}

	public function login(string $email, string $password) : Response{	
		try{
			$user = $this->authRepository->login($email, $password);

			if (!$this->passwordValidator->validate($password, $user->password)) {
				return new Response(400, false, ["error" => "Password non valida"]);
			}

			return new Response(200, true, ["message" => "Login effettuato"]);
		}
		catch(Exception $e){
			if ($e instanceof UserNotFoundException) {
				return new Response(400, false, ["error" => "Utente non trovato"]);
			}
			return new Response(500, false, ["error" => $e->getMessage()]);
		}
	}

	public function register(string $name, string $email, string $password) : Response{
		try{
			$this->authRepository->register($name, $email, $password);
			return new Response(200, true, ["message" => "Registrazione effettuata"]);
		}
		catch(Exception $e){
			if ($e instanceof UserAlreadyExistsException) {
				return new Response(400, false, ["error" => "Utente già registrato"]);
			}
			return new Response(500, false, ["error" => $e->getMessage()]);
		}
	}

}