<?php

namespace features\auth\commands;

use core\http\HttpMethod;
use core\http\Response;
use core\interfaces\Command;
use core\interfaces\Selector;
use core\model\Role;
use core\model\User;

class RegisterCommand extends Command {

    public function __construct(
        private Selector $strategySelector) {
            
        parent::__construct();
    }

    public function execute(array $params, array $query = [], ?User $user = null): Response {
        $strategy = $this->strategySelector->select($params["register_type"]);
        $result = $strategy->execute($params);
        return new Response(200, true, $result);
    }

    public function getRequiredHttpMethod(): string {
        return HttpMethod::POST->value;
    }

    public function getRequiredBodyParameters(): array {
        return ["register_type", "name", "email", "password", "role"];
    }

    public function requiresAuthentication(): bool {
        return false;
    }

    public function getRequiredRoles(): array {
        return [Role::USER->value, Role::ADMIN->value];
    }
}
