<?php

namespace resources;

use auth\interfaces\AuthRepository;
use resources\commands\GetAllResourceCommand;
use core\http\CommandController;
use resources\interfaces\ResourceService;

final class ResourceController extends CommandController {

    public function __construct(AuthRepository $authRepo, private ResourceService $service) {
        parent::__construct($authRepo);
    }
    
    protected function registerCommands(): void {
        $this->registry->register("", new GetAllResourceCommand($this->service));
    }
}