<?php

namespace features\auth\selectors;

use core\exceptions\CustomException;
use core\factory\Factory;
use core\interfaces\Selector;
use features\auth\strategies\EmailRegisterStrategy;

class SimpleRegisterStrategySelector implements Selector {

    public function __construct(private Factory $factory) {}

    public function select(string $type): object {
        return match($type) {
            'email' => $this->factory->get(EmailRegisterStrategy::class),
            default => throw new CustomException("Tipo di registrazione non supportato: $type", 400)
        };
    }
}
