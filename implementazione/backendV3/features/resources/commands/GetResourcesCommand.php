<?php

namespace features\resources\commands;

use core\http\HttpMethod;
use core\http\Response;
use core\interfaces\Command;
use core\interfaces\Selector;
use core\model\Role;
use core\model\User;

final class GetResourcesCommand extends Command {
    public function __construct(
        private Selector $resourceSelector
    ) {
        parent::__construct();
    }

    public function execute(array $params, array $query = [], ?User $user = null): Response {
        $repository = $this->resourceSelector->select($query["type"]);

        $result = $repository->getAll();

        return new Response(200, true, $result);
    }
    
    public function getRequiredQueryParameters(): array {
        return ["type"];
    }
    
    public function getRequiredHttpMethod(): string {
        return HttpMethod::GET->value;
    }
    
    public function requiresAuthentication(): bool {
        return false;
    }
    
    public function getRequiredRoles(): array {
        return [Role::USER->value, Role::ADMIN->value];
    }
}
