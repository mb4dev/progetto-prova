<?php

namespace features\resources\controller;


use core\interfaces\HttpSecurity;
use core\interfaces\Selector;
use core\utility\CommandController;
use features\resources\commands\GetResourcesCommand;

final class ResourceController extends CommandController {
    public function __construct(
        HttpSecurity $authMiddleware,
        private Selector $resourceSelector
    ) {
        parent::__construct($authMiddleware);
    }
    
    protected function registerCommands(): void {
        $this->registry->register("", new GetResourcesCommand($this->resourceSelector));
    }
}
