<?php

namespace core\interfaces;

interface BookingService {
	public function insertBooking(int $userId, int $resourceId, string $date, string $slot) : array;

}