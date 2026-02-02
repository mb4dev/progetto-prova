<?php

namespace core\interfaces;

interface BookingRepository  {
	public function getBooking(int $resourceId, string $date);
	public function insertBooking(int $userId, int $resourceId, string $date, string $slot);
	public function getBookingsForUser(int $userId): array;
}