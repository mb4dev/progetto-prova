<?php

namespace core\http;

use auth\interfaces\AuthRepository;
use core\exceptions\AuthException;
use core\utility\CommandRegistry;
use core\utility\interfaces\JwtTokenService;

abstract class CommandController {
    protected CommandRegistry $registry;

    public function __construct(private AuthRepository $authRepo, private JwtTokenService $tokenService){
        $this->registry = new CommandRegistry();
        $this->registerCommands();
    }

    abstract protected function registerCommands(): void;

    private function getBody(): array {
		if ($_SERVER['CONTENT_TYPE'] ?? '' === 'application/json') {
			$input = file_get_contents('php://input');
			return json_decode($input, true) ?? [];
		}
		return $_POST;
	}

    public function resolveAction(string $action): Response{ 
        $command = $this->registry->getCommand($action);
        
        $httpMethod = $_SERVER["REQUEST_METHOD"] ?? "GET";
        $command->validateHttpMethod($httpMethod);
        
        $body = $this->getBody();
        $command->validateBody($body);
        
        $queryParams = $_GET;
        $command->validateQueryParameters($queryParams);

        if($command->requiresAuthentication()){
            $this->authenticate();
        }

        return $command->execute($body, $queryParams);
    }

    private function authenticate(){
        $token = $this->getToken();
        if(!$token) throw new AuthException("token mancante", 401);
        
        $payload = $this->tokenService->decode($token);
        $user = $this->authRepo->getUserById($payload->id); 
        return $user;
    }

    private function getToken() : ?string{
        $header = trim($_SERVER["HTTP_AUTHORIZATION"] ?? "");
        if($header === "") return null;

        $headerParts = explode(" ", $header);
        if(count($headerParts) !== 2) return null;
        
        [$scheme, $token] = $headerParts;

        if(strtolower($scheme) !== "bearer") return null;

        return $token;   
    }

}        



