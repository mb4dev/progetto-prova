<?php

namespace core\interfaces;

use core\model\Response;

interface Command
{
    /**
     * Execute the command with given parameters
     * 
     * @param array $params Request body parameters
     * @param array $query Query string parameters
     * @return Response
     */
    public function execute(array $params, array $query = []): Response;

    /**
     * Validate if the HTTP method is allowed for this command
     * 
     * @param string $method HTTP method (GET, POST, PUT, DELETE, etc.)
     * @return bool
     */
    public function validateHttpMethod(string $method): bool;

    /**
     * Get the list of required parameters
     * 
     * @return array Array of required parameter names
     */
    public function getRequiredParameters(): array;

    /**
     * Get the list of optional parameters with their default values
     * 
     * @return array Associative array of optional parameters => default values
     */
    public function getOptionalParameters(): array;

    /**
     * Get the description of what this command does
     * 
     * @return string
     */
    public function getDescription(): string;
}