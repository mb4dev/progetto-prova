<?php

namespace core\interfaces;
use core\interfaces\Strategy;

interface PaymentStrategy extends Strategy {
	public function pay(int $userId, array $order) : array;

}