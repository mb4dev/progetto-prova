<?php

namespace features\resources;

use core\exceptions\CustomException;
use core\factory\Factory;
use core\interfaces\ResourcesRepository;

class ResourceRegistry {
    private array $resources = [];

    public function register(string $type, string $repositoryClass): void {
        $this->resources[$type] = $repositoryClass;
    }

    public function get(string $type, Factory $factory): ResourcesRepository {
        if (!isset($this->resources[$type])) {
            throw new CustomException("Tipo di risorsa non registrato: $type", 400);
        }

        $repositoryClass = $this->resources[$type];
        return $factory->get($repositoryClass);
    }
}
