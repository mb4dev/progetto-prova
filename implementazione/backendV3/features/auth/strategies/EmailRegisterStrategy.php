<?php

namespace features\auth\strategies;

use core\interfaces\AuthRepository;
use core\interfaces\PasswordManager;
use core\interfaces\Strategy;
use core\interfaces\TokenService;
use core\model\Role;

class EmailRegisterStrategy implements Strategy{
    public function __construct(
        private AuthRepository $repository,
        private PasswordManager $passwordManager,
        private TokenService $tokenService
    ) {}

    public function execute(array $params): array {
        $hashedPassword = $this->passwordManager->hash($params["password"]);
        $role = Role::tryFrom($params["role"]) ?? Role::USER;
        
        $user = $this->repository->register(
            $params["name"],
            $params["email"],
            $hashedPassword,
            $role
        );

        $token = $this->tokenService->encode($user);

        return [
            "token" => $token,
            "user" => [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "role" => $user->role->value
            ]
        ];
    }
}
