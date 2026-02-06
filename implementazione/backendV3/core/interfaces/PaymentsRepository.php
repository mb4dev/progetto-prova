<?php

namespace core\interfaces;

interface PaymentsRepository {
	public function getOrderFromPayment(int $paymentId) : array;
	public function insertPagamento(int $userId, float $amount) : int;
	public function insertVocePagamento(int $paymentId, string $type, $amount, int $resourceId) : int;

}
