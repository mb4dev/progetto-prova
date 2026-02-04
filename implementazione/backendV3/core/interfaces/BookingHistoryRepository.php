<?php

namespace core\interfaces;

interface BookingHistoryRepository {
	public function getHistoryForUser(int $userId): array;
}
