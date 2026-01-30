<?php

namespace core\utility;

use core\exceptions\CustomException;
use core\http\Response;
use core\interfaces\HttpSecurity;
use core\utility\CommandRegistry;

abstract class CommandController {
    
    protected CommandRegistry $registry;

    public function __construct(private HttpSecurity $authMiddleware){
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
            $token = $this->getToken();
            if(!$token) throw new CustomException("token autorizzazione mancante", 400);
            $this->authMiddleware->authenticate($this->getToken());
        }

        return $command->execute($body, $queryParams);
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