<?php

namespace features\auth\strategies;

use core\interfaces\AuthRepository;
use core\interfaces\PasswordManager;
use core\interfaces\TokenService;
use core\exceptions\CustomException;
use core\interfaces\Strategy;

class EmailLoginStrategy implements Strategy {
    public function __construct(
        private AuthRepository $repository,
        private PasswordManager $passwordManager,
        private TokenService $tokenService
    ) {}

    public function execute(array $params): array {
        $user = $this->repository->login($params["email"]);
        
        if (!$this->passwordManager->validate($params["password"], $user->password)) {
            throw new CustomException("password non valida", 401);
        }

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
