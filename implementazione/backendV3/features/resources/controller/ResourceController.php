<?php

namespace features\resources\controller;

use core\factory\Factory;
use core\interfaces\HttpSecurity;
use core\utility\CommandController;
use features\resources\commands\GetResourcesCommand;
use features\resources\registry\ResourceRegistry;

final class ResourceController extends CommandController {
    public function __construct(
        HttpSecurity $authMiddleware,
        private ResourceRegistry $resourceRegistry,
        private Factory $factory
    ) {
        parent::__construct($authMiddleware);
    }
    
    protected function registerCommands(): void {
        $this->registry->register("", new GetResourcesCommand($this->resourceRegistry, $this->factory));
    }
}
