<?php

namespace features\payments\strategies;

use core\interfaces\PaymentStrategy;


final class SubscriptionPaymentStrategy implements PaymentStrategy {
	public function pay(){
		echo "abbonamento";
		}
		
	public function execute(array $params): array{
		echo "abbonamento";
		return [];
	}
}