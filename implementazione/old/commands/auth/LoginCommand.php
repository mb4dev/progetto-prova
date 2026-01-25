<?php

namespace commands\auth;

use core\interfaces\Command;
use core\model\Response;
use auth\interfaces\AuthService;
use auth\interfaces\AuthRepository;
use auth\DefaultAuthService;
use auth\DefaultAuthRepository;
use Role;

class LoginCommand implements Command
{
    private AuthService $authService;

    public function __construct()
    {
        $repository = new DefaultAuthRepository();
        $this->authService = new DefaultAuthService($repository);
    }

    public function execute(array $params, array $query = []): Response
    {
        $response = $this->authService->login($params['email'], $params['password']);
        
        if ($response->success && isset($response->jsonData['token'])) {
            setcookie('jwt_token', $response->jsonData['token'], time() + 3600, '/', '', false, true);
        }
        
        return $response;
    }

    public function validateHttpMethod(string $method): bool
    {
        return $method === 'POST';
    }

    public function getRequiredParameters(): array
    {
        return ['email', 'password'];
    }

    public function getOptionalParameters(): array
    {
        return [];
    }

    public function getDescription(): string
    {
        return 'Authenticate user and return JWT token';
    }
}