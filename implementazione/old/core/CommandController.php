<?php

namespace core;

use core\interfaces\Controller;
use core\interfaces\Command;
use core\model\Response;
use core\CommandRegistry;
use utility\ParameterValidator;
use core\exceptions\GlobalExceptionHandler;

abstract class CommandController extends Controller
{
    protected CommandRegistry $registry;
    protected ParameterValidator $validator;

    public function __construct()
    {
        parent::__construct();
        $this->registry = new CommandRegistry();
        $this->validator = new ParameterValidator();
        $this->registerCommands();
    }

    /**
     * Register all available commands for this controller
     * This method must be implemented by concrete controllers
     * 
     * @return void
     */
    abstract protected function registerCommands(): void;

    /**
     * Resolve action using Command Pattern
     * 
     * @param string $action The action name
     * @return Response The response from command execution
     */
    public function resolveAction(string $action): Response
    {
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
    }

    /**
     * Get allowed HTTP methods for a command
     * This is a helper method - can be overridden for more complex scenarios
     * 
     * @param Command $command The command instance
     * @return array Array of allowed HTTP methods
     */
    protected function getAllowedMethodsForCommand(Command $command): array
    {
        // Common HTTP methods to check
        $methods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];
        $allowed = [];
        
        foreach ($methods as $method) {
            if ($command->validateHttpMethod($method)) {
                $allowed[] = $method;
            }
        }
        
        return $allowed;
    }

    /**
     * Get the command registry (useful for testing or inspection)
     * 
     * @return CommandRegistry The command registry
     */
    protected function getRegistry(): CommandRegistry
    {
        return $this->registry;
    }

    /**
     * Get the parameter validator (useful for testing or inspection)
     * 
     * @return ParameterValidator The parameter validator
     */
    protected function getValidator(): ParameterValidator
    {
        return $this->validator;
    }
}