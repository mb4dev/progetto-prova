<?php

namespace features\auth\controller;

use core\factory\Factory;
use core\interfaces\HttpSecurity;
use core\interfaces\Selector;
use core\utility\CommandController;
use features\auth\commands\LoginCommand;
use features\auth\commands\RegisterCommand;

final class AuthController extends CommandController {

    public function __construct(
        HttpSecurity $authMiddleware,
        private Selector $loginSelector,
        private Selector $registerSelector
    ) {
        parent::__construct($authMiddleware);
    }

    protected function registerCommands(): void {
        $this->registry->register("login", new LoginCommand($this->loginSelector));
        $this->registry->register("register", new RegisterCommand($this->registerSelector));
    }
}
