<?php

namespace features\auth\factory;

use core\interfaces\AuthStrategy;
use core\interfaces\AuthRepository;
use core\interfaces\PasswordManager;
use core\interfaces\TokenService;
use core\exceptions\CustomException;
use core\interfaces\Strategy;
use features\auth\strategies\LoginStrategy;
use features\auth\strategies\RegisterStrategy;

class AuthStrategyFactory {
    public function __construct(
        private AuthRepository $repository,
        private PasswordManager $passwordManager,
        private TokenService $tokenService
    ) {}

    public function create(string $action): Strategy {
        return match($action) {
            "login" => new LoginStrategy(
                $this->repository,
                $this->passwordManager,
                $this->tokenService
            ),
            "register" => new RegisterStrategy(
                $this->repository,
                $this->passwordManager,
                $this->tokenService
            ),
            default => throw new CustomException("Azione auth non supportata: $action", 400)
        };
    }
}
