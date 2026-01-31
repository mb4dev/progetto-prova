<?php

namespace core\interfaces;

use features\resources\ResourceType;

interface BookingService {
	public function getBooking(string $resourceType, int $resourceId, string $date);
	public function insertBooking(int $userId, int $resourceId, string $date, string $slot) : array;

}