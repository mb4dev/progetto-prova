<?php

namespace core\http;

use core\exceptions\ValidationException;
use core\utility\CommandRegistry;
use Exception;

abstract class CommandController {
    protected CommandRegistry $registry;

    public function __construct(){
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
        if(!$command->validateHttpMethod($httpMethod))
            throw new ValidationException("metodo $httpMethod non consentito", 400);
            
        $body = $this->getBody();
        if(!$command->validateBody($body)){
            $requiredParams = implode(', ', $command->getRequiredBodyParameters());
            throw new ValidationException("body malformato, parametri richiesti $requiredParams", 400);
        }
        
        $queryParams = $_GET;
        return $command->execute($body, $queryParams);
    }
}                                           
