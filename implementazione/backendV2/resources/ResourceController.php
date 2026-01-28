<?php

namespace resources;

use resources\commands\GetAllResourceCommand;
use core\http\CommandController;
use resources\interfaces\ResourceService;

final class ResourceController extends CommandController {

    public function __construct(private ResourceService $service) {
        parent::__construct();
    }
    
    protected function registerCommands(): void {
        $this->registry->register("", new GetAllResourceCommand($this->service));
    }
}