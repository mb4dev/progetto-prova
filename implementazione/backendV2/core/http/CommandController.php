<?php

namespace core\http;

use core\utility\CommandRegistry;

/**
 * Controller base per gestire i command
 * 
 * Questo controller:
 * 1. Gestisce il registry dei command
 * 2. Risolve l'action richiesta
 * 3. Valida la request (HTTP method, body, query params)
 * 4. Esegue i middleware del command
 * 5. Esegue il command
 */
abstract class CommandController {
    protected CommandRegistry $registry;

    public function __construct() {
        $this->registry = new CommandRegistry();
        $this->registerCommands();
    }

    /**
     * Registra i command specifici del controller
     * Deve essere implementato dalle classi figlie
     */
    abstract protected function registerCommands(): void;

    /**
     * Ottiene il body della request
     * Supporta sia JSON che form data
     */
    private function getBody(): array {
		if ($_SERVER['CONTENT_TYPE'] ?? '' === 'application/json') {
			$input = file_get_contents('php://input');
			return json_decode($input, true) ?? [];
		}
		return $_POST;
	}

    /**
     * Risolve e esegue l'action richiesta
     * 
     * @param string $action Nome dell'action da eseguire
     * @return Response La risposta del command
     */
    public function resolveAction(string $action): Response { 
        $command = $this->registry->getCommand($action);
        
        // Valida HTTP method
        $httpMethod = $_SERVER["REQUEST_METHOD"] ?? "GET";
        $command->validateHttpMethod($httpMethod);
        
        // Ottiene e valida body e query params
        $body = $this->getBody();
        $command->validateBody($body);
        
        $queryParams = $_GET;
        $command->validateQueryParameters($queryParams);

        // Crea il contesto della request
        $context = [
            'body' => $body,
            'query' => $queryParams,
        ];

        // Esegue i middleware del command
        $middleware = $command->getMiddleware();
        foreach ($middleware as $mw) {
            $context = $mw->handle($context);
        }

        // Esegue il command passando il contesto aggiornato
        return $command->execute($context['body'], $context['query']);
    }
}        

