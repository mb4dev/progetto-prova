<?php

namespace features\resources;

use core\interfaces\HttpSecurity;
use core\interfaces\ResourceService;
use core\utility\CommandController;
use features\resources\commands\GetAllResourceCommand;

final class ResourceController extends CommandController {

    public function __construct(
        private HttpSecurity $authMiddleware, private ResourceService $service) {
        parent::__construct($authMiddleware);
    }
    
    protected function registerCommands(): void {
        $this->registry->register("", new GetAllResourceCommand($this->service));
    }
}