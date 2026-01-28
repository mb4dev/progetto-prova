<?php

namespace core\http;

use core\utility\CommandRegistry;

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
        $command->validateHttpMethod($httpMethod);
        
        $body = $this->getBody();
        $command->validateBody($body);
        
        $queryParams = $_GET;
        $command->validateQueryParameters($queryParams);

        return $command->execute($body, $queryParams);
    }
}                                           
