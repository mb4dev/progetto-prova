<?php

namespace core\interfaces;

interface BookingRepository {
	public function getOccupiedSlots(int $resourceId, string $date): array;
	public function insertBooking(int $userId, int $resourceId, string $date, string $slot): int;

	public function getBooking(int $id) : array;
}