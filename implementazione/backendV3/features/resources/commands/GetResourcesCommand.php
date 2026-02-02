<?php

namespace features\resources\commands;

use core\exceptions\CustomException;
use core\factory\Factory;
use core\http\HttpMethod;
use core\http\Response;
use core\interfaces\Command;
use core\model\Role;
use core\model\User;
use features\resources\ResourceRegistry;

final class GetResourcesCommand extends Command {
    public function __construct(
        private ResourceRegistry $registry,
        private Factory $factory
    ) {
        parent::__construct();
    }

    public function execute(array $params, array $query = [], ?User $user = null): Response {
        $repository = $this->registry->get($query["type"], $this->factory);

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
