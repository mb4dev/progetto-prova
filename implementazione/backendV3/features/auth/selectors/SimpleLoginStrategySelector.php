<?php

namespace features\auth\selectors;

use core\exceptions\CustomException;
use core\factory\Factory;
use core\interfaces\Selector;
use core\interfaces\Strategy;
use features\auth\strategies\EmailLoginStrategy;

class SimpleLoginStrategySelector implements Selector {

    public function __construct(private Factory $factory) {}

    public function select(string $type): object {
        return match($type) {
            'email' => $this->factory->get(EmailLoginStrategy::class),
            default => throw new CustomException("Tipo di login non supportato: $type", 400)
        };
    }
}
