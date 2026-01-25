<?php

namespace core\http;

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
        try {
            $command = $this->registry->getCommand($action);

            $httpMethod = $_SERVER["REQUEST_METHOD"] ?? "GET";
            if(!$command->validateHttpMethod($httpMethod))
                return new Response(405, false, ["errore" => "metodo non consentito"]);

            $body = $this->getBody();
            if(!$command->validateBody($body)){
                $requiredParams = $command->getRequiredBodyParameters();
                return new Response(400, false, [
                    "error" => "body malformato",
                    "required_parameters" => $requiredParams]);
            }
            return $command->execute([]);
        }
        catch(Exception $e){
            return new Response(500, false, ["error" => $e->getMessage()]);
        }
		/*
        try {
            $command = $this->registry->getCommand($action);
            
            if (!$command) {
                return new Response(404, false, [
                    "error" => "Action non trovata",
                    "available_actions" => $this->registry->getRegisteredActions()
                ]);
            }

            $httpMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
            
            if (!$command->validateHttpMethod($httpMethod)) {
                return new Response(405, false, [
                    "error" => "Metodo HTTP non consentito",
                    "method" => $httpMethod,
                    "allowed_methods" => $this->getAllowedMethodsForCommand($command)
                ]);
            }

            $body = $this->getBody();
            $query = $_GET;

            // Validate required parameters
            $requiredParams = $command->getRequiredParameters();
            $validationResult = $this->validator->validateRequired($body, $requiredParams);
            
            if (!$validationResult['valid']) {
                return new Response(400, false, [
                    "error" => "Parametri mancanti",
                    "missing_parameters" => $validationResult['missing']
                ]);
            }

            // Set default values for optional parameters
            $optionalParams = $command->getOptionalParameters();
            foreach ($optionalParams as $param => $defaultValue) {
                if (!isset($body[$param])) {
                    $body[$param] = $defaultValue;
                }
            }

            return $command->execute($body, $query);

        } catch (\Exception $e) {
            return GlobalExceptionHandler::handle($e);
        }
			*/
    }
}                                           
