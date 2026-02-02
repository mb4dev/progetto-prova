<?php

namespace features\auth\controller;

use core\factory\Factory;
use core\interfaces\HttpSecurity;
use core\utility\CommandController;
use features\auth\commands\LoginCommand;
use features\auth\commands\RegisterCommand;
use features\auth\registry\LoginStrategyRegistry;
use features\auth\registry\RegisterStrategyRegistry;

final class AuthController extends CommandController {

    public function __construct(
        HttpSecurity $authMiddleware,
        private LoginStrategyRegistry $loginRegistry,
        private RegisterStrategyRegistry $registerRegistry,
        private Factory $factory
    ) {
        parent::__construct($authMiddleware);
    }

    protected function registerCommands(): void {
        $this->registry->register("login", new LoginCommand($this->loginRegistry, $this->factory));
        $this->registry->register("register", new RegisterCommand($this->registerRegistry, $this->factory));
    }
}
