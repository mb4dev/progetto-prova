<?php

namespace core\interfaces;

interface BookingStrategy extends Strategy {
	public function insertBooking(int $userId, int $resourceId, string $date, string $slot): array;
	public function getOccupiedSlots(int $resourceId, string $date): array;
}
