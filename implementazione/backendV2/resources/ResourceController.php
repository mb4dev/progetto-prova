<?php

namespace resources;

use resources\commands\GetAllResourceCommand;
use core\http\CommandController;
use resources\interfaces\ResourceService;
use core\di\Container;
use core\http\ControllerTypes;

/**
 * Controller per le risorse (campi, corsi)
 */

final class ResourceController extends CommandController {

    public function __construct(private ResourceService $service) {
        parent::__construct();
    }
    
    protected function registerCommands(): void {
        $this->registry->register("", new GetAllResourceCommand($this->service));
    }
    
    /**
     * Factory Method - Registra le dipendenze del modulo Resources
     */
    public static function register(Container $container): void {
        // Registra PostgreFieldsRepository
        $container->register(PostgreFieldsRepository::class, function($c) {
            return new PostgreFieldsRepository($c->get('pdo'));
        }, true);

        // Registra PostgreCoursesRepository
        $container->register(PostgreCoursesRepository::class, function($c) {
            return new PostgreCoursesRepository($c->get('pdo'));
        }, true);

        // Registra ResourceService
        $container->register(ResourceService::class, function($c) {
            return new StandardResourceService(
                $c->get(PostgreFieldsRepository::class),
                $c->get(PostgreCoursesRepository::class)
            );
        }, true);

        // Registra il controller
        $container->register(ControllerTypes::RESOURCE->value, function($c) {
            return new self($c->get(ResourceService::class));
        });
    }
}