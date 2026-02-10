<?php

namespace core\interfaces;
use core\interfaces\Strategy;

interface PaymentStrategy extends Strategy {
	public function pay(int $userId, float $total,  array $order) : array;

}