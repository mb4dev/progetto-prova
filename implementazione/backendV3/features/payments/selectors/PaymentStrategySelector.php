<?php

namespace features\payments\selectors;

use core\exceptions\CustomException;
use core\factory\Factory;
use core\interfaces\PaymentStrategy;
use core\interfaces\Selector;
use core\interfaces\Strategy;
use features\payments\strategies\NormalPaymentStrategy;
use features\payments\strategies\SubscriptionPaymentStrategy;

final class PaymentStrategySelector implements Selector {

	public function __construct(private Factory $factory) {}

	public function select(string $type): PaymentStrategy {
		return match($type) {
			'carta' => $this->factory->get(NormalPaymentStrategy::class),
			'abbonamento' => $this->factory->get(SubscriptionPaymentStrategy::class),
			default => throw new CustomException("Tipo di pagamento non supportato: $type", 400)
		};
	}
}
