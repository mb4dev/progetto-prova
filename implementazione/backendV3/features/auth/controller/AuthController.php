<?php

namespace features\auth\controller;

use core\interfaces\HttpSecurity;
use core\utility\CommandController;
use core\utility\Context;
use features\auth\context\AuthContext;
use features\auth\factory\AuthStrategyFactory;
use features\auth\commands\LoginCommand;
use features\auth\commands\RegisterCommand;

final class AuthController extends CommandController {

    public function __construct(
        HttpSecurity $authMiddleware,
        private Context $context,
        private AuthStrategyFactory $factory
    ) {
        parent::__construct($authMiddleware);
    }

    protected function registerCommands(): void {
        $this->registry->register("login", new LoginCommand($this->context, $this->factory, "login"));
        $this->registry->register("register", new RegisterCommand($this->context, $this->factory, "register"));
    }
}
