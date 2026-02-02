<?php

namespace features\auth\commands;

use core\http\HttpMethod;
use core\http\Response;
use core\interfaces\Command;
use core\model\Role;
use core\model\User;
use core\utility\Context;
use features\auth\factory\AuthStrategyFactory;

class LoginCommand extends Command {
    
    public function __construct(
        private Context $context,
        private AuthStrategyFactory $factory,
        private string $action
    ) {
        parent::__construct();
    }

    public function execute(array $params, array $query = [], ?User $user = null): Response {
        $strategy = $this->factory->create($this->action);
        $this->context->setStrategy($strategy);
        $result = $this->context->execute($params);
        return new Response(200, true, $result);
    }

    public function getRequiredHttpMethod(): string {
        return HttpMethod::POST->value;
    }
    
    public function getRequiredBodyParameters(): array {
        return ["email", "password"];
    }

    public function requiresAuthentication(): bool {
        return false;
    }

    public function getRequiredRoles(): array {
        return [Role::USER->value, Role::ADMIN->value];
    }
}
