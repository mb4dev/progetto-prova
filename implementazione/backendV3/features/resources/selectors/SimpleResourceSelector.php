<?php

namespace features\resources\selectors;

use core\exceptions\CustomException;
use core\factory\Factory;
use core\interfaces\ResourcesRepository;
use core\interfaces\Selector;
use features\resources\repository\PostgreFieldsRepository;
use features\resources\repository\PostgreCoursesRepository;

class SimpleResourceSelector implements Selector {

    public function __construct(private Factory $factory) {}

    public function select(string $type): object {
        return match($type) {
            'campi' => $this->factory->get(PostgreFieldsRepository::class),
            'corsi' => $this->factory->get(PostgreCoursesRepository::class),
            default => throw new CustomException("Tipo di risorsa non supportato: $type", 400)
        };
    }
}
